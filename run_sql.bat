@echo off
"C:\xampp\mysql\bin\mysql.exe" -u root backup < "C:\xampp\htdocs\backup\create_tables.sql"
pause