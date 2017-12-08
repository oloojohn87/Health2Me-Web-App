/*
 *  Author:         Bruno Lima
 *  Date Created:   June 19, 2015
 *  Description:    Defines the Patients module as a class to be used with Express
 */

var Patients = function (dictionary)
{
    // Private Properties
    var app = dictionary.app;
    var pool = dictionary.conn;
    var patient_properties = ['Identif', 'email', 'telefono', 'Name', 'Surname'];
    
    // Private Methods
    function loadPatients(req, res)
    {
        // Load all of the patients that are accessible by the doctor that is logged in
        var doctor_id = req.CareLayerSessionCookie.id;
        pool.getConnection(function(err, connection)
        {
            if (err) 
            {
                connection.release();
                res.json({"code" : 100, "status" : "Error in connection database"});
                return;
            } 
            var query_str = 'SELECT PAT.* FROM \
                                (SELECT DISTINCT Identif FROM (SELECT DISTINCT DG2.IdDoctor FROM doctorsgroups DG1 INNER JOIN doctorsgroups DG2 ON DG1.IdGroup = DG2.IdGroup WHERE DG1.IdDoctor = ?) AS DG \
                                    INNER JOIN \
                                usuarios U \
                                    ON DG.IdDoctor = U.IdCreator \
                        UNION \
                            SELECT DISTINCT DLD.IdPac AS Identif FROM \
                                doctorslinkdoctors DLD \
                                    INNER JOIN \
                                (SELECT DISTINCT DG2.IdDoctor FROM doctorsgroups DG1 INNER JOIN doctorsgroups DG2 ON DG1.IdGroup = DG2.IdGroup WHERE DG1.IdDoctor = ?) AS DG \
                                    ON DLD.IdMED = DG.IdDoctor \
                        UNION \
                            SELECT DISTINCT DLU.IdUS AS Identif FROM \
                                doctorslinkusers DLU \
                                    INNER JOIN \
                                (SELECT DISTINCT DG2.IdDoctor FROM doctorsgroups DG1 INNER JOIN doctorsgroups DG2 ON DG1.IdGroup = DG2.IdGroup WHERE DG1.IdDoctor = ?) AS DG \
                                    ON DLU.IdMED = DG.IdDoctor \
                                ) RES LEFT JOIN usuarios PAT ON PAT.Identif = RES.Identif WHERE RES.Identif IS NOT NULL AND PAT.Identif IS NOT NULL ORDER BY PAT.Surname, PAT.name';
            connection.query(query_str, [doctor_id, doctor_id, doctor_id], function(err, rows)
            {
                connection.release();
                var ret = [];
                if(!err) 
                {
                    for(var i = 0 ; i < rows.length; i++)
                    {
                        var patient = rows[i];
                        var patient_object = {};
                        
                        for(var property in patient)
                        {
                            if(patient.hasOwnProperty(property) && patient_properties.indexOf(property) != -1)
                            {
                                patient_object[property] = patient[property];
                            }
                        }
                        
                        ret.push(patient_object);
                    }
                    
                }
                else
                {
                    ret.error = "An Error Has Occured";
                    ret.status = 'error';
                }
                
                res.send(ret);
            });
        });
    }
    
    
    // Constructor
    function constructor()
    {
        // Handle AJAX calls for Patients
        app.post('/patients', function(req, res)
        {
            if(req.hasOwnProperty('CareLayerSessionCookie') && req.CareLayerSessionCookie.hasOwnProperty('id') && req.CareLayerSessionCookie.id > 0)
            {
                if(req.body.type == 'loadPatients')
                {
                    loadPatients(req, res);
                }
            }
            else
            {
                res.send({error: 'Session Not Set'});
            }
            
        });
    }
    
    constructor();
    
    return this;
};

module.exports = Patients;