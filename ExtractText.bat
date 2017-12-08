@echo off
rmdir c:\xampp\htdocs\Parsing/s/q
mkdir c:\xampp\htdocs\Parsing

"C:\Program Files (x86)\gs\gs9.05\bin\"gswin32.exe -dNOPAUSE -o multipage-tiffg4.tif -sDEVICE=tiffg4 -r400x400 -dBATCH -sPAPERSIZE=a4 -sOutputFile=C:\xampp\htdocs\Parsing\abc.tiff C:\xampp\htdocs\Packages\%1
if %errorlevel% neq 0 goto err

tesseract c:\xampp\htdocs\Parsing\abc.tiff c:\xampp\htdocs\ExtractedData -l eng
if %errorlevel% neq 0 goto err

echo success
exit

:err : 
echo failure
	
