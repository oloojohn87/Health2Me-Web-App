 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <title>table1</title>
 <script language="javascript" type="text/javascript">
 function check()
 {

 if(document.getElementById('name1').value=='')
 {
 alert("Please enter your name");
 document.getElementById('name1').focus();
 }
 else if(document.getElementById('pass1').value=='')
 {
 alert("please enter your pass");
 document.getElementById('pass1').focus();
 }
 else
 {
	//document.getElementById('accesso').value="23222";
	//alert(document.getElementById('accesso').value);
	document.form.submit();
	//alert('here');

 }
 }
 </script>
 </head>
 
<body>
 <form method="post" action="file2.php" name="form" >
 <table width="100%" border="0">
 <tr>
 <td><label>Enter your name: </label>
 </td>
 <td><input type="text" name="name"  id="name1"; />
 </td>
 </tr>
 <tr>
 <td><label>Enter your Password:</label>
 </td>
 <td><input type="password" name="pass" id="pass1"; />
 </td>
 </tr>
 
 <tr>
 <td colspan="2"> <div align="center">
 
 <input type="button" value="Submit" onclick="check();" name="Submit" />
 
</div></td>
 </tr>
 </table>
 </form>
 </body>
 </html>