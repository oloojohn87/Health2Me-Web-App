<?php
include 'billing_header.php';         
?>
<body>          
    <div id="container_box">
        <form id="transactionForm" method="post" action="billing_save.php"> 
            <section id="left_column">
                <div class="line">
                    <label for="PTID">Account: </label><input class="codeslot ajax_search" type="text" id="PTID" name="PTID" value="" /><input lang="en" class="btn btn-primary" type="button" id="search" name="search" value="Search" />
                </div>
                <div class="line">
                    &emsp;&emsp;&emsp;<span id="fname" class="desc"></span>&nbsp;<span id="mname" class="desc"></span>&nbsp;<span id="lname" class="desc"></span>
                </div>
                <br />
                <div class="line">
                    <label for="PBal">Patient Balance: </label>
                    <span id="PBal" class="desc"></span>
                </div>
                <div class="line">
                    <label for="NPBal">NonPatient Balance: </label>
                    <span id="NPBal" class="desc"></span>
                </div>
                <div class="line">
                    <label for="UACredits">Unapplied Credits: </label>
                    <span id="UACredits" class="desc"></span>
                </div>
                <fieldset>
                    <legend>Transaction Type</legend>
                    <div class="line">
                        <button type="button" id="CHRG" class="btn btn-default control" data-toggle="modal" data-target="#problemModal">Charges</button>
                    </div>
                    <div class="line">
                        <a href="#" id="PAA" role="button" class="btn btn-default control">Payments and Adjustments</a>            
                    </div>
                    <div class="line">
                        <a href="#" id="DCC" role="button" class="btn btn-default control">Delete/Change Charge</a> 
                    </div>
                    <div class="line">
                        <a href="#" id="AOR" role="button" class="btn btn-default control">Authorization of Referral</a> 
                    </div>
                </fieldset>
                <!--Notes Modal Starts-->
                <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="notesModalLabel">Notes</h4>
                      </div>
                      <div class="modal-body">
                        <textarea id="notes" style="width: 95%;" onkeyup="AutoGrowTextArea(this)"></textarea>
                      </div>
                      <div class="modal-footer">
                          <button type="button" id="noteSave" class="btn btn-primary" data-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!--Notes Modal Ends-->
                </form><!-- end of transaction form-->
                <br />
                <div class="line">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#notesModal">Notes</button>                    
                </div>     
            </section>
            <!-- CHARGES MODAL STARTS -->
            <div id="problemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="problemModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="problemModalLabel">Problem Information</h4>
                  </div>
                  <div class="modal-body">
                    <?php include "probForm_modal.php" ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- CHARGES MODAL ENDS -->
            <section id="right_column">
                <!-- STARTING CHARGE FIELDSET -->
                <fieldset id="charge" class="trans" style="display: none;">
                    <legend>Charges Entry</legend>
                    <form id="transaction_chargeForm" name="transaction_chargeForm" method="post" action="billing_save.php">
                        <input type="hidden" id="TID" name="TID" value="" />
                        <div class="half_line">
                            <div class="line">                         
                                <label for="Prob-desc">Problem: </label><span id="Prob-desc" class="desc"></span>
                                <input type="hidden" id="ProbID" class="id_code" name="ProbID" value="" />
                            </div>
                            <div class="line">                         
                                <label for="LC">Location: </label><input class="shortinput auto_dropdown" type="text" id="LC" name="LC" value="" />  
                                <input type="hidden" class="id_code" id="LCID" name="LCID" value="" />
                                <span id="LC-desc" class="desc"></span>
                            </div>
                            <div class="line">                         
                                <label for="PV">Provider: </label><input class="shortinput auto_dropdown" type="text" id="PV" name="PV" value="" />
                                <input type="hidden" id="PVID" class="id_code" name="PVID" value="" />
                                <span id="PV-desc" class="desc"></span>
                            </div>
                            <div class="line">
                                <label for="ProcID">Procedure: </label><input class="shortinput auto_dropdown id_code" type="text" id="ProcID" name="ProcID" value="" />
                                <br />
                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<span id="Proc-desc" class="desc"></span>                
                            </div>
                            <div class="line">
                                <label for="Units">Units: </label><input class="codeslot auto_calculate" type="text" id="Units" name="Units" value="" />
                                <input type="hidden" id="Amt" name="Amt" value="" />
                            </div>
                            <div class="line">
                                <label for="totAmt">Amount: </label><input class="codeslot" type="text" id="totAmt" name="totAmt" value="" />
                            </div>
                            <div class="line">
                                <label for="serviceDate" style="font-size: 0.9em;">Service Date: </label><input class="shortinput" type="date" id="serviceDate" name="serviceDate" value="" />
                            </div>
                            <div class="line">
                                <label for="ticket">Ticket: </label><input class="codeslot" type="text" id="ticket" name="ticket" value="" />
                            </div>
                        </div>
                        <div class="half_line">
                            <div class="line">    
                                <label for="active">This Code is Active </label><input type="checkbox" id="active" name="active" value=1 />
                            </div>
                            <div class="line">
                                <label for="mods">Mod(s): </label>
                            </div>
                            <div class="line">
                                1: <input class="codeslot auto_dropdown id_code" type="text" id="mod1" name="mod1" value="" />
                                <span id="display_for_mod1" class="desc"></span> 
                            </div>
                            <div class="line">
                                2: <input class="codeslot auto_dropdown id_code" type="text" id="mod2" name="mod2" value="" />
                                <span id="display_for_mod2" class="desc"></span>
                            </div>
                            <div class="line">
                                3: <input class="codeslot auto_dropdown id_code" type="text" id="mod3" name="mod3" value="" />
                                <span id="display_for_mod3" class="desc"></span>
                            </div>
                            <div class="line">
                                4: <input class="codeslot auto_dropdown id_code" type="text" id="mod4" name="mod4" value="" />
                                <span id="display_for_mod4" class="desc"></span>
                            </div>
                            <div class="line">
                                <label for="Resp" style="font-size: 0.9em;">Responsible: </label>
                                <select id="Resp" name="Resp">
                                    <option id="pt" value="patient">Patient</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="SDThru">Thru: </label><input class="shortinput" type="date" id="SDThru" name="SDThru" value="" />
                            </div>
                            <div class="line">
                                <label for="auth">Authorization: </label><input class="codeslot" type="text" id="auth" name="auth" value="" />
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <fieldset style="width: 90%; padding-bottom: 20px;">
                            <legend>Diagnosis</legend>
                            <span id="diag1-tran" class="desc" style="width: 180px; float: left; margin-left: 30px;"></span>&emsp;&emsp;<span id="diag2-tran" class="desc" style="width: 180px; float: left; margin-left: 30px;"></span>
                            <span id="diag3-tran" class="desc" style="width: 180px; float: left; margin-left: 30px;"></span>&emsp;&emsp;<span id="diag4-tran" class="desc" style="width: 180px; float: left; margin-left: 30px;"></span>
                            <span style="clear:both;"></span>
                        </fieldset>
                        <div class="line">
                            <input class="btn btn-primary" type="submit" id="apply" name="apply" value="Post Charge" />
                            <input class="btn btn-danger" type="button" id="delete" name="delete" value="Delete" />
                        </div>
                        <input type="hidden" id="PTID" name="PTID" value="" />
                    </form>
                </fieldset>       
                <!-- ENDING CHARGE FIELDSET -->      
                
                <!-- STARTING PAYMENT/ADJUSTMENT FIELDSET -->
                <fieldset id="pay_adj" class="trans" style="display: none;">
                    <legend>Payment And Adjustment Entry</legend>              
                    <div id="pay_adj_section" class="half_line">
                        <div class="line">                         
                            <label for="totAmt">Charge: </label><span id="totAmt" class="desc"></span>
                        </div>
                        <div class="line">                         
                            <label for="Allowed">Allowed: </label><span id="Allowed" class="desc"></span>
                        </div>
                        <div class="line">                         
                            <label for="writedown">Write Down: </label><span id="writedown" class="desc"></span>
                        </div>
                        <div class="line">                         
                            <label for="prevPay">Previous Payment: </label><span id="prevPay" class="desc"></span>
                        </div>
                        <div class="line">                         
                            <label for="prevAdj">Previous Adjustment: </label><span id="prevAdj" class="desc"></span>
                        </div>
                        <div class="line">
                            <label for="balance">Balance Due: </label><span id="balance" class="desc"></span>
                        </div>
                        <div class="line">
                            <label for="payNotes">Notes: </label>
                        </div>
                        <textarea id="payNotes" name="payNotes" onkeyup="AutoGrowTextArea(this, 293)"></textarea >
                    </div>
                    <div class="half_line">
                        <form id="transaction_pay_adjForm" name="transaction_pay_adjForm" method="post" action="billing_save.php">
                            <input type="hidden" id="TID" name="TID" value="" />
                            <input type="hidden" id="PAID" name="PAID" value="" />
                            <div class="line">    
                                <label for="date">Date: </label><input type="date" class="shortinput" id="date" name="date" value="" />
                            </div>     
                            <div class="line">
                                <label for="method" style="font-size: 0.9em;">Method: </label>
                                <select id="method" name="method">
                                    <option value="Line Item">Line Item</option>
                                    <option value="To Unapplied Credit">To Unapplied Credit</option>
                                    <option value="Balance Forward">Balance Forward</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="source" style="font-size: 0.9em;">Source: </label>
                                <select id="source" name="source">
                                    <option value="patient">Patient</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="checkNum">Check #: </label><input class="shortinput" type="text" id="checkNum" name="checkNum" value="" />
                            </div>
                            <div class="line">
                                <label for="payment" style="font-size: 0.9em;">Payment: </label>
                                <select id="payment" name="payment">
                                    <option value="">Select One</option>
                                    <option value="Attorney Check">Attorney Check</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cashiers Check">Cashiers Check</option>
                                    <option value="Check">Check</option>
                                    <option value="Credit Cards">Credit Cards</option>
                                    <option value="Employer Check">Employer Check</option>
                                    <option value="Insurance Check">Insurance Check</option>
                                    <option value="Money Order">Money Order</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="adjustment" style="font-size: 0.9em;">Adjustment: </label>
                                <select id="adjustment" name="adjustment">
                                    <option value="">Select One</option>
                                    <option value="Bad Dept">Bad Dept</option>
                                    <option value="Insurance Adjs">Insurance Adjs</option>
                                    <option value="Insurance Refund">Insurance Refund</option>
                                    <option value="Patient Courtesy">Patient Courtesy</option>
                                    <option value="Prof Courtesy">Prof Courtesy</option>
                                    <option value="Refund">Refund</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="payAmt" style="font-size: 0.9em;">Amount: </label><input class="shortinput" type="text" id="payAmt" name="payAmt" value="" />
                            </div>
                            <div class="line">
                                <label for="writedown" style="font-size: 0.9em;">Write Down: </label>
                                <select id="writedown" name="writedown">
                                    <option value="">** Blank **</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="billbalto" style="font-size: 0.9em;">Bill Balance To: </label>
                                <select id="billbalto" name="billbalto">
                                    <option value="">** Blank **</option>
                                </select> 
                            </div>
                            <div class="line">
                                <label for="addDescr">Additional Description: </label><input type="text" id="addDescr" name="addDescr" value="" />
                            </div>
                            <div class="line">
                                <input class="btn btn-primary" type="submit" id="apply" name="apply" value="Post" />
                                <input class="btn btn-danger" type="button" id="delete" name="delete" value="Delete" />
                            </div>
                            </form>
                        </div>
                        <div style="clear:both;"></div>      
                </fieldset> 
                <!-- ENDING PAYMENT/ADJUSTMENT FIELDSET -->
      
            </section>   
            <div style="clear:both;"></div>
            <br />
            <br />
            <div id="transaction_container">
                <div class="scriptHolder_num">
                    <div class="listHeader_num">#</div>
                    <ul id="TID_list" class="scrollingList"></ul>
                </div>
                <div class="scriptHolder">
                    <div class="listHeader">Doctor</div>
                    <ul id="PV_list" class="scrollingList"></ul>
                </div>
                <div class="scriptHolder">
                    <div class="listHeader">Procedure</div>
                    <ul id="Proc_list" class="scrollingList"></ul>
                </div>
                <div class="scriptHolder">
                    <div class="listHeader service_dates">Service Date(s)</div>
                    <ul id="SDate_list" class="scrollingList"></ul>
                </div>
                <div class="scriptHolder">
                    <div class="listHeader">Amount</div>
                    <ul id="Amt_list" class="scrollingList"></ul>
                </div>
                <div class="scriptHolder">
                    <div class="listHeader">Units</div>
                    <ul id="Units_list" class="scrollingList"></ul>
                </div>
                <div class="scriptHolder">
                    <div class="listHeader">Total</div>
                    <ul id="totAmt_list" class="scrollingList"></ul>
                </div>
            </div>
    </div>   
    <script>
        //FUNCTION FOR FILLING THE PAYMENT/ADJUSTMENT LIST
        function pay_adj_fill(tid, $uls) {
            var headerObj = {'PAID_li': '#', 'doc_li': 'Doctor', 'trans_li': 'Transaction', 'date_li': 'Date', 'addDescr_li': 'Description', 'source_li': 'Source', 'payAmt_li': 'Amount'};
            var headerObjKeys = Object.keys(headerObj);
            var headerConnectorList = {'PAID_li': 'TID_list', 'doc_li': 'PV_list', 'trans_li': 'Proc_list', 'date_li': 'SDate_list', 'addDescr_li': 'Amt_list', 'source_li': 'Units_list', 'payAmt_li': 'totAmt_list'};
            $.ajax({
                type: "POST",
                url: "billing_pay_adj_search.php",
                data: "tid_pay_adj="+tid,
                dataType: "json",
                async: false,
                success: function(data) { 
                    if (data) {
                        $.each(data, function(i, elem) {
                            var paid = this['PAID'];
                            var tid = this['TID'];
                            //BEFORE THE FIRST PAYMENT/ADJUSTMENT LIST, PUT THE HEADER WITH A CATEGORY LIST
                            if(i == 0) {
                                $uls.each(function(j) {
                                    $(this).append($("<li class='PAheader "+headerObjKeys[j]+"' data-tid='"+tid+"'></li>").text(headerObj[headerObjKeys[j]]));
                                });
                            }
                            $.each(elem, function(key, value) {
                                $uls.filter(function() {
                                    return headerConnectorList[key+'_li'] == this.id;
                                }).append($("<li class='PAli "+paid+"' data-tid='"+tid+"'></li>").text($("<div>").html(value).text()));
                            });
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        
        //FUNCTION FOR FILLING THE TRANSACTION LIST
        function transaction_fill(ptid) {
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "ptid_trans="+ptid,
                dataType: "json",
                success: function(data) {
                    var $uls= $('.scrollingList');
                    $uls.empty();
                    $.each(data, function() {
                        var tid = this["TID"];
                        $.each(this, function(key, value) {
                            $uls.filter(function() {
                                if (key+'_list' == this.id) return this.id;
                            }).append($("<li class='changeCharge "+tid+"'></li>").text($("<div>").html(value).text())); 
                        });
                         pay_adj_fill(tid, $uls);
                    });                      
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        //FUNCTION FOR FILLING TRANSACTION FORM
        function filling_transForm(tid) {
            //GETTING TRANSACTION WITH A TID    
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "tid="+tid,
                dataType: "json",
                success: function(data) {
                    var $inputs = $('#transaction_chargeForm input, #transaction_chargeForm select, #pay_adj_section textarea');
                    var $spans = $('#pay_adj_section span');
                    $.each(data, function(key, value) {                          
                        $inputs.filter(function() {
                            //CHECKBOX VALIDATION 
                            if(this.type == "checkbox") {
                                if (value == 1) $('#'+key).prop('checked',true);
                                else $('#'+key).prop('checked',false);
                            }
                            else return key == this.name;
                        }).val($("<div>").html(value).text());
                        $spans.filter(function() {
                            return key == this.id;
                        }).html($("<div>").html(value).text());
                        //FILL UP id_codes
                        var code_id = $inputs.filter('.id_code').filter(function() {
                            return key == this.name;
                        }).attr('id');
                        if (typeof(code_id) !== "undefined") {
                            var elem = this;
                            var id, table, query = "";
                            if (key == "PVID") {
                                id = "PVID";
                                table = "billing_providerInfo";
                                query = value;
                            }
                            else if(key == "LCID") {
                                id = "LCID";
                                table = "billing_locationInfo";
                                query = value;
                            }
                            else if(key == "ProbID") {
                                id = "PBID";
                                table = "billing_probInfo";
                                query = value;
                                $.ajax({
                                    type: "POST",
                                    url: "search_code.php",
                                    data: "id=ProbID&query="+value,
                                    dataType: 'json',
                                    cache: false,
                                    success: function(data) {             
                                        var trans = ["diag1-tran", "diag2-tran", "diag3-tran", "diag4-tran"];
                                        for (var key in trans) {
                                            var value = data[trans[key]];
                                            if (value !== null) $('#'+trans[key]).html(value);
                                        }
                                    },
                                    error: function(error) {
                                        console.log('Error: '+error);
                                    }
                                });         
                            }
                            else if(key == "ProcID") {
                                id = "PCID";
                                table = "billing_procedure_codes";
                                query = value;
                            }
                            else if(key.indexOf('mod') == 0) {
                                id = "MCode";
                                table = "billing_modifiers";
                                query = value;
                            }
                            if (query.length > 0) {
                                $.ajax({
                                    type: "POST",
                                    url: "search_code.php",
                                    data: "id="+id+"&table="+table+"&query="+query,
                                    dataType: 'json',
                                    cache: false,
                                    success: function(data) { 
                                        $('#transaction_chargeForm #'+key).val($("<div>").html(data.id).text());
                                        if(key.indexOf('mod') == 0) $('#display_for_'+key).html(data.name);
                                        else if(key == 'ProcID' || key == 'ProbID') $('#'+key.substring(0, key.length - 2)+'-desc').html(data.name);
                                        else {
                                            $('#transaction_chargeForm #'+key.substring(0, key.length - 2)).val($("<div>").html(data.name).text());
                                        }
                                    },
                                    error: function(error) {
                                        console.log('Error: ');
                                        console.log(error);
                                    }
                                });
                            }
                        }
                    });                      
                },
                error: function(error) {
                    console.log(error);
                }
            });    
        }
        //FUNCTION FOR FILLING JUST THE LEFT PAY SECTION
        function filling_chargeForm(tid) {
            //GETTING TRANSACTION WITH A TID    
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "tid="+tid,
                dataType: "json",
                success: function(data) {
                    var $spans = $('#pay_adj_section span');
                    $.each(data, function(key, value) {                          
                        $spans.filter(function() {
                            return key == this.id;
                        }).html($("<div>").html(value).text());
                        if (key == 'payNotes') $('#pay_adj_section textarea').val(value);
                    });
                    
                }
            });
        }
        //FUNCTION FOR CALCULATION THE ALL PAYMENT/ADJUSTMENT LISTS WITH GIVEN TRANSACTION AMOUNT
        function calculate_pay(tid) {
            $.ajax({
                type: "POST",
                url: "billing_calculate.php",
                data: 'tid='+tid,
                async: false,
                success: function(data) {
                    if(!data) console.log("Error: "+data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        //SUBMIT FORM AJAX FOR TRANSACTION
        $('#transaction_chargeForm').submit(function(e) {
            e.preventDefault();
            var postedData = $(this).serializeArray();
            postedData.push({ name: 'table', value: 'billing_transactions' });
            
            //PUT 0s AS VALUES OF UNCHECKED CHECKBOXES
            var checkbox_names = new Array();
            var unchecked_checkboxes = new Array();
            $('#transaction_chargeForm input:checkbox').each(function() {
                checkbox_names.push(this.name);
            });
            $.each(checkbox_names, function(key, value) {
                var checker = false;
                $.map(postedData, function(obj) {
                    if (value == obj.name) checker = true;
                });
                if(!checker) unchecked_checkboxes.push(value);
            });
            $.each(unchecked_checkboxes, function() {
                postedData.push({ name: this, value: 0 });
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
            var ptid = $('#PTID').val();
            transaction_fill(ptid);
            //PREVENT THE ACTUAL FORM SUBMISSION
            return false;
        });
        //DELETE FROM CHARGE FORM
        $('#transaction_chargeForm #delete').click(function() {
            var tid = $('#transaction_pay_adjForm #TID').val();
            $.ajax({
                type: "POST",
                url: 'billing_delete.php',
                data: 'tid='+tid+'&delete=true',
                success: function(data) {
                    if(data == "Successfully Deleted!") alert(data);
                    else console.log("Error: "+data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
            var ptid = $('#transactionForm #PTID').val();
            transaction_fill(ptid);
        });
        //DELETE FROM PAYMENT/ADJUSTMENT FORM
        $('#transaction_pay_adjForm #delete').click(function() {
            var paid = $('#transaction_pay_adjForm #PAID').val();
            $.ajax({
                type: "POST",
                url: 'billing_delete.php',
                data: 'paid='+paid+'&delete=true',
                async: false,
                success: function(data) {
                    if(data == "Successfully Deleted!") alert(data);
                    else console.log("Error: "+data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
            var ptid = $('#transactionForm #PTID').val();
            var tid = $('#transaction_pay_adjForm #TID').val();
            calculate_pay(tid);
            transaction_fill(ptid);
            filling_chargeForm(tid);
        });
        //SUBMIT FORM AJAX FOR PAYMENT/ADJUSTMENT
        $('#transaction_pay_adjForm').submit(function(e) {
            e.preventDefault();
            var postedData = $(this).serializeArray();
            postedData.push({ name: 'table', value: 'billing_pay_adj' });
            
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
            //SAVING NOTES TO TRANSACTION
            var noteVal = $('#payNotes').val();
            var tid = $('#transaction_pay_adjForm #TID').val();
            $.ajax({
                type: "POST",
                url: formURL,
                data: {'TID': tid, 'payNotes': noteVal, 'table': 'billing_transactions' },
                success: function(data) {
                    if(data != "Successfully Saved!") console.log("Error: "+data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
            var ptid = $('#transactionForm #PTID').val();
            calculate_pay(tid);
            transaction_fill(ptid);
            filling_chargeForm(tid);
            console.log(tid);
            //PREVENT THE ACTUAL FORM SUBMISSION
            return false;
        });
        //SEARCH FUNCTION AJAX FILLING UP THE FORM FROM THE DB
        $('#transactionForm #search').click(function () {
            var ptid = $('#PTID').val();
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "ptid_trans_main="+ptid,
                dataType: "json",
                success: function(data) {
                    var $spans = $('#transactionForm span');
                    var $inputs = $('#transaction_chargeForm input, #transaction_chargeForm select');
                    var $Resp = $('select#Resp');
                    $Resp.find('option:not(:last)').remove();
                    $.each(data, function(key, value) {
                        //PUTTING PTID IN THE HIDDEN FIELD FOR THE PROBLEM FORM SEARCH
                        if (key == "PTID") {
                            localStorage.setItem("ptid_hidden", value);
                        }
                        $spans.filter(function() {
                            return key == this.id; 
                        }).text(value);
                        //FILLING UP THE NOTES
                        if (key == 'notes') {
                            $('#notes').val(value);
                            AutoGrowTextArea(document.getElementById('notes'));
                        }
                        if(key.indexOf("Carrier") == 0 && value != 0) {
                            id = "ICID";
                            table = "billing_insurCarriers";
                            query = value;
                            $.ajax({
                                type: "POST",
                                url: "search_code.php",
                                data: "id="+id+"&table="+table+"&query="+query,
                                dataType: 'json',
                                cache: false,
                                success: function(data) { 
                                    if($Resp.find('option').length == 1) $Resp.prepend($('<option></option>').attr('value', data.id).text(data.name));
                                    else $Resp.find('option:first').after($('<option></option>').attr('value', value).text(data.name));
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
            //PUTTING PTID in transaction_chargeForm
            $('#transaction_chargeForm #PTID').val(localStorage.getItem('ptid_hidden'));
            //CALLING TRANSACTION DATA
            transaction_fill(ptid);
        });
        //SEE THE TRANSACTION WHEN CLICKED
        $('.scrollingList').on("click", '.changeCharge', function() {
            $('#transaction_chargeForm')[0].reset();
            $('#transaction_pay_adjForm')[0].reset();
            $('#transaction_chargeForm input[type="hidden"]').val(null);
            $('#transaction_pay_adjForm input[type="hidden"]').val(null);
            $("input[type='date']").val(new Date().toDateInputValue());
            $('#transaction_chargeForm span').empty();
            $('#pay_adj_section span').empty();
            
            //HIGHLIGHT WHEN A TRANSACTION HAS BEEN SELECTED
            var class_match = $(this).attr('class').match(/\d+/ig);
            var tid = null;
            if(class_match && class_match.length > 0) tid = class_match[0];
            $('.changeCharge').css('background-color','#cccccc');
            $('.PAheader').css('background-color','#a3a3a3');
            $('.PAli').css('background-color','#CCF2CC');
            $('.'+tid).css('background-color','#a3a3a3');
            var selectedpays = $('li.PAli[data-tid="'+tid+'"]');
            selectedpays.css('background-color','#99FF99');
            $('.PAheader[data-tid="'+tid+'"]').css('background-color','#777777');
            
            //FUNCTION FOR FILLING THE TRANSACTION FORM
            filling_transForm(tid);
            
            //FILLING IN PAYMENT/ADJUSTMENT FORM
            $('#transaction_pay_adjForm #TID').val(tid); 
            
            if ($('#PAA').hasClass('btn-default')) {
                $('fieldset#charge').show();
                $('.control').removeClass('btn-success').add('btn-default');
                $('#DCC').removeClass('btn-default').addClass('btn-success');
            }
        });
        
        //SEE THE PAYMENT/ADJUSTMENT WHEN CLICKED
        $('.scrollingList').on("click", '.PAli', function() {
            $('#transaction_pay_adjForm')[0].reset();
            $('#transaction_pay_adjForm input[type="hidden"]').val(null);
            $("input[type='date']").val(new Date().toDateInputValue());
             //HIGHLIGHT WHEN A PAYMENT/ADJUSTMENT HAS BEEN SELECTED
            var class_match = $(this).attr('class').match(/\d+/ig);
            var paid = null;
            if(class_match && class_match.length > 0) paid = class_match[0];
            $('.PAli').css('background-color','#CCF2CC');
            $('.PAheader').css('background-color','#a3a3a3');
            $('.changeCharge').css('background-color','#cccccc');
            
            var selected_tid = $(this).attr('data-tid');
            $('.changeCharge.'+selected_tid).css('background-color','#a3a3a3');
            $('.PAli[data-tid="'+selected_tid+'"]').css('background-color','#99FF99');
            $('.PAheader[data-tid="'+selected_tid+'"]').css('background-color','#777777');

            $('.PAli.'+paid).css('background-color','#66FF66');
            
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "paid="+paid,
                dataType: 'json',
                cache: false,
                success: function(data) { 
                    var $inputs = $('#transaction_pay_adjForm input, #transaction_pay_adjForm select');
                    $.each(data, function(key, value) {
                        $inputs.filter(function() {
                            return key == this.id; 
                        }).val($("<div>").html(value).text());
                    });
                },
                error: function(error) {
                    console.log('Error: '+error);
                }
            });   
            //FILLING IN THE LEFT PART
            $.ajax({
                type: "POST",
                url: "billing_idsearch.php",
                data: "tid="+selected_tid,
                dataType: 'json',
                cache: false,
                success: function(data) { 
                    var $spans = $('#pay_adj_section span');
                    $.each(data, function(key, value) {
                        $spans.filter(function() {
                            return key == this.id; 
                        }).html($("<div>").html(value).text());
                        //NOTE
                        if(key == 'payNotes') $('#pay_adj_section textarea').val($("<div>").html(value).text());
                    });
                },
                error: function(error) {
                    console.log('Error: '+error);
                }
            });   
            
            //FILLING IN PAYMENT/ADJUSTMENT FORM
            $('#transaction_pay_adjForm #TID').val(selected_tid);
            
            $('#PAA').trigger('click');
        });
        
        //REMOVES THE NOTES WHEN THE PTID IS ABOUT TO CHANGE
        $("#transactionForm #PTID").change(function() {
            if ($(this).val() == '') {
                $("p#notes").empty();
                $("#transactionForm span").empty();
                $('#transaction_chargeForm')[0].reset();
                $('#transaction_chargeForm input[type="hidden"]').val(null);
                $("input[type='date']").val(new Date().toDateInputValue());
                $('#transaction_chargeForm span').empty();
                $('#charge').hide();               
                localStorage.removeItem("ptid_hidden");
            }
        });
        //WHEN PROB MODAL IS SHOWING, IT EMPTIES THE transaction_chargeForm
        $('#problemModal').on('show.bs.modal', function () {
            $('#transaction_chargeForm')[0].reset();
            $('#transaction_chargeForm input[type="hidden"]').val(null);
            $("input[type='date']").val(new Date().toDateInputValue());
            $('#transaction_chargeForm span').empty();
        });
        //WHEN PROB MODAL IS CLOSED, IT EMPTIES THE FORM
        $('#problemModal').on('hidden.bs.modal', function () {
            $('#problemForm')[0].reset();
            $('#problemForm input[type="hidden"]').val(null);
            $("input[type='date']").val(new Date().toDateInputValue());
            $('#problemForm span').empty();
        });
        //WHEN NOTES IS SAVED
        $('#noteSave').click(function () {
            var ptid = $('#transactionForm #PTID').val();
            $.ajax({
                type: "POST",
                url: 'billing_save.php',
                data: 'PTID='+ptid+'&notes='+$('#notes').val(),
                success: function(data) {
                    if(data == "Successfully Saved!") {
                        alert(data);
                    }
                    else console.log("Error: "+data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
        
        //AUTOMATICALLY CALCULATE THE UNITS WITH THE AMOUNT PRICE
        $('.auto_calculate').change(function() {
            if ($(this).val() == 0) {
                alert('Can\'t insert 0 as number of unit.');
                $(this).val(1);
                $('#totAmt').val($('#Amt').val());
            }
            else if ($(this).val() == 1) {
                $('#totAmt').val($('#Amt').val());
            }
            else {
                var AmtVal = $(this).val() * $('#Amt').val(); 
                $('#totAmt').val(AmtVal);
            }
            
        });
        //WHEN CHARGE BUTTON IS CLICKED
        $('#addtrans').click(function() {
            $('#problemForm').trigger("submit");
            //TO SEE IF THE FORM IS SUCCESSSFULLY SAVED
            pass = localStorage.getItem('pass');
            if (pass) {
                var probid = $('#ProbID').val()
                $('#transaction_rightForm #ProbID').val(probid);
                $('#Prob-desc').html($('#Prob').val());
                $.ajax({
                    type: "POST",
                    url: "search_code.php",
                    data: "id=ProbID&query="+probid,
                    dataType: 'json',
                    cache: false,
                    success: function(data) {             
                        var trans = ["diag1-tran", "diag2-tran", "diag3-tran", "diag4-tran"];
                        for (var key in trans) {
                            var value = data[trans[key]];
                            if (value !== null) $('#'+trans[key]).html(value);
                        }
                    },
                    error: function(error) {
                        console.log('Error: '+error);
                    }
                });
                //WHEN CLOSING THE FORM WILL BE EMPTIED
                $('#problemModal').modal('toggle');
            }
        });
        //CONTROL BUTTONS
        //CHARGE
        $('#CHRG').click(function() {
            $('.control').removeClass('btn-success').add('btn-default');
            $(this).removeClass('btn-default').addClass('btn-success'); 
            $('fieldset#pay_adj').hide();
            $('fieldset#charge').show();
        });
        $('#PAA').click(function() {
            $('.control').removeClass('btn-success').add('btn-default');
            $(this).removeClass('btn-default').addClass('btn-success');    
            $('fieldset#charge').hide();
            $('fieldset#pay_adj').show();
        });
        $('#DCC').click(function() {
            $('.control').removeClass('btn-success').add('btn-default');
            $(this).removeClass('btn-default').addClass('btn-success'); 
            $('fieldset#pay_adj').hide();
            $('fieldset#charge').show();
        });
        //TO MAKE CHOOSE EITHER PAYMENT OR ADJUSTMENT TYPE ONLY
        $('#payment').change(function() {
            $('#adjustment').val($('#adjustment option:first').val());
        });
        $('#adjustment').change(function() {
            $('#payment').val($('#payment option:first').val());
        });
    </script>
</body>
</html>