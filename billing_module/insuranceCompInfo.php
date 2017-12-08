<?php
include 'billing_header.php';         
?>
    <div id="container_box">
        <form id="ICForm" method="post" action="billing_save.php">          
            <div class="line">
                <label for="ICID">Insurance Comp. Code: </label><input class="codeslot ajax_search" type="text" id="ICID" name="ICID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
            </div>
            <div class="line">
                <label for="ICname">Name: </label><input type="text" id="ICname" name="ICname" value="" />
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
                <label for="contact">Contact: </label><input type="text" id="contact" name="contact" value="" />
            </div>
            <div class="line">
                <label for="email">Email: </label><input type="text" id="email" name="email" value="" />
            </div>
            <div class="line">
                <label for="PBM">Primary Billing Method: </label><input type="text" id="PBM" name="PBM" value="" />
            </div>
            <div class="line">
                <label for="EM">Eligibility Method: </label><input type="text" id="EM" name="EM" value="" />
            </div>
            <div class="line">
                <label for="HCFAPayType">HCFA Patment Type: </label><input type="text" id="HCFAPayType" name="HCFAPayType" value="" />
            </div>
            <div class="line">
                <label for="CHID">Clearinghouse ID: </label><input class="codeslot" type="text" id="CHID" name="CHID" value="" />
                <label for="EID">Eligibility ID: </label><input class="codeslot" type="text" id="EID" name="EID" value="" />
                <label for="active">This Code is Active</label><input type="checkbox" id="active" name="active" value=1 />
            </div>
            <div class="line">
                <label for="paymentPercent">Payment Percent: </label><input class="codeslot" type="text" id="paymentPercent" name="paymentPercent" value="" />
                <label for="printTracerDays">Print Tracer Days: </label><input class="codeslot" type="text" id="printTracerDays" name="printTracerDays" value="" />
                <label for="AcceptAssign">Accept Assignment</label>
                <select id="AcceptAssign" name="AcceptAssign">
                    <option id="yes" value=1>Yes</option>
                    <option id="no" value=0>No</option>
                </select>
            </div>
            <div class="line">
                <span id="PNTitle" style="font-size: 16px; font-weight: bold">Phone Numbers</span>
            </div>
            <div class="line">
                <label for="ClaimPN">Claims: </label><input type="text" id="ClaimPN" name="ClaimPN" onkeydown="mask(this)" value="" />
            </div>
            <div class="line">
                <label for="BenefitsPN">Benefits: </label><input type="text" id="BenefitsPN" name="BenefitsPN" value="" onkeydown="mask(this)" />
            </div>
            <div class="line">
                <label for="AuthorPN">Authorization: </label><input type="text" id="AuthorPN" name="AuthorPN" value="" onkeydown="mask(this)" />
            </div>
            <div class="line">
                <label for="ProviderRelationsPN">Provider Relations: </label><input type="text" id="ProviderRelationsPN" name="ProviderRelationsPN" onkeydown="mask(this)" value="" />
            </div>    
            <div class="line">
                <label for="fax">Fax: </label><input type="text" id="fax" name="fax" onkeydown="mask(this)" value="" />
            </div>
            
            <div class="line">
                <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
            </div>   
        </form>
    </div>
    <script>
        $(function() {
            $('#ICForm').submit(function(e) {
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_insurCarriers' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#ICForm input:checkbox').each(function(key, value) {
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
                var icid = $('#ICID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "icid="+icid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#ICForm input, select');
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
                            if(key.indexOf("PN") > 0 || key.indexOf("fax") == 0) {
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