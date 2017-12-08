

<script>
console.log('human_api LOADED!');
var token_object;

var connectBtn = document.getElementById('connect-health-data-btn');
connectBtn.addEventListener('click', function(e) {
  var opts = {
    // grab this from the app settings page
    clientId: '39c0e6576652e5863fd455383d3a8ecd9d897910',
    // can be email or any other internal id of the user in your system
    clientUserId: '1',
    finish: function(err, sessionTokenObject) {
      // When user finishes health data connection to your app
      // `finish` function will be called.
      // `sessionTokenObject` object will have several fields in it.
      // You need to pass this `sessionTokenObject` object to your server
      // add `CLIENT_SECRET` to it and send `POST` request to the `https://user.humanapi.co/v1/connect/tokens` endpoint.
      // In return you will get `accessToken` for that user that can be used to query Human API.
      //alert(sessionTokenObject);
      console.log(sessionTokenObject);
      token_object = sesionTokenObject;
      console.log(err);
      
		
    },
    close: function() {
      // do something here when user just closed popup
      // `close` callback function is optional
      console.log(token_object);
    }
  }
  HumanConnect.open(opts);
  console.log(token_object);
});


</script>
