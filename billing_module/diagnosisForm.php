<?php
include 'billing_header.php';         
?>   
    <div id="container_box">
        <form id="diagnosisForm" name="diagnosisForm" method="post" action="billing_save.php">
            <div class="line">
                <label for="DGID">Diagnosis Code: </label><input class="codeslot ajax_search" type="text" id="DGID" name="DGID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
            </div>
            <div class="line">                         
                <label for="descr">Description: </label><input type="text" id="descr" name="descr" value="" />     
            </div>
            <div class="line">
                <label for="stateDescr">Statement Description: </label><input type="text" id="stateDescr" name="stateDescr" value="" />                
            </div>
            <div class="line">
                <label for="AltProcCode1">Alternate Procedure Code #1: </label><input class="codeslot" type="text" id="AltProcCode1" name="AltProcCode1" value="" />
            </div>
            <div class="line">
                <label for="AltProcCode2">Alternate Procedure Code #2: </label><input class="codeslot" type="text" id="AltProcCode2" name="AltProcCode2" value="" />
            </div>
            <div class="line">
                <label for="AgeSpec">Age Specific: </label>
                <select id="AgeSpec" name="AgeSpec">
                    <option value="A">All Ages</option>
                    <option value="1">Below 1</option>
                    <option value="18">Below 18</option>
                    <option value="14">Over 14</option>
                    <option value="55">12 To 55</option>
                </select>
            </div>
            <div class="line">
                <label for="SexSpec">Sex Specific: </label>
                <select id="SexSpec" name="SexSpec">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    <option value="B">Both</option>
                </select>
            </div>
            <div class="line">    
                <label for="active">This Code is Active </label><input type="checkbox" id="active" name="active" value=1 />
            </div>            
            <div class="line">
                <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
            </div>           
        </form>
    </div>
    <script>
        $(function() {
            $('#diagnosisForm').submit(function(e) {
                e.preventDefault();
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_diagnoses' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#diagnosisForm input:checkbox').each(function(key, value) {
                    checkbox_names.push(value.name);
                });
                $.each(checkbox_names, function(key, value) {
                    var checker = false;
                    $.map(postedData, function(obj) {
                        if (value == obj.name) checker = true;
                    });
                    if(!checker) unchecked_checkboxes.push(value);
                });
                $.each(unchecked_checkboxes, function(key, val) {
                    postedData.push({ name: val, value: 0 });
                });
                var formURL = $(this).attr("action");
                console.log(formURL);
                $.ajax({
                    type: "POST",
                    url: formURL,
                    data: postedData,
                    success: function(data) {
                        if(data == "Successfully Saved!") alert(data);
                        else console.log("Error: "+data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });                
                //PREVENT THE ACTUAL FORM SUBMISSION
                return false;
            });
            $('#search').click(function () {
                var dgid = $('#DGID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "dgid="+dgid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#diagnosisForm input, select');
                        $.each(data, function(key, value) {                           
                            $inputs.filter(function() {
                                //CHECKBOX VALIDATION 
                                if(this.type == "checkbox") {
                                    if (value == 1) $('#'+key).prop('checked',true);
                                    else $('#'+key).prop('checked',false);
                                }
                                else return key == this.name;
                            }).val($("<div>").html(value).text());    
                        });                      
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>
</html>