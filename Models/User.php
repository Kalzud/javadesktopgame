<?php
session_start();
class User implements JsonSerializable
{
    //declare fields
    protected $userID, $emailAddress, $realName, $userName, $password, $profileImage, $longitude, $latitude, $bio;

    /**
     * User constructor.
     * @param $userID
     * @param $emailAddress
     * @param $realname
     * @param $username
     * @param $password
     * @param $profileImage
     * @param $longitude
     * @param $latitude
     * @param $bio
     */
    public function __construct($dbRow){
        $this->userID = $dbRow['user_id'];
        $this->emailAddress = $dbRow['emailAddress'];
        $this->realName = $dbRow['realName'];
        $this->userName = $dbRow['userName'];
        $this->password = $dbRow['password'];
        $this->profileImage = $dbRow['profileImage'];
        $this->longitude = $dbRow['longitude'];
        $this->latitude = $dbRow['latitude'];
        $this->bio = $dbRow['bio'];
    }

    public function jsonSerialize()
    {
        return [
            '_id' => $this->userID,
            '_username' => $this->userName,
            '_name' => $this->realName,
            '_email' => $this->emailAddress,
            '_photo' => $this->profileImage,
            '_lat' => $this->latitude,
            '_long' => $this->longitude,
            '_bio' => $this->bio,
            '_password' => $this->password,
        ];
    }



    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @return mixed
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }
}