<?php
namespace controller;

require_once('view/NavigationView.php');
require_once('controller/Application.php');
require_once('controller/RegisterController.php');
require_once('model/memberDAL.php');

class MasterController{

    private $navigationView;
    private $appController;
    private $registerControll;
    private $view;
    private $dal;
    private $mysql;

    public function __construct(){
        $this->mysqli = new \mysqli(\Settings::SERVER, \Settings::USERNAME,\Settings::PASSWORD,\Settings::DATABASE);
        if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}

        $this->navigationView = new \view\NavigationView();
        $this->dal = new \model\memberDAL($this->mysqli);
        $this->loginModel = new \model\Login($this->dal);
    }

    public function handleInput(){
        if (!$this->navigationView->atTheRegister()) {
            //Controller for login
            $this->appController = new \controller\Application($this->loginModel, $this->dal);
            $this->appController->appRun();

            $this->view = $this->appController->responseHTML();
        }else {
            //Controller for registration
            $this->registerControll = new \controller\RegisterController($this->dal);
            $this->registerControll->handleRegister();

            $this->view = $this->registerControll->responseHTML();
        }
    }

    public function getLink(){
        if ($this->loginModel->getSession()) {
            return null;
        }
        return $this->navigationView->getLink();
    }
    public function getHTML(){
        return $this->view;
    }

    public function getSession(){
        return $this->loginModel->getSession();
    }


}
