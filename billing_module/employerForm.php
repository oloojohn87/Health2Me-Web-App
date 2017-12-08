<?php
include 'billing_header.php';
?>  
    <style type="text/css">
        textarea { width: 560px; height: 260px; }
    </style>
    <div id="container_box">
        <form id="employerForm" method="post" action="billing_save.php">     
            <div class="line">
                <label for="EPID">Employer ID: </label><input class="codeslot ajax_search" type="text" id="EPID" name="EPID" value="" />    
                <input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
            </div> 
            <div class="line">
                <label for="name">Employer: </label><input type="text" id="name" name="name" value="" />     
            </div>
            <div class="line">
                <label for="address1">Address #1: </label><input class="address" type="text" id="address1" name="address1" value="" />     
            </div>
            <div class="line">
                <label for="address2">Address #2: </label><input class="address" type="text" id="address2" name="address2" value="" />
            </div>  
            <div class="line">
                <label for="city">City: </label><input type="text" id="city" name="city" value="" />
                <label for="state">State: </label><input class="codeslot" type="text" id="state" name="state" value="" />
                <label for="zip">Zip Code: </label><input class="shortinput" type="text" id="zip" name="zip" value="" />
            </div>
            <div class="line">
                <label for="contact">Contact Person: </label><input type="text" id="contact" name="contact" value="" />
            </div> 
            <div class="line">
                <label for="telephone">Telephone: </label><input type="text" id="telephone" name="telephone" onkeydown="mask(this)" value="" />
                <label for="fax">Fax: </label><input type="text" id="fax" name="fax" value="" onkeydown="mask(this)" />
            </div>
            <div class="line">
                <label for="active">Active? </label><input type="checkbox" id="active" name="active" value=1>
            </div>
            <div class="line">
                <label for="notes">Notes: </label><textarea id="notes" name="notes" ></textarea>
            </div>   
            <br />
            <div class="line">
                <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
            </div>   
        </form>
    </div>
    
    <script>
        $(function() {
            $('#employerForm').submit(function(e) {
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_employers' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#employerForm input:checkbox').each(function(key, value) {
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
                $.ajax({
                    type: "POST",
                    url: formURL,
                    data: postedData,
                    success: function(data) {
                        if(data == "Successfully Saved!") alert(data);
                        else console.log("Error: "+data);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
                e.preventDefault();
                //PREVENT THE ACTUAL FORM SUBMISSION
                return false;
            });
            $('#search').click(function () {
                var epid = $('#EPID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "epid="+epid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#employerForm input, textarea');
                        $.each(data, function(key, value) {
                            $inputs.filter(function() {
                                //CHECKBOX VALIDATION 
                                if(this.type == "checkbox") {
                                    if (value == 1) $('#'+key).prop('checked',true);
                                    else $('#'+key).prop('checked',false);
                                }
                                else return key == this.name;
                            }).val($("<div>").html(value).text());
                            //MASKING PHONE NUMBERS
                            if(key.indexOf("phone") > 0 || key.indexOf("fax") == 0) {
                                if(value == 0) $('#'+key).val(null);
                                else mask(document.getElementById(key));
                            }                             
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