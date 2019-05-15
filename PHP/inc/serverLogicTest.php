<?php
require "../db/db.php";
class ServerLogicTest
{
    private $db;
    private $var;

    function __construct()
    {
        $this->db = new DataBase();
        if (!empty($_POST)) {


            if (!empty($_POST) && $_POST['action'] == "insertRow") {
                $this->handlePostVars();
                $this->insertRow();
            }
            if (!empty($_POST) && $_POST['action'] == "updateRow") {
                $this->handlePostVars();
                $this->updateRow();
            }
            if (!empty($_POST) && $_POST['action'] == "deleteContact") {
                $this->deleteContact($this->db->escape($_POST['contactId']));
            }
            die();
        }
        else if (!empty($_GET['action']))
        {
            if (!empty($_GET) && $_GET['action'] == "getContacts") {
                $this->handleGetVars(false);
                echo json_encode($this->getContacts());
                die();
            }
            if (!empty($_GET) && $_GET['action'] == "searchContacts") {
                $this->handleGetVars(false);
                echo json_encode($this->searchContact());
                die();
            }
        }

    }

    function searchContact()
    {
        $search = $this->var["search"]["value"];
        $currentPos = $this->var["page"]["value"] * $this->var["limit"]["value"];
        $sql = "SELECT * FROM contact 
                WHERE Name LIKE '%".$search."%' 
                OR Surname LIKE '%".$search."%'
                AND IsDeleted is NULL
                OR ContactId IN (SELECT ContactId FROM contactnumber WHERE ContactNumber LIKE '%".$search."%')
                OR ContactId IN (SELECT ContactId FROM contactemail WHERE ContactEmail LIKE '%".$search."%')
                ORDER BY ContactId 
                LIMIT ".$currentPos.",".$this->var["limit"]["value"];

        $contacts = $this->db->query($sql);
        return $this->getContactNumbersAndEmails($contacts);
    }

    function deleteContact($id)
    {
        $sql = "UPDATE contact SET IsDeleted = 1 WHERE ContactId ='".$id."'";
        $this->db->query($sql);

    }

    function getContactNumbersAndEmails($contacts)
    {

        $sql = "SELECT Count(ContactId) AS total FROM contact WHERE isDeleted is NULL";
        $result = $this->db->query($sql);


        $sql = "SELECT * FROM contactnumber ORDER BY ContactId";
        $contactNumbers = $this->db->query($sql);
        foreach ($contacts as $k=>$v)
        {
            foreach ($contactNumbers as $kk=>$vv)
            {
                if($v['ContactId'] == $vv['ContactId']) {
                    $contacts[$k]['contacts'][] = $vv;
                    unset($contactNumbers[$kk]);
                }
            }
            $temp = array();
            foreach ($contacts[$k]['contacts'] as $kk=>$vv)
            {
                $temp[] = $vv["ContactNumber"];
            }
            $contacts[$k]['contacts'] = implode(",",$temp);
            if($k == 0)
                $contacts[$k]['total'] = $result[0]['total'];
        }
        unset($contactNumbers);
        $sql = "SELECT * FROM contactemail ORDER BY ContactId";
        $contactEmails = $this->db->query($sql);
        foreach ($contacts as $k=>$v)
        {
            foreach ($contactEmails as $kk=>$vv)
            {
                if($v['ContactId'] == $vv['ContactId']) {
                    $contacts[$k]['emails'][] = $vv;
                    unset($contactEmails[$kk]);
                }
            }
            $temp = array();
            foreach ($contacts[$k]['emails'] as $kk=>$vv)
            {
                $temp[] = $vv["ContactEmail"];
            }
            $contacts[$k]['emails'] = implode(",",$temp);

        }

        unset($contactEmails);
        return $contacts;
    }

    function getContacts()
    {
        $this->checkRequired();
        $currentPos = $this->var["page"]["value"] * $this->var["limit"]["value"];
        $sql = "SELECT * FROM contact 
                WHERE IsDeleted is null 
                ORDER BY ContactId 
                LIMIT ".$currentPos.",".$this->var["limit"]["value"];

        $contacts = $this->db->query($sql);

        return $this->getContactNumbersAndEmails($contacts);
    }

    function checkRequired()
    {
        foreach ($this->var as $key => $value) {
            if (empty($value["value"]) && $value["value"] != 0 && !empty($value["error"])) {
                die($value["error"]);
            }
        }
    }

    function updateRow()
    {
        $this->checkRequired();
        $sql = "UPDATE contact SET 
                `Name` = '" . $this->var["firstName"]["value"] . "'
                ,Surname ='" . $this->var["lastName"]["value"] . "'
                WHERE ContactId = '" . $this->var["id"]["value"] . "'";

        if (!empty($this->db->query($sql))) {
            $this->updateContacts($this->var["id"]["value"]);
            $this->updateEmails($this->var["id"]["value"]);
        }
    }

    function insertRow()
    {

        $this->checkRequired();
        $sql = "INSERT INTO contact(Name,Surname) 
                   VALUES ('" . $this->var["firstName"]["value"] . "'
                          ,'" . $this->var["lastName"]["value"] . "')";

        $id = $this->db->query($sql);

       $this->updateContacts($id);
       $this->updateEmails($id);
    }

    function updateContacts($id)
    {
        $sql = "DELETE FROM contactnumber WHERE ContactId = '".$id."'";

        if(!empty($this->db->query($sql))) {

            $contacts = explode(',',$this->var["contactNumber"]["value"] );
            foreach ($contacts as $k => $v) {
                $sql = "INSERT INTO contactnumber(ContactNumber,ContactId) VALUES('" . $v . "','" . $id . "')";

                $this->db->query($sql);
            }
        }
    }

    function updateEmails($id)
    {
        $sql = "DELETE FROM contactEmail WHERE ContactId = '".$id."'";
        if(!empty($this->db->query($sql))) {
            $emails = explode(',',$this->var["email"]["value"] );
            foreach ($emails as $k => $v) {
                $sql = "INSERT INTO contactEmail(ContactEmail,ContactId) VALUES('" . $v . "','" . $id . "')";
                $this->db->query($sql);
            }
        }
    }

    function handlePostVars()
    {
        $this->var["firstName"]["error"] = "First name is required";
        $this->var["lastName"]["error"] = "Last name is required";
        $this->var["contactNumber"]["error"] = "Contact number Is required";
        $this->var["email"]["error"] = "email Is required";

        $this->var["id"]["value"] = $this->db->escape($_POST['id']);
        $this->var["firstName"]["value"] = $this->db->escape($_POST['firstname']);
        $this->var["lastName"]["value"] = $this->db->escape($_POST['lastname']);
        $this->var["email"]["value"] = $this->db->escape($_POST['email']);
        $this->var["contactNumber"]["value"] = $this->db->escape($_POST["contactNumber"]);
    }

    function handleGetVars()
    {
        $this->var["page"]["error"] = "page is required";
        $this->var["limit"]["error"] = "limit Is required";

        $this->var["limit"]["value"] = $this->db->escape($_GET['limit']);
        $this->var["page"]["value"] = $this->db->escape($_GET['page']);
        $this->var["search"]["value"] = $this->db->escape($_GET["search"]);

    }
}

?>