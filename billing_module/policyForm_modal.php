<div id="container_box" class="modal_container">
    <form id="PolicyForm" method="post" action="billing_save.php">
        <section class="half_line">
            <div class="line">
                <span id="PNTitle">Policy Information</span>
            </div>
            <div class="line">
                <label for="ICname" style="line-height: 3em;">Insurance Comapny: </label>
                <input type="text" class="shortinput auto_dropdown id_code" id="Carrier" name="Carrier" value="" />
                    <span id="ICname" class="desc"></span>
                <input type="hidden" id="ICID" name="ICID" value="" />
            </div>
            <div class="line">
                <label for="order">Payment Responsible Order: </label>
                <select id="order" name="order">
                    <option id="o1" value="Primary">Primary</option>
                    <option id="o2" value="Secondary">Secondary</option>
                    <option id="o3" value="Tertiary">Tertiary</option>
                    <option id="o4" value="Quarternary">Quarternary</option>
                </select>
            </div>
            <div class="line">
                <label for="PN">Policy Number: </label><input type="text" id="PN" name="PN" value="" />
            </div>
            <div class="line">
                <label for="GN">Group Number: </label><input type="text" id="GN" name="GN" value="" />
            </div>
            <div class="line">
                <label for="GName">Group Name: </label><input type="text" id="GName" name="GName" value="" />
            </div>
            <div class="line">
                <label for="Rel">Relationship: </label><input type="text" id="Rel" name="Rel" value="" />
            </div>
        </section>
        <section class="half_line">
            <div class="line">
                <span id="PNTitle">&emsp;&emsp;&emsp;Insurance Card Front</span>&emsp;&emsp;&emsp;&emsp;<span id="PNTitle">Insurance Card Back</span>
            </div>
            <div class="line">
                <div class="image_field"></div><div class="image_field"></div>
            </div>  
            <div class="line">
                <span id="PNTitle">Effective Dates</span>
            </div>
            <div class="line">
                <label for="dateFrom">From: </label><input type="date" id="dateFrom" name="dateFrom" value="" />
                <label for="dateThru">Thru: </label><input type="date" id="dateThru" name="dateThru" value="" />
            </div>
            <div class="line">
                <span id="PNTitle">Policy Limits</span>
            </div>
            <div class="line">
                <label for="YTD">YTD: </label><input class="codeslot" type="text" id="YTD" name="YTD" value="" />
                <label for="annual">Annual: </label><input class="codeslot" type="text" id="annual" name="annual" value="" />
            </div>
            <div class="line">
                <label for="lifetime">Lifetime: </label><input class="codeslot" type="text" id="lifetime" name="lifetime" value="" />
                <label for="payment">Insurance Payment %: </label><input class="codeslot" type="text" id="payment" name="payment" value="" />
            </div>        
        </section> 
        
        <div style="clear:both;"></div>        
        <div class="line">
            <span id="PNTitle">Policy Holder</span>&emsp;&emsp;&emsp;&emsp;<input type="checkbox" id="copy" name="copy" value="1" /> Same as Patient Info
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
            <label for="phone">Phone: </label><input type="text" id="phone" name="phone" onkeydown="mask(this)" value="" />
            <label for="dob">Date of Birth: </label><input type="date" id="dob" name="dob" value="" />
        </div>
        <div class="line">
            <label for="SSNum">SS #: </label><input type="text" id="SSNum" name="SSNum" value="" />
            <label for="gender">Gender: </label>
            <select id="gender" name="gender">
                <option id="male" value="M">M</option>
                <option id="female" value="F">F</option>
            </select>
            <label for="employer">Employer: </label><input class="codeslot" type="text" id="employer" name="employer" value="" />
        </div>
        <div class='half_line'>
            <fieldset>
                <legend>Group Health Plan</legend>
                <input type="checkbox" id="EGHPlan" name="EGHPlan" value=1><label for="EGHPlan">Employer Group Health Plan</label><br />
                <input type="checkbox" id="LGHPlan" name="LGHPlan" value=1><label for="LGHPlan">Large Group Health Plan</label>
            </fieldset>
        </div>
        <div class='half_line'>
            <div class="line">
                <span id="PNTitle">CoPays</span><span id="PNTitle">Type of Service</span>
            </div>
            <div class="line">
                <label for="CoPayDef">Default: </label><input class="shortinput" type="text" id="CoPayDef" name="CoPayDef" value="" />
                <label for="askCoPay" style="font-size:12px;">Ask CoPay Amount at Charge Entry</label><input type="checkbox" id="askCoPay" name="askCoPay" value=1>
            </div>
            <div class="line">
                <label for="alt1">Alt #1: </label><input class="shortinput" type="text" id="alt1" name="alt1" value="" />
                <select id="alt1type" name="alt1type">
                    <option id="01" value="01-Medical Care">01-Medical Care</option>
                </select>
            </div>
            <div class="line">
                <label for="alt2">Alt #2: </label><input class="shortinput" type="text" id="alt2" name="alt2" value="" />
                <select id="alt2type" name="alt2type">
                    <option id="01" value="01-Medical Care">01-Medical Care</option>
                </select>
            </div>        
            <div class="line">
                <input type="checkbox" id="auth" name="auth" value=1><label for="auth">Requires Authorization</label>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div class="line">
            <input class="btn btn-primary" type="submit" id="insert" name="insert" value="Save" />
        </div>   
    </form>
</div>
<script>
    $(function() {
        $('#PolicyForm').submit(function(e) {
            var postedData = $(this).serializeArray();
            postedData.push({ name: 'table', value: 'billing_insurPolicies' });

            //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
            var checkbox_names = new Array();
            var unchecked_checkboxes = new Array();
            $('#PolicyForm input:checkbox').each(function(key, value) {
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
        $('#copy').click(function () {
            var ptid = $('#PTID').val();
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "pid="+pid,
                dataType: "json",
                success: function(data) {
                    var $inputs = $('#PolicyForm input, select');
                    $.each(data, function(key, value) {
                        $inputs.filter(function() {
                            return key == this.name;
                        }).val($("<div>").html(value).text());
                        //MASKING PHONE NUMBERS
                        if(key.indexOf("phone") == 0) {
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