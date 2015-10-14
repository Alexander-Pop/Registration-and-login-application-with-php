<?php

namespace model;

class MemberDAL{
        private static $user = "user";
        private $userlist = array();
        private $dal;

        public function __construct(\mysqli $db){
            $this->dal = $db;
        }

        public function addUser(\model\User $user){
            if (!$this->doExist($user)) {
                $sqlQuery = $this->dal->prepare("INSERT INTO Users(Username, Password)VALUES (?,?)");
                if ($sqlQuery == false) {
                    throw new \Exception($this->dal->error);
                }

                $username = $user->getUsername();
                $password = $user->getPassword();

                $sqlQuery->bind_param('ss', $username, $password);
                $sqlQuery->execute();

                // Detta medför ett sträng beroende mellan memberDAL och loginView,
                // Men kunde inte komma på ett smidigare sätt att skicka med meddelandet och namnet.
                // Login.php rad 68.
                // LoginView.php rad 83 & 193
                // memberDAL.php rad 29
                $_SESSION["succesfull"] = 'Registered new user.';
                $_SESSION["newUser"] = $username;
            }else{
                throw new \Exception('User exists, pick another username.');
            }
        }

        public function doExist(\model\User $user){
            $sqlQuery = $this->dal->prepare("SELECT EXISTS(SELECT 1 FROM Users WHERE Username=?)");
            if ($sqlQuery == false) {
                throw new \Exception($this->dal->error);
            }

            $username = $user->getUsername();

            $sqlQuery->bind_param('s', $username);
            $sqlQuery->execute();

            $sqlQuery->bind_result($result);
            $sqlQuery->fetch();

            if ($result == 1) {
                return true;
            }
            return false;
        }

        public function controlUser($username, $password){
            $password = self::getHash($password);

            $sqlQuery = $this->dal->prepare("SELECT Username FROM Users WHERE BINARY Username = ?  AND Password=? ");
            if ($sqlQuery == false) {
                throw new \Exception($this->dal->error);
            }

            $sqlQuery->bind_param("ss", $username, $password);
            $sqlQuery->execute();

            $sqlQuery->bind_result($result);
            $sqlQuery->store_result();
            $sqlQuery->fetch();

            if ($result != NULL) {
                return true;
            }else {
                return false;
            }
        }

        private function getHash($pass){
            return sha1(\Settings::SALT . $pass);
        }
}
