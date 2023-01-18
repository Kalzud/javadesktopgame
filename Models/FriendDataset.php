<?php
require_once ("Database.php");
require_once ("Models/Friend.php");
class FriendDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetch_my_friends($user_id){
        try {
            //get list of friends from database for particular user
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
//            $statement->bindParam(1, $user_id);
//            $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $statement->execute();

            $dataset = [];
            while ($row = $statement->fetch()) {
                $dataset[] = new User($row);
            }
            return $dataset;
        }catch (PDOException $e){
            die($e->getMessage());
        }
    }


    public function block($friend, $receiver){
        $sqlQuery = "DELETE FROM friend WHERE ((friend1 = $friend AND friend2 = $receiver) OR (friend1 = $receiver AND friend2 = $friend))";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $friend);
        $statement->bindParam(2, $receiver);
        $statement->execute();
    }



}