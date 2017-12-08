<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script><script>// <![CDATA[
            $(document).ready(function() {
				var peerConnectionConfig;
				
                $.post("https://api.xirsys.com/getIceServers", {
                    ident: "bombartier",
                    secret: "4a213061-f2b0-4ec9-bd2a-0c5e0ce0c2e1",
                    domain: "dev.health2.me",
                    application: "default",
                    room: "default",
                    secure: 1
                },
                function(data, status) {
                    //alert("Data: " + data + "nnStatus: " + status);
                    //console.log("Data: " + data + "nnStatus: " + status);
                    var parsed_json = JSON.parse(data);
                    peerConnectionConfig = parsed_json.d;
                    console.log(peerConnectionConfig);
                });
                
                
            });
        
// ]]></script>
