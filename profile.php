<?php
require_once ("Models/UserDataset.php");
require_once ("Models/FriendDataset.php");
require_once ("Models/RequestDataset.php");
require_once ("Models/SearchDataset.php");
require_once ("Models/ImagePostDataset.php");
require_once ("Models/LikesDataset.php");
$view = new stdClass();
$view->pageTitle = "Profile Page";
$userDataset = new UserDataset();
$requestDataset = new RequestDataset();
$friendDataset = new FriendDataset();
$imagePostDataset = new ImagePostDataset();
$likesDataset = new LikesDataset();
$view->request = null;
$view->cfriend = null;
//$userid = $_SESSION['id'];
if(isset($_GET['id'])){ //gets candidate details
    $userid = htmlentities($_SESSION['id']);
    $thisuser = htmlentities($_SESSION['id']);
    $otherUserid = htmlentities($_GET['id']);
    $view->otherUsers = $userDataset->fetchUserDetails($otherUserid); //fetch candidate details of particular id
    $view->user = $userDataset->fetchUserDetails($userid);
    $view->othersPicUploads = $imagePostDataset->fetchUserPicUploads($otherUserid);
//    var_dump($view->user);
//    echo $view->user->getRealName();
    $view->requestDataset = $requestDataset->showRequest($_SESSION['id']);
   $view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
    $view->request = count($view->requestDataset);
    $view->cfriend = count($view->friend);
    $view->totalLikes = $likesDataset->totalLikes($otherUserid);
    $view->totLikes = count($view->totalLikes);
    //$view->result = $likesDataset->usersThatLiked($_SESSION['id']);
    //$view->likers = array_unique($view->result);
    //var_dump($view->likers);
}
//$otherUserid = htmlentities($_GET['id']);
//$view->otherUsers = $userDataset->fetchUserDetails($otherUserid);
//$view->user = $userDataset->fetchUserDetails($userid);
//$view->requestDataset = $requestDataset->showRequest($_SESSION['id']);
//$view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
    if(isset($_GET['like'])){
                $likerid = htmlentities($_SESSION['id']);
        //var_dump($likerid);
        $imgLiked = htmlentities($_GET['imgpath']);
        //var_dump($imgLiked);
        $imgLikedUserid = htmlentities($_GET['id']);
        //var_dump($imgLikedUserid);
        $likesDataset->like($imgLikedUserid,$imgLiked,$likerid);
        $userid = htmlentities($_SESSION['id']);
        $thisuser = htmlentities($_SESSION['id']);
        $otherUserid = htmlentities($_GET['id']);
        $view->otherUsers = $userDataset->fetchUserDetails($otherUserid); //fetch candidate details of particular id
        $view->user = $userDataset->fetchUserDetails($userid);
        $view->othersPicUploads = $imagePostDataset->fetchUserPicUploads($otherUserid);
//    var_dump($view->user);
//    echo $view->user->getRealName();
        $view->requestDataset = $requestDataset->showRequest($_SESSION['id']);
        $view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
        $view->request = count($view->requestDataset);
        $view->cfriend = count($view->friend);
        $view->totalLikes = $likesDataset->totalLikes($imgLikedUserid);
        //var_dump($view->totalLikes);
      $view->totLikes = count($view->totalLikes);
    }
require_once ("Views/profile.phtml");