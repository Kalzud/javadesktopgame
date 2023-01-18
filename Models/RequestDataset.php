<?php
require_once ("Models/Database.php");
require_once ("Models/Request.php");
require_once ("Models/UserDataset.php");
require_once ("Models/User.php");
class RequestDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function sendRequest($senderID, $receiverID){
        if($this->verifySendRequest($senderID, $receiverID) == true){
            $sqlQuery = "INSERT INTO request (sender, receiver) VALUE (?,?)";
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->bindParam(1, $senderID);
            $statement->bindParam(2, $receiverID);
            $statement->execute();
        }

    }

    public function verifySendRequest($senderID, $receiverID){
        if($senderID == $receiverID){
            return false;
        }
        $sqlQuery = "SELECT * FROM friend WHERE friend1 = $senderID AND friend2 = $receiverID OR friend1 = $receiverID AND friend2 = $senderID";
//        var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
//        $statement->bindParam(1, $senderID);
//        $statement->bindParam(2, $receiverID);
        $statement->execute();

//        $row = [];
        $row = $statement->fetch();
        if(!empty($row))
            return false;

        $sqlQuery = "SELECT * FROM request WHERE sender = $senderID AND receiver = $receiverID OR sender = $receiverID AND receiver = $senderID";
       // var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
//        $statement->bindParam(1, $senderID);
//        $statement->bindParam(2, $receiverID);
        $statement->execute();

        $row = $statement->fetch();
        //var_dump($row);
        if(!empty($row))
            return false;

        return true;

    }

    public function showRequest($user_id){
        $sqlQuery = "SELECT * FROM request,all_users WHERE receiver = ? AND all_users.user_id = sender";
//        var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $user_id);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);
 //              var_dump($dataset);

        }
        return $dataset;
    }

    public function acceptRequest($sender, $receiver){
        $sqlQuery = "SELECT * FROM friend WHERE (friend1 = '$sender' AND friend2 = '$receiver') OR (friend2 = '$sender' AND friend1 ='$receiver')";
        //var_dump ($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $sender);
        $statement->bindParam(2, $receiver);
        $statement->execute();
        $row = $statement->fetch();

        if(empty($row)){
            $sqlQuery = "INSERT INTO friend (friend1, friend2) VALUE ($sender, $receiver)";
            //var_dump ($sqlQuery);
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->bindParam(1, $sender);
            $statement->bindParam(2, $receiver);
            $statement->execute();

            //Delete from request
            $sqlQuery = "DELETE FROM request WHERE receiver = $receiver AND sender = $sender";
            //var_dump($sqlQuery);
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->bindParam(1, $sender);
            $statement->bindParam(2, $receiver);
            //var_dump($statement);
            $statement->execute();
            return true;
        }
       return false;
    }

    public function deleteRequest($receiver, $sender){
        $sqlQuery = "DELETE FROM request WHERE receiver = $receiver AND sender = $sender";
        //var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $receiver);
        $statement->bindParam(2, $sender);
        //var_dump($statement);
        $statement->execute();
    }
    public function showPending($user_id){
        $sqlQuery = "SELECT * FROM request,all_users WHERE sender = $user_id AND all_users.user_id = receiver";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);
            //              var_dump($dataset);

        }
        return $dataset;
    }

}