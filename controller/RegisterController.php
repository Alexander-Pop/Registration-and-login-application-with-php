<?php

namespace controller;

require_once('view/RegisterView.php');
require_once('model/User.php');

class RegisterController{

    private $regView;
    private $dal;

    public function __construct(\model\memberDAL $DAL){
        $this->dal = $DAL;
        $this->regView = new \view\RegisterView();
    }

    public function handleRegister(){
        if ($this->regView->hasSubmittedRegister()) {
            $user = $this->regView->getUser();
            if ($user != null) {
                try {
                    $this->dal->addUser($user);
                    $link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
                    header("Location:$link");
                } catch (\Exception $e) {
                    $this->regView->setExistingUser();
                }

            }
        }
    }


    public function responseHTML(){
        return $this->regView->response();
    }
}
