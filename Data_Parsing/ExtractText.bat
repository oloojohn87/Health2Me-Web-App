@echo off
rmdir Parsing/s/q
mkdir Parsing

echo %2 | "C:\Program Files (x86)\Apache Software Foundation\Apache2.2\bin\"openssl.exe aes-256-cbc -pass stdin -d -in C:\xampp\htdocs\Packages_Encrypted\%1 -out Parsing\%1 
if %errorlevel% neq 0 goto err

"C:\Program Files (x86)\gs\gs9.05\bin\"gswin32.exe -dNOPAUSE -o multipage-tiffg4.tif -sDEVICE=tiffg4 -r400x400 -dBATCH -sPAPERSIZE=a4 -sOutputFile=Parsing\abc.tiff Parsing\%1
if %errorlevel% neq 0 goto err

tesseract Parsing\abc.tiff ExtractedData -l eng
if %errorlevel% neq 0 goto err

echo success
exit

:err : 
echo failure
	
