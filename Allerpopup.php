<head>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />
<style> 
.fieldclass { float:left;width:100%;margin-bottom:10px; }
.btnAdd2 { float:right; margin:10px 0px 10px 10px;}
.inputlabelClass { float:left;width:30%;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
font-size: 13px;
line-height: 20px;
color: rgb(51, 51, 51); }
.grid {
  	background:#fff;
		margin-top:30px;
		border:1px solid #cacaca;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
.label,
.badge {
  font-size: 11.844px;
  font-weight: bold;
  line-height: 14px;
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  white-space: nowrap;
  vertical-align: baseline;
  background-color: #999999;
}
.label {
  padding: 1px 4px 2px;
  margin-top:20px;
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}
.label-info,
.badge-info {
  background-color: #22aeff;
}
body {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  }
 input[type="button"]
  {
  width: auto;
}
button,
input[type="button"],
input[type="reset"],
input[type="submit"] {
  cursor: pointer;
  -webkit-appearance: button;
}
.btn-primary {
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #61a4f0;
  background-image: linear-gradient(to bottom, #61a4f0, #2c63c2);
  background-repeat: repeat-x;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
}
.btn-primary:hover,
.btn-primary:active,
.btn-primary.active,
.btn-primary.disabled,
.btn-primary[disabled] {
  color: #ffffff;
  background-color: #2c63c2;
  *background-color: #2c63c2;
}
.btn {
  display: inline-block;
  *display: inline;
  *margin-left: .3em;
  font-size: 13px;
  text-align: center;
  vertical-align: middle;
  -webkit-border-radius: 3px;
          border-radius: 3px;
  -webkit-box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
  box-shadow:         0px 1px 3px rgba(0, 0, 0, 0.1);
}
input,
button,
select,
textarea {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
label,
input,
button,
select,
textarea {
  font-weight: normal;
}
.btn:hover {
  text-decoration: none;
  *background-color: #d9d9d9;
  -webkit-transition: background-position 0.1s linear;
          transition: background-position 0.1s linear;
}
.btnAdd1 { float:right; margin:40px 0px 40px 40px;}
</style>
<script>
function allergies()
{
nme = $('#allergyname').val();
typ = $('#type').val();
dte = $('#datepicker4').val();
descr = $('#description').val();
			//alert (tbox + '  ' + dtp);
queUrl ='aller.php?name='+nme+'&type='+typ+'&rec='+dte+'&desc='+descr;
	var sal2 = LanzaAjax (queUrl);
	alert('Data Entered');

}

	function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
	    $.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		}    


$(function() {
$( "#datepicker4" ).datepicker({
showOn: "button",
buttonImage: "images/calendar.gif",
buttonImageOnly: true
});

});
</script>
</head>
<form>
<div class="grid" style="padding:10px; height:260px;">
<div class="span9" style="margin-top:10px">
<span class="label label-info" style="left:0px; margin-left:0px; font-size:25px;">Allergies</span>
<div class="grid" style="padding:10px; height:150px;">
<div style="float:left;width:100%;">
	<div class="fieldclass">
		<label class="inputlabelClass">Allergy Name</label>
		<input type="text" name="NameAller" id="allergyname" placeholder="Allergy Name" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Type</label>
		<input type="text" name="TypeAller" id="type" placeholder="Type( Food Drug Pollen etc)" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Date Recorded</label>
		<input type="text" name="DateRec" id="datepicker4" placeholder="Date Recorded"/>
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Description</label>
		<input type="text" name="Description" id="description" placeholder="Description(Severity)" />
	</div>
	
</div>	
</div>
<div class="btnAdd2"><input type="submit"  id="Addaller" name="Addaller" value="Save" onclick="allergies()" class="btn btn-primary"/></div>
</div>
</div>
</form>