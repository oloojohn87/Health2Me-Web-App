console.log('init_chart.js LOADED!');

//GATHER VARIABLES
var medid = $("#MEDID").val();


var day_consultations_data = 
	{
		label: "Inbound",
		fillColor: "rgba(220,220,220,0.2)",
		strokeColor: "#22AEFF",
		pointColor: "#22AEFF",
		pointStrokeColor: "#22AEFF",
		pointHighlightFill: "#fff",
		pointHighlightStroke: "rgba(220,220,220,1)",
		data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
	};
var day_users_data = 
	{
		label: "Outbound",
		fillColor: "rgba(151,187,205,0.2)",
		strokeColor: "#5EB529",
		pointColor: "#5EB529",
		pointStrokeColor: "#5EB529",
		pointHighlightFill: "#fff",
		pointHighlightStroke: "rgba(151,187,205,1)",
		data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
	};
var day_consultations_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var day_users_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var day_chart_data = {
	labels: ["12:00 AM", "1:00 AM", "2:00 AM", "3:00 AM", "4:00 AM", "5:00 AM", "6:00 AM", "7:00 AM", "8:00 AM", "9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM", "6:00 PM", "7:00 PM", "8:00 PM", "9:00 PM", "10:00 PM", "11:00 PM"],
	datasets: [
		{
			label: "Consultations",
			fillColor: "rgba(220,220,220,0.2)",
			strokeColor: "#22AEFF",
			pointColor: "#22AEFF",
			pointStrokeColor: "#22AEFF",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		},
		{
			label: "Users",
			fillColor: "rgba(151,187,205,0.2)",
			strokeColor: "#5EB529",
			pointColor: "#5EB529",
			pointStrokeColor: "#5EB529",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(151,187,205,1)",
			data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		}
	]
};
var week_consultations_data = [0, 0, 0, 0, 0, 0, 0];
var week_users_data = [0, 0, 0, 0, 0, 0, 0];
var week_chart_data = {
	labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	datasets: [
		{
			label: "Consultations",
			fillColor: "rgba(220,220,220,0.2)",
			strokeColor: "#22AEFF",
			pointColor: "#22AEFF",
			pointStrokeColor: "#22AEFF",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data: [0, 0, 0, 0, 0, 0, 0]
		},
		{
			label: "Users",
			fillColor: "rgba(151,187,205,0.2)",
			strokeColor: "#5EB529",
			pointColor: "#5EB529",
			pointStrokeColor: "#5EB529",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(151,187,205,1)",
			data: [0, 0, 0, 0, 0, 0, 0]
		}
	]
};
var month_consultations_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var month_users_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var month_chart_data = {
	labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
	datasets: [
		{
			label: "Consultations",
			fillColor: "rgba(220,220,220,0.2)",
			strokeColor: "#22AEFF",
			pointColor: "#22AEFF",
			pointStrokeColor: "#22AEFF",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		},
		{
			label: "Users",
			fillColor: "rgba(151,187,205,0.2)",
			strokeColor: "#5EB529",
			pointColor: "#5EB529",
			pointStrokeColor: "#5EB529",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(151,187,205,1)",
			data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		}
	]
};
var year_consultations_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var year_users_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var year_chart_data = {
	labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	datasets: [
		{
			label: "Consultations",
			fillColor: "rgba(220,220,220,0.2)",
			strokeColor: "#22AEFF",
			pointColor: "#22AEFF",
			pointStrokeColor: "#22AEFF",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		},
		{
			label: "Users",
			fillColor: "rgba(151,187,205,0.2)",
			strokeColor: "#5EB529",
			pointColor: "#5EB529",
			pointStrokeColor: "#5EB529",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(151,187,205,1)",
			data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		}
	]
};

var day_ctx = $("#day_chart").get(0).getContext("2d");
var Day_Chart = new Chart(day_ctx).Line(day_chart_data, {bezierCurve: false});
var week_ctx = $("#week_chart").get(0).getContext("2d");
var Week_Chart = new Chart(week_ctx).Line(week_chart_data, {bezierCurve: false});
var month_ctx = $("#month_chart").get(0).getContext("2d");
var Month_Chart = new Chart(month_ctx).Line(month_chart_data, {bezierCurve: false});
var year_ctx = $("#year_chart").get(0).getContext("2d");
var Year_Chart = new Chart(year_ctx).Line(year_chart_data, {bezierCurve: false});
var user_values = [{}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}];
var current_table = 1;
var current_column = "patientSurname,patientName";
var ascending = true;
var current_page = 1;
var data_type = 1;
var show_consultations = true;
var show_users = true;
