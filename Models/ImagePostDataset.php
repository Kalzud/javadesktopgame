<?php
require_once ("Models/Database.php");
require_once ("Models/User.php");
require_once ("Models/ImagePost.php");
class ImagePostDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchAllPics(){
        $sqlQuery = "SELECT * FROM post_img";
        //echo $sqlQuery;
        $statement = $this->_dbHandle->prepare($sqlQuery);
        //var_dump($statement);
        //$statement->bindParam(1, $username);
        $statement->execute();

        $dataset = [];
        while($row = $statement->fetch()){
            $dataset[] = new ImagePost($row);
        }

        return $dataset;
    }
    public function fetchAllPicsAndUsers(){
        $sqlQuery = "SELECT all_users.user_id, all_users.userName, all_users.profileImage, post_img.img_post, post_img.img_tag, post_img.post_img_id, post_img.userid, post_img.text_post, post_img.text_post_id FROM all_users INNER JOIN post_img ON post_img.userid = all_users.user_id";
        //echo $sqlQuery;
        $statement = $this->_dbHandle->prepare($sqlQuery);
        //var_dump($statement);
        //$statement->bindParam(1, $username);
        $statement->execute();

        $dataset = [];
        while($row = $statement->fetch()){
            $dataset[] = new ImagePost($row);
        }

        return $dataset;
    }
    public function fetchUserPicUploads($userid)
    {
        $sqlQuery = "SELECT * FROM post_img WHERE userid = $userid";
       // var_dump($sqlQuery);
//        var_dump($sqlQuery);
        //echo $sqlQuery;
        $statement = $this->_dbHandle->prepare($sqlQuery);
        //var_dump($statement);
        //$statement->bindParam(1, $username);
        $statement->execute();

        $dataset = [];
        while($row = $statement->fetch()){
            $dataset[] = new ImagePost($row);
        }
        //var_dump($dataset);
        return $dataset;
    }
    public function post($imgpath, $userid, $tag){
        $sqlQuery = "INSERT INTO post_img (userid, img_post, img_tag) VALUES ($userid, '$imgpath', '$tag')";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
    public function updateTag($tag, $path, $userid){
        $sqlQuery = "UPDATE post_img SET img_tag = '$tag' WHERE img_post ='$path' AND userid = '$userid'";
        var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
    public function comment($userid, $comment, $id, $image){
        $sqlQuery = "INSERT INTO post_img (userid, text_post, text_post_id, img_post) VALUES ($userid, '$comment', $id, '$image')";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
    public function deletePost($uid, $img) //delete candidate where c_id is same as c_id entered
    {
        $sqlQuery = "DELETE FROM post_img WHERE userid = '$uid' AND img_post = '$img'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
    public function deleteTextPost($uid, $text) //delete candidate where c_id is same as c_id entered
    {
        $sqlQuery = "DELETE FROM post_img WHERE userid = '$uid' AND text_post = '$text'";
        var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
    public function fetchPostComments($imgowner, $img){
        $sqlquery = "SELECT * FROM post_img WHERE userid = '$imgowner' AND img_post = '$img'";
        $statement = $this->_dbHandle->prepare($sqlquery);
        $statement->execute();
        $dataset = [];
        while($row = $statement->fetch()){
            $dataset [] = new ImagePost($row);
        }
        return $dataset;
    }
}