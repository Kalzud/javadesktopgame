<?php
require_once("Models/FriendDataset.php");
require_once("Models/UserDataset.php");
require_once("Models/RequestDataset.php");
require_once ("Models/SearchDataset.php");
$view = new stdClass();
$view->pageTitle = "Friends";
//session_start();
$view->searchDataset = null;
$friendDataset = new FriendDataset();
$requestDataset = new RequestDataset();
$searchDataset = new SearchDataset();
$userDataset = new UserDataset();
if (isset($_SESSION['id'])){
    $userid = htmlentities($_SESSION['id']);
    $userName = htmlentities($_SESSION['username']);
    $view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
    $view->requestDataset = $requestDataset->showRequest($userid);
    $view->user = $userDataset->fetchUserDetails($userid);

    //get the friends details and store in an array
    if(isset($_GET['liveMap'])){
        //show list of friends
        $view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);


        $friendList = [];
        foreach($view->friend as $friend){
            $array = [];
//            $array[] = $friend -> getProfileImage();//get friends profile image
            $array[] = $friend -> getUsername();//get friends username
            $array[] = $friend -> getLatitude();//get friends latitude to be able to view friend's location on the map
            $array[] = $friend -> getLongitude();//get friends longitude to be able to view friend's location on the map
            $array[] = $friend -> getProfileImage();//get friends profile image
            $friendList[] = $array;
        }

        echo json_encode($friendList);
        return;
    }
}


$view->cfriend = count($view->friend);
$view->request = count($view->requestDataset);


if(isset($_GET['block_id'])){
    $friend = htmlentities($_GET['block_id']);
   $receiver = htmlentities($_SESSION['id']);
    $friendDataset->block($friend, $receiver);
    header('location: friend.php');
}


if(isset($_POST["mini-search"])) {

    $searchTerm = htmlentities($_POST["search"]);
    // var_dump($searchTerm);
    $user_id = htmlentities($_SESSION['id']);
    $results = $searchDataset->searchFriend($user_id);
    $view->searchDataset = [];
    foreach ($results as $data) {
        $username = $data->getUsername();
        $name = $data->getRealName();
        // var_dump($username);
        if (str_contains(strtolower($username), strtolower($searchTerm)) or str_contains(strtolower($name), strtolower($searchTerm))) {
            // return true;
            $view->searchDataset[] = $data;
        }
    }

}

require_once("Views/friend.phtml");