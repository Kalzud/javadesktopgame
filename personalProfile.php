<?php
require_once ("Models/UserDataset.php");
require_once ("Models/FriendDataset.php");
require_once ("Models/RequestDataset.php");
require_once ("Models/SearchDataset.php");
require_once ("Models/ImagePostDataset.php");
require_once ("Models/LikesDataset.php");
$view = new stdClass();
$view->pageTitle = "Profile";
$userDataset = new UserDataset();
$requestDataset = new RequestDataset();
$friendDataset = new FriendDataset();
$imagePostDataset = new ImagePostDataset();
$likesDataset = new LikesDataset();
$userid = $_SESSION['id'];
$view->user = $userDataset->fetchUserDetails($userid); //fetch candidate details of particular id
$view->yourPics = $imagePostDataset->fetchUserPicUploads($userid);
$view->requestDataset = $requestDataset->showRequest($userid);
$view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
$view->totalLikes = $likesDataset->totalLikes($_SESSION['id']);
$view->totLikes = count($view->totalLikes);
$view->request = count($view->requestDataset);
$view->cfriend = count($view->friend);


if(isset($_POST['editName'])){
$name = htmlentities($_POST['name']);
$userDataset->updateRealName($name, $userid);
header('Location: personalProfile.php');
}
if(isset($_POST['editEmail'])){
$email = htmlentities($_POST['email']);
$userDataset->updateEmail($email, $userid);
header('Location: personalProfile.php');
}
if(isset($_POST['editBio'])){
    $bio = htmlentities($_POST['bio']);
    //var_dump($bio);
    $userDataset->updateBio($bio, $userid);
    header('Location: personalProfile.php');
}
if(isset($_POST['editUsername'])){
$username = htmlentities($_POST['username']);
$userDataset->updateUsername($username, $userid);
$_SESSION = ['id' => $userid, 'username' => $username];
header('Location: personalProfile.php');
}
if(isset($_POST['editProfilePic'])){
    $file = htmlentities($_FILES['profilepic']);
    $fileName =  htmlentities($file['name']);
    $fileTmpName =  htmlentities($file['tmp_name']);
    $fileSize =  htmlentities($file['size']);
    $fileError =  htmlentities($file['error']);
    $fileType =  htmlentities($file['type']);

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            if($fileSize < 100000){
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'images/'.$fileNameNew;
                $userDataset->uploadProfileImage($fileDestination, $userid);
                move_uploaded_file($fileTmpName, $fileDestination);
                header('Location: personalProfile.php');
            }else{
                $view->_sizeError = "File too big!";
            }
        }else{
            $view->_error = "Error uploading file!";
        }
    }else{
        $view->_typeError = "You cannot upload files of this type!";
    }
}
if(isset($_POST['post'])){
    $tag = htmlentities($_POST['tag']);
    $file = $_FILES['post'];
    $fileName =  $file['name'];
    $fileTmpName =  $file['tmp_name'];
    $fileSize =  $file['size'];
    $fileError =  $file['error'];
    $fileType =  $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            if($fileSize < 300000){
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'images/'.$fileNameNew;
                $imagePostDataset->post($fileDestination, $userid, $tag);
                move_uploaded_file($fileTmpName, $fileDestination);
                header('Location: personalProfile.php');
            }else{
                $view->_sizeError = "File too big!";
            }
        }else{
            $view->_error = "Error uploading file!";
        }
    }else{
        $view->_typeError = "You cannot upload files of this type!";
    }
}
if(isset($_POST['textPost'])){
    $text = htmlentities(addslashes($_POST['text']));
    //var_dump($text);
//    $path = $_POST['imgpath'];
    $imagePostDataset->postText($userid, $text);
    header('Location: personalProfile.php');
}

if(isset($_GET['deletePost'])){
    $imgpath = htmlentities($_GET['imgpath']);
    //var_dump($imgpath);
    $imagePostDataset->deletePost($userid, $imgpath);
    header('Location: personalProfile.php');
}
//if(isset($_GET['deleteTextPost'])){
//    $text = ''.$_GET['text'].'';
//   // var_dump($text);
//    $imagePostDataset->deleteTextPost($userid, $text);
//    header('Location: personalProfile.php');
//
//}
require_once ("Views/personalProfile.phtml");