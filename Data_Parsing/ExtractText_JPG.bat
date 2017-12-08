@echo off
rmdir Parsing/s/q
mkdir Parsing

echo %2 | "C:\Program Files (x86)\Apache Software Foundation\Apache2.2\bin\"openssl.exe aes-256-cbc -pass stdin -d -in C:\xampp\htdocs\Packages_Encrypted\%1 -out Parsing\%1
if %errorlevel% neq 0 goto err

tesseract Parsing\%1 ExtractedData -l eng
if %errorlevel% neq 0 goto err

echo success
exit

:err : 
echo failure
	
