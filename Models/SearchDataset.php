<?php
require_once ("Models/Database.php");
require_once ("Models/FriendDataset.php");
require_once ("Models/UserDataset.php");
require_once ("Models/RequestDataset.php");
class SearchDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }


    public function fetchSomeUsers($searchTerm){ //search for everything
        $sqlQuery = "SELECT * FROM all_users WHERE realName LIKE '%".$searchTerm."%' OR userName LIKE '%".$searchTerm."%'";

        $statement = $this->_dbHandle->prepare($sqlQuery);
//            var_dump($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);

//            var_dump($dataset);
        }
        return $dataset;
    }

    public function fetchNames($searchTerm){ //search for Name
        $sqlQuery = "SELECT * FROM all_users WHERE realName LIKE '%".$searchTerm."%'";

        $statement = $this->_dbHandle->prepare($sqlQuery);
//            var_dump($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);

//            var_dump($dataset);
        }
        return $dataset;
    }

    public function fetchEmails($searchTerm){ //search for emailaddress
        $sqlQuery = "SELECT * FROM all_users WHERE emailAddress LIKE '%".$searchTerm."%'";

        $statement = $this->_dbHandle->prepare($sqlQuery);
//            var_dump($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);

//            var_dump($dataset);
        }
        return $dataset;
    }

    public function fetchUserNames($searchTerm){ //search for username
        $sqlQuery = "SELECT * FROM all_users WHERE userName LIKE '%".$searchTerm."%'";

        $statement = $this->_dbHandle->prepare($sqlQuery);
//            var_dump($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);

//            var_dump($dataset);
        }
        return $dataset;
    }

    public function verifyRequestSearch($user_id){
        $sqlQuery = "SELECT * FROM request,all_users WHERE receiver = $user_id AND all_users.user_id = sender";
        //var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);
            //              var_dump($dataset);

        }

//        if($check != null)
//            return false;

        return true;
    }

    public function searcRequest($user_id, $searchTerm){
        $sqlQuery = "SELECT * FROM request,all_users WHERE receiver = $user_id AND all_users.user_id = sender";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);

            //              var_dump($dataset);

        }
        return $dataset;
    }

    public function searchFriend($user_id){
        try {
            //$sqlQuery = "SELECT * FROM friends WHERE user_one = :user_id OR user_two = :user_id";
            $sqlQuery = "
            SELECT * from (
                SELECT *
                from all_users
            WHERE user_id in (
                SELECT  friend1 as friend
                FROM friend
                WHERE (friend.friend1 = $user_id OR friend.friend2 = $user_id)
                UNION
                SELECT friend2 as friend
                FROM friend
                WHERE (friend.friend1 = $user_id OR friend.friend2 = $user_id)

            )
                and user_id != $user_id
            )ping inner join friend WHERE ((friend1=ping.user_id and friend2= $user_id) OR (friend1=$user_id AND friend2=ping.user_id))";

            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $statement->execute();

//            var_dump($str);
            $dataset = [];
            while ($row = $statement->fetch()) {
                $dataset[] = new Friend($row);
//                var_dump($dataset);

            }
            //$friendList = new Friend($row);
            // return $friendList;
            return $dataset;
        }catch (PDOException $e){
            die($e->getMessage());
        }
    }

    public function searchRequest($user_id){
        $sqlQuery = "SELECT * FROM request,all_users WHERE receiver = $user_id AND all_users.user_id = sender";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);
        }
        return $dataset;
    }
    public function searchPends($user_id){
        $sqlQuery = "SELECT * FROM request,all_users WHERE sender = $user_id AND all_users.user_id = receiver";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new User($row);
        }
        return $dataset;
    }
}