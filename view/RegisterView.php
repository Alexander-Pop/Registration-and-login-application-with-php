<?php

// '.$this->getTextField("Username", self::$username).' <br />

namespace view;


class RegisterView{

    private static $username = "RegisterView::UserName";
    private static $repeatpass = "RegisterView::PasswordRepeat";
    private static $password = "RegisterView::Password";
    private static $registerSubmit = "RegisterView::Register";
    private static $registerMessage = "RegisterView::Message";
    private static $ExistingUser = "User exists, pick another username.";

    private $message = "";

    public function getUser(){
        $username = $_POST[self::$username];
        $password = $_POST[self::$password];
        $reapetedPassword = $_POST[self::$repeatpass];

        try{
            return new \model\User($username, $password, $reapetedPassword);
        }catch(\model\NoUsernameException $e){
            $this->setMessage('Username has too few characters, at least 3 characters.');
        }catch(\model\NoPasswordException $e){
            $this->setMessage('Password has too few characters, at least 6 characters.');
        }catch(\model\NotSamePasswordException $e){
            $this->setMessage('Passwords do not match.');
        }catch(\model\EmptyInputException $e){
            $this->setMessage('Username has too few characters, at least 3 characters.'.'<br/>'.'Password has too few characters, at least 6 characters.');
        }catch(\model\ContainsHTMLtagException $e){
            $this->setMessage('Username contains invalid characters.');
        }catch(Exception $e){
            $this->setMessage($e->getMessage());
        }
        return null;
    }

    public function registerHTML($message){
        return '
        <h2>Register new user</h2>
            <form method="post" >
                <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                    <p id="'.self::$registerMessage.'">'.$message.'</p>
                    <label for="'.self::$username.'">Username :</label>
        			<input id="'.self::$username.'" type="text" value="'.self::getPostField(self::$username).'" name="'.self::$username.'" size="20" /> <br />

                    <label for="'.self::$password.'">Password :</label>
        			<input id="'.self::$password.'" type="password" value="" name="'.self::$password.'" size="20" /> <br />

                    <label for="'.self::$repeatpass.'">Repeat password :</label>
        			<input id="'.self::$repeatpass.'" type="password" value="" name="'.self::$repeatpass.'" size="20" /> <br />

                    <input type="submit" name="'.self::$registerSubmit.'" value="Register" />
                </fieldset>
                </form>
        ';
    }

    public function response(){
        return $this->registerHTML($this->getMessage());
    }

	private function getPostField($field) {
		if (isset($_POST[$field])) {
            $fieldString = strip_tags($_POST[$field]);
			return trim($fieldString);
		}
		return  "";
	}

    public function hasSubmittedRegister(){
        return isset($_POST[self::$registerSubmit]);
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function getMessage(){
        return $this->message;
    }

    public function setExistingUser(){
        $this->setMessage(self::$ExistingUser);
    }
}
