@echo off
del c:\xampp\htdocs\DocPDF\output.pdf > NUL 2>&1
soffice -norestore -nofirststartwizard -nologo -headless -p %1
set /a count=0
:file_check : 
if %count% == 3 (goto error)
IF exist c:\xampp\htdocs\DocPDF\output.pdf (GOTO file_exists) else (timeout /t 5 > NUL & set /a count=count+1)
GOTO file_check
:file_exists :
copy c:\xampp\htdocs\DocPDF\output.pdf %2 > NUL 2>&1
echo success
goto done
:error :
echo failure
:done: