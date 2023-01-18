<?php
require_once ("Models/SearchDataset.php");
require_once ("Models/RequestDataset.php");

$view = new stdClass();
$view->pageTitle = "Notifications";
$requestDataset = new RequestDataset();
$friendDataset = new FriendDataset();
$view->searchDataset = null;
$view->searchPend = null;
$view->pendingDataset = null;
$view->requestDataset = null;
$searchDataset = new SearchDataset();
$userDataset = new UserDataset();

if(isset($_GET['user_id'])){
    $receiver = htmlentities($_GET['user_id']);
    $sender = htmlentities($_SESSION['id']);
    $requestDataset->sendRequest($sender, $receiver);
//    if($_GET['fromLiveSearch']==true){
//        return;
//    }
    header('Location:home.php');
}
if(isset($_GET['acc_user_id'])){
    $sender = htmlentities($_GET['acc_user_id']);
    $receiver = htmlentities($_SESSION['id']);
    $requestDataset->acceptRequest($sender, $receiver);
}
if(isset($_GET['del_user_id'])){
    $sender = htmlentities($_GET['del_user_id']);
    $receiver = htmlentities($_SESSION['id']);
    $requestDataset->deleteRequest($receiver, $sender);
}
if (isset($_SESSION['id'])){
    $userid = htmlentities($_SESSION['id']);
    $username = htmlentities($_SESSION['username']);
    $view->requestDataset = $requestDataset->showRequest($userid);
    $view->pendingDataset = $requestDataset->showPending($userid);
    $view->friend = $friendDataset->fetch_my_friends($_SESSION['id']);
    $view->user = $userDataset->fetchUserDetails($userid);

    if(isset($_GET['liveRequest'])){
        $view->requestDataset = $requestDataset->showRequest($userid);
//        $view->pendingDataset = $requestDataset->showPending($userid);
        echo json_encode($view->requestDataset);
        return;
    }
}
$view->request = count($view->requestDataset); //count all requests
$view->cfriend = count($view->friend);



if(isset($_POST["mini-search"])) {

    $searchTerm = htmlentities($_POST["search"]);
   // var_dump($searchTerm);
    $user_id = htmlentities($_SESSION['id']);
    $results = $searchDataset->searchRequest($user_id);
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
   // var_dump($view->searchDataset);
   // $view->searchDataset = $searchDataset->fetchSomeUsers($searchTerm);
}


if(isset($_POST["pend-search"])) {

    $searchTerm = htmlentities($_POST["search"]);
    // var_dump($searchTerm);
    $user_id = htmlentities($_SESSION['id']);
    $results = $searchDataset->searchPends($user_id);
    $view->searchDataset = [];
    foreach ($results as $data) {
        $username = $data->getUsername();
        $name = $data->getRealName();
        // var_dump($username);
        if (str_contains(strtolower($username), strtolower($searchTerm)) or str_contains(strtolower($name), strtolower($searchTerm))) {
            // return true;
            $view->searchPend[] = $data;
        }
    }
    // var_dump($view->searchDataset);
    // $view->searchDataset = $searchDataset->fetchSomeUsers($searchTerm);
}
//str_contains($username, $searchTerm)
    require_once ("Views/request.phtml");
