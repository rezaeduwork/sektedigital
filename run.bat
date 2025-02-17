@echo off
start cmd /k "npm run dev"
start cmd /k "C:\Users\LENOVO\Documents\laragon\bin\php\php-8.3.10-Win32-vs16-x64\php.exe artisan queue:work"