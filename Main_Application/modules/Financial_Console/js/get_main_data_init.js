console.log('get_main_data_init.js LOADED!');
//GATHER VARIABLES
var medid = $("#MEDID").val();
console.log(medid);
function get_main_data(scope, period)
{
	for(var i = 0; i < 31; i++)
	{
		user_values[i] = {};
	}
	$("#day_chart").css("display", "none");
	$("#week_chart").css("display", "none");
	$("#month_chart").css("display", "none");
	$("#year_chart").css("display", "none");
	$("#chart_loader").css("display", "block");
	$.post("chartClassUnit.php?ajax=grab_financial_stats", {period: period, medid: medid}, function(data, status)
	{
		var d = JSON.parse(data);
		$("#chart_loader").css("display", "none");
		var consultations = d["consultations"];
		var users = d["users"];
		var num_consultations = 0;
		var num_users = d["numUsers"];
		if(period == 1) // today
		{
			Day_Chart.clear();
			var cumulative = 0;
			for(var k = 0; k < Day_Chart.datasets[1].points.length; k++)
			{
				if(k < users.length)
				{
					if(data_type == 2)
					{
						cumulative += users[k];
						day_users_data[k] = cumulative;
					}
					else
					{
						
						day_users_data[k] = users[k];
					}
				}
				else
				{
					if(data_type == 2)
					{
						day_users_data[k] = cumulative;
					}
					else
					{
						day_users_data[k] = 0;
					}
				}
			}
			cumulative = 0;
			for(var k = 0; k < Day_Chart.datasets[0].points.length; k++)
			{
				if(k < consultations.length)
				{
					if(data_type == 2)
					{
						cumulative += consultations[k];
						day_consultations_data[k] = cumulative;
					}
					else
					{
						
						day_consultations_data[k] = consultations[k];
					}
					num_consultations += consultations[k];
				}
				else
				{
					if(data_type == 2)
					{
						day_consultations_data[k] = cumulative;
					}
					else
					{
						day_consultations_data[k] = 0;
					}
				}
			}
			reload_chart(1);
			$("#day_chart").css("display", "block");
		}
		else if(period == 2) // this week
		{
			Week_Chart.clear();
			var cumulative = 0;
			for(var k = 0; k < Week_Chart.datasets[1].points.length; k++)
			{
				if(k < users.length)
				{
					if(data_type == 2)
					{
						cumulative += users[k];
						week_users_data[k] = cumulative;
					}
					else
					{
						
						week_users_data[k] = users[k];
					}
				}
				else
				{
					if(data_type == 2)
					{
						week_users_data[k] = cumulative;
					}
					else
					{
						week_users_data[k] = 0;
					}
				}
			}
			cumulative = 0;
			for(var k = 0; k < Week_Chart.datasets[0].points.length; k++)
			{
				if(k < consultations.length)
				{
					if(data_type == 2)
					{
						cumulative += consultations[k];
						week_consultations_data[k] = cumulative;
					}
					else
					{
						
						week_consultations_data[k] = consultations[k];
					}
					num_consultations += consultations[k];
				}
				else
				{
					if(data_type == 2)
					{
						week_consultations_data[k] = cumulative;
					}
					else
					{
						week_consultations_data[k] = 0;
					}
				}
			}
			reload_chart(2);
			$("#week_chart").css("display", "block");
		}
		else if(period == 3) // this month
		{
			Month_Chart.clear();
			var cumulative = 0;
			for(var k = 0; k < Month_Chart.datasets[1].points.length; k++)
			{
				if(k < users.length)
				{
					if(data_type == 2)
					{
						cumulative += users[k];
						month_users_data[k] = cumulative;
					}
					else
					{
						
						month_users_data[k] = users[k];
					}
				}
				else
				{
					if(data_type == 2)
					{
						month_users_data[k] = cumulative;
					}
					else
					{
						month_users_data[k] = 0;
					}
				}
			}
			cumulative = 0;
			for(var k = 0; k < Month_Chart.datasets[0].points.length; k++)
			{
				if(k < consultations.length)
				{
					if(data_type == 2)
					{
						cumulative += consultations[k];
						month_consultations_data[k] = cumulative;
					}
					else
					{
						
						month_consultations_data[k] = consultations[k];
					}
					num_consultations += consultations[k];
				}
				else
				{
					if(data_type == 2)
					{
						month_consultations_data[k] = cumulative;
					}
					else
					{
						month_consultations_data[k] = 0;
					}
				}
			}
		   reload_chart(3);
			$("#month_chart").css("display", "block");
		}
		else if(period == 4) // this year
		{
			Year_Chart.clear();
			var cumulative = 0;
			for(var k = 0; k < Year_Chart.datasets[1].points.length; k++)
			{
				if(k < users.length)
				{
					if(data_type == 2)
					{
						cumulative += users[k];
						year_users_data[k] = cumulative;
					}
					else
					{
						
						year_users_data[k] = users[k];
					}
				}
				else
				{
					if(data_type == 2)
					{
						year_users_data[k] = cumulative;
					}
					else
					{
						year_users_data[k] = 0;
					}
				}
			}
			cumulative = 0;
			for(var k = 0; k < Year_Chart.datasets[0].points.length; k++)
			{
				if(k < consultations.length)
				{
					if(data_type == 2)
					{
						cumulative += consultations[k];
						year_consultations_data[k] = cumulative;
					}
					else
					{
						
						year_consultations_data[k] = consultations[k];
					}
					num_consultations += consultations[k];
				}
				else
				{
					if(data_type == 2)
					{
						year_consultations_data[k] = cumulative;
					}
					else
					{
						year_consultations_data[k] = 0;
					}
				}
			}
			reload_chart(4);
			$("#year_chart").css("display", "block");
		}
		$("#number_of_consultations").text(num_consultations);
		$("#number_of_users").text(num_users);
		
	});
}
