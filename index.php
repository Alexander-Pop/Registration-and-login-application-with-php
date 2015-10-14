<?php

session_start();
//INCLUDE THE FILES NEEDED...
require_once('Settings.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/Login.php');
require_once('controller/MasterController.php');


//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE CONTROLLER
$masterController = new \controller\MasterController();
$masterController->handleInput();

//Creates the date/time atm, and the LayoutView to render out html.
$dtv = new DateTimeView();
$lv = new LayoutView();

$lv->render($masterController->getSession(), $masterController->getHTML(), $dtv, $masterController->getLink());
