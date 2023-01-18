<?php
require_once ("Models/UserDataset.php");
require_once ("Models/FriendDataset.php");
require_once ("Models/RequestDataset.php");
require_once ("Models/ImagePostDataset.php");
require_once ("Models/SearchDataset.php");
require_once ("Models/LikesDataset.php");

$view = new stdClass();
$for = new stdClass();
$view->pageTitle = "Home";
$requestDataset = new RequestDataset();
$friendDataset = new FriendDataset();
$imagePostDataset = new ImagePostDataset();
$likesDataset = new LikesDataset();
//session_start();
$view->user = null;
$userDataset = new UserDataset();
$view->searchDataset = null;
$searchDataset = new SearchDataset();
$view->search = null;
$view->requestDataset = null;
$view->friend = null;
//$for->user = null;

if (isset($_SESSION['id'])){
    $userid = htmlentities($_SESSION['id']);
    $userName = htmlentities($_SESSION['username']);
    $for->user = $userDataset->fetchAllUserDetails();
    $view->user = $userDataset->fetchUserDetails($userid);
    $view->requestDataset = $requestDataset->showRequest($userid);
    $view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
    $view->Pics = $imagePostDataset->fetchAllPicsAndUsers();
    //$view->photos = array_merge($view->Pics, $view->user);
    $view->thePics = array_reverse($view->Pics, false);
    $view->totalLikes = $likesDataset->totalLikes($_SESSION['id']);
    $view->totLikes = count($view->totalLikes);
    $view->request = count($view->requestDataset);
    $view->cfriend = count($view->friend);
}
if(isset($_GET['like'])){
    $likerid = htmlentities($_SESSION['id']);
    $imgLiked = htmlentities($_GET['imgpath']);
    $imgLikedUserid = htmlentities($_GET['id']);
    $likesDataset->like($imgLikedUserid,$imgLiked,$likerid);
}
if(isset($_GET['likeText'])){
    $likerid = htmlentities($_SESSION['id']);
    $text = htmlentities($_GET['text']);
    $id = htmlentities($_GET['id']);
    $likesDataset->likeText($text,$id,$likerid);
}
if(isset($_GET['comment'])){
    $text = htmlentities($_GET['text']);
    $id = htmlentities($_GET['id']);
    $image = htmlentities($_GET['imgpath']);
    //var_dump($text);
//    $path = $_POST['imgpath'];
    $imagePostDataset->comment($id, $text, $_SESSION['id'], $image);
    header('Location: home.php');
}
require_once ("Views/home.phtml");

