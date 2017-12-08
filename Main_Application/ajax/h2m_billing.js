 function mask(f){  
    tel='';  
    var val =f.value.split('');  
    for(var i=0;i<val.length;i++){  
        if(i==0) val[i]='('+val[i];
        if(i==2) val[i]=val[i]+')';  
        if(i==5) val[i]=val[i]+'-';  
        tel=tel+val[i];
    }  
    f.value=tel;  
}  