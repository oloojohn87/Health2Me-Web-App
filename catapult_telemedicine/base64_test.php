</html>

<!DOCTYPE html>
<html>
<title>Catapult API Test</title>

<script src="../js/jquery.min.js"></script>
<script src="report_64.js"></script>

<body>
	<div id='tester_div'>
	</div>
</body>
<script>

var patients = [{"patients":{"PatientId":1, 
				"CompanyName":"Catapult", 
				"FirstName":"Jon", 
				"LastName":"Shupe", 
				"DOB":"09/25/1984", 
				"SSN":"765121659", 
				"EmployeeId":"A222",
				"Gender":"M",
				"LanguageId":1,
				"MiddleInitial":"G",
				"Address1":"4205 Whippoorwill Lane",
				"Address2":"",
				"City":"Plano",
				"State":"Texas",
				"PostalCode":"75093",
<<<<<<< Updated upstream
				"HomePhone":"19727622294",
				"CellPhone":"19727622294",
				"EmailAddress":"jon.shupe@catapulthealth.com",
=======
				"HomePhone":"14692680295",
				"CellPhone":"14692680295",
				"EmailAddress":"jvinals@edgeh.com",
>>>>>>> Stashed changes
				"InsuranceGroupId":"Insur445",
				"InsuranceMemberId":"Mem876",
				"InsurancePlanId":5,
				"HispanicLatino":0,
				"EthnicityId":0,
				"IsActive":1,
                "filedata": encoded,
                "filetype": 'pdf',
                "filename": 'report.pdf'
				}}];

$.ajax({
     url: "http://dev.health2.me/CatapultAPI.php",
     type: "POST",
     async: false,
     data:{ json: JSON.stringify(patients)},
     success: function (data){
     	$('#tester_div').html(data);
     },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 
        alert("Status: " + textStatus); alert("Error: " + errorThrown); 
    } 
 });

</script>



</html>