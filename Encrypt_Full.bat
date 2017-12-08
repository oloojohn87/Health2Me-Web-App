@echo off
del list.txt
FOR %%i IN (C:\xampp\htdocs\PackagesTH\*.*) DO echo %%~nxi >> list.txt
FOR /F %%i IN (list.txt) DO (
ECHO ENCRYPTING %%i
echo InmersIT | openssl.exe aes-256-cbc -pass stdin -salt -in C:\xampp\htdocs\PackagesTH\%%i -out C:\xampp\htdocs\PackagesTH_Encrypted\%%i
)

