# Orbital Theme Manager

Install or uninstall the **Orbital** WordPress theme with a simple menu.

## One-line install (Linux VPS / server)

```bash
bash <(curl -sL https://raw.githubusercontent.com/Sarthak345662/orbital/main/ORBITAL-GUI)
```

A terminal menu opens: **1 Install**, **2 Uninstall**, **3 Status**, **0 Exit**.
It finds your WordPress folder, downloads `orbital.zip` from this repo and installs it into `wp-content/themes/orbital`.
Needs `curl` and `unzip` (or `python3`).

## Desktop GUI (Windows / Mac / Linux)

Download this repo, then run:

- Windows: double-click `Run-Windows.bat`
- Mac / Linux: `python3 orbital_manager.py` (or `Run-Mac-Linux.command`)

Choose local folder, FTP, FTPS or SFTP, then press **INSTALL** or **UNINSTALL**.
(SFTP needs `pip install paramiko`.)

## Manual install

WordPress Admin -> Appearance -> Themes -> Add New -> Upload Theme -> choose `orbital.zip` -> Activate.

## WP-CLI

```bash
wp theme install https://github.com/Sarthak345662/orbital/raw/main/orbital.zip --activate
wp theme delete orbital      # switch to another theme first
```

## Credits

Theme: **Orbital** by BIGSEO (GNU GPL v2 or later). This repository only provides the installer tools.
