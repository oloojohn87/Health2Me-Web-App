<?php

require("environment_detail.php");


class PatientsData

{
    
    private $PDO;
    private $doctor;
    private $searchString;
    private $group;
    private $page;
    private $itemsPerPage;

    function __construct($connection, $doctor_id, $search_string, $is_group, $current_page, $items_per_page)
    {

        $this->PDO = $connection;

        $this->doctor = $doctor_id;
        $this->searchString = $search_string;
        $this->group = $is_group;
        $this->page = $current_page;
        $this->itemsPerPage = $items_per_page;

        if($this->searchString == null || $this->searchString == " " || $this->searchString == "" || $this->searchString == -111)
        {
            $this->searchString = "";
        }
        else
        {
            $this->group = -1;
        }
    }

    //Function for getting the names of the patients and emails    
    public function getPatients()
    {

        $result_array = array();
        $total = -1;
        $count = 0;
        
        /* 
         *  If this is the first page, we want the query to get all of the results so we can tell the client can know how many pages there are, 
         *  then we return the first page by fetching just the first $this->itemsPerPage number of results
         *
         *  If, however, this is not the first page, then the client already knows how many pages there are. 
         *  So we can query for just the page we are interested in with LIMIT X, Y
         *
         */
        
        $limit_query = "";
        if($this->page > 1)
            $limit_query = "LIMIT ?, ?";
        
        $query = $this->PDO->prepare("Select q.* from ((select USU.* from ".$dbname.".usuarios USU INNER JOIN ((select A.idDoctor from ".$dbname.".doctorsgroups A INNER JOIN (select idGroup from ".$dbname.".doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup)) DG where DG.idDoctor=USU.IdCreator)UNION(select USU.* from ".$dbname.".usuarios USU INNER JOIN (select IdPac from ".$dbname.".doctorslinkdoctors where IdMED2=?) DLD where DLD.IdPac=USU.Identif)UNION(select USU.* from ".$dbname.".usuarios USU INNER JOIN (select IdUs from ".$dbname.".doctorslinkusers where IdMED=?) DLU where DLU.IdUs=USU.Identif)UNION(Select * from ".$dbname.".usuarios where IdCreator=?))q where q.Surname like ? or q.Name like ? or q.IdUsFIXEDNAME like ? ".$limit_query);

        $query->bindValue(1, $this->doctor, PDO::PARAM_INT);
        $query->bindValue(2, $this->doctor, PDO::PARAM_INT);
        $query->bindValue(3, $this->doctor, PDO::PARAM_INT);
        $query->bindValue(4, $this->doctor, PDO::PARAM_INT);
        $query->bindValue(5, '%'.$this->searchString.'%', PDO::PARAM_STR);
        $query->bindValue(6, '%'.$this->searchString.'%', PDO::PARAM_STR);
        $query->bindValue(7, '%'.$this->searchString.'%', PDO::PARAM_STR);
        if($this->page > 1)
        {
            $query->bindValue(8, intval($this->page - 1) * intval($this->itemsPerPage), PDO::PARAM_INT);
            $query->bindValue(9, $this->itemsPerPage, PDO::PARAM_INT);
        }
        
        $query->execute();

        if($this->page == 1)
        {
            $total = $query->rowCount();
        }

        while(($row = $query->fetch(PDO::FETCH_ASSOC)) && $count < $this->itemsPerPage)
        {
            $patient = array();
            $patient['identifier'] = $row['Identif'];
            $patient['first_name'] = $row['Name'];
            $patient['last_name'] = $row['Surname'];
            $patient['user_name'] = $row['email'];

            // get number of reports
            $reports = $this->getReportsData($row['Identif']);
            $patient['reports'] = $reports['reports'];
            $patient['total_reports'] = $reports['total'];

            //Pushing the patient to the array
            array_push($result_array, $patient);

            $count++;
        }

        return array("page" => $this->page, "total_pages" => ceil($total / $this->itemsPerPage), "data" => $result_array);

    }

    //Function for getting the report specific data  
    public function getReportsData($userId)
    {

        $countPIN = 0;
        $countactualPIN = 0;

        $TotalPIN = $this->PDO->prepare("SELECT count(*) as numreports FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? and emr_old = 0 ");
        $TotalPIN->bindValue(1, $userId, PDO::PARAM_INT);
        $TotalPIN->execute();


        if($rowNUM = $TotalPIN->fetch(PDO::FETCH_ASSOC))
        {
            $countPIN = $rowNUM['numreports'];
        }

        $dluPIN = $this->PDO->prepare("SELECT * from doctorslinkusers where IdUs = ? and IdMED = ?");
        $dluPIN->bindValue(1, $userId, PDO::PARAM_INT);
        $dluPIN->bindValue(2, $this->doctor, PDO::PARAM_INT);
        $result = $dluPIN->execute();
        $dluqquery = '';
        $num = $dluPIN->rowCount();

        $i = 0;
        if($num > 0)

        {

            while($rowdlu = $dluPIN->fetch(PDO::FETCH_ASSOC))
            {
                $dluqquery = '';

                if($rowdlu['IdPIN']==null)
                {

                    $dluqquery = "UNION(select lp.* from ".$dbname.".lifepin lp INNER JOIN (select IdMED from ".$dbname.".doctorslinkusers where IdUs=? and IdMED=? and IdPIN IS NULL) dlu where dlu.IdMED=lp.IdMED and lp.IdUsu=?)";

                }
                else
                {

                    if($rowdlu['IdPIN']!=null)
                    {

                        $dluqquery="UNION(select lp.* from ".$dbname.".lifepin lp INNER JOIN (select * from ".$dbname.".doctorslinkusers where IdUs=? and IdMED=?) dlu where dlu.IdPIN=lp.IdPin and lp.IdUsu=?)";

                    }

                }

            }
        }


        $loadquery="(select LP.* from ".$dbname.".lifepin LP INNER JOIN ((select A.idDoctor from ".$dbname.".doctorsgroups A INNER JOIN (select idGroup from ".$dbname.".doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup) UNION (select Id from ".$dbname.".doctors where Id=?) UNION (select IdMED from ".$dbname.".doctorslinkdoctors where IdMED2=? and IdPac=?)) AB where LP.IdMed=AB.idDoctor and IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from ".$dbname.".tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0)UNION(select * from ".$dbname.".lifepin where IdMED=? and IdUsu=? and (markfordelete=0 or markfordelete is null) and emr_old=0)".$dluqquery;

        $viewablePIN = $this->PDO->prepare($loadquery);
        $viewablePIN->bindValue(1, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(2, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(3, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(4, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(5, $userId, PDO::PARAM_INT);
        $viewablePIN->bindValue(6, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(7, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(8, $userId, PDO::PARAM_INT);
        $viewablePIN->bindValue(9, $userId, PDO::PARAM_INT);
        $viewablePIN->bindValue(10, $this->doctor, PDO::PARAM_INT);
        $viewablePIN->bindValue(11, $userId, PDO::PARAM_INT);
        $viewablePIN->execute();


        $hasfullaccess = 0;
        if($viewablePIN->fetch(PDO::FETCH_ASSOC))
        {
           $countactualPIN = $viewablePIN->rowCount();
           $hasfullaccess = 1;
        }

        if($hasfullaccess==1)
        {
            $viewable= $this->PDO->prepare("Select count(*) as patreport from ".$dbname.".lifepin where (IdMed IS NULL or IdMed=0 or IdMed=?) and (markfordelete IS NULL or markfordelete=0) and IdUsu=?");
            $viewable->bindValue(1, $userId, PDO::PARAM_INT);
            $viewable->bindValue(2, $userId, PDO::PARAM_INT);
            $viewable->execute();

            if($row=$viewable->fetch(PDO::FETCH_ASSOC))
                $numPIN = $row['patreport'];
            
            //If Idpin is null the doctor has full access to the patients
            $countactualPIN += $numPIN;          

        }

        //$NReports = $countactualPIN."/".$countPIN;

        return array("reports" => $countactualPIN, "total" => $countPIN);

    }

}

?>
