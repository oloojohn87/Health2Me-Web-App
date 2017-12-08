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
function medication()
{
name = $('#drugname').val();
code $('#drugcode').val();
dose = $('#dose').val();
num = $('#npday').val();
start = $('#datepicker4').val();
end = $('#datepicker5').val();
			//alert (tbox + '  ' + dtp);
queUrl ='med.php?mname='+name+'&mcodee='+code+'&mdose='+dose+'&mnum='+num+'&mstart'+start+'&mend'+end;
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
</script>
<script type="text/javascript">
$(function() {
$( "#datepicker4" ).datepicker({
showOn: "button",
buttonImage: "images/calendar.gif",
buttonImageOnly: true
});
$( "#datepicker5" ).datepicker({
showOn: "button",
buttonImage: "images/calendar.gif",
buttonImageOnly: true
});
});
</script>
</head>
<form>
<div class="grid" style="padding:10px; height:320px; margin-top:5px;margin-bottom:10px;">
<div class="span9" style="margin-top:10px">
<span class="label label-info" style="left:0px; margin-left:0px;margin-top:20px; font-size:25px;">Medication</span>
<div class="grid" style="padding:10px; height:210px;">
<div style="float:left;width:100%;">
	<div class="fieldclass">
		<label class="inputlabelClass">Drug Name</label>
		<input type="text" name="NameMed" id="drugname" placeholder="Drug Name" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Drug Code</label>
		<input type="text" name="DRUGCODE" id="drugcode" placeholder="Drug Code" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Dossage</label>
		<input type="text" name="Dosage" id="dose" placeholder="Dosage" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Number per Day</label>
		<input type="text" name="NumberDay" id="npday" placeholder="Number Per Day" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Date Start</label>
		<input type="text" name="DateStart" id="datepicker4" placeholder="Date Start" />
	</div>
	<div class="fieldclass">
		<label class="inputlabelClass">Date Stop</label>
		<input type="text" name="DateStop" id="datepicker5" placeholder="Date Stop" />
	</div>
	
</div>
</div>
<div class="btnAdd2"><input type="submit"  id="Addmed" name="Addmed" onclick="medication()" value="Save" class="btn btn-primary"/></div>
</div>
</div>
</form>
