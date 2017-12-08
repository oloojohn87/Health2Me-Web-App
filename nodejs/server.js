var args = process.argv.slice(2);

// defaults
var port = 4226;
var secure = false;
var key = '/etc/ssl/key/comodokey.key';
var cert = '/etc/ssl/key/comodocrt.crt';
var ca = ['/etc/ssl/key/ca1.crt', '/etc/ssl/key/ca1.crt'];

// parse command line arguments
var ca_overwritten = false;
for(var i = 0; i < args.length; i++)
{
    if(args[i] == '-p' || args[i] == '-P')
    {
        port = args[i + 1];
        i += 1;
    }
    else if(args[i] == '-s' || args[i] == '-S')
    {
        secure = true;
    }
    else if(args[i].toLowerCase() == '-key')
    {
        key = args[i + 1];
        i += 1;
    }
    else if(args[i].toLowerCase() == '-cert')
    {
        cert = args[i + 1];
        i += 1;
    }
    else if(args[i].toLowerCase() == '-ca')
    {
        if(!ca_overwritten)
        {
            ca_overwritten = true;
            ca = [];
        }
        ca.push(args[i + 1]);
        i += 1;
    }
}

// create app
var express = require('express');
var mysql = require('mysql');
var bodyParser = require('body-parser');
//var refer = require('getReferredIn_Out.js');
var app = express();
app.use(function(req, res, next) 
{
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    res.header("Access-Control-Allow-Headers", "Content-Type");
    res.header("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");
    next();
});
app.use(bodyParser());

// create pool of mysql connections
var pool = mysql.createPool(
{
    connectionLimit : 100,
    host     : 'dev.health2.me',
    user     : 'monimed',
    password : 'ardiLLA98',
    database : 'monimed',
    debug    :  false
});

// define express routes
app.post('/referredInOut', function (req, res)
{
    var doctor = req.body['Doctor'];
    var type = req.body['TypeEntry'];
    var num_reports = req.body['NReports'];
    var days_explore = 30;//req.body['days'];
    var group = req.body['Group'];
    
    pool.getConnection(function(err,connection)
    {
        if (err) 
        {
            connection.release();
            res.json({"code" : 100, "status" : "Error in connection database"});
            return;
        } 

        //Referred In
        if(type == 'in') {
            if(group == 0) {
                var query_str = "SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount" +
    "FROM(SELECT * FROM doctorslinkdoctors WHERE IdMED2 = ? ORDER BY Fecha DESC LIMIT 10) RES" +
    
     "LEFT JOIN" +

     "referral_stage RS ON RS.referral_id = RES.id" +

     "LEFT JOIN" +

     "doctors D ON D.id = RES.IdMED" +

     "LEFT JOIN" +

     "usuarios U ON U.identif = RES.IdPac" +

     "LEFT JOIN" +

    "(SELECT COUNT( * ) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = 'new'" +
         "AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status, patient_id) AS MI ON RES.IdPac = MI.patient_id" +

     "LEFT JOIN" +

         "(SELECT COUNT( * ) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ?" +
        "AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id";

                connection.query(query_str, [doctor, doctor, days_explore, doctor, days_explore],function(err,rows)
                {
                    connection.release();
                    if(!err) 
                    {
                        res.json(rows);
                    }           
                });
            }
            else
            {
                var query_str = 'SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM' +
                    '(' +
                        '(SELECT' + 'DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD WHERE DLD.IdMED2 = ?)' +
                            'UNION (SELECT' + 'DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD INNER JOIN' +
                                '(SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G' +
                            'ON DLD.IdMED2 = G.IdDoctor)' +
                            'ORDER BY Fecha DESC LIMIT 10' +
                    ') RES' +

                        'LEFT JOIN' +

                    'referral_stage RS ON RS.referral_id = RES.id' +

                        'LEFT JOIN' +

                    'doctors D ON D.id = RES.IdMED' +

                        'LEFT JOIN' +

                    'usuarios U ON U.identif = RES.IdPac' +

                        'LEFT JOIN' +

                    '(SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = "new" AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id'+

                        'LEFT JOIN' +

                    '(SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id';

                connection.query(query_str, [doctor, doctor, doctor, days_explore, doctor, days_explore],function(err,rows)
                {
                    connection.release();
                    if(!err) 
                    {
                        res.json(rows);
                    }           
                });
            }
        } else {
            if(group == 0) {
                var query_str = 'SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM' +
                    '(' +
                        'SELECT * FROM doctorslinkdoctors WHERE IdMED = ? ORDER BY Fecha DESC LIMIT 10' +
                    ') RES' +

                        'LEFT JOIN' +

                    'referral_stage RS ON RS.referral_id = RES.id' +

                        'LEFT JOIN' +

                    'doctors D ON D.id = RES.IdMED2' +

                        'LEFT JOIN' +

                    'usuarios U ON U.identif = RES.IdPac' +

                        'LEFT JOIN' +

                    '(SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = "new" AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id' +

                        'LEFT JOIN' +

                    '(SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id';

                connection.query(query_str, [doctor, doctor, days_explore, doctor, days_explore],function(err,rows)
                {
                    connection.release();
                    if(!err) 
                    {
                        res.json(rows);
                    }           
                });
            }
            else
            {
                var query_str = 'SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM' +
                    '(' +
                        '(SELECT' + 'DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD WHERE DLD.IdMED = ?)' +
                            'UNION (SELECT  DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD INNER JOIN' +
                                '(SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G' +
                            'ON DLD.IdMED = G.IdDoctor)' +
                            'ORDER BY Fecha DESC LIMIT 10' +
                    ') RES' +

                        'LEFT JOIN' +

                    'referral_stage RS ON RS.referral_id = RES.id' +

                        'LEFT JOIN' +

                    'doctors D ON D.id = RES.IdMED2' +

                        'LEFT JOIN' +

                    'usuarios U ON U.identif = RES.IdPac' +

                        'LEFT JOIN' +

                    '(SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = "new" AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id' +

                        'LEFT JOIN' +

                    '(SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id';

                connection.query(query_str, [doctor, doctor, doctor, days_explore, doctor, days_explore],function(err,rows)
                {
                    connection.release();
                    if(!err) 
                    {
                        res.json(rows);
                    }           
                });
            }
        }

        connection.on('error', function(err) 
        {      
            res.json({"code" : 100, "status" : "Error in connection database"});
            return;     
        });      
  });
});

// define express routes
app.post('/recentActivity', function (req, res)
{
    var doctor = req.body['Doctor'];
    var num_reports = req.body['NReports'];
    var group = req.body['Group'];
    
    pool.getConnection(function(err,connection)
    {
        if (err) 
        {
            connection.release();
            res.json({"code" : 100, "status" : "Error in connection database"});
            return;
        } 
        
        if(group == 0)
        {
        
            var query_str = "SELECT idus.Identif, U.Name, U.Surname,IFNULL(L.UPReport,0) AS UPreport,SUM(IF(M.tofrom = 'to',1,0)) AS Msg,SUM(IF(M.tofrom = 'from',1,0)) AS MsgSent,TIMESTAMPDIFF(HOUR, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(C.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS recent,TIMESTAMPDIFF(SECOND, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(C.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS compare,0 AS gr FROM( SELECT Identif FROM usuarios WHERE IdCreator = ? AND TIMESTAMPDIFF(DAY, signUpDate, NOW()) <= 30 UNION SELECT IdUs as Identif FROM doctorslinkusers WHERE IdMED = ? UNION SELECT IdPac as Identif FROM doctorslinkdoctors WHERE IdMED2 = ? ) idus /* JUST JOINING PURE usuarios TABLE FOR PATIENT NAMES */ LEFT JOIN usuarios U ON U.Identif = idus.Identif /* lifepin TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW AND NOT MARKED AS DELETED */ LEFT JOIN (SELECT COUNT(IdPin) AS UPReport, IdUsu, FechaInput FROM lifepin WHERE markfordelete IS NULL AND TIMESTAMPDIFF(DAY, FechaInput, NOW()) <= 30 GROUP BY IdUsu ) L ON L.IdUsu = idus.Identif /* message_infrastructureuser TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW */ LEFT JOIN (SELECT patient_id, fecha, tofrom FROM message_infrastructureuser WHERE TIMESTAMPDIFF(DAY, fecha, NOW()) <= 30 GROUP BY message_id) M ON M.patient_id = idus.Identif /* consults TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW WHEN THE PATIENT HAD A CONSULTATION WITH THE DOCTOR ONLY. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT Patient, Doctor, lastActive FROM consults WHERE TIMESTAMPDIFF(DAY, lastActive, NOW()) <= 30 GROUP BY consultationId) C ON C.Patient = idus.Identif AND C.Doctor = ? /* basicemrdata TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT IdPatient, latest_update FROM basicemrdata WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY IdPatient) B ON B.IdPatient = idus.Identif /* p_family TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT idpatient, latest_update FROM p_family WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) F ON F.idpatient = idus.Identif /* p_diagnostics TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT idpatient, latest_update FROM p_diagnostics WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) D ON D.idpatient = idus.Identif /* p_habits TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT idpatient, latest_update FROM p_habits WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) H ON H.idpatient = idus.Identif /* p_immuno TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT idpatient, latest_update FROM p_immuno WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) I ON I.idpatient = idus.Identif /* p_medication TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */ LEFT JOIN (SELECT idpatient, latest_update FROM p_medication WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) m ON m.idpatient = idus.Identif GROUP BY idus.Identif /* LIMITS SET FOR RECENT 30 DAYS FROM NOW */ HAVING recent < 720 and compare < 2592000 ORDER BY compare, U.Surname, U.Name";
        
            connection.query(query_str, [doctor, doctor, doctor, doctor],function(err,rows)
            {
                connection.release();
                if(!err) 
                {
                    res.json(rows);
                }           
            });
        }
        else
        {
            var query_str = $query_str = "SELECT idus.Identif, U.Name, U.Surname, IFNULL(L.UPReport,0) AS UPreport, SUM(IF(M.tofrom = 'to',1,0)) AS Msg, \
                  SUM(IF(M.tofrom = 'from',1,0)) AS MsgSent, TIMESTAMPDIFF(HOUR, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(Con.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS recent,TIMESTAMPDIFF(SECOND, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(Con.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS compare,MIN(idus.gr) AS gr FROM ( SELECT Identif, 0 AS gr FROM usuarios WHERE IdCreator = ? AND TIMESTAMPDIFF(DAY, signUpDate, NOW()) <= 30 UNION SELECT DLU.IdUs AS Identif, IF(DLU.IdMED = ?,0,1) AS gr FROM doctorslinkusers DLU INNER JOIN doctorsgroups DG1 ON DLU.IdMED = DG1.idDoctor INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup WHERE DG2.idDoctor = ? UNION SELECT IdPac as Identif, IF(DLD.IdMED2 = ?,0,1) AS gr FROM doctorslinkdoctors DLD INNER JOIN doctorsgroups DG1 ON DLD.IdMED2 = DG1.idDoctor INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup WHERE DG2.idDoctor = ?) idusLEFT JOIN usuarios U ON U.Identif = idus.Identif LEFT JOIN (SELECT COUNT(IdPin) AS UPReport, IdUsu, FechaInput FROM lifepin WHERE markfordelete IS NULL AND TIMESTAMPDIFF(DAY, FechaInput, NOW()) <= 30 GROUP BY IdUsu) L ON L.IdUsu = idus.Identif LEFT JOIN (SELECT patient_id, fecha, tofrom FROM message_infrastructureuser WHERE TIMESTAMPDIFF(DAY, fecha, NOW()) <= 30 GROUP BY message_id) M ON M.patient_id = idus.Identif LEFT JOIN (SELECT Patient, Doctor, lastActive FROM consults C INNER JOIN doctorsgroups DG1 ON C.Doctor = DG1.idDoctor INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup WHERE DG2.idDoctor = ? AND TIMESTAMPDIFF(DAY, C.lastActive, NOW()) <= 30 GROUP BY C.consultationId) Con ON Con.Patient = idus.Identif LEFT JOIN (SELECT IdPatient, latest_update FROM basicemrdata WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY IdPatient) B ON B.IdPatient = idus.Identif LEFT JOIN (SELECT idpatient, latest_update FROM p_family WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) F ON F.idpatient = idus.Identif LEFT JOIN (SELECT idpatient, latest_update FROM p_diagnostics WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) D ON D.idpatient = idus.Identif LEFT JOIN (SELECT idpatient, latest_update FROM p_habits WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) H ON H.idpatient = idus.Identif LEFT JOIN (SELECT idpatient, latest_update FROM p_immuno WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) I ON I.idpatient = idus.Identif LEFT JOIN (SELECT idpatient, latest_update FROM p_medication WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) m ON m.idpatient = idus.Identif GROUP BY idus.Identif HAVING recent < 720 AND compare < 2592000 ORDER BY compare, U.Surname, U.Name";
            
            connection.query(query_str, [doctor, doctor, doctor, doctor, doctor, doctor],function(err,rows)
            {
                connection.release();
                if(!err) 
                {
                    res.json(rows);
                }           
            });
        }

        connection.on('error', function(err) 
        {      
            res.json({"code" : 100, "status" : "Error in connection database"});
            return;     
        });
  });
    
});

// create server
var fs = require('fs');
var net = require('net');
var server = null;
if(secure)
{
    var options = 
    {
        key: fs.readFileSync(key),
        cert: fs.readFileSync(cert),
        ca: [],
        requestCert: true
    };
    for(var i = 0; i < ca.length; i++)
    {
        options.ca.push(fs.readFileSync(ca[i]));
    }
    server = require('https').createServer(options, app);
}
else
{
    server = require('http').createServer(app);
}

// launch socket.io, make it listen on the server
var io = require('socket.io').listen(server, {log: false, origins: '*:*'});

// define socket.io actions
io.sockets.on('connection', function(socket){
    socket.on('disconnect', function(){ });
});

// make the server listen on the port provided
server.listen(port);