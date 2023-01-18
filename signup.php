<?php
require_once ('Models/UserDataset.php');
$view = new stdClass();
$view->pageTitle = 'SIGNUP';
//session_start();
$userDataset = new UserDataset();
if(isset($_POST['signupbtn'])){
    $emailAddress = htmlentities($_POST['email']);
   //var_dump($emailAddress);
    $realName = htmlentities($_POST['realname']);
    $userName = htmlentities($_POST['username']);
    $password = htmlentities($_POST['password']);
    $confirmEmail = htmlentities($_POST['confirmEmail']);
    $confirmPassword = htmlentities($_POST['confirmPassword']);
    $profileImage = 'images/blank.png';
    $file = $_FILES['file'];

    $fileName = htmlentities($file['name']);
    $fileTmpName = htmlentities($file['tmp_name']);
    $fileSize = htmlentities($file['size']);
    $fileError = htmlentities($file['error']);
    $fileType = htmlentities($file['type']);

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            if($fileSize < 1000000){
            $fileNameNew = uniqid('', true).".".$fileActualExt;
            $fileDestination = 'images/'.$fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
            //echo "upload success";
            }else{
                echo "File too big!";
            }

        }else{
            echo "Error uploading file!";
        }
    }else{
        echo "You cannot upload files of this type!";
    }

if($emailAddress == $confirmEmail){
    if($password == $confirmPassword){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $successfulSignup = $userDataset->signup($emailAddress, $realName, $userName, $password, $profileImage);
        if($successfulSignup){
            $user = $userDataset->fetchUserDetails($userName);

            //login the user after signing up
//        $_SESSION["id"] = $user->getUserID();
//        $_SESSION["userName"] = $user->getUserName();
//        $_SESSION["loggedin"] = true;
            $userDataset->loginUser($userName,$_POST['password']);
            $userid = $_SESSION['id'];
//            if($file == null){
              //  $userDataset->giveProfileImage($profileImage, $userid);
//            }else{
                $userDataset->uploadProfileImage($fileDestination, $userid);
//            }
           // var_dump($userDataset->loginUser());
          //  header("Location: home.php");
        }else{
//            $view->_usernameError = "username or Email taken";
            $view->_usernameError = 'Username or Email taken';
        }
    }else{
        $view->_pwdMismatch = 'Passwords do not match';
    }

} else{
    $view->_emailMismatch = 'Emails do not match';
}

}

if(isset($_POST['submit'])){
    $userid = htmlentities($_SESSION['id']);
    $file = htmlentities($_FILES['file']);
    $fileName = htmlentities($_FILES['file']['name']);
    $fileTmpName = htmlentities($_FILES['file']['tmp_name']);
    $fileSize = htmlentities($_FILES['file']['size']);
    $fileError = htmlentities($_FILES['file']['error']);
    $fileType = htmlentities($_FILES['file']['type']);

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            if($fileSize < 1000000){
            $fileNameNew = uniqid('', true).".".$fileActualExt;
            $fileDestination = 'images/'.$fileNameNew;
            $userDataset->uploadProfileImage($fileDestination, $userid);
            move_uploaded_file($fileTmpName, $fileDestination);
            //echo "upload success";
            }else{
                echo "File too big!";
            }

        }else{
            echo "Error uploading file!";
        }
    }else{
        echo "You cannot upload files of this type!";
    }

}
require_once('Views/signup.phtml');
