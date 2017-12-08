@echo off
echo %4 | "C:\Program Files (x86)\Apache Software Foundation\Apache2.2\bin\"openssl.exe aes-256-cbc -pass stdin -d -in %1/%2 -out temp/%3/%1/%2 
echo success