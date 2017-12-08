#!/bin/sh

echo $$

sudo libreoffice --headless --convert-to pdf $1 --outdir $2

status=$?

if [ $status -eq "0" ] 

	then
		echo "success"
	else
		echo "failed status $status"
fi
