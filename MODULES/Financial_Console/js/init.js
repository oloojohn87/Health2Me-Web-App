console.log('init.js LOADED!');

//GATHER VARIABLES
var medid = $("#MEDID").val();

$(document).ready(function(){
$("#period_select").on("change", function()
	{
		get_main_data($("#scope_select").val(), parseInt($(this).val()));
		
		current_page = 1;
		load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
	});
	$("#scope_select").on("change", function()
	{
		get_main_data($(this).val(), parseInt($("#period_select").val()));
		get_doctors_availability();
		current_page = 1;
		load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
	});
	$("#search_bar_clear_button").on("click", function()
	{
		$("#search_bar").val("");
		$(this).css("visibility", "hidden");
		current_page = 1;
		ascending = 1;
		if(current_table == 1)
		{
			current_column = "name";
		}
		else if(current_table == 2)
		{
			current_column = "doctor";
		}
		else if(current_table == 3)
		{
			current_column = "name";
		}
		else if(current_table == 4)
		{
			current_column = "name";
		}
		load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
	});
	
	
	$("button[id^=\"segment_\"]").on("click", function()
	{
		// To select the type of table (customers, consultations, or doctors)
		if(!$(this).hasClass("segmented_control_selected"))
		{
			var selected = parseInt($(this).attr("id").split("_")[1]);
			$("button[id^=\"segment_\"]").addClass("segmented_control");
			$("button[id^=\"segment_\"]").removeClass("segmented_control_selected");
			$(this).addClass("segmented_control_selected");
			$("#customers_table").css("display", "none");
			$("#consultations_table").css("display", "none");
			$("#doctors_table").css("display", "none");
			$("#newusers_table").css("display", "none");
			if(selected == 1)
			{
				// load customers
				current_table = 1;
				$("#customers_table").css("display", "block");
				$("#caret_button_customers_name").parent().parent().children().each(function(index)
				{
					$(this).css("background-color", "#22AEFF");
				});
				$("#caret_button_customers_name").parent().css("background-color", "#6ECCFF");
				current_column = "name";
			}
			else if(selected == 2)
			{
				// load consultations
				current_table = 2;
				$("#consultations_table").css("display", "block");
				$("#caret_button_consultations_doctor").parent().parent().children().each(function(index)
				{
					$(this).css("background-color", "#22AEFF");
				});
				$("#caret_button_consultations_doctor").parent().css("background-color", "#6ECCFF");
				current_column = "doctor";
			}
			else if(selected == 3)
			{
				// load doctors
				current_table = 3;
				$("#doctors_table").css("display", "block");
				$("#caret_button_doctors_name").parent().parent().children().each(function(index)
				{
					$(this).css("background-color", "#22AEFF");
				});
				$("#caret_button_doctors_name").parent().css("background-color", "#6ECCFF");
				current_column = "name";
			}else if(selected == 4)
			{
				// load doctors
				current_table = 4;
				$("#newusers_table").css("display", "block");
				$("#caret_button_newusers").parent().parent().children().each(function(index)
				{
					$(this).css("background-color", "#22AEFF");
				});
				$("#caret_button_newusers").parent().css("background-color", "#6ECCFF");
				current_column = "name";
			}
			current_page = 1;
			ascending = 1;
			load_table(current_table, current_column, ascending, 1, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
				
		}
	});
	$("button[id^=\"toggle_\"]").on("click", function()
	{
		// To select whether the charts are regular or cumulative
		if(!$(this).hasClass("segmented_control_selected"))
		{
			data_type = parseInt($(this).attr("id").split("_")[1]);
			get_main_data($("#scope_select").val(), parseInt($("#period_select").val()));
			$("button[id^=\"toggle_\"]").addClass("segmented_control");
			$("button[id^=\"toggle_\"]").removeClass("segmented_control_selected");
			$(this).addClass("segmented_control_selected");
		}
	});
	$("button[id^=\"caret_button_\"]").on("click", function()
	{
		// for choosing the column to sort the table by
		var button_data = $(this).attr("id").split("_");
		console.log(button_data[2] + ":" + button_data[3]);
		current_column = button_data[3];
		if($(this).hasClass("icon-caret-down"))
		{
			$(this).removeClass("icon-caret-down").addClass("icon-caret-up");
			ascending = false;
		}
		else
		{
			$(this).removeClass("icon-caret-up").addClass("icon-caret-down");
			ascending = true;
		}
		load_table(current_table, current_column, ascending, 1, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
		current_page = 1;
		
		$(this).parent().parent().children().each(function(index)
		{
			$(this).css("background-color", "#22AEFF");
		});
		$(this).parent().css("background-color", "#6ECCFF");
	});
	$("button[class^=\"consultations_button\"]").on("click", function()
	{
		if($(this).hasClass("consultations_button_selected"))
		{
			$(this).removeClass("consultations_button_selected").addClass("consultations_button");
			show_consultations = false;
		}
		else if($(this).hasClass("consultations_button"))
		{
			$(this).removeClass("consultations_button").addClass("consultations_button_selected");
			show_consultations = true;
		}
		reload_chart(parseInt($("#period_select").val()));
	});
	$("button[class^=\"users_button\"]").on("click", function()
	{
		if($(this).hasClass("users_button_selected"))
		{
			$(this).removeClass("users_button_selected").addClass("users_button");
			show_users = false;
		}
		else if($(this).hasClass("users_button"))
		{
			$(this).removeClass("users_button").addClass("users_button_selected");
			show_users = true;
		}
		reload_chart(parseInt($("#period_select").val()));
	});
												  
	$("#page_button_left").on("click", function()
	{
		current_page -= 1;
		load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
		
	});
	$("#page_button_right").on("click", function()
	{
		current_page += 1;
		load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
	});
	get_main_data("Global", 1); 
	load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
	
	
});
