<?php
require_once "User.php";
class Friend extends User
{
    protected $friendsID, $friend1, $friend2;

    /**
     * User constructor.
     * @param $userID
     * @param $emailAddress
     * @param $realname
     */
    public function __construct($dbRow)
    {
        parent::__construct($dbRow);
        $this->friendsID = $dbRow['friends_id'];
        $this->friend1 = $dbRow['friend1'];
        $this->friend2 = $dbRow['friend2'];

    }

    /**
     * @return mixed
     */
    public function getFriendsID(): mixed
    {
        return $this->friendsID;
    }

    /**
     * @return mixed
     */
    public function getFriend1(): mixed
    {
        return $this->friend1;
    }

    /**
     * @return mixed
     */
    public function getFriend2(): mixed
    {
        return $this->friend2;
    }
}