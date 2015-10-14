<?php

namespace view;

class NavigationView
{
    private static $registerView = "register";
    private static $loggedInAs = "";

    public function getLinkToStart(){
        return "<a href='?'>Back to login</a>";
    }

    public function getLinkToRegister(){
        return "<a href='?".self::$registerView ."'>Register a new user</a>";
    }

    public function atTheRegister(){
        return isset($_GET[self::$registerView]);
    }

    public function getLink(){
            if (!$this->atTheRegister()) {
                return $this->getLinkToRegister();
            }else {
                return $this->getLinkToStart();
            }
    }
}
