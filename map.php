<?php
require_once("Models/FriendDataset.php");
require_once("Models/UserDataset.php");

$view = new stdClass();
$view->pageTitle = "Map";
//session_start();

$friendDataset = new FriendDataset();

$userDataset = new UserDataset();
if (isset($_SESSION['id'])) {

    if ($_GET['option'] == "getLatLong") {
       $view->friends = $friendDataset->fetch_my_friends($_SESSION['id']);//fetch the friend using the function and pass value into variable
        echo json_encode($view->friends);

    } elseif ($_GET['option'] == "sendLatLong") {
        $latitude = $_GET['latitude'];
        $longitude = $_GET['longitude'];
        $id = $_SESSION['id'];

        $userDataset->updatePosition($latitude, $longitude, $id);
        echo "This are the coordinates : $longitude $latitude";

    }


}