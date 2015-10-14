<?php
//  \
namespace model;
class LoginException extends \Exception {};

class Login {

    private static $Username = "Admin";
    private static $Password = "Password";
    private static $Authorized = 'Authorized';
    private $dal;

    public function __construct(\model\memberDAL $db){
        $this->dal = $db;
        if (isset($_SESSION[self::$Authorized]) && !empty($_SESSION[self::$Authorized])) {
            $_SESSION[self::$Authorized] = true;
        }
    }

    //Destroys the session when called on
    public function destroySession(){
        $_SESSION[self::$Authorized] = false;
        session_destroy();
    }

    //Returns a bool if the session is active or not
    public function getSession(){
        if (isset($_SESSION[self::$Authorized])) {
            if ($_SESSION[self::$Authorized] == true) {
                return true;
            }
        }
        return false;
    }

    //Comparing the field input with the right Username/password
    public function tryLogin($name, $pass){
        $name = self::reformInputs($name);
        $pass = self::reformInputs($pass);
        //$pass = self::getHash($pass);

        if (empty($name)) {
            throw new LoginException('Username is missing');
        }
        else if(empty($pass)) {
            throw new LoginException('Password is missing');
        }
        else if($this->dal->controlUser($name, $pass)) {
            if (isset($_SESSION[self::$Authorized]) && $_SESSION[self::$Authorized] == true) {
                throw new LoginException('');
            }
            $_SESSION[self::$Authorized] = true;
            return true;
        }
        throw new LoginException('Wrong name or password');
    }

    private function reformInputs($input){
        $value = trim($input);
        $value = htmlentities($value);
        return $value;
    }

    private function getHash($pass){
        return sha1(\Settings::SALT . $pass);
    }

    public function destroyNewUserSession(){
        // Detta medför ett sträng beroende mellan memberDAL och loginView,
        // Men kunde inte komma på ett smidigare sätt att skicka med meddelandet och namnet.
        // Login.php rad 68.
        // LoginView.php rad 83 & 193
        // memberDAL.php rad 29
        $_SESSION["succesfull"] = "";
        $_SESSION["newUser"] = "";
        session_unset();
    }
}
