@echo off
cd /d "%~dp0"
where py >nul 2>nul && (py orbital_manager.py & goto :eof)
where python >nul 2>nul && (python orbital_manager.py & goto :eof)
echo Python not found. Install it from https://www.python.org/downloads/ (tick "Add Python to PATH"), then run this again.
pause
