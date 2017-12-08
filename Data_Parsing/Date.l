/*
@author : Ankit Parekh
Description : This file contains the regular expressions to detect dates in the file "ExtractedData.txt"
*/
%{
#include<stdio.h>
#include<string.h>
#include "EditDistanceAlgorithm.h"

typedef struct node
{
	char orig_text[50];
	char derived[15];
	int pos;
	int dist;
	struct node *next;
}node;

node* root;
node* rear;

typedef struct datepos
{
	int pos;
	struct datepos* next;

}datepos;

datepos *droot;
datepos *drear;

char months[][12] = {" ","jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"};

void processR1(char date[]);
void processR2(char date[]);
void processR3(char date[]);
void processR4(char date[]);
void processR5(char date[]);
int process_months(char mon[],int *arr,int *k);
char find_replacement(char c);
int process_year(char year[]);
void add_to_list(char org[],char der[]);
void add_date_to_list(int pos);
void print_list();
node* make_decision();
void add_date_to_list(int pos);
void print_dlist();
int min(int a,int b);
int count=1;



%}

PROB 	        [zeijlotZDUTOAEJLtu]
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
				
"date"|"Date"      {
			//printf("\nDetected Date at %d\n",count);
			add_date_to_list(count);
		   }

{RULE1}		   {count++;
		    /*printf("\nRule1 : %s",yytext);*/
                    processR1(yytext);
		   }
			
			
{RULE2}		   {count++;
		    /*printf("\nRule2 : %s",yytext);*/
		    processR2(yytext);
		   }
			
			
{RULE3}		  { count++;
		    /*printf("\nRule3 : %s",yytext);*/
		    processR3(yytext);
		  }
			
			
{RULE4}		  {count++;
		   /*printf("\nRule4 : %s",yytext);*/
		   processR4(yytext);
		  }
			

{RULE5}		  {count++;
		   /*printf("\nRule5 : %s",yytext);*/
		   processR5(yytext);
		  }


[\n]		  {
		    /*Ignore Character*/
		  }

" "               {count++;}

.		  {
                    //count++;
                  }


%%



int main()
{
	int i;
	node *a;
	
	FILE *fp=fopen("ExtractedData.txt","r");
	yyin=fp;
	root=NULL;
	rear=NULL;
	droot=NULL;
	drear=NULL;
	//printf("\n\nSuggested Dates : \n");
	yylex();
	//printf("\n\n\n");
	if(root==NULL && droot==NULL)
	{	
		return 0;
	}
	else if(root == NULL)
	{
		//no date found
		return 0;
	}
	else
	{
		a=make_decision();
		printf("%s\n",a->derived);

	}
	//printf("Parsing Done");
	return 0;
}

yywrap()
{
	return 1;
} 

void processR1(char date[])
{
	char mon[10],day[10],year[10],str[12];
	int i=0;
	int k=0;
	int mon_val,day_val,yy;


	while(i<strlen(date))
	{
		
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			mon[k]=find_replacement(date[i]);
		}
		else
		{
			
			mon[k]=date[i];
		}
	   	k++;
	     	i++;	
	}

	
	mon[k]='\0';
	
		
	mon_val = atoi(mon);	
	//printf("Here");	
	if(mon_val < 1 || mon_val >12)
	{

		//printf("\tIncorrect Date");
		return;
	}
	//printf("Here");

	i++;
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			day[k]=find_replacement(date[i]);
		}
		else
		{
			day[k]=date[i];
		}
		k++;
		i++;
	}
	day[k]='\0';
	day_val = atoi(day);
	if(day_val < 1 || day_val > 31)
	{
	     //printf("\tIncorrect Date");
	     return;  
	}	


	i++;
	k=0;
	while(i<strlen(date))
	{
		year[k]=date[i];
		k++;
		i++;
	}
	year[k]='\0';

	yy = process_year(year);
	if(yy > 1000 && yy < 10000)
	{
		sprintf(str,"%d-%d-%d",yy,mon_val,day_val);
		add_to_list(date,str);
		//printf("%s ,",str);
	}
}


void processR2(char date[])
{

	char mon[10],day[10],year[10],str[12];
	int i=0;
	int k=0;
	int yy,mon_val,day_val;	
	int mon_arr[12];
	
	
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			day[k]=find_replacement(date[i]);
		}
		else
		{
			day[k]=date[i];
		}
		k++;
		i++;
	}
	day[k]='\0';
	day_val = atoi(day);
	if(day_val < 1 || day_val > 31)
	{
	     //printf("\tIncorrect Date");		
	     return;  
	}	


	i++;
	k=0;	
	
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
	        mon[k]=date[i];
	   	k++;
	     	i++;	
	}
	mon[k]='\0';
	mon_val=0;
	process_months(mon,mon_arr,&mon_val);
	
	i++;
	k=0;
	while(i<strlen(date))
	{
		year[k]=date[i];
		k++;
		i++;
	}
	year[k]='\0';

	for(i=0;i<mon_val;i++)
	{	yy = process_year(year);
		if(yy > 1000 && yy < 10000)
		{
			sprintf(str,"%d-%d-%d",yy,mon_arr[i],day_val);
			add_to_list(date,str);
			//printf("%s , ",str);			
		}
	}
}


void processR3(char date[])
{


	char mon[10],day[10],year[10],str[12];
	int i=0;
	int k=0;
	int mon_val=0,day_val,yy;	
	int mon_arr[12];
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
	        mon[k]=date[i];
	   	k++;
	     	i++;	
	}
	mon[k]='\0';
	//printf("Here11");
	process_months(mon,mon_arr,&mon_val);
	//printf("Here");
	i++;
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			day[k]=find_replacement(date[i]);
		}
		else
		{
			day[k]=date[i];
		}
		k++;
		i++;
	}
	day[k]='\0';
	day_val = atoi(day);
	
	if(day_val < 1 || day_val > 31)
	{
	     //printf("\tIncorrect Date");
	     return;  
	}	


	i++;
	k=0;	
	while(i<strlen(date))
	{
		year[k]=date[i];
		k++;
		i++;
	}
	year[k]='\0';
	
	for(i=0;i<mon_val;i++)
	{   yy = process_year(year);
		if(yy > 1000 && yy < 10000)
		{
			sprintf(str,"%d-%d-%d",yy,mon_arr[i],day_val);
			add_to_list(date,str);
			//printf("%s , ",str);
		}
	}

	
}


void processR4(char date[])
{


	char mon[10],day[10],year[10],str[12];
	int i=0;
	int k=0;
	int mon_val=0,day_val,yy;	
	int mon_arr[12];
	
	
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		year[k]=date[i];
		k++;
		i++;
	}
	year[k]='\0';
	

	
	i++;
	k=0;
	while(i<strlen(date))
	{
		
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			mon[k]=find_replacement(date[i]);
		}
		else
		{
			
			mon[k]=date[i];
		}
	   	k++;
	     	i++;	
	}

	
	mon[k]='\0';
	
		
	mon_val = atoi(mon);	
	if(mon_val < 1 || mon_val >12)
	{

		//printf("\tIncorrect Date");
		return;
	}

	
	i++;
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			day[k]=find_replacement(date[i]);
		}
		else
		{
			day[k]=date[i];
		}
		k++;
		i++;
	}
	day[k]='\0';
	day_val = atoi(day);
	
	if(day_val < 1 || day_val > 31)
	{
	     //printf("\tIncorrect Date");
	     return;  
	}	

	yy=process_year(year);
	sprintf(str,"%d-%d-%d",yy,mon_val,day_val);
	add_to_list(date,str);
	//printf("%s , ",str);
	

	
}


void processR5(char date[])
{


	char mon[10],day[10],year[10],str[12];
	int i=0;
	int k=0;
	int mon_val=0,day_val,yy;	
	int mon_arr[12];
	
	
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		year[k]=date[i];
		k++;
		i++;
	}
	year[k]='\0';
	

	
	i++;
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
	        mon[k]=date[i];
	   	k++;
	     	i++;	
	}
	mon[k]='\0';
	process_months(mon,mon_arr,&mon_val);
	

	
	i++;
	k=0;
	while(i<strlen(date))
	{
		if(date[i]=='-' || date[i]=='/' || date[i]==' ' || date[i]=='\0')
		{
			break;
		}
		if((date[i]>64 && date[i]<91)||(date[i]>96 && date[i]<123))
		{
			day[k]=find_replacement(date[i]);
		}
		else
		{
			day[k]=date[i];
		}
		k++;
		i++;
	}
	day[k]='\0';
	day_val = atoi(day);
	
	if(day_val < 1 || day_val > 31)
	{
	     //printf("\tIncorrect Date");
	     return;  
	}	

	for(i=0;i<mon_val;i++)
	{	yy = process_year(year);
		if(yy > 1000 && yy < 10000)
		{
			
			sprintf(str,"%d-%d-%d",yy,mon_arr[i],day_val);
			add_to_list(date,str);
			//printf("%s , ",str);
			
		}
	}
}






int process_months(char mon[],int *arr,int *k)
{
	int i,a,b,c,m;
	int temp[13];
	//convert mon to all lowercase   (pending)
	for(i=0;i<=strlen(mon);i++)
	{
           if(mon[i]>=65&&mon[i]<=90)
                mon[i]=mon[i]+32;
  	}


	if(strlen(mon)==3)  //all short forms
	{
		m=999;
		for(i=1;i<13;i++)
		{	
			a=EditDistance( mon, months[i], 3, 3 );
			//printf("\n****%s  -->  %s  == %d",mon,months[i],a);
			if(a<=m)
			{
				m=a;
				
			}	
			temp[i]=a;		
		}		
		for(i=1;i<13;i++)
		{
		    //printf("\n%d temp[%d] =  %d",i,temp[i]);
			if(temp[i]==m)
			{
			    //printf("\nAdded %d",i);
				*(arr+*k)=i;
				*k=*k+1;
			}
		}	
	}
	else if(strlen(mon)==4)  // june,july
	{
		if(EditDistance( mon, "june", 4, 4 ) < EditDistance( mon, "july", 4, 4 ))
		{	
			*(arr+*k)=6;
		}
		else
		{
			*(arr+*k)=7;
		}	
		*k=*k+1;
	}
	else if(strlen(mon)==5)  //march,april
	{
		if(EditDistance( mon, "march", 5, 5 ) < EditDistance( mon, "april", 5, 5 ))
		{	
			*(arr+*k)=3;
		}
		else
		{
			*(arr+*k)=4;
		}	
		*k=*k+1;
	}
	else if(strlen(mon)==6) //august
	{
		*(arr+*k)=8;
		*k=*k+1;

	}
	else if(strlen(mon)==7) //january,october
	{
		if(EditDistance( mon, "january", 7, 7 ) < EditDistance( mon, "october", 7, 7 ))
		{	
			*(arr+*k)=1;
		}
		else
		{
			*(arr+*k)=10;
		}	
		*k=*k+1;
	}
	else if(strlen(mon)==8) //february,november,december
	{
		a=EditDistance( mon, "february", 8, 8 );
		b=EditDistance( mon, "november", 8, 8 );
		c=EditDistance( mon, "december", 8, 8 );
		if(a<b && a<c)	
		{
			*(arr+*k)=2;
			*k=*k+1;
		}
		else if(b < a && b<c)
		{
			*(arr+*k)=11;
			*k=*k+1;
		}
		else
		{
			*(arr+*k)=12;
			*k=*k+1;
		}
	}
	else if(strlen(mon)==9)  //september
	{
		*(arr+*k)=9;
		*k=*k+1;
	}
	else
	{
		*(arr+*k)=21;    //Invalid Month
		*k=*k+1;
	}	

 

}


char find_replacement(char c)
{

	switch(c)
	{
		case 'z':
		case 'Z': return '2';
		
		case 'e':
		case 'E': return '3';

		case 'i':
                case 'I':
		case 'j':
		case 'J':
		case 'L':
		case 'l':
		case 't':
		case 'T': return '1';

		case 'o':
		case 'O':
		case 'D':
		case 'u':
		case 'U': return '0';
	
		case 'A': return '4';

	}


}


int process_year(char year[])
{
	char temp[5];
	int k,i;
	
	if(strlen(year)==2)
	{
		temp[0]='2';
		temp[1]='0';
		k=2;
	}
	else
	{
		k=0;
	}

	for(i=0;i<strlen(year);i++)
	{
		if((year[i]>64 && year[i]<91)||(year[i]>96 && year[i]<123))
		{
			temp[k]=find_replacement(year[i]);
		}
		else
		{
			temp[k]=year[i];
		}	
		k++;
	}
	temp[k]='\0';
	return atoi(temp);
}





/*
int process_month(char mon[])
{
	if(strcmp(mon,"january")==0 || strcmp(mon,"jan")==0)
	{
		return 1;
	}		
	else if(strcmp(mon,"february")==0 || strcmp(mon,"feb")==0)
	{
		return 2;
	}
	else if(strcmp(mon,"march")==0 || strcmp(mon,"mar")==0)
	{
		return 3;
	}
	else if(strcmp(mon,"april")==0 || strcmp(mon,"apr")==0)
	{
		return 4;
	}
	else if(strcmp(mon,"may")==0 )
	{
		return 5;
	}
	else if(strcmp(mon,"june")==0 || strcmp(mon,"jun")==0)
	{
		return 6;
	}
	else if(strcmp(mon,"july")==0 || strcmp(mon,"jul")==0)
	{
		return 7;
	}
	else if(strcmp(mon,"august")==0 || strcmp(mon,"aug")==0)
	{
		return 8;
	}
	else if(strcmp(mon,"september")==0 || strcmp(mon,"sep")==0)
	{
		return 9;
	}
	else if(strcmp(mon,"october")==0 || strcmp(mon,"oct")==0)
	{
		return 10;
	}
	else if(strcmp(mon,"november")==0 || strcmp(mon,"nov")==0)
	{
		return 11;
	}
	else if(strcmp(mon,"december")==0 || strcmp(mon,"dec")==0)
	{
		return 12;
	}

}
*/


void add_date_to_list(int pos)
{
	datepos *d = (datepos*)malloc(sizeof(datepos));
	d->pos=pos;
	d->next=NULL;
	
	if(droot==NULL)
	{
		droot=d;
		drear=d;
	}	
	else
	{
		drear->next=d;
		drear=d;
	}

}

void add_to_list(char org[],char der[])
{
	int flag=0,i;
	node *n=(node*)malloc(sizeof(node));
	strcpy(n->orig_text,org);
	strcpy(n->derived,der);
	n->pos = count;
	n->next = NULL;	
	n->dist=999;

	for(i=0;i<strlen(org);i++)
	{
		if(org[i]>='0' && org[i]<='9')
		{
			flag=1;
		}
	}

	if(flag==0)
	{
		return;
	}

	
	if(root==NULL)
	{
		root=n;
		rear=n;
	}
	else
	{
		rear->next = n;
		rear=n;
	}
}


void print_list()
{
	node *p=root;
	while(p!=NULL)
	{
		printf("%s   %s    %d\n",p->orig_text,p->derived,p->pos);
		p=p->next;
	}
}

void print_dlist()
{
	datepos* d=droot;
	printf("\n\n");
	while(d!=NULL)
	{
		printf("%d\n",d->pos);
		d=d->next;
	}

}


node* make_decision()
{
	int m;
	node *p=root;
	datepos *d=droot;
	node *ans;
	
	//print_list();
	//print_dlist();
	
	if(droot==NULL)
	{
		return root;
	}
	


	while(p!=NULL)	
	{
		p->dist = p->pos - d->pos;		
		p=p->next;
	}
	d=d->next;
	
	while(d!=NULL)
	{
		p=root;
		while(p!=NULL)
		{
			if(p->pos-d->pos > 0)
			{
				p->dist = min(p->dist,p->pos-d->pos);
			}			
			p=p->next;
		}
		d=d->next;	
	}

	p=root;
	m=999;
	while(p!=NULL)
	{
		if(p->dist>0)
		{
			if(p->dist < m)
			{
				m=p->dist;
				ans = p;
			}
		}
		p=p->next;
	}	


	return ans;
}

int min(int a,int b)
{
	if(a<b)
		return a;
	else
		return b;
}
