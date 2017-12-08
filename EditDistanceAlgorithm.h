/*
@author : Ankit Parekh
Description : This file contains implementation of EditDistance Algorithm
*/

int EditDistance( char *X, char *Y, int m, int n )
{
    // Base cases
    if( m == 0 && n == 0 )
        return 0;
 
    if( m == 0 )
        return n;
 
    if( n == 0 )
        return m;
 
    // Recurse
    int left = EditDistance(X, Y, m-1, n) + 1;
    int right = EditDistance(X, Y, m, n-1) + 1;
    int corner = EditDistance(X, Y, m-1, n-1) + (X[m] != Y[n]);
 
    return Minimum(left, right, corner);
}

int Minimum(int a, int b, int c)
{
    if(a < b && a < c)
	return a;
    else if(b < a && b < c)
	return b;
    else
	return c;
}


