<?php
include 'billing_header.php';         
?>    
    <div id="container_box">
        <form id="locationForm" method="post" action="billing_save.php">          
            <div class="line">
                <label for="LCID">Location Code</label><input class="codeslot ajax_search" type="text" id="LCID" name="LCID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
            </div>
            <div class="line">
                <label for="name">Name: </label><input type="text" id="name" name="name" value="" />              
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
                <label for="active">This Code is Active </label><input type="checkbox" id="active" name="active" value=1 />
            </div>
            <div class="line">
                <label for="contact">Contact: </label><input class="address" type="text" id="contact" name="contact" value="" />
                <label for="rural">Rural Health Clinic </label><input type="checkbox" id="rural" name="rural" value=1 />
            </div>
            <div class="line">
                <label for="telephone">Telephone: </label><input type="text" id="telephone" name="telephone" onkeydown="mask(this)" value="" />
                <label for="FID">Facility ID</label><input class="shortinput" type="text" id="FID" name="FID" value="" />
                <label for="publicHousing">Public Housing </label><input type="checkbox" id="publicHousing" name="publicHousing" value=1 />
            </div>
            <div class="line">
                <label for="fax">Fax: </label><input type="text" id="fax" name="fax" onkeydown="mask(this)" value="" />
                <label for="AHCAID">AHCA ID</label><input class="shortinput" type="text" id="AHCAID" name="AHCAID" value="" />
            </div>
            <div class="line">
                <label for="CLIA">CLIA # (labs only)</label><input class="shortinput" type="text" id="CLIA" name="CLIA" value="" />
                <label for="MedicareID">Medicare ID</label><input class="shortinput" type="text" id="MedicareID" name="MedicareID" value="" />
            </div>
            <div class="line">
                <label for="stateID">State ID</label><input class="shortinput" type="text" id="stateID" name="stateID" value="" />
                <label for="MedicaidID">Medicaid ID</label><input class="shortinput" type="text" id="MedicaidID" name="MedicaidID" value="" />
            </div>
            <div class="line">
                <label for="MCert">Mammography Certificate</label><input class="shortinput" type="text" id="MCert" name="MCert" value="" />
                <label for="NPI">NPI</label><input class="shortinput" type="text" id="NPI" name="NPI" value="" />
            </div>
            <div class="line">
                <label for="email">Email: </label><input type="text" id="email" name="email" value="" />               
            </div>
            <div class="line">
                <label for="HCFAPlace">HCFA Place of Service: </label>
                <select id="HCFAPlace" name="HCFAPlace">
                    <option id="21" value="21-Inpatient Hospital">21-Inpatient Hospital</option>
                </select>
            </div>
            <div class="line">
                <label for="LType">Location Type: </label>
                <select id="LType" name="LType">
                    <option id="hospital" value="Hospital">Hospital</option>
                </select>
            </div>
            <div class="line">
                <label for="TaxRate">Tax Rate: </label><input class="codeslot" type="text" id="TaxRate" name="TaxRate" value="" /> [%age - i.e. for 6.5% enter 6.5]
            </div>
            <div class="line">
                <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
            </div>   
        </form>
    </div>
    <script>
        $(function() {
            $('#locationForm').submit(function(e) {
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_locationInfo' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#locationForm input:checkbox').each(function(key, value) {
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
                var lcid = $('#LCID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "lcid="+lcid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#locationForm input, select');
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
                            if(key.indexOf("telephone") == 0 || key.indexOf("fax") == 0) {
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