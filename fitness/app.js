
/**
 * Module dependencies.
 */

var express = require('express')
  , routes = require('./routes');

var app = module.exports = express.createServer();

// Configuration

app.configure(function(){
  app.set('views', __dirname + '/views');
  app.set('view engine', 'jade');
  app.use(express.bodyParser());
  app.use(express.methodOverride());
  app.use(app.router);
  app.use(express.static(__dirname + '/public'));
});

app.configure('development', function(){
  app.use(express.errorHandler({ dumpExceptions: true, showStack: true }));
});

app.configure('production', function(){
  app.use(express.errorHandler());
});

// Routes

//app.get('/', routes.index);

app.get('/', function(req, res, next){
	res.render('index', { title: 'Express'});
});

app.post('/connect/finish', function(req, res) {
		  var sessionTokenObject = req.body;
		  // grab client secret from app settings page and `sign` `sessionTokenObject` with it.
		  sessionTokenObject.clientSecret = 'a1ea2091f8ce065e7ca404787daf49d4e92a88eb';

		  request({
			method: 'POST',
			uri: 'https://user.humanapi.co/v1/connect/tokens',
			json: sessionTokenObject
		  }, function(err, resp, body) {
			  if(err) return res.send(422);
			  // at this point if request was successfull body object
			  // will have `accessToken`, `publicToken` and `humanId` associated in it.
			  // You probably want to store these fields in your system in association to user's data.
			  res.send(201, body);
			});
		});

app.listen(3000, function(){
  console.log("Express server listening on port %d in %s mode", app.address().port, app.settings.env);
});
