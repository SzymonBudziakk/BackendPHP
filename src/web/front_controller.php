<?php
require '../../vendor/autoload.php';
require_once '../dispatcher.php';
require_once '../routing.php';
require_once '../controllers.php';

session_start();
if(!isset($_SESSION['images'])) {
    $_SESSION['images'] = [];
}
get_messages();

$action_url = $_GET['action'];
dispatch($routing, $action_url);

