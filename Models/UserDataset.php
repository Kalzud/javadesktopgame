<?php
require_once ("Models/Database.php");
require_once ("Models/User.php");
class UserDataset
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function loginUser($userName, $password)
    {
        $this->verifyLogin($userName, $password);
        // TODO: verify user
        // TODO: login user
    }

    private function verifyLogin($userName, $password)
    {
        try {
            $sqlQuery = "SELECT * FROM all_users WHERE userName = '$userName'";
            $statement = $this->_dbHandle->prepare($sqlQuery);
//            $statement->bindParam(1, $userName);
//            $statement->bindParam(2, $password);
            $statement->execute();

            if ($statement->rowCount() === 1) {
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                $verify = password_verify($password, $row['password']);
                if ($verify) {
                    $_SESSION = ['id' => $row['user_id'],
                        'username' => $row['userName']];
                    header('Location: home.php');
                }else{
                    return $this->_errorMessage = 'Wrong username or password';
                }
            } else {
                return $this->_errorMessage = 'Wrong username or password';
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //get loggedin user details
    public function fetchUserDetails($userid)
    {
        $sqlQuery = "SELECT * FROM all_users WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindParam(1, $userid);
        $statement->execute();

        $row = $statement->fetch();
        //var_dump($row);
        $user = new User($row);
        return $user;
    }


//sign user up
    public function signup($emailAddress, $realName, $userName, $password, $profileImage){

        $sqlQuery = "SELECT * FROM all_users WHERE userName = '$userName' OR emailAddress = '$emailAddress'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        //$statement->bindParam(1,$username);
        $statement->execute();

        $row = $statement->fetch();
        if (empty($row)) {
            $sqlQuery = "INSERT INTO all_users(emailAddress, realName, userName, password, profileImage) VALUES('$emailAddress', '$realName','$userName', '$password', '$profileImage')";
            $statement = $this->_dbHandle->prepare($sqlQuery);
//            $parameters = array($emailAddress, $realName, $userName, $password);
            $statement->execute();
            return true;
        } else {
            return false;
        }
    }

    public function fetchAllUserDetails()
    {
        $sqlQuery = "SELECT * FROM all_users";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataset = [];
        while($row = $statement->fetch()){
            $dataset[] = new User($row);
        }
        return $dataset;
    }
    public function uploadProfileImage($fileDestination, $userid){
        $sqlQuery = "UPDATE all_users SET profileImage = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $fileDestination);
        $statement->bindParam(2, $userid);
        $statement->execute();
    }
    public function updateRealName($name, $id){
        $sqlQuery = "UPDATE all_users SET realName = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $name);
        $statement->bindParam(2, $id);
        $statement->execute();
    }
    public function updateUsername($username, $id){
        $sqlQuery = "UPDATE all_users SET userName = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $username);
        $statement->bindParam(2, $id);
        $statement->execute();
    }
    public function updatePassword($password, $id){
        $sqlQuery = "UPDATE all_users SET password = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $password);
        $statement->bindParam(2, $id);
        $statement->execute();
    }
    public function updateEmail($email, $id){
        $sqlQuery = "UPDATE all_users SET emailAddress = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $id);
        $statement->execute();
    }
    public function updateBio($bio, $id){
        $sqlQuery = "UPDATE all_users SET bio = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $bio);
        $statement->bindParam(2, $id);
        $statement->execute();
    }

    public function updatePosition($latitude, $longitude, $id){
        $sqlQuery = "UPDATE all_users SET longitude = ?, latitude = ? WHERE user_id = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $longitude);
        $statement->bindParam(2, $latitude);
        $statement->bindParam(3, $id);
        $statement->execute();

    }



}