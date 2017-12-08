<?php
include 'billing_header.php';         
?>   
    <div id="container_box">
        <form id="modifierForm" name="modifierForm" method="post" action="billing_save.php">
            <div class="line">
                <label for="MCode">Modifier Code: </label><input class="codeslot ajax_search" type="text" id="MCode" name="MCode" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
            </div>
            <div class="line">                         
                <label for="descr">Description: </label><input type="text" id="descr" name="descr" value="" />     
            </div>
            <div class="line">
                <label for="fMult">Fee Multiplier: </label><input class="codeslot" type="text" id="fMult" name="fMult" value="1.00" />                
            </div>
            <div class="line">
                <label for="AppTo">Applies To: </label>
                <select id="AppTo" name="AppTo">
                    <option value="both">Both</option>
                    <option value="Allowed">Allowed Only</option>
                    <option value="Charge">Charge Only</option>
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
            $('#modifierForm').submit(function(e) {
                e.preventDefault();
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_modifiers' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#modifierForm input:checkbox').each(function(key, value) {
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
                var mcode = $('#MCode').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "mcode="+mcode,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#modifierForm input, select');
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