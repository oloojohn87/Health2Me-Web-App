console.log('reload_chart.js LOADED!');

//GATHER VARIABLES
var medid = $("#MEDID").val();

function reload_chart(chart)
{
	if(chart == 1) // today
	{
		//Day_Chart.clear();
		if(show_users)
		{
			for(var k = 0; k < Day_Chart.datasets[1].points.length; k++)
			{
				Day_Chart.datasets[1].points[k].value = day_users_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Day_Chart.datasets[1].points.length; k++)
			{
				Day_Chart.datasets[1].points[k].value = 0;
			}
		}
		if(show_consultations)
		{
			for(var k = 0; k < Day_Chart.datasets[0].points.length; k++)
			{
				Day_Chart.datasets[0].points[k].value = day_consultations_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Day_Chart.datasets[0].points.length; k++)
			{
				Day_Chart.datasets[0].points[k].value = 0;
			}
		}
		Day_Chart.update();
		$("#day_chart").css("display", "block");
	}
	else if(chart == 2) // this week
	{
		Week_Chart.clear();
		if(show_users)
		{
			for(var k = 0; k < Week_Chart.datasets[1].points.length; k++)
			{
				Week_Chart.datasets[1].points[k].value = week_users_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Week_Chart.datasets[1].points.length; k++)
			{
				Week_Chart.datasets[1].points[k].value = 0;
			}
		}
		if(show_consultations)
		{
			for(var k = 0; k < Week_Chart.datasets[0].points.length; k++)
			{
				Week_Chart.datasets[0].points[k].value = week_consultations_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Week_Chart.datasets[0].points.length; k++)
			{
				Week_Chart.datasets[0].points[k].value = 0;
			}
		}
		Week_Chart.update();
		$("#week_chart").css("display", "block");
	}
	else if(chart == 3) // this month
	{
		Month_Chart.clear();
		if(show_users)
		{
			for(var k = 0; k < Month_Chart.datasets[1].points.length; k++)
			{
				Month_Chart.datasets[1].points[k].value = month_users_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Month_Chart.datasets[1].points.length; k++)
			{
				Month_Chart.datasets[1].points[k].value = 0;
			}
		}
		if(show_consultations)
		{
			for(var k = 0; k < Month_Chart.datasets[0].points.length; k++)
			{
				Month_Chart.datasets[0].points[k].value = month_consultations_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Month_Chart.datasets[0].points.length; k++)
			{
				Month_Chart.datasets[0].points[k].value = 0;
			}
		}
		Month_Chart.update();
		$("#month_chart").css("display", "block");
	}
	else if(chart == 4) // this year
	{
		Year_Chart.clear();
		if(show_users)
		{
			for(var k = 0; k < Year_Chart.datasets[1].points.length; k++)
			{
				Year_Chart.datasets[1].points[k].value = year_users_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Year_Chart.datasets[1].points.length; k++)
			{
				Year_Chart.datasets[1].points[k].value = 0;
			}
		}
		if(show_consultations)
		{
			for(var k = 0; k < Year_Chart.datasets[0].points.length; k++)
			{
				Year_Chart.datasets[0].points[k].value = year_consultations_data[k];
			}
		}
		else
		{
			for(var k = 0; k < Year_Chart.datasets[0].points.length; k++)
			{
				Year_Chart.datasets[0].points[k].value = 0;
			}
		}
		Year_Chart.update();
		$("#year_chart").css("display", "block");
	}
}
