#!/usr/bin/env python3
"""
Orbital Theme Manager  -  GUI to INSTALL / UNINSTALL the Orbital WordPress theme.

Works with:
  * Local WordPress (XAMPP, LocalWP, MAMP, WAMP ...)  -> pick the wp-content/themes folder
  * Remote server via FTP / FTPS / SFTP (SSH)         -> your hosting or VPS

Needs only Python 3 (tkinter ships with it).
SFTP additionally needs:   pip install paramiko
Linux: if tkinter is missing:   sudo apt install python3-tk
"""

import ftplib
import os
import posixpath
import queue
import re
import shutil
import stat
import threading
import zipfile

try:
    import tkinter as tk
    from tkinter import filedialog, messagebox, ttk
except ImportError:  # GUI unavailable (core functions still work)
    tk = None

APP_DIR = os.path.dirname(os.path.abspath(__file__))
DEFAULT_ZIP = os.path.join(APP_DIR, "orbital.zip")
DEFAULT_SLUG = "orbital"


# --------------------------------------------------------------------------
# Core: theme zip handling
# --------------------------------------------------------------------------
def theme_entries(zip_path):
    """Find the theme root inside the zip (the folder holding style.css).

    Returns (prefix, names) where names are the zip entries that belong to the theme.
    Works whether or not the zip has a top-level folder.
    """
    with zipfile.ZipFile(zip_path) as z:
        names = [
            n for n in z.namelist()
            if not n.startswith("__MACOSX/") and not n.endswith(".DS_Store")
        ]
    styles = [n for n in names if posixpath.basename(n) == "style.css"]
    if not styles:
        raise ValueError("style.css not found in this zip - it is not a WordPress theme zip.")
    root = min(styles, key=lambda n: n.count("/"))
    prefix = root[: -len("style.css")]
    return prefix, [n for n in names if n.startswith(prefix) and n != prefix]


def valid_slug(slug):
    return bool(re.fullmatch(r"[A-Za-z0-9_-]+", slug or ""))


# --------------------------------------------------------------------------
# Core: local install / uninstall
# --------------------------------------------------------------------------
def install_local(zip_path, themes_dir, slug, log):
    if not os.path.isdir(themes_dir):
        raise RuntimeError("Themes folder not found: " + themes_dir)
    prefix, names = theme_entries(zip_path)
    dest = os.path.normpath(os.path.join(themes_dir, slug))
    if os.path.isdir(dest):
        log("Old version found - removing it...")
        shutil.rmtree(dest)
    os.makedirs(dest)
    count = 0
    with zipfile.ZipFile(zip_path) as z:
        for n in names:
            rel = n[len(prefix):]
            target = os.path.normpath(os.path.join(dest, *rel.split("/")))
            if target != dest and not target.startswith(dest + os.sep):
                continue  # zip-slip protection
            if n.endswith("/"):
                os.makedirs(target, exist_ok=True)
                continue
            os.makedirs(os.path.dirname(target), exist_ok=True)
            with z.open(n) as src, open(target, "wb") as out:
                shutil.copyfileobj(src, out)
            count += 1
            if count % 100 == 0:
                log("Copied %d files..." % count)
    log("Copied %d files to %s" % (count, dest))


def uninstall_local(themes_dir, slug, log):
    dest = os.path.normpath(os.path.join(themes_dir, slug))
    if not os.path.isdir(dest) or not os.path.isfile(os.path.join(dest, "style.css")):
        raise RuntimeError("Theme '%s' is not installed in %s" % (slug, themes_dir))
    shutil.rmtree(dest)
    log("Removed " + dest)


# --------------------------------------------------------------------------
# Core: remote (FTP / FTPS / SFTP)
# --------------------------------------------------------------------------
class FTPRemote:
    def __init__(self, host, port, user, password, tls):
        self.c = ftplib.FTP_TLS() if tls else ftplib.FTP()
        self.c.connect(host, port, timeout=30)
        self.c.login(user, password)
        if tls:
            self.c.prot_p()

    def isdir(self, path):
        here = self.c.pwd()
        try:
            self.c.cwd(path)
            return True
        except ftplib.all_errors:
            return False
        finally:
            try:
                self.c.cwd(here)
            except ftplib.all_errors:
                pass

    def mkdirs(self, path):
        cur = "/" if path.startswith("/") else ""
        for part in [p for p in path.split("/") if p]:
            cur = posixpath.join(cur, part) if cur else part
            try:
                self.c.mkd(cur)
            except ftplib.error_perm:
                pass  # already exists

    def put(self, fileobj, path):
        self.c.storbinary("STOR " + path, fileobj)

    def listdir(self, path):
        try:
            out = []
            for name, facts in self.c.mlsd(path, facts=["type"]):
                if name in (".", "..") or facts.get("type") in ("cdir", "pdir"):
                    continue
                out.append((name, facts.get("type") == "dir"))
            return out
        except ftplib.all_errors:
            out = []
            for full in self.c.nlst(path):
                name = posixpath.basename(full.rstrip("/"))
                if name in ("", ".", ".."):
                    continue
                out.append((name, self.isdir(posixpath.join(path, name))))
            return out

    def rmtree(self, path):
        for name, is_dir in self.listdir(path):
            full = posixpath.join(path, name)
            if is_dir:
                self.rmtree(full)
            else:
                self.c.delete(full)
        self.c.rmd(path)

    def close(self):
        try:
            self.c.quit()
        except Exception:
            pass


class SFTPRemote:
    def __init__(self, host, port, user, password, key_file):
        try:
            import paramiko
        except ImportError:
            raise RuntimeError("SFTP needs paramiko. Open a terminal and run:  pip install paramiko")
        self.ssh = paramiko.SSHClient()
        self.ssh.load_system_host_keys()
        self.ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        self.ssh.connect(
            host, port=port, username=user,
            password=password or None, key_filename=key_file or None, timeout=30,
        )
        self.c = self.ssh.open_sftp()

    def isdir(self, path):
        try:
            return stat.S_ISDIR(self.c.stat(path).st_mode)
        except IOError:
            return False

    def mkdirs(self, path):
        cur = "/" if path.startswith("/") else ""
        for part in [p for p in path.split("/") if p]:
            cur = posixpath.join(cur, part) if cur else part
            if not self.isdir(cur):
                self.c.mkdir(cur)

    def put(self, fileobj, path):
        self.c.putfo(fileobj, path)

    def listdir(self, path):
        return [(a.filename, stat.S_ISDIR(a.st_mode)) for a in self.c.listdir_attr(path)]

    def rmtree(self, path):
        for name, is_dir in self.listdir(path):
            full = posixpath.join(path, name)
            if is_dir:
                self.rmtree(full)
            else:
                self.c.remove(full)
        self.c.rmdir(path)

    def close(self):
        try:
            self.c.close()
            self.ssh.close()
        except Exception:
            pass


def connect_remote(cfg):
    if cfg["mode"] == "sftp":
        return SFTPRemote(cfg["host"], cfg["port"], cfg["user"], cfg["password"], cfg["key_file"])
    return FTPRemote(cfg["host"], cfg["port"], cfg["user"], cfg["password"], cfg["mode"] == "ftps")


def install_remote(zip_path, cfg, slug, log):
    prefix, names = theme_entries(zip_path)
    log("Connecting to %s ..." % cfg["host"])
    r = connect_remote(cfg)
    try:
        themes = cfg["remote_dir"].rstrip("/") or "/"
        if not r.isdir(themes):
            raise RuntimeError("Remote themes folder not found: " + themes)
        dest = posixpath.join(themes, slug)
        if r.isdir(dest):
            log("Old version found - removing it...")
            r.rmtree(dest)
        r.mkdirs(dest)
        made = {dest}
        total = sum(1 for n in names if not n.endswith("/"))
        count = 0
        with zipfile.ZipFile(zip_path) as z:
            for n in names:
                rel = n[len(prefix):]
                target = posixpath.join(dest, rel.rstrip("/"))
                folder = target if n.endswith("/") else posixpath.dirname(target)
                if folder not in made:
                    r.mkdirs(folder)
                    made.add(folder)
                if n.endswith("/"):
                    continue
                with z.open(n) as src:
                    r.put(src, target)
                count += 1
                if count % 25 == 0:
                    log("Uploaded %d / %d files..." % (count, total))
        log("Uploaded %d files to %s" % (count, dest))
    finally:
        r.close()


def uninstall_remote(cfg, slug, log):
    log("Connecting to %s ..." % cfg["host"])
    r = connect_remote(cfg)
    try:
        themes = cfg["remote_dir"].rstrip("/") or "/"
        dest = posixpath.join(themes, slug)
        if not r.isdir(dest) or "style.css" not in [n for n, _ in r.listdir(dest)]:
            raise RuntimeError("Theme '%s' is not installed in %s" % (slug, themes))
        r.rmtree(dest)
        log("Removed " + dest)
    finally:
        r.close()


# --------------------------------------------------------------------------
# GUI
# --------------------------------------------------------------------------
if tk is not None:

    class App(tk.Tk):
        def __init__(self):
            super().__init__()
            self.title("Orbital Theme Manager")
            self.geometry("740x700")
            self.minsize(700, 640)
            self.q = queue.Queue()

            self.mode = tk.StringVar(value="local")
            self.zip_path = tk.StringVar(value=DEFAULT_ZIP if os.path.isfile(DEFAULT_ZIP) else "")
            self.slug = tk.StringVar(value=DEFAULT_SLUG)
            self.local_dir = tk.StringVar()
            self.host = tk.StringVar()
            self.port = tk.StringVar(value="21")
            self.user = tk.StringVar()
            self.password = tk.StringVar()
            self.key_file = tk.StringVar()
            self.remote_dir = tk.StringVar(value="/var/www/html/wp-content/themes")

            self.build()
            self.on_mode()
            self.after(100, self.poll)

        # ---- layout
        def build(self):
            pad = {"padx": 10, "pady": 4}
            root = ttk.Frame(self)
            root.pack(fill="both", expand=True, padx=12, pady=10)

            ttk.Label(root, text="Orbital Theme Manager", font=("TkDefaultFont", 16, "bold")).pack(anchor="w")
            ttk.Label(root, text="Install or uninstall the Orbital WordPress theme").pack(anchor="w", pady=(0, 8))

            # theme
            f1 = ttk.LabelFrame(root, text="1. Theme")
            f1.pack(fill="x", pady=4)
            f1.columnconfigure(1, weight=1)
            ttk.Label(f1, text="Theme zip").grid(row=0, column=0, sticky="w", **pad)
            ttk.Entry(f1, textvariable=self.zip_path).grid(row=0, column=1, sticky="ew", **pad)
            ttk.Button(f1, text="Browse", command=self.pick_zip).grid(row=0, column=2, **pad)
            ttk.Label(f1, text="Folder name").grid(row=1, column=0, sticky="w", **pad)
            ttk.Entry(f1, textvariable=self.slug, width=24).grid(row=1, column=1, sticky="w", **pad)

            # where
            f2 = ttk.LabelFrame(root, text="2. Where is your WordPress?")
            f2.pack(fill="x", pady=4)
            row = ttk.Frame(f2)
            row.pack(fill="x", padx=10, pady=4)
            for text, val in (("This computer (local)", "local"), ("FTP", "ftp"),
                              ("FTPS", "ftps"), ("SFTP / SSH (VPS)", "sftp")):
                ttk.Radiobutton(row, text=text, value=val, variable=self.mode,
                                command=self.on_mode).pack(side="left", padx=(0, 14))

            self.local_f = ttk.Frame(f2)
            self.local_f.columnconfigure(1, weight=1)
            ttk.Label(self.local_f, text="wp-content/themes").grid(row=0, column=0, sticky="w", **pad)
            ttk.Entry(self.local_f, textvariable=self.local_dir).grid(row=0, column=1, sticky="ew", **pad)
            ttk.Button(self.local_f, text="Browse", command=self.pick_dir).grid(row=0, column=2, **pad)

            self.remote_f = ttk.Frame(f2)
            self.remote_f.columnconfigure(1, weight=1)
            fields = (("Host / IP", self.host, None), ("Port", self.port, None),
                      ("Username", self.user, None), ("Password", self.password, "*"),
                      ("SSH key file (optional)", self.key_file, None),
                      ("Remote themes path", self.remote_dir, None))
            for i, (label, var, show) in enumerate(fields):
                ttk.Label(self.remote_f, text=label).grid(row=i, column=0, sticky="w", **pad)
                e = ttk.Entry(self.remote_f, textvariable=var, show=show or "")
                e.grid(row=i, column=1, sticky="ew", **pad)
                if var is self.key_file:
                    self.key_entry = e
                    ttk.Button(self.remote_f, text="Browse", command=self.pick_key).grid(row=i, column=2, **pad)

            # actions
            f3 = ttk.Frame(root)
            f3.pack(fill="x", pady=8)
            f3.columnconfigure((0, 1), weight=1)
            self.btn_install = tk.Button(f3, text="INSTALL", font=("TkDefaultFont", 13, "bold"),
                                         bg="#1f9d55", fg="white", activebackground="#17794a",
                                         activeforeground="white", height=2, command=self.do_install)
            self.btn_uninstall = tk.Button(f3, text="UNINSTALL", font=("TkDefaultFont", 13, "bold"),
                                           bg="#d64545", fg="white", activebackground="#a83232",
                                           activeforeground="white", height=2, command=self.do_uninstall)
            self.btn_install.grid(row=0, column=0, sticky="ew", padx=(0, 6))
            self.btn_uninstall.grid(row=0, column=1, sticky="ew", padx=(6, 0))

            # log
            f4 = ttk.LabelFrame(root, text="Log")
            f4.pack(fill="both", expand=True, pady=4)
            self.logbox = tk.Text(f4, height=8, wrap="word", state="disabled")
            sb = ttk.Scrollbar(f4, command=self.logbox.yview)
            self.logbox.configure(yscrollcommand=sb.set)
            sb.pack(side="right", fill="y")
            self.logbox.pack(fill="both", expand=True)

        def on_mode(self):
            m = self.mode.get()
            if m == "local":
                self.remote_f.pack_forget()
                self.local_f.pack(fill="x")
            else:
                self.local_f.pack_forget()
                self.remote_f.pack(fill="x")
                if self.port.get() in ("21", "22", ""):
                    self.port.set("22" if m == "sftp" else "21")
                self.key_entry.configure(state="normal" if m == "sftp" else "disabled")

        # ---- pickers
        def pick_zip(self):
            p = filedialog.askopenfilename(title="Select theme zip", filetypes=[("Zip", "*.zip")])
            if p:
                self.zip_path.set(p)

        def pick_dir(self):
            p = filedialog.askdirectory(title="Select your WordPress wp-content/themes folder")
            if p:
                self.local_dir.set(p)

        def pick_key(self):
            p = filedialog.askopenfilename(title="Select SSH private key")
            if p:
                self.key_file.set(p)

        # ---- logging / threading
        def log(self, msg):
            self.q.put(("log", msg))

        def poll(self):
            try:
                while True:
                    kind, msg = self.q.get_nowait()
                    if kind == "log":
                        self.write(msg)
                    elif kind == "done":
                        self.busy(False)
                        self.write("DONE")
                        messagebox.showinfo("Orbital", msg)
                    elif kind == "error":
                        self.busy(False)
                        self.write("ERROR: " + msg)
                        messagebox.showerror("Orbital", msg)
            except queue.Empty:
                pass
            self.after(100, self.poll)

        def write(self, msg):
            self.logbox.configure(state="normal")
            self.logbox.insert("end", msg + "\n")
            self.logbox.see("end")
            self.logbox.configure(state="disabled")

        def busy(self, flag):
            state = "disabled" if flag else "normal"
            self.btn_install.configure(state=state)
            self.btn_uninstall.configure(state=state)

        # ---- actions
        def config(self):
            slug = self.slug.get().strip()
            if not valid_slug(slug):
                raise ValueError("Folder name can only use letters, numbers, - and _")
            cfg = {"mode": self.mode.get(), "slug": slug}
            if cfg["mode"] == "local":
                cfg["local_dir"] = self.local_dir.get().strip()
                if not cfg["local_dir"]:
                    raise ValueError("Select your wp-content/themes folder first.")
            else:
                cfg.update(host=self.host.get().strip(), user=self.user.get().strip(),
                           password=self.password.get(), key_file=self.key_file.get().strip(),
                           remote_dir=self.remote_dir.get().strip())
                try:
                    cfg["port"] = int(self.port.get())
                except ValueError:
                    raise ValueError("Port must be a number.")
                if not (cfg["host"] and cfg["user"] and cfg["remote_dir"]):
                    raise ValueError("Host, username and remote themes path are required.")
            return cfg

        def run(self, fn, ok_msg):
            self.busy(True)

            def worker():
                try:
                    fn()
                    self.q.put(("done", ok_msg))
                except Exception as e:  # show every failure in the GUI
                    self.q.put(("error", str(e) or e.__class__.__name__))

            threading.Thread(target=worker, daemon=True).start()

        def do_install(self):
            try:
                cfg = self.config()
                zp = self.zip_path.get().strip()
                if not os.path.isfile(zp):
                    raise ValueError("Select the theme zip first.")
            except ValueError as e:
                messagebox.showwarning("Orbital", str(e))
                return
            if not messagebox.askyesno("Install", "Install theme '%s'?\nAn existing copy with the same "
                                       "folder name will be replaced." % cfg["slug"]):
                return
            if cfg["mode"] == "local":
                fn = lambda: install_local(zp, cfg["local_dir"], cfg["slug"], self.log)
            else:
                fn = lambda: install_remote(zp, cfg, cfg["slug"], self.log)
            self.run(fn, "Theme installed!\n\nNext: WordPress Admin > Appearance > Themes > "
                         "activate 'Orbital'.")

        def do_uninstall(self):
            try:
                cfg = self.config()
            except ValueError as e:
                messagebox.showwarning("Orbital", str(e))
                return
            if not messagebox.askyesno("Uninstall", "Delete theme folder '%s'?\n\nIf this theme is active, "
                                       "first activate another theme in WordPress Admin, otherwise your "
                                       "site will break." % cfg["slug"], icon="warning"):
                return
            if cfg["mode"] == "local":
                fn = lambda: uninstall_local(cfg["local_dir"], cfg["slug"], self.log)
            else:
                fn = lambda: uninstall_remote(cfg, cfg["slug"], self.log)
            self.run(fn, "Theme removed.")


def main():
    if tk is None:
        print("tkinter is not installed.\n"
              "  Windows/Mac: reinstall Python from python.org (tick 'tcl/tk').\n"
              "  Linux:       sudo apt install python3-tk")
        return
    App().mainloop()


if __name__ == "__main__":
    main()
