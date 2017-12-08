function RTCPush(id, address)
{
    this.id = id;
    this.socket = io.connect(address);
    this.socket.emit('__jn', {id: this.id});

    this.bind = function(title, callback)
    {
        this.socket.on(title, function(data)
        {
            callback(data);
        });
    };

    this.send = function(id, title, data)
    {
        this.socket.emit(title, {id: id, data: data});
    }

}

function WebRTC(dictionary) {
    
    /*
     *  PRIVATE PROPERTIES
     */
    
    var that = this;
    var my_id = null;
    var remote_id = null;
    var local_stream = null;
    var remote_stream = null;
    var peer_connection = null;
    var constraints = {video: true, audio: true};
    var peer_config = null;
    var remote_sdp = null;
    var remote_candidates = [];
    var local_video = null;
    var remote_video = null;
    var send_data_channel = null;
    var signaling_server = window.location.host + ':8889';
    
    /*
     *  CALLBACK FUNCTIONS
     */
    
    var readyToCall = null;
    var dataChannelReady = null;
    var dataChannelMessage = null;
    var connecting = null;
    var connected = null;
    var failed = null;
    var disconnecting = null;
    
    /*
     *  PARSE DICTIONARY
     */
    
    if(dictionary.hasOwnProperty('ID'))
    {
        my_id = dictionary['ID'];
    }
    if(dictionary.hasOwnProperty('remoteID'))
    {
        remote_id = dictionary['remoteID'];
    }
    if(dictionary.hasOwnProperty('localVideo'))
    {
        local_video = document.getElementById(dictionary['localVideo']);
    }
    if(dictionary.hasOwnProperty('remoteVideo'))
    {
        remote_video = document.getElementById(dictionary['remoteVideo']);
    }
    if(dictionary.hasOwnProperty('signaling'))
    {
        signaling_server = dictionary['signaling'];
    }
    if(dictionary.hasOwnProperty('onReadyToCall'))
    {
        readyToCall = dictionary['onReadyToCall'];
    }
    if(dictionary.hasOwnProperty('onConnecting'))
    {
        connecting = dictionary['onConnecting'];
    }
    if(dictionary.hasOwnProperty('onConnected'))
    {
        connected = dictionary['onConnected'];
    }
    if(dictionary.hasOwnProperty('onFailed'))
    {
        failed = dictionary['onFailed'];
    }
    if(dictionary.hasOwnProperty('onDisconnected'))
    {
        disconnecting = dictionary['onDisconnected'];
    }
    if(dictionary.hasOwnProperty('onDataChannelReady'))
    {
        dataChannelReady = dictionary['onDataChannelReady'];
    }
    if(dictionary.hasOwnProperty('onDataChannelMessage'))
    {
        dataChannelMessage = dictionary['onDataChannelMessage'];
    }
    
    // connect to signaling server
    
    var rtcpush = new RTCPush(my_id, signaling_server);

    /*
     *  PRIVATE METHODS
     */

    function gotStream(stream)
    {
        console.log("Got Local Stream");
        local_stream = stream;
        local_video.src = URL.createObjectURL(local_stream);
        local_video.hidden = false;
        local_video.muted = true;
        stream.getVideoTracks()[0].enabled = false;
        stream.getAudioTracks()[0].enabled = false;
        
        that.enterRoom = function()
        {
            console.log('entering room');
            stream.getVideoTracks()[0].enabled = true;
            stream.getAudioTracks()[0].enabled = true;
            
            rtcpush.send(my_id, 'JoinRoom', {id_1: my_id, id_2: remote_id});
        };
        
        that.leaveRoom = function()
        {
            peer_connection = null;
            stream.getVideoTracks()[0].enabled = false;
            stream.getAudioTracks()[0].enabled = false;
            rtcpush.send(my_id, 'LeaveRoom', {id_1: my_id, id_2: remote_id});
        };
        
        if(typeof(readyToCall) === 'function')
        {
            readyToCall();
        }
    }
    
    function createOffer()
    {
        if( typeof(webkitRTCPeerConnection) === 'function') 
        {
            peer_connection = new webkitRTCPeerConnection(peer_config/*, { optional: [{ RtpDataChannels: true }] }*/);
        }
        else if( typeof(mozRTCPeerConnection) === 'function') 
        {
            peer_connection = new mozRTCPeerConnection(peer_config/*, { optional: [{ RtpDataChannels: true }] }*/);
        }
        else if( typeof(RTCPeerConnection) === 'function') 
        {
            peer_connection = new RTCPeerConnection(peer_config/*, { optional: [{ RtpDataChannels: true }] }*/);
        }
        
        peer_connection.sent_offer = false;
        
        peer_connection.addStream(local_stream);
        
        peer_connection.onaddstream = function(e) 
        {
    		console.log('Got Remote Stream');
    		remote_stream = e.stream;
			remote_video.hidden = false;
            remote_video.src = URL.createObjectURL(remote_stream);
            
    	};
        
        peer_connection.onicecandidate = function(icecandidate) 
        {
            // send our ice candidate to be added to our room
            if(!icecandidate.hasOwnProperty('candidate') || icecandidate.candidate == null)
            {
                if(!peer_connection.sent_offer)
                {
                    rtcpush.send(remote_id, 'Offer', peer_connection.localDescription);
                    peer_connection.sent_offer = true;
                }
                return;
            }
            console.log('SENDING ICE CANDIDATE: ' + JSON.stringify(icecandidate));
            rtcpush.send(remote_id, 'IceCandidate', icecandidate);
    	};
        
        
        
    	
        
        send_data_channel = peer_connection.createDataChannel("sendDataChannel", {reliable: false});
        send_data_channel.onmessage = function(event)
        {
            if(typeof(dataChannelMessage) === 'function')
            {
                dataChannelMessage(JSON.parse(event.data));
            }
        };
        send_data_channel.onopen = function(event)
        {
            that.send = function(data)
            {
                send_data_channel.send(JSON.stringify(data));
            };
            if(typeof(dataChannelReady) === 'function')
            {
                dataChannelReady();
            }
        };
        
        var mediaConstraints = {
            optional: [{DtlsSrtpKeyAgreement: true}],
            mandatory: {
                OfferToReceiveAudio: true,
                OfferToReceiveVideo: true
            }
        };
        
        peer_connection.oniceconnectionstatechange = function()
        {
            if((peer_connection.iceConnectionState == 'new' || peer_connection.iceConnectionState == 'checking') && typeof(connecting) === 'function')
            {
                connecting();
            }
            else if((peer_connection.iceConnectionState == 'connected' || peer_connection.iceConnectionState == 'completed') && typeof(connected) === 'function')
            {
                connected();
                
            }
            else if(peer_connection.iceConnectionState == 'failed' && typeof(failed) === 'function')
            {
                remote_video.hidden = true;
                remote_video.src = null;
                remote_stream = null;
                failed();
            }
            else if((peer_connection.iceConnectionState == 'disconnected' || peer_connection.iceConnectionState == 'closed') && typeof(connected) === 'function')
            {
                remote_video.hidden = true;
                remote_video.src = null;
                remote_stream = null;
                disconnecting();
            }
        }
    
        peer_connection.onidpassertionerror = function()
        {
            if(typeof(failed) === 'function')
            {
                failed();
            }
        };
        
        peer_connection.onidpvalidationerror = function()
        {
            if(typeof(failed) === 'function')
            {
                failed();
            }
        };
        
        peer_connection.createOffer(function(sdp)
        {
    		// set our sdp as local description
    		peer_connection.setLocalDescription(sdp);
    		console.log('sending offer to: ' + remote_id);
            
            //rtcpush.send(remote_id, 'Offer', sdp);

    	}, function(){ }, mediaConstraints);
    }
    
    function createAnswer()
    {
        
        if( typeof(webkitRTCPeerConnection) === 'function') 
        {
            peer_connection = new webkitRTCPeerConnection(peer_config/*, { optional: [{ RtpDataChannels: true }] }*/);
        }
        else if( typeof(mozRTCPeerConnection) === 'function') 
        {
            peer_connection = new mozRTCPeerConnection(peer_config/*, { optional: [{ RtpDataChannels: true }] }*/);
        }
        else if( typeof(RTCPeerConnection) === 'function') 
        {
            peer_connection = new RTCPeerConnection(peer_config/*, { optional: [{ RtpDataChannels: true }] }*/);
        }
        
        peer_connection.onaddstream = function(e) 
        {
    		console.log('Got Remote Stream');
    		remote_stream = e.stream;
			remote_video.hidden = false;
            remote_video.src = URL.createObjectURL(remote_stream);
            
    	};
        
        peer_connection.sent_answer = false;
        
        peer_connection.addStream(local_stream);
        
        
        var description;
	    if( typeof(webkitRTCSessionDescription) === 'function') 
        {
	        description = new webkitRTCSessionDescription(remote_sdp);
	    }
        else if( typeof(mozRTCSessionDescription) === 'function') 
        {
	        description = new mozRTCSessionDescription(remote_sdp);
	    }
        else if( typeof(RTCSessionDescription) === 'function') 
        {
	        description = new RTCSessionDescription(remote_sdp);
	    }
        
        peer_connection.setRemoteDescription(description, function()
        {
            peer_connection.createAnswer(function(sdp)
            {
                // set our SDP as local description
                peer_connection.setLocalDescription(sdp);

                for (var i = 0; i < remote_candidates.length; i++) 
                {
                    if (remote_candidates[i].candidate) 
                    {
                        var ice;
                        if( typeof(webkitRTCIceCandidate) === 'function') 
                        {
                            ice = new webkitRTCIceCandidate(remote_candidates[i].candidate);
                        }
                        else if( typeof(mozRTCIceCandidate) === 'function') 
                        {
                            ice = new mozRTCIceCandidate(remote_candidates[i].candidate);
                        }
                        else if( typeof(RTCIceCandidate) === 'function') 
                        {
                            ice = new RTCIceCandidate(remote_candidates[i].candidate);
                        }
                        peer_connection.addIceCandidate(ice);
                    }
                }

                //rtcpush.send(remote_id, 'Answer', sdp);
            }, function(){ }, mediaConstraints);
        }, function(){ console.log("SET REMOTE DESCRIPTION ERROR"); });
        
        peer_connection.onicecandidate = function(icecandidate) 
        {
            // send our ice candidate to be added to our room
            if(!icecandidate.hasOwnProperty('candidate') || icecandidate.candidate == null)
            {
                if(!peer_connection.sent_answer)
                {
                    rtcpush.send(remote_id, 'Answer', peer_connection.localDescription);
                    peer_connection.sent_answer = true;
                }
                return;
            }
            console.log('SENDING ICE CANDIDATE');
            rtcpush.send(remote_id, 'IceCandidate', icecandidate);
    	};
        
        
        

        peer_connection.ondatachannel = function(event) 
        {
            send_data_channel = event.channel;
            send_data_channel.onmessage = function(event)
            {
                if(typeof(dataChannelMessage) === 'function')
                {
                    dataChannelMessage(JSON.parse(event.data));
                }
            };
            send_data_channel.onopen = function(event)
            {
                that.send = function(data)
                {
                    send_data_channel.send(JSON.stringify(data));
                };
                if(typeof(dataChannelReady) === 'function')
                {
                    dataChannelReady();
                }
            };
            
        };
        
        var mediaConstraints = {
            optional: [{DtlsSrtpKeyAgreement: true}],
            mandatory: {
                OfferToReceiveAudio: true,
                OfferToReceiveVideo: true
            }
        };
        
        peer_connection.oniceconnectionstatechange = function()
        {
            if((peer_connection.iceConnectionState == 'new' || peer_connection.iceConnectionState == 'checking') && typeof(connecting) === 'function')
            {
                connecting();
            }
            else if((peer_connection.iceConnectionState == 'connected' || peer_connection.iceConnectionState == 'completed') && typeof(connected) === 'function')
            {
                connected();
            }
            else if(peer_connection.iceConnectionState == 'failed' && typeof(failed) === 'function')
            {
                //remote_video.hidden = true;
                //remote_video.src = null;
                remote_stream = null;
                failed();
            }
            else if((peer_connection.iceConnectionState == 'disconnected' || peer_connection.iceConnectionState == 'closed') && typeof(connected) === 'function')
            {
                //remote_video.hidden = true;
                //remote_video.src = null;
                remote_stream = null;
                disconnecting();
            }
        }
        
        peer_connection.onidpassertionerror = function()
        {
            if(typeof(failed) === 'function')
            {
                failed();
            }
        };
        
        peer_connection.onidpvalidationerror = function()
        {
            if(typeof(failed) === 'function')
            {
                failed();
            }
        };
        
        
        
    }
    
    var handshakeDone = function()
    {
        var description = null;
	    
	    if( typeof(webkitRTCSessionDescription) === 'function') 
        {
	        description = new webkitRTCSessionDescription(remote_sdp);
	    }
        else if( typeof(mozRTCSessionDescription) === 'function') 
        {
	        description = new mozRTCSessionDescription(remote_sdp);
	    }
        else if( typeof(RTCSessionDescription) === 'function') 
        {
	        description = new RTCSessionDescription(remote_sdp);
	    }
        peer_connection.setRemoteDescription(description, function(){ }, function(){ console.log("SET REMOTE DESCRIPTION ERROR"); });
        
		for (var i = 0; i < remote_candidates.length; i++) 
        {
            if (remote_candidates[i].candidate) 
            {
                var ice;
                if( typeof(webkitRTCIceCandidate) === 'function') 
                {
                    ice = new webkitRTCIceCandidate(remote_candidates[i].candidate);
                }
                else if( typeof(mozRTCIceCandidate) === 'function') 
                {
                    ice = new mozRTCIceCandidate(remote_candidates[i].candidate);
                }
                else if( typeof(RTCIceCandidate) === 'function') 
                {
                    ice = new RTCIceCandidate(remote_candidates[i].candidate);
                }
                peer_connection.addIceCandidate(ice);
            }
        }
        
        peer_connection.ondatachannel = function(event) 
        {
            var receiveChannel = event.channel;
            receiveChannel.onmessage = function(event)
            {
                console.log('RECEIVED FROM DATA CHANNEL: ' + event.data);
            };
        };
        
        
    };
    
    /*
     *  PUSH LISTENERS
     */
    
    rtcpush.bind('CreateOffer', function(data)
    {
        createOffer();
    });
    
    rtcpush.bind('Offer', function(data)
    {
        console.log('offer received, answer will be created');
        remote_sdp = data;
        createAnswer();
    });
    
    rtcpush.bind('Answer', function(data)
    {
        console.log('answer received, connection will be established');
        remote_sdp = data;
        handshakeDone();
    });
    
    rtcpush.bind('IceCandidate', function(data)
    {
        if(!remote_sdp) 
        {
            console.log('NO REMOTE SDP');
            remote_candidates.push(data);
        }
        
        if(remote_sdp && data.candidate && data.candidate !== null ) 
        {
            console.log('ADDING ICE CANDIDATE');
            var ice;
            if( typeof(webkitRTCIceCandidate) === 'function') 
            {
                ice = new webkitRTCIceCandidate(data.candidate);
            }
            else if( typeof(mozRTCIceCandidate) === 'function') 
            {
                ice = new mozRTCIceCandidate(data.candidate);
            }
            else if( typeof(RTCIceCandidate) === 'function') 
            {
                ice = new RTCIceCandidate(data.candidate);
            }
            peer_connection.addIceCandidate(ice);
        }
    });
    
    rtcpush.bind('LeftRoom', function(data)
    {
        remote_stream = null;
        remote_video.hidden = true;
        remote_video.src = null;
    });
    
    /*
     *  INITIALIZE
     */
    
    
    
    $.post("https://api.xirsys.com/getIceServers", 
    {
        ident: "bombartier",
        secret: "9693e103-4bc4-43d7-a0fc-962f85268ea0",
        domain: "dev.health2.me",
        application: "default",
        room: "default",
        secure: 1
    }, function(data, status)
    {
        console.log(data);
        //var info = JSON.parse(data);
        peer_config = data.d;
        //peer_config = {"iceServers":[{"url":"stun:stun.l.google.com:19302"}]};
        
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        navigator.getUserMedia(constraints, gotStream, function(error) 
        {
            console.log("navigator.getUserMedia error");
        });
    });
}