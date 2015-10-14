<?php

namespace model;

class NoUsernameException extends \Exception {};
class NoPasswordException extends \Exception {};
class NotSamePasswordException extends \Exception {};
class EmptyInputException extends \Exception {};
class ContainsHTMLtagException extends \Exception {};

class User{
    private $username;
    private $password;

    public function __construct($username, $password, $repeatedPass){
        if (empty($username) && empty($username) && empty($username)) {
            throw new EmptyInputException();
        }
        if (is_string($username) == false || strlen($username) < 3){
			throw new NoUsernameException();
        }
		if (is_string($password) == false || strlen($password) < 6){
			throw new NoPasswordException();
        }
        if (is_string($repeatedPass) == false || strlen($repeatedPass) < 6 || $repeatedPass != $password){
			throw new NotSamePasswordException();
        }
        if (strlen($username) != strlen(strip_tags($username))) {
            throw new ContainsHTMLtagException(); // Either this if statement or you can use  regex..
        }

        $this->username = $username;
        $this->password = self::hash($password);
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    private function hash($pass){
        return sha1(\Settings::SALT . $pass);
    }
}
