<?php
include 'billing_header.php';         
?>   
    <div id="container_box">
        <form id="procedureForm" name="procedureForm" method="post" action="billing_save.php">
            <div class="half_line">
                <div class="line">
                    <label for="PCID">Procedure Code: </label><input class="codeslot ajax_search" type="text" id="PCID" name="PCID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
                </div>
                <div class="line">                         
                    <label for="descr">Description: </label><input type="text" id="descr" name="descr" value="" />     
                </div>
                <div class="line">
                    <label for="stateDescr">Statement Description: </label><input type="text" id="stateDescr" name="stateDescr" value="" />                
                </div>
                <div class="line">
                    <label for="dept">Department: </label>
                    <select id="dept" name="dept">
                        <option value="Consults">Consults</option>
                        <option value="New Patients">New Patients</option>
                    </select>
                </div>
                <div class="line">
                    <label for="typeService">Type of Service: </label>
                    <select id="typeService" name="typeService">
                        <option value="None">N/A</option>
                        <option value="02">02 - Surgery</option>
                    </select>
                </div>
                <div class="line">
                    <label for="apptType">Appointment Type: </label>
                    <select id="apptType" name="apptType">
                        <option value="None">N/A</option>
                    </select>
                </div>
                <div class="line">
                    <label for="DocInd">Documentation Indicator: </label>
                    <select id="DocInd" name="DocInd">
                        <option value="9">9 - No Documentation Required</option>
                    </select>
                </div>
                <div class="line">
                    <label for="AttReptype">Attachment Report Type: </label>
                    <select id="AttReptype" name="AttReptype">
                        <option value="None">** None **</option>
                        <option value="04">04 - Drugs Administered</option>
                    </select>
                </div>
                <div class="line">
                    <label for="StdChrgAmt">Standard Charge Amount: </label><input class="shortinput" type="text" id="StdChrgAmt" name="StdChrgAmt" value="" />
                </div>
                <div class="line">
                    <label for="StdAllwAmt">Standard Allowed Amount: </label><input class="shortinput" type="text" id="StdAllwAmt" name="StdAllwAmt" value="" />
                </div>
                <div class="line">
                    <label for="GenLedgAmt">General Ledger Amount: </label><input class="shortinput" type="text" id="GenLedgAmt" name="GenLedgAmt" value="" />
                </div>
                <div class="line">
                    <label for="ApptLen">Appointment Length: </label><input class="codeslot" type="text" id="ApptLen" name="ApptLen" value="" /> (in minutes)          
                </div>
                <div class="line">
                    <label for="AltProcCode">Alternate Procedure Code: </label><input class="codeslot" type="text" id="AltProcCode" name="AltProcCode" value="" />
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
                    <label for="ForcedBillMethod">Forced Billing Method: </label>
                    <select id="ForcedBillMethod" name="ForcedBillMethod">
                        <option value="None">** Do Not Force **</option>
                        <option value="NPI0212">Medicate NPI HCFA 0212</option>
                    </select>
                </div>
                <fieldset>
                    <div id="feeTable">
                        <div class="tr">
                            <div class="th">Fee Table</div><div class="th">Alt Code</div><div class="th">Charge</div><div class="th">Allowed</div><div class="th">RVU</div>
                        </div>
                        <div class="tr">
                            <div class="tf">Standard</div><div class="td"></div><div class="td"></div><div class="td"></div><div class="th"></div>
                        </div>
                    </div>
                </fieldset>
                <br />
                <div class="line">
                    <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
                </div>
            </div><!--end of the left half line-->
            
            <div class="half_line">
                <div class="line">    
                    <label for="immune">Immunization </label><input type="checkbox" id="immune" name="immune" value=1 />
                    <label for="AllwSameDayDuplic">Allow Same Day Duplicates </label><input type="checkbox" id="AllwSameDayDuplic" name="AllwSameDayDuplic" value=1 />
                </div>
                <div class="line">    
                    <label for="active">This Code is Active </label><input type="checkbox" id="active" name="active" value=1 />
                    <label for="EpoetinTherapy">Epoetin Therapy </label><input type="checkbox" id="EpoetinTherapy" name="EpoetinTherapy" value=1 />
                </div>            
                <div class="line">    
                    <label for="multi">Multiply By Units </label><input type="checkbox" id="multi" name="multi" value=1 />
                    <label for="Hematocrit">Hematocrit </label><input type="checkbox" id="Hematocrit" name="Hematocrit" value=1 />
                </div>
                <div class="line">    
                    <label for="Unit2DateRg">Units to Date Range </label><input type="checkbox" id="Unit2DateRg" name="Unit2DateRg" value=1 />
                </div>
                <div class="line">    
                    <label for="Taxable">Taxable </label><input type="checkbox" id="Taxable" name="Taxable" value=1 />
                    <label for="FQHCVisit">FQHC Visit </label><input type="checkbox" id="FQHCVisit" name="FQHCVisit" value=1 />
                </div>
                <div class="line">    
                    <label for="PurchasedTest">Purchased Test </label><input type="checkbox" id="PurchasedTest" name="PurchasedTest" value=1 />
                    <label for="CompleteExam">Complete Exam </label><input type="checkbox" id="CompleteExam" name="CompleteExam" value=1 />
                </div>
                <div class="line">    
                    <label for="Hemoglobin">Hemoglobin </label><input type="checkbox" id="Hemoglobin" name="Hemoglobin" value=1 />
                    <label for="NDC">National Drug Code </label><input type="checkbox" id="NDC" name="NDC" value=1 />
                </div>
                <div class="line">    
                    <label for="NOC">NOC </label><input type="checkbox" id="NOC" name="NOC" value=1 />
                    <label for="ENC">Escribe Numerator Code </label><input type="checkbox" id="ENC" name="ENC" value=1 />
                </div>   
                <div class="line">    
                    <label for="HomeboundInd">Homebound Indicator </label><input type="checkbox" id="HomeboundInd" name="HomeboundInd" value=1 />
                </div>
            </div><!--end of the right half line-->         
        </form>
    </div>
    <script>
        $(function() {
            $('#procedureForm').submit(function(e) {
                e.preventDefault();
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_procedure_codes' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#procedureForm input:checkbox').each(function(key, value) {
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
                var pcid = $('#PCID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "pcid="+pcid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#procedureForm input, select');
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