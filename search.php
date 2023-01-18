<?php
require_once ("Models/SearchDataset.php");
require_once ("Models/UserDataset.php");

$view = new stdClass();
$view->pageTitle = "Search";
//session_start();
$view->userDataset = null;
$userDataset = new UserDataset();
$view->searchDataset = null;
$searchDataset = new SearchDataset();
//$view->search = $userDataset->fetchUserDetails();

if (isset($_POST["searchBtn"])) {
    $view->search = 0;
    $searchTerm = htmlentities($_POST["search"]);
    $filter = htmlentities($_POST["filter"]);
    //var_dump($filter);
    $view->user = $userDataset->fetchAllUserDetails();//returns all members.
    if($filter == null){
        $view->searchDataset = $searchDataset->fetchSomeUsers($searchTerm);//finds member based of users' search parameter.
        $view->search = count($view->searchDataset);
    }
    if($filter == "all"){
        $view->searchDataset = $searchDataset->fetchSomeUsers($searchTerm);//finds member based of users' search parameter.
        $view->search = count($view->searchDataset);
    }
    if($filter == "name"){
        $view->searchDataset = $searchDataset->fetchNames($searchTerm);//finds member based of users' search parameter.
        $view->search = count($view->searchDataset);
    }
    if($filter == "username"){
        $view->searchDataset = $searchDataset->fetchUserNames($searchTerm);//finds member based of users' search parameter.
        $view->search = count($view->searchDataset);
    }
//    else{
//      //  $view->searchDataset = $searchDataset->fetchSomeUsers($searchTerm);//finds member based of users' search parameter.
//        $view->search = count($view->searchDataset);
//    }
}


//if (isset($_POST['searchBtn'])){
//    $searchTerm = $_POST['search'];
//    $view->searchDataset = $searchDataset->fetchSomeUsers($searchTerm);
//    $user = $userDataset->fetchUserDetails($_POST[$searchTerm]);
//}
//else{
//    $view->search = $userDataset->fetchUserDetails();
//}

//live search
if(isset($_GET['liveSearch'])){
    $term = $_GET['liveSearch'];
    $result = $searchDataset->fetchSomeUsers($term);
    echo json_encode($result);
//echo "work please";
    return;
}
require_once ("Views/search.phtml");