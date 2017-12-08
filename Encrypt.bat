@echo off
echo %3 | openssl.exe aes-256-cbc -pass stdin -salt -in %1\%2 -out temp\%2
del %1/%2
copy temp\%2 %1\%2
del temp\%2