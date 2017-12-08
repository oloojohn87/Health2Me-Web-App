<?php
include 'billing_header.php';
?>  
    <div id="container_box">
        <form id="patientForm" method="post" action="billing_save.php">          
            <div class="line">
                <label for="PTID">Account</label><input class="codeslot ajax_search" type="text" id="PTID" name="PTID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
                <label for="SSN">SS #: </label><input type="text" id="SSN" name="SSN" value="" />
            </div>
            <div class="line">
                <label for="lname">Last Name: </label><input type="text" id="lname" name="lname" value="" />              
                <label for="fname">First </label><input type="text" id="fname" name="fname" value="" />
                <label for="mname">MI </label><input class="codeslot" type="text" id="mname" name="mname" value="" />
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
                <label for="DOB">Date of Birth: </label><input type="date" id="DOB" name="DOB" value="" />
            </div>
            <div class="line">
                <label for="HomePhone">Home Phone: </label><input type="text" id="HomePhone" name="HomePhone" onkeydown="mask(this)" value="" />
                <label for="WorkPhone">Work </label><input type="text" id="WorkPhone" name="WorkPhone" value="" onkeydown="mask(this)" />
                <label for="WorkPhoneExt">Ext. </label><input class="codeslot" type="text" id="WorkPhoneExt" name="WorkPhoneExt" value="" />
            </div>
            <div class="line">
                <label for="CellPhone">Cellular: </label><input type="text" id="CellPhone" name="CellPhone" onkeydown="mask(this)" value="" />
                <label for="veteran">Veteran? </label><input type="checkbox" id="veteran" name="veteran" value=1>
                <label for="firstVisit">First Visit: </label><input type="date" id="firstVisit" name="firstVisit" value="" />
            </div>
            <div class="line">
                <label for="salut">Salutation: </label><input class="codeslot" type="text" id="salut" name="salut" value="" />
                <label for="lastVisit">Last Visit: </label><input type="date" id="lastVisit" name="lastVisit" value="" />
            </div>
            <div class="line">
                <label for="race">Race: </label>
                <select id="race" name="race">
                    <option id="native" value="American Indian or Alaska Native">American Indian or Alaska Native</option>
                    <option id="asian" value="Asian">Asian</option>
                    <option id="black" value="Black or African American">Black or African American</option>
                    <option id="islander" value="Native Hawaiian or Other Pacific Islander">Native Hawaiian or Other Pacific Islander</option>
                    <option id="white" value="White">White</option>
                    <option id="other" value="Other Race">Other Race</option>
                </select>   
                <label for="gender">Gender: </label>
                <select id="gender" name="gender">
                    <option id="male" value="M">M</option>
                    <option id="female" value="F">F</option>
                </select>         
            </div>
            <div class="line">
                <label for="ethnicity">Ethnicity: </label>
                <select id="ethnicity" name="ethnicity">
                    <option id="hispanic" value="Hispanic or Latino">Hispanic or Latino</option>
                    <option id="non_hispanic" value="Not Hispanic or Latino">Not Hispanic or Latino</option>
                </select>
                <label for="signature">Signature On File: </label>
                <select id="signature" name="signature">
                    <option id="yes" value=1>Yes</option>
                    <option id="no" value=0>No</option>
                </select>
            </div>
            <div class="line">
                <label for="FC">Financial Class: </label><input class="shortinput auto_dropdown" type="text" id="FC" name="FC" value="" />
                <input type="hidden" id="FCID" name="FCID" value="" />
                <span id="FC-desc" class="desc"></span>
                <label for="rinfo">Release Info: </label>
                <select id="rinfo" name="rinfo">
                    <option id="yes" value=1>Yes</option>
                    <option id="no" value=0>No</option>
                </select>
            </div>
            <div class="line">
                <label for="occupation">Occupation: </label><input type="text" id="occupation" name="occupation" value="" />
                <label for="mstatus">Marital Status: </label>
                <select id="mstatus" name="mstatus">
                    <option id="single" value="Single">Single</option>
                    <option id="married" value="Married">Married</option>
                    <option id="separated" value="Separated">Separated</option>
                    <option id="divorced" value="Divorced">Divorced</option>
                    <option id="widowed" value="Widowed">Widowed</option>
                    <option id="partnered" value="Partnered">Partnered</option>
                    <option id="unknow" value="Unknown">Unknown</option>
                </select>
            </div>
            <div class="line">
                <label for="email">Email: </label><input type="text" id="email" name="email" value="" />
                <label for="assignedBenefit">Assigned Benefits: </label>
                <select id="assignedBenefit" name="assignedBenefit">
                    <option id="yes" value=1>Yes</option>
                    <option id="no" value=0>No</option>
                </select>
            </div>
            <div class="line">
                <label for="DriversLicNo">Driver's License #: </label><input type="text" id="DriversLicNo" name="DriversLicNo" value="" />
                <label for="contactinstr">Contact Instruction: </label><input class="shortinput" type="text" id="contactinstr" name="contactinstr" value="" />
                &emsp;&emsp;&emsp;<input lang="en" class="btn btn-primary" type="button" id="addPol" name="addPol" value="Add A Policy" />
            </div>
            <div class="line">
                <div class="half_line">
                    <label for="Carrier1">Carrier #1: </label><input class="shortinput auto_dropdown id_code" type="text" id="Carrier1" name="Carrier1" value="" />
                    <input type="hidden" id="Carrier1ID" name="Carrier1ID" value="" />
                    <span id="Carrier1-desc" class="desc"></span>
                </div>
                <div class="half_line">
                    <label for="Carrier2">Carrier #2: </label><input class="shortinput auto_dropdown id_code" type="text" id="Carrier2" name="Carrier2" value="" />
                    <input type="hidden" id="Carrier2ID" name="Carrier2ID" value="" />
                    <span id="Carrier2-desc" class="desc"></span>
                </div>
            </div>
            <div class="line">
                <div class="half_line">
                    <label for="Carrier3">Carrier #3: </label><input class="shortinput auto_dropdown id_code" type="text" id="Carrier3" name="Carrier3" value="" />
                    <input type="hidden" id="Carrier3ID" name="Carrier3ID" value="" />
                    <span id="Carrier3-desc" class="desc"></span>
                </div>              
                <div class="half_line">
                    <label for="Carrier4">Carrier #4: </label><input class="shortinput auto_dropdown id_code" type="text" id="Carrier4" name="Carrier4" value="" />
                    <input type="hidden" id="Carrier4ID" name="Carrier4ID" value="" />
                    <span id="Carrier4-desc" class="desc"></span>
                </div>
            </div>
            <div class="line">
                <div class="half_line">
                    <label for="PV">Provider: </label><input class="auto_dropdown id_code" type="text" id="PV" name="PV" value="" />
                    <input type="hidden" id="PVID" name="PVID" value="" />
                    <span id="PV-desc" class="desc"></span>
                </div>
                <div class="half_line">
                    <label for="LC">Location: </label><input class="shortinput auto_dropdown id_code" type="text" id="LC" name="LC" value="" />
                    <input type="hidden" id="LCID" name="LCID" value="" />
                    <span id="LC-desc" class="desc"></span>
                </div>
            </div>
            <div class="line">
                <div class="half_line">
                    <label for="refer">Referred By: </label><input class="shortinput auto_dropdown" type="text" id="refer" name="refer" value="" />
                    <input type="hidden" id="referred" name="referred" value="" />
                    <span id="refer-desc" class="desc"></span>
                </div>
                <div class="half_line">
                    <label for="accountStat">Account Status: </label>
                    <select id="accountStat" name="accountStat">
                        <option id="active" value=1>Active</option>
                        <option id="settled" value=0>Settled</option>
                    </select>
                </div>
            </div>           
            <div class="line">
                <div class="half_line">
                    <label for="ATT">Attorney: </label><input class="shortinput auto_dropdown" type="text" id="ATT" name="ATT" value="" />
                    <input type="hidden" id="ATTID" name="ATTID" value="" />
                    <span id="ATT-desc" class="desc"></span>
                </div>
                <div class="half_line">
                    <label for="PCP">PCP: </label><input class="shortinput auto_dropdown" type="text" id="PCP" name="PCP" value="" />
                    <input type="hidden" id="PCPID" name="PCPID" value="" />
                    <span id="PCP-desc" class="desc"></span>
                    <!--label for="formMethod">Form Method: </label><input class="shortinput" type="text" id="formMethod" name="formMethod" value="" /-->
                </div>
            </div>    
            <br />
            <div class="line">
                <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
            </div>   
        </form>
        <!-- CARRIER MODAL STARTS -->
        <div id="carrierModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="carrierModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="carrierModalLabel">Policy Information</h4>
              </div>
              <div class="modal-body">
                <?php include "policyForm_modal.php" ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- CARRIER MODAL ENDS -->
    </div>
    
    <script>
        $(function() {
            $('#patientForm').submit(function(e) {
                e.preventDefault();
                var postedData = $(this).serializeArray();
                postedData.push({ name: 'table', value: 'billing_patients' });
                
                //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
                var checkbox_names = new Array();
                var unchecked_checkboxes = new Array();
                $('#patientForm input:checkbox').each(function(key, value) {
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
                var ptid = $('#PTID').val();
                $.ajax({
                    type: "POST",
                    url: "billing_idsearch.php",
                    data: "ptid="+ptid,
                    dataType: "json",
                    success: function(data) {
                        var $inputs = $('#patientForm input, select');
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
                            if(key.indexOf("Phone") > 0 && key.length < 10) {
                                if(value == 0) $('#'+key).val(null);
                                else mask(document.getElementById(key));
                            }       
                            //FILL UP id_codes
                            var code_id = $inputs.filter('.id_code').filter(function() {
                                return key == this.name+'ID';
                            }).attr('id');
                            if (typeof(code_id) !== "undefined") {
                                var elem = this;
                                var id, table, query = "";
                                if (key == "PVID") {
                                    id = "PVID";
                                    table = "billing_providerInfo";
                                    query = value;
                                }
                                else if(key.indexOf("Carrier") == 0) {
                                    id = "ICID";
                                    table = "billing_insurCarriers";
                                    query = value;
                                }
                                else if(key == "LCID") {
                                    id = "LCID";
                                    table = "billing_locationInfo";
                                    query = value;
                                }
                                $.ajax({
                                    type: "POST",
                                    url: "search_code.php",
                                    data: "id="+id+"&table="+table+"&query="+query,
                                    dataType: 'json',
                                    cache: false,
                                    success: function(data) {             
                                        var select_id = '#'+key.substring(0, key.length - 2);
                                        $(select_id).val($("<div>").html(data.name).text());
                                        //ONLY WHEN CARRIERS WERE SELECTED
                                        if (id == 'ICID') $(select_id+'-desc').html(data.address1+" "+data.address2+"<br>"+data.city+", "+data.state+", "+data.zip);
                                    },
                                    error: function(error) {
                                        console.log('Error: '+error);
                                    }
                                });
                            }
                        });                      
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
            //ADD A POLICY
            $('#addPol').click(function() {
                $('#carrierModal').modal('toggle');
            });
        });
    </script>
</body>
</html>