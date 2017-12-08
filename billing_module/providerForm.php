<?php
include 'billing_header.php';         
?>   
    <div id="container_box">
        <form id="providerForm" name="providerForm" method="post" action="billing_save.php"> 
            <div class="line">
                <label for="PVID">Provider Code</label><input class="codeslot ajax_search" type="text" id="PVID" name="PVID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
            </div>
            <div class="line">                         
                <label for="fname">First Name: </label><input type="text" id="fname" name="fname" value="" />
                <label for="mname">MI </label><input class="codeslot" type="text" id="mname" name="mname" value="" />
                <label for="lname">Last </label><input type="text" id="lname" name="lname" value="" />
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
                <label for="country">Country: </label><input type="text" id="country" name="country" value="" />
                <label for="dob">Date of Birth: </label><input type="date" id="dob" name="dob" value="" />
            </div>
            <div class="line">
                <label for="homephone">Home phone: </label><input type="text" id="homephone" name="homephone" onkeydown="mask(this)" value="" />
                <label for="cellphone">Cell phone: </label><input type="text" id="cellphone" name="cellphone" onkeydown="mask(this)" value="" />
            </div>
            <div class="line">
                <label for="fax">Fax: </label><input type="text" id="fax" name="fax" onkeydown="mask(this)" value="" />
                <label for="taxid">TAX ID</label><input class="shortinput" type="text" id="taxid" name="taxid" value="" />
            </div>
            <div class="line">
                <label for="SSNum">SS #</label><input class="shortinput" type="text" id="SSNum" name="SSNum" value="" />
                <label for="UPIN">UPIN #</label><input class="shortinput" type="text" id="UPIN" name="UPIN" value="" />
            </div>
            <div class="line">
                <label for="stateLic">State License</label><input class="shortinput" type="text" id="stateLic" name="stateLic" value="" />
                <label for="pager">Pager</label><input class="shortinput" type="text" id="pager" name="pager" value="" />
            </div>
            <div class="line">
                <label for="DEA">DEA #</label><input class="shortinput" type="text" id="DEA" name="DEA" value="" />
                <label for="credentials">Credentials</label><input class="shortinput" type="text" id="credentials" name="credentials" value="" />               
            </div>
            <div class="line">
                <label for="email">Email: </label><input type="text" id="email" name="email" value="" />               
            </div>
            <div class="line">
                <label for="specialty">Specialty: </label>
                <select id="specialty" name="specialty">
                    <option id="0" value="Hand Surgery">Hand Surgery</option>
                    <option id="1" value="Plastic Surgery">Plastic Surgery</option>
                </select>
                <label for="phy_assist">Phy Assistant: </label>
                <select id="phy_assist" name="phy_assist">
                    <option id="0" value=1>Yes</option>
                    <option id="1" value=0>No</option>
                </select>
            </div>
            <div class="line">
                <label for="seg_specialty">Second Specialty: </label>
                <select id="seg_specialty" name="seg_specialty">
                    <option id="0" value="Hand Surgery">Hand Surgery</option>
                    <option id="1" value="Plastic Surgery">Plastic Surgery</option>
                </select>
                <label for="siteid">Site ID</label><input class="shortinput" type="text" id="siteid" name="siteid" value="" />
            </div>
            <div class="line">
                <label for="taxonomyNum">Taxonomy #</label><input class="shortinput" type="text" id="taxonomyNum" name="taxonomyNum" value="" />
                <label for="NPI">NPI</label><input class="shortinput" type="text" id="NPI" name="NPI" value="" />
            </div>
            <div class="line">
                <label for="VRx_suffix">Location Type: </label>
                <select id="VRx_suffix" name="VRx_suffix">
                    <option id="MD" value="MD">MD</option>
                </select>
                <label for="active">This Code is Active </label><input type="checkbox" id="active" name="active" value=1 />
            </div>
            <div class="line">
                <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
            </div>   
        </form>
    </div>
    <script>
        $(function() {
            $('#providerForm').submit(function(e) {
                e.preventDefault();
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_providerInfo' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#providerForm input:checkbox').each(function(key, value) {
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
                //PREVENT THE ACTUAL FORM SUBMISSION
                return false;
            });
            $('#search').click(function () {
                var pvid = $('#PVID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "pvid="+pvid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#providerForm input, select');
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
                            if(key.indexOf("phone") > -1 || key.indexOf("fax") == 0) {
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