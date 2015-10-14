<?php
//  \
namespace controller;
require_once('view/LoginView.php');

class Application{

    private $model;
    private $view;

    public function __construct(\model\Login $loginModel, \model\memberDAL $db){
        $this->dal = $db;
        $this->model = $loginModel;
        $this->view = new \view\LoginView($this->model);
    }

    //Runs the whole application
    public function appRun(){

    /*  //Kod för att försöka logga in med cookies, vill inte fungera riktigt!
      if ($this->view->checkCookie()) {
        try {
            $this->view->loginWithCookie();
        } catch (Exception $e) {
            $this->view->setMessage($e->getMessage());
        }
      }*/


      if ($this->view->hasPostedLogout() && $this->getSession()) {
          $this->view->setLogoutMessage();
          $this->view->response();
          $this->view->removeCookies();
          $this->destroySession();
      }

      if ($this->view->hasPostedLogin()) {
            try {
              if ($this->model->tryLogin($this->view->getUsernameInput(), $this->view->getUserpassInput())) {
                /*  //Skall hantera "keep me logged in" och skapa cookies men får de inte att fungera helt och hållet!
                  if ($this->view->wantToBeKept()) {
                    $this->view->setMessage($this->view->setCookies());
                    $this->view->response();
                  }*/
                    $this->view->setWelcomeMessage();
                    $this->view->response();
              }
            } catch (\model\LoginException $e) {
                $this->view->setMessage($e->getMessage());
            }
        }
      }

    //Calling the destroySession in the login model.
    public function destroySession(){
      return $this->model->destroySession();
    }

    //Calling the getSession in the login model.
    public function getSession(){
      return $this->model->getSession();
    }

    public function responseHTML(){
        return $this->view->response();
    }

}
