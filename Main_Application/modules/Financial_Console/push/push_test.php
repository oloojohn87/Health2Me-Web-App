<html>
    <head>
        <title>Test page for H2M Push Notifications</title>
        <script src="../js/jquery.min.js"></script>
        <script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
        <script src="push_client.js"></script>
    </head>
    <body>
        <input id="id" placeholder="ID" type="text" />
        <input id="message" placeholder="Message.." type="text" />
        <button id="send">send</button>
        <script>
        $(document).ready(function()
        {
            var id = '<?php echo $_GET['id']; ?>';
            var push = new Push(id, window.location.hostname + ':3955');
            push.bind('msg', function(data)
            {
                console.log(data);
            });
            
            $("#send").on('click', function()
            {
                push.send($("#id").val(), 'msg', $("#message").val());
            });
        });
        
        </script>
    </body>
</html>