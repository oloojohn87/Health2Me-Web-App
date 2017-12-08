/*
@author : Ankit Parekh
Description : This file contains the regular expressions to detect dates in the file "ExtractedData.txt"
*/
%{
#include<stdio.h>
#include<string.h>
#include "EditDistanceAlgorithm.h"
#include "DateProcessing.h"

%}

PROB		    [zeijlotZDUTOAEJLtu]
DIGIT           [0-9]         	    
LETTER	        [a-zA-Z]

NO              {DIGIT}{PROB}|{PROB}{DIGIT}|{DIGIT}{DIGIT}|{PROB}{PROB}
NOS		        {NO}|{DIGIT}|{PROB} 	

YEAR        	{NO}|{NO}{NO}

SEPARATOR   	[-/'.', ]

MONTHS		    {LETTER}{LETTER}{LETTER}{LETTER}?{LETTER}?{LETTER}?{LETTER}?{LETTER}?{LETTER}?

RULE1  		    {NOS}{SEPARATOR}?{NOS}{SEPARATOR}?{YEAR}
RULE2		    {NOS}{SEPARATOR}?{MONTHS}{SEPARATOR}?{YEAR}
RULE3		    {MONTHS}{SEPARATOR}?{NOS}{SEPARATOR}?{YEAR}           
RULE4		    {YEAR}{SEPARATOR}?{NOS}{SEPARATOR}?{NOS}
RULE5		    {YEAR}{SEPARATOR}?{MONTHS}{SEPARATOR}?{NOS}

%%
				
{RULE1}		{
				//printf("%s , ",yytext);
                processR1(yytext);
			}
			
			
{RULE2}		{
				//printf("%s , ",yytext);
				processR2(yytext);
			}
			
			
{RULE3}		{
				//printf("%s , ",yytext);
				processR3(yytext);
			}
			
			
{RULE4}		{
				//printf("%s , ",yytext);
				processR4(yytext);
			}
			

{RULE5}		{
				//printf("%s , ",yytext);
				processR5(yytext);
			}


[\n]		 	{
				exit(0);
			}

.			{}


%%



int main(int argc,char **argv)
{
	int i;
	
	FILE *fp=fopen(argv[1],"r");
	//printf("%s",argv[1]);
	yyin=fp;
	//printf("\n\nSuggested Dates : \n");
	yylex();
	
	//printf("\n\nParsing Done");
	return 0;
	
	
}

yywrap()
{
	exit(0);
} 




