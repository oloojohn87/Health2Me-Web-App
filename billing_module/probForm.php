<?php
include 'billing_header.php';
?>  
    <div id="container_box">
        <form id="problemForm" method="post" action="billing_save.php" > 
            <div class="half_line">
                <div class="line">
                    <label for="ProbID">Problem ID:</label><input class="codeslot ajax_search" type="text" id="ProbID" name="ProbID" value="" />
                    <input lang="en" class="btn btn-primary" type="button" id="psearch" name="psearch" value="Search" />
                </div>
                <div class="line">
                    <label for="Prob">Problem</label><input type="text" id="Prob" name="Prob" value="" />   
                </div>
                <div class="line">
                    <label for="refPhysician">Referring Physician: </label><input class="shortinput" type="text" id="refPhysician" name="refPhysician" value="" />              
                </div>
                <div class="line">
                    <label for="diag1">Diagnosis #1: </label><input class="codeslot auto_dropdown id_code" type="text" id="diag1" name="diag1" value="" /> 
                    <span id="display_for_diag1" class="desc"></span>
                </div>
                <div class="line">
                    <label for="diag2">&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp; #2: </label><input class="codeslot auto_dropdown id_code" type="text" id="diag2" name="diag2" value="" /> 
                    <span id="display_for_diag2" class="desc"></span>
                </div>
                <div class="line">
                    <label for="diag3">&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp; #3: </label><input class="codeslot auto_dropdown id_code" type="text" id="diag3" name="diag3" value="" /> 
                    <span id="display_for_diag3" class="desc"></span>
                </div>
                <div class="line">
                    <label for="diag4">&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp; #4: </label><input class="codeslot auto_dropdown id_code" type="text" id="diag4" name="diag4" value="" /> 
                    <span id="display_for_diag4" class="desc"></span>
                </div>
                <div class="line">
                    <label for="employer">Employer: </label><input class="codeslot" type="text" id="employer" name="employer" value="" /> 
                    <span id="display_for_employer"></span>
                </div>
                <div class="line">
                    <label for="accState">Accident State: </label><input class="codeslot" type="text" id="accState" name="accState" value="" /> 
                </div>
                <div class="line">
                    <label for="localUse">Local Use: </label><input type="text" id="localUse" name="localUse" value="" /> 
                </div>
                <div class="line">
                    <label for="delayReason">Delay Reason: </label><input type="text" id="delayReason" name="delayReason" value="" /> 
                </div>
                <div class="line">    
                    <label for="EmpRel">Employment Related </label><input type="checkbox" id="EmpRel" name="EmpRel" value=1 />
                    <label for="EmergTreat">Emergency Treatment </label><input type="checkbox" id="EmergTreat" name="EmergTreat" value=1 />
                </div>
                <div class="line">    
                    <label for="AutoAcc">Automobile Accident </label><input type="checkbox" id="AutoAcc" name="AutoAcc" />
                    <label for="OutLabWork">Outside Lab Work </label><input type="checkbox" id="OutLabWork" name="OutLabWork" />
                </div>            
                <div class="line">    
                    <label for="otherAcc">Other Accident </label><input type="checkbox" id="otherAcc" name="otherAcc" />
                    <label for="FamPlan">Family Planning </label><input type="checkbox" id="FamPlan" name="FamPlan" />
                </div>
                <div class="line">    
                    <label for="SymptRel">Symptom Related </label><input type="checkbox" id="SymptRel" name="SymptRel"/>
                    <label for="injuryRel">Injury Related </label><input type="checkbox" id="injuryRel" name="injuryRel" />
                </div>
                <div class="line">    
                    <label for="XRaysAvail">X-Rays Available </label><input type="checkbox" id="XRaysAvail" name="XRaysAvail" />
                    <label for="MedUnnecc">Medically Unnecessary </label><input type="checkbox" id="MedUnnecc" name="MedUnnecc" />
                </div>
                <div class="line">    
                    <label for="ThirdPartyLiab">3rd Party Liable </label><input type="checkbox" id="ThirdPartyLiab" name="ThirdPartyLiab" />
                    <label for="EPSDT">EPSDT </label><input type="checkbox" id="EPSDT" name="EPSDT" />
                </div>
                <div class="line">    
                    <label for="blLung">Black Lung </label><input type="checkbox" id="blLung" name="blLung" value=1 />
                    <label for="ESRD">ESRD </label><input type="checkbox" id="ESRD" name="ESRD" />
                </div>            
                <fieldset>
                    <legend>UB 92 Information</legend>
                        <label for="UB92Freq">Frequency [3rd Digit]: </label>
                        <select id="UB92Freq" name="UB92Freq">
                            <option id="none" value="None">None Yet</option>
                        </select>
                </fieldset>
                <br />
                <div class="line">
                    <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
                </div> 
            </div>            
            <div class="half_line">
                <div id="line">
                    <label>Patient Name: </label><span id="fname" class="desc"></span>&nbsp;<span id="mname" class="desc"></span>&nbsp;<span id="lname" class="desc"></span>
                </div>
                <div class="line">
                    <span id="PNTitle">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span><span id="PNTitle">Start</span><span id="PNTitle">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span><span id="PNTitle">End</span>
                </div>
                <div class="line">
                    <label for="TreatStart">Treatment: </label><input type="date" id="TreatStart" name="TreatStart" value="" />&emsp;<input type="date" id="TreatEnd" name="TreatEnd" value="" />
                </div>
                <div class="line">
                    <label for="DisStart">Disability: </label><input type="date" id="DisStart" name="DisStart" value="" />&emsp;<input type="date" id="DisEnd" name="DisEnd" value="" />
                </div>
                <div class="line">
                    <label for="HospStart">Hospitalization: </label><input type="date" id="HospStart" name="HospStart" value="" />&emsp;<input type="date" id="HospEnd" name="HospEnd" value="" />
                </div>
                <div class="line">
                    <label for="Consulted">Consulted: </label><input type="date" id="Consulted" name="Consulted" value="" />
                </div>
                <div class="line">
                    <label for="ill">Illness/Injury: </label><input type="date" id="ill" name="ill" value="" />
                </div>
                <div class="line">
                    <label for="ReturnWork">Return to Work: </label><input type="date" id="ReturnWork" name="ReturnWork" value="" />
                </div>
                <div class="line">
                    <label for="simill">Similar Illness: </label><input type="date" id="simill" name="simill" value="" />
                </div>
                <div class="line">
                    <label for="XRayTaken">X-Rays Taken: </label><input type="date" id="XRayTaken" name="XRayTaken" value="" />
                </div>
                <div class="line">
                    <label for="LastSeen">Last Seen: </label><input type="date" id="LastSeen" name="LastSeen" value="" />
                </div>
                <div class="line">
                    <label for="LastMenst">Last Menstrual: </label><input type="date" id="LastMenst" name="LastMenst" value="" />
                </div>
                <div class="line">
                    <label for="DisType">Disability Type: </label>
                    <select id="DisType" name="DisType">
                        <option id="noDis" value="No Disability">No Disability</option>
                        <option id="some" value="Some Disability">Some Disability</option>
                    </select>
                </div>
                <div class="line">
                    <label for="K3">K3: </label><input type="text" id="K3" name="K3" value="" />
                </div>
                <div class="line">
                    <label for="OthClmCtrlNum">Other Claim Ctrl#: </label><input type="text" id="OthClmCtrlNum" name="OthClmCtrlNum" value="" />
                </div>
                <div id="payOrderContainer">
                    <span id="PNTitle">Payment Responsibility Order</span>
                    <textarea id="payOrder"></textarea>
                    <input type="button" id="payOrderChange" name="payOrderChange" value="Change Above List" />
                </div>
            </div>   
            <div style="clear:both;"></div>
       </form>
    </div>   
    <script type="text/javascript"> 
        //TO SEE IF THE FORM IS SUCCESSSFULLY SAVED
        var pass = false;
        //SUBMIT FORM AJAX
        $('#problemForm').submit(function(e) {         
            e.preventDefault();
            var postedData = $(this).serializeArray();
            postedData.push({ name: 'table', value: 'billing_probInfo' });

            //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
            var checkbox_names = new Array();
            var unchecked_checkboxes = new Array();
            $('#problemForm input:checkbox').each(function(key, value) {
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
                    if(data == "Successfully Saved!") {
                        localStorage.setItem('pass', true);
                        alert(data);
                    }
                    else console.log("Error: "+data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
            //PREVENT THE ACTUAL FORM SUBMISSION
            return false;
        });
        //SEARCH FUNCTION AJAX FILLING UP THE FORM FROM THE DB
        $('#psearch').click(function () {
            var probid = $('#ProbID').val();
            //GETTING PTID    
            var ptid = localStorage.getItem("ptid_hidden");
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "probid="+probid+"&ptid="+ptid,
                dataType: "json",
                success: function(data) {
                    var $inputs = $('#problemForm input, select');
                    $.each(data, function(key, value) {                          
                        $inputs.filter(function() {
                            //CHECKBOX VALIDATION 
                            if(this.type == "checkbox") {
                                if (value == 1) $('#'+key).prop('checked',true);
                                else $('#'+key).prop('checked',false);
                            }
                            else return key == this.name;
                        }).val($("<div>").html(value).text());
                        //FILL UP id_codes
                        var code_id = $inputs.filter('.id_code').filter(function() {
                            return key == this.name;
                        }).attr('id');
                        if (typeof(code_id) !== "undefined") {
                            var elem = this;
                            var id, table, query = "";
                            if(key.indexOf("diag") == 0) {
                                id = "DGID";
                                table = "billing_diagnoses";
                                query = value;
                            }
                            $.ajax({
                                type: "POST",
                                url: "search_code.php",
                                data: "id="+id+"&table="+table+"&query="+query,
                                dataType: 'json',
                                cache: false,
                                success: function(data) { 
                                    $('#'+key).val($("<div>").html(data.id).text());
                                    $('#display_for_'+key).html(data.name+"<br>Alt: "+data.AltProcCode1+", "+data.AltProcCode2+" "+data.AgeSpec+" "+data.SexSpec);
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
    </script>
</body>
</html>