<?php

class Likes
{
    //declare fields
    protected $likeID, $imgLikeUserID, $imgLiked, $likerID;

    /**
     * User constructor.
     * @param $likeID
     * @param $imgLikeUserID
     * @param $imgLiked
     * @param $likerID
     */
    public function __construct($dbRow){
        $this->likeID = $dbRow['likeid'];
        $this->imgLikeUserID = $dbRow['img_like_userid'];
        $this->imgLiked = $dbRow['img_liked'];
        $this->likerID = $dbRow['likerid'];
    }

    /**
     * @return mixed
     */
    public function getLikeID(): mixed
    {
        return $this->likeID;
    }

    /**
     * @return mixed
     */
    public function getImgLikeUserID(): mixed
    {
        return $this->imgLikeUserID;
    }

    /**
     * @return mixed
     */
    public function getImgLiked(): mixed
    {
        return $this->imgLiked;
    }

    /**
     * @return mixed
     */
    public function getLikerID(): mixed
    {
        return $this->likerID;
    }
}