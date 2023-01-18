<?php

class ImagePost
{
    //declare fields
    protected $postImgID, $userID, $imgPost, $imgTag, $textPost, $textPostID;

    /**
     * User constructor.
     * @param $postImgID
     * @param $userID
     * @param $imgPost
     * @param $imgTag
     * @param $textPost
     * @param $textPostID
     */
    public function __construct($dbRow){
        $this->postImgID = $dbRow['post_img_id'];
        $this->userID = $dbRow['userid'];
        $this->imgPost = $dbRow['img_post'];
        $this->imgTag = $dbRow['img_tag'];
        $this->textPost = $dbRow['text_post'];
        $this->textPostID = $dbRow['text_post_id'];
    }

    /**
     * @return mixed
     */
    public function getUserID(): mixed
    {
        return $this->userID;
    }

    /**
     * @return mixed
     */
    public function getImgPost(): mixed
    {
        return $this->imgPost;
    }

    /**
     * @return mixed
     */
    public function getPostImgID(): mixed
    {
        return $this->postImgID;
    }

    /**
     * @return mixed
     */
    public function getImgTag(): mixed
    {
        return $this->imgTag;
    }

    /**
     * @return mixed
     */
    public function getTextPost(): mixed
    {
        return $this->textPost;
    }

    /**
     * @return mixed
     */
    public function getTextPostID(): mixed
    {
        return $this->textPostID;
    }
}