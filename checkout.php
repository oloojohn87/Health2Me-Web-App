<html>
    <head>
        <style>
            .connectMemberRow{
                width: 486px; 
                height: 38px;
                padding: 6px;
                color: #333;
                border: 1px solid #E6E6E6;

            }
            .connectMemberRow_bg1{
                background-color: #E6E6E6;
            }
            .connectMemberRow_bg2{
                background-color: #FBFBFB;
            }
            .connectMemberRow span{
                color: #777;
                font-size: 12px;
                margin-top: -10px;
            }
            .connectMemberRow:hover{
                background-color: #54BC00;
                border: 1px solid #54BC00;
                color: #FFF;
                cursor: pointer;
            }
            .connectMemberRow:hover span{
                color: #FFF;
            }

            #connectMembersSearchBar{
                float: left; 
                width: 430px; 
                border-top-left-radius: 5px; 
                border-bottom-left-radius: 5px;
                border-top-right-radius: 0px; 
                border-bottom-right-radius: 0px;
                padding-top: 14px;
                padding-bottom: 15px;
            }
            .connectMembersSearchBarButton{
                width: 70px;
                height: 30px;
                background-color: #FAFAFA;
                outline: 0px;
                border: 1px solid #E7E7E7;
                color: #7A7A7A;
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
                text-align: center;
                font-size:0.7em;
                padding: 4px 8px 4px 8px;
            }


        </style>
    </head>
    <body>
        <div id="connectMemberStep1" style="width: 100%; height: 460px; margin-top: 20px;">
            <span style="color: #54BC00; font-size: 18px; font-family: Helvetica, sans-serif;">1. Select member to connect</span>
            
            <div class="controls" style="width: 500px; margin: auto; margin-top: 20px;margin-bottom: 8px;">
                <input class="span7" id="connectMembersSearchBar" lang="en" placeholder="Search Member (Name or Email)" type="text" />
                <input class="connectMembersSearchBarButton" id="connectMembersSearchBarButton" lang="en" type="button" value="Search" />
            </div>
            <div id="connectMemberTable" style="border-radius: 5px; width: 500px; height: 400px; margin: auto; overflow: hidden; overflow-y: auto; margin-top: 15px;">

            </div>
        </div>
        <div id="connectMemberStep2" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">2. Member Details</span>
            <div style="width: 500px; margin: auto; margin-top: 20px;">
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;">Email:</div> <input type="text" id="connectMemberEmail" placeholder="Member Email" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;">Phone:</div> <input type="tel" id="connectMemberPhone" placeholder="Member Phone Number" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px; margin-top: 25px;">
                    <div style="width: 300px; height: 15px; padding: 7px; background-color: #FAFAFA; border-radius: 15px; border: 1px solid #DDD; margin: auto;">
                        <button id="connectMemberSubscribeButton" style="width: 15px; height: 15px; outline: none; border: 1px solid #DDD; background-color: #FFF; border-radius: 15px; float: left; color: #54BC00; font-size: 12px;">
                        </button>
                        <div style="color: #666; padding-left: 17px; float: left; margin-top: -2px;">Subscribe this member to a probe</div>
                    </div>
                </div>
                <div id="connectMembersProbeSection" style="height: 200px; margin-top: 25px; display: none;">
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>
                    <select id="connectMember_probe_protocols" style="width: 64%; height: 30px;">
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Time:</div>
                    <input id="connectMember_probe_time" type="text"  style="width: 61%; height: 18px;"/>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Timezone: </div>
                    <select id="connectMember_probe_timezone" style="width: 64%; height: 30px;">
                        <option value="3">US Pacific Time</option>
                        <option value="4">US Mountain Time</option>
                        <option value="2">US Central Time</option>
                        <option value="1">US Eastern Time</option>
                        <option value="5">Europe Central Time</option>
                        <option value="6">Greenwich Mean Time</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                    <select id="connectMember_probe_method" style="width: 64%; height: 30px;">
                        <option value="1">Text Message</option>
                        <option value="2">Phone Call</option>
                        <option value="3">Email</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                    <select id="connectMember_probe_interval" style="width: 64%; height: 30px;"> 
                        <option value="1">Daily</option>
                        <option value="7">Weekly</option>
                        <option value="30">Monthly</option>
                        <option value="365">Yearly</option>
                    </select>
                </div>
            </div>
            <div style="width: 450px; height: 30px; margin: auto; text-align: center;">
                <button id="connectMemberCheckoutPrevButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">Prev</button>
                <button id="connectMemberCheckoutButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin-left: 15px;">Next</button>
            </div>
        </div>
        <div id="connectMemberStep3" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">3. Billing</span>
            <div style="width: 500px; margin: auto; margin-top: 20px;">
                <div style="width: 100%; height: 40px;">
                    <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Credit Card Number:</div> <input type="text" id="connectMemberCreditCard" placeholder="Credit Card Number" style="float: left; width: 260px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Expiration Date:</div> <input type="month" id="connectMemberExpDate" style="float: left; width: 260px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 200px; float: left; color: #666; padding-top: 5px;">CVC:</div> <input type="text" id="connectMemberCVC" placeholder="CVC" style="float: left; width: 260px;" />
                </div>
                <div style="width: 150px; height: 30px; margin: auto; margin-top: 15px;">
                    <button id="connectMemberFinishButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">
                        Finish
                    </button>
                </div>
                <div id="connectMemberFinishCode" style="width: 100%; height: 100px; padding-top: 80px; margin-top: 10px; display: none; text-align: center;">
                    <span style="color: #333; font-size: 18px;">Member Code: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
                    <span style="color: #888;">Please provide this code to the member.</span>
                </div>
            </div>
        </div>
        <div id="connectMemberFinalStep" style="width: 100%; height: 260px; padding-top: 200px; margin-top: 20px; display: none; text-align: center;">
            <span style="color: #333; font-size: 18px;">Member Code: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
            <span style="color: #888;">Please provide this code to the member.</span>
        </div>
    </body>
</html>