console.log('load_table_init.js LOADED!');

//GATHER VARIABLES
var medid = $("#MEDID").val();

function load_table(table_holder,sort, ascending, page, scope, period, search)
{
	var table = 2;
	var back_end_file = "";
	if(table == 1)
	{
		back_end_file= "../../getCustomerData2";
	}
	else if(table == 2)
	{
		back_end_file = "../../getConsultationsDataTable";
	}
	else if(table == 3)
	{
		back_end_file = "../../getDoctorsData";
	}
	else if(table == 4)
	{
		back_end_file = "../../getNewUserData";
	}
	var sort_order = "asc";
	if(!ascending)
	{
		sort_order = "desc";
	}
	$("tr").each(function(index, element)
	{
		if($(element).children().eq(0).is("td"))
		{
			$(element).remove();
		}
	});
	$("#table_loader").css("display", "block");
	var medid = $("#MEDID").val();
	$.post(back_end_file+".php", {medid: medid, period: period, sortOrder: sort_order, sortField: sort, searchField: search, currentPage: page}, function(data, status)
	{
		console.log(data);
		var pre_info = JSON.parse(data);
		var num_pages = pre_info["pages"];
		if(page == num_pages)
		{
			$("#page_button_right").attr("disabled", "disabled");
		}
		else
		{
			$("#page_button_right").removeAttr("disabled");
		}
		if(page == 1)
		{
			$("#page_button_left").attr("disabled", "disabled");
		}
		else
		{
			$("#page_button_left").removeAttr("disabled");
		}
		if(num_pages > 0)
		{
			$("#page_label").text(page + " of " + num_pages);
		}
		else
		{
			$("#page_label").text("0 of 0");
			$("#page_button_right").attr("disabled", "disabled");
			$("#page_button_left").attr("disabled", "disabled");
		}
		
		var html = "";
		var table = 2;
		if(table == 1) // customers table
		{
			var info = pre_info["customers"];
			for(var i = 0; i < info.length; i++)
			{
				var color = "#F5F5F5";
				if(i % 2 == 1)
				{
					color = "#E9E9E9";
				}
				html += "<tr><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += "<a href=\"patientdetailMED-new.php?IdUsu="+info[i]["Identif"]+"\" style=\"color: #5EB529\">"+info[i]["Name"]+" "+info[i]["Surname"]+"</a>";
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["numberOfPhoneCalls"];
				//html += "</td><td style=\"background-color: "+color+"; width: 150px; height: 15px; text-align: center;\">";
				//html += info[i]["video_calls"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["SignUpDate"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["typeOfPlan"];
				html += "</td></tr>";
			}
			$("#customers_table").children("table").eq(0).append(html);
		}
		else if(table == 2) // consultations table
		{
			var info = pre_info["consultations"];
			for(var i = 0; i < info.length; i++)
			{
				var color = "#F5F5F5";
				if(i % 2 == 1)
				{
					color = "#E9E9E9";
				}
				html += "<tr><td style=\"background-color: "+color+"; width: 130px; height: 15px; text-align: center;\">";
				html += info[i]["doctorName"] + " " + info[i]["doctorSurname"];
				html += "</td><td style=\"background-color: "+color+"; width: 130px; height: 15px; text-align: center;\">";
				html += "<a href=\"patientdetailMED-new.php?IdUsu="+info[i]["pat_id"]+"\" style=\"color: #5EB529\">"+info[i]["patientName"] + " " + info[i]["patientSurname"]+"</a>";
				html += "</td><td style=\"background-color: "+color+"; width: 130px; height: 15px; text-align: center;\">";
				html += info[i]["DateTime"];
				html += "</td><td style=\"background-color: "+color+"; width: 130px; height: 15px; text-align: center;\">";
				html += info[i]["Type"];
				html += "</td><td style=\"background-color: "+color+"; width: 130px; height: 15px; text-align: center;\">";
				html += info[i]["Length"];
				html += " sec";
				html += "</td><td style=\"background-color: "+color+"; width: 130px; height: 15px; text-align: center;\">";
				html += info[i]["Status"];
				html += "</td><td style=\"background-color: "+color+"; width: 40px; height: 15px; text-align: center;\">";
				html += "<a class=\"notes_link\" href=\"" + info[i]["Data_File"] + "\">Show</a>";
				html += "</td><td style=\"background-color: "+color+"; width: 40px; height: 15px; text-align: center;\">";

				//html += "<a class=\"summary_link\" href="" + info[i]["Summary_PDF"]+ "\">Show</a>";
				
				//html += checkSummary(info[i]["Summary_PDF"]);   
				
				html += "</td><td style=\"background-color: "+color+"; width: 40px; height: 15px; text-align: center;\">";
				
				function doesFileExist(url)
				{
					var http = new XMLHttpRequest();
					http.open("HEAD", url, false);
					//http.send();
				return http.status!=404;
				}
				
				var resultxyz = doesFileExist("http://beta.health2.me/recordings/"+info[i]["Recorded_File"]);

				if (resultxyz == true) {
				html += "<a class=\"recording_link\" href=\""+info[i]["Recorded_File"]+"\">Show</a>";
				} else {
				html += "N/A";
				}
				
				html += "</td></tr>";
			}
			console.log(html);
			$("#consultations_table").children("table").eq(0).append(html);
			$("#consultations_table").css("display", "block");
			$(".recording_link").on("click", function(e)
			{
				e.preventDefault();
				$.post("pdmst_dashboard_decrypt.php", {file: $(this).attr("href"), recording: true}, function(data, status)
				{
					console.log(data);
					$("#recordingModal").html("<iframe src=\""+data+"\" style=\"width:400px;height:400px;\"></iframe>");
					$("#recordingModal").dialog({bigframe: true, width: 406, height: 440, modal: true});
					$.post("pdmst_dashboard_decrypt.php", {erase: true, erase_file: data}, function(data, status){console.log(data);});
				});
			});
			$("#recordingModal").on( "dialogclose", function( event, ui ) {$("#recordingModal").html("");} );
			$(".summary_link").on("click", function(e)
			{
				e.preventDefault();
				$.post("pdmst_dashboard_decrypt.php", {file: $(this).attr("href")}, function(data, status)
				{
					console.log(data);
					$("#summaryModal").html("<iframe src=\""+data+"\" style=\"width:680px;height:800px;\"></iframe>");
					$("#summaryModal").dialog({bigframe: true, width: 680, height: 800, modal: true});
					$.post("pdmst_dashboard_decrypt.php", {erase: true, erase_file: data}, function(data, status){console.log(data);});
				});
			});
			$(".notes_link").on("click", function(e)
			{
				e.preventDefault();
				$.post("pdmst_dashboard_decrypt.php", {file: $(this).attr("href")}, function(data, status)
				{
					console.log(data);
					$("#notesModal").html("<iframe src=\""+data+"\" style=\"width:600px;height:800px;\"></iframe>");
					$("#notesModal").dialog({bigframe: true, width: 605, height: 400, modal: true});
					$.post("pdmst_dashboard_decrypt.php", {erase: true, erase_file: data}, function(data, status){console.log(data);});

				});
			});
		}
		else if(table == 3) // doctors table
		{
			var info = pre_info["doctors"];
			
			for(var i = 0; i < info.length; i++)
			{
				var color = "#F5F5F5";
				if(i % 2 == 1)
				{
					color = "#E9E9E9";
				}
				html += "<tr><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["name"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["calls"];
				//html += "</td><td style=\"background-color: "+color+"; width: 150px; height: 15px;\">";
				//html += info[i]["video_calls"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["numberOfConsultedPatients"];
				//html += "</td><td style=\"background-color: "+color+"; width: 150px; height: 15px;\">";
				//html += info[i]["summaries"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["reportsCreated"];
				html += "</td></tr>";
			}
			$("#doctors_table").children("table").eq(0).append(html);
		}
		else if(table == 4) // doctors table
		{
			var info = pre_info["newusers"];
			
			for(var i = 0; i < info.length; i++)
			{
				var color = "#F5F5F5";
				if(i % 2 == 1)
				{
					color = "#E9E9E9";
				}
				html += "<tr><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["name"]+" "+info[i]["surname"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["telefono"];
				//html += "</td><td style=\"background-color: "+color+"; width: 150px; height: 15px;\">";
				//html += info[i]["video_calls"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["email"];
				//html += "</td><td style=\"background-color: "+color+"; width: 150px; height: 15px;\">";
				//html += info[i]["summaries"];
				html += "</td><td style=\"background-color: "+color+"; width: 225px; height: 15px; text-align: center;\">";
				html += info[i]["signupdate"];
				html += "</td></tr>";
			}
			$("#newusers_table").children("table").eq(0).append(html);
		}
		$("#table_loader").css("display", "none");
		
	});
}
