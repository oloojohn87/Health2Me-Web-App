#!/bin/sh

convert $1[0] -colorspace RGB -geometry 200 $2 2>&1

echo $3 | openssl aes-256-cbc -pass stdin -salt -in $2 -out $4

##rm $2

cp $4 $2

##rm $4 

echo $3 | openssl aes-256-cbc -pass stdin -salt -in $1 -out $5

##rm $1

cp $5 $6

##rm $5

status=$?

if [ $status -eq "0" ] 

	then
		echo "success"
	else
		echo "failed status $status"
fi
