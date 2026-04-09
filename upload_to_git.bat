@echo off
echo.
echo ===========================================
echo   Git Upload Tool for Beach_2024-5-11
echo ===========================================
echo.

:: Initialize git if not already
if not exist .git (
    echo Initializing Git...
    git init
)

:: Set remote origin
echo Configuring remote origin...
git remote add origin https://github.com/GitBababoo/Beach_2024-5-11.git 2>nul
git remote set-url origin https://github.com/GitBababoo/Beach_2024-5-11.git

:: Add all files
echo Adding files...
git add .

:: Commit
set /p msg="Enter commit message (default: 'Initial upload'): "
if "%msg%"=="" set msg=Initial upload
git commit -m "%msg%"

:: Rename branch to main and push
echo Pushing to GitHub...
git branch -M main
git push -u origin main

echo.
if %ERRORLEVEL% EQU 0 (
    echo [SUCCESS] Project uploaded successfully!
) else (
    echo [ERROR] Something went wrong. Please check your credentials or internet connection.
)
echo.
pause
