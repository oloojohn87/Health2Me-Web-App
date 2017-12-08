/*
 *  Author:         Bruno Lima
 *  Date Created:   June 16, 2015
 *  Description:    Defines the User module as a class to be used with Express
 */

var Encryption = new require('./Encryption.js')();

var User = function (dictionary)
{
    // Private Properties
    var app = dictionary.app;
    var pool = dictionary.conn;
    var doctor_properties = ["id", "Name", "Surname", "IdMEDEmail", "phone", "previlege"];
    
    // Private Methods
    function handleLogin(req, res)
    {
        // Attempt to login user with the given username and assword
        var username = req.body.username;
        var password = req.body.password;
        pool.getConnection(function(err, connection)
        {
            if (err) 
            {
                connection.release();
                res.json({"code" : 100, "status" : "Error in connection database"});
                return;
            } 
            var query_str = "SELECT * FROM doctors WHERE IdMEDEmail = ? OR IdMEDFIXEDNAME = ?";
            connection.query(query_str, [username, username], function(err, rows)
            {
                connection.release();
                var ret = {};
                if(!err && rows.length > 0) 
                {
                    // Retrieve first doctor found
                    var doctor = rows[0];
                    
                    // Validate password
                    var password_good = Encryption.validatePassword(password, 'sha256:1000:' + doctor['salt'] + ':' + doctor['IdMEDRESERV']);
                    
                    if(password_good)
                    {
                        // If the password is good, get properties from the doctor and create the User object with them, and also
                        // set them as session variable
                        var user = {}
                        for(var property in doctor)
                        {
                            if(doctor.hasOwnProperty(property) && doctor_properties.indexOf(property) != -1)
                            {
                                user[property] = doctor[property];
                                
                                // set session variables
                                req.CareLayerSessionCookie[property] = doctor[property];
                            }
                        }
                        
                        ret.User = user;
                        ret.status = 'success';
                    }
                    else
                    {
                        ret.error = "Invalid Password";
                        ret.status = 'error';
                    }
                }
                else
                {
                    ret.error = "Invalid Username";
                    ret.status = 'error';
                }
                
                res.send(ret);
            });
        });
    }
    
    function retrieveSession(req, res)
    {
        // Attempt to retrieve the current session if it exists and send it back to the User object
        
        var ret = {};
        if(req.hasOwnProperty('CareLayerSessionCookie') && req.CareLayerSessionCookie.hasOwnProperty('id') && req.CareLayerSessionCookie.id > 0)
        {
            var user = {};
            for(var property in req.CareLayerSessionCookie)
            {
                if(req.CareLayerSessionCookie.hasOwnProperty(property))
                {
                    user[property] = req.CareLayerSessionCookie[property];
                }
            }
            ret.User = user;

        }
        else
        {
            ret.error = "Session Not Set";
        }
        res.send(ret);
    }
    
    // Constructor
    function constructor()
    {
        // Handle AJAX calls for User
        app.post('/user', function(req, res)
        {
            if(req.body.type == 'retrieveSession')
            {
                retrieveSession(req, res);
            }
            else if(req.body.type == 'login')
            {
                handleLogin(req, res);
            }
            else if(req.body.type == 'logout')
            {
                req.CareLayerSessionCookie.reset();
                res.send({});
            }
            
        });
    }
    
    constructor();
    
    return this;
};

module.exports = User;