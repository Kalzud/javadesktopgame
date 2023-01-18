<?php
require_once ("Models/Database.php");
require_once ("Models/User.php");
require_once ("Models/Likes.php");
class LikesDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function like($imgLikedUserid, $imgLiked, $likerid){
        $sqlquery = "SELECT * FROM likes WHERE img_like_userid = '$imgLikedUserid' AND img_liked = '$imgLiked' AND likerid = '$likerid'";
        $statement = $this->_dbHandle->prepare($sqlquery);
        $statement->execute();
        $row = $statement->fetch();
        if(empty($row)){
            $sqlquery = "INSERT INTO likes (img_like_userid, img_liked, likerid) VALUES ('$imgLikedUserid', '$imgLiked', '$likerid')";
            $statement = $this->_dbHandle->prepare($sqlquery);
            $statement->execute();
            return true;
        }
        return false;
    }
    public function likeText($text, $id, $likerid){
        $sqlquery = "SELECT * FROM likes WHERE text_post = '$text' AND img_like_userid = '$id' AND likerid = '$likerid'";
        //var_dump($sqlquery);
        $statement = $this->_dbHandle->prepare($sqlquery);
        $statement->execute();
        $row = $statement->fetch();
        if(empty($row)){
            $sqlquery = "INSERT INTO likes (text_post, img_like_userid, likerid) VALUES ('$text', '$id', '$likerid')";
           // var_dump($sqlquery);
            $statement = $this->_dbHandle->prepare($sqlquery);
            $statement->execute();
            return true;
        }
        return false;
    }

    public function totalLikes($imgLikedUserid){
        $sqlquery = "SELECT * FROM likes WHERE img_like_userid = $imgLikedUserid AND img_liked is not null";
        $statement = $this->_dbHandle->prepare($sqlquery);
        $statement->execute();
        $dataset = [];
        while($row = $statement->fetch()){
            $dataset[] = new Likes($row);
        }
        return $dataset;
    }

    public function imgLikes($imgowner, $img){
        $sqlquery = "SELECT * FROM likes WHERE img_like_userid = '$imgowner' AND img_liked = '$img'";
        $statement = $this->_dbHandle->prepare($sqlquery);
        $statement->execute();
        $dataset = [];
        while($row = $statement->fetch()){
            $dataset [] = new Likes($row);
        }
        return $dataset;
    }
    public function textLikes($imgowner, $text){
        $sqlquery = "SELECT * FROM likes WHERE img_like_userid = '$imgowner' AND text_post = '$text'";
        $statement = $this->_dbHandle->prepare($sqlquery);
        $statement->execute();
        $dataset = [];
        while($row = $statement->fetch()){
            $dataset [] = new Likes($row);
        }
        return $dataset;
    }


//    public function usersThatLiked($id){
//        $sqlquery = "SELECT * FROM likes,all_users WHERE img_like_userid = '$id'";
//        //var_dump($sqlquery);
//        $statement = $this->_dbHandle->prepare($sqlquery);
//        $statement->execute();
//        $dataset = [];
//        while($row = $statement->fetch()){
//            $dataset [] = new User($row);
//        }
//        return $dataset;
//    }
}