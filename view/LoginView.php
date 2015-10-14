<?php

namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $welcome = 'Welcome';
	private static $byeMessage = 'Bye bye!';
	private static $cookieMessage = 'Welcome back with cookie';
	//private static $salt = 'termos1337';
	private $errorMessage = '';

	//Get the login model to read from it.
	private $loginModel;

	public function __construct(\model\Login $login){
			$this->loginModel = $login;
	}


	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = $this->getMessage();
		$response = $this->getHTMLForm($message);
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form method="post">
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . self::getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		// Detta medför ett sträng beroende mellan memberDAL och loginView,
		// Men kunde inte komma på ett smidigare sätt att skicka med meddelandet och namnet.
		// Login.php rad 68.
		// LoginView.php rad 83 & 193
		// memberDAL.php rad 29
		if(isset($_SESSION["newUser"])){
			$string = $_SESSION["newUser"];
			$this->loginModel->destroyNewUserSession();
			return $string;
		}else if(isset($_POST[self::$name])){
			return $_POST[self::$name];
		}
	}


	//Checking if cookies are set or not
	public function checkCookie(){
		if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
			return true;
		}
			return false;
	}

	//Creates cookies depending on if "keep me logged in" is checked
	public function setCookies(){
		if (isset($_POST[self::$keep])) {
				// set cookies for a day
				setcookie('username', $_POST[self::$name], time()+86400);
				setcookie('password', md5($_COOKIE['username'] . self::$salt), time()+86400);
				return "Welcome and you will be remembered";
		}
	}

	//Tries to login with the cookies, works but wont change the message right..
	public function loginWithCookie(){

			if (isset($_COOKIE['password'])) {
				if ($this->hasPostedLogin() && $this->getMessage() !== self::$cookieMessage) {
					$this->setMessage('');
					$this->response();
				}else {
				echo $_COOKIE['password'];
					$this->setMessage(self::$cookieMessage);
					$this->response();
				}
			}
		}

	//Removes existing cookies
	public function removeCookies(){
		if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
			setcookie('username', '', time() - 3600);
			setcookie('password', '', time() - 3600);
			unset($_COOKIE['username']);
			unset($_COOKIE['password']);
		}
	}

	//Handle requests with the keep me logged in button
	public function wantToBeKept(){
		if (isset($_POST[self::$keep])) {
			return true;
		}
	}

	//Handle requests with the log in button
	public function hasPostedLogin(){
		if (isset($_POST[self::$login])) {
			return true;
		}
		return false;
	}

	//Handle requests with the logut button
	public function hasPostedLogout(){
		if (isset($_POST[self::$logout])) {
			return true;
		}
		return false;
	}

	//Returns the input value from username field in the form
	public function getUsernameInput(){
		if (isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
	}

	//Returns the value from password field in the form
	public function getUserpassInput(){
		if (isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
	}

	//Sets the message wich should be displayed
	public function setMessage($message){
		$this->errorMessage = $message;
	}

	//Sets the welcome message
	public function setWelcomeMessage(){
		$this->setMessage(self::$welcome);
	}

	//Sets the logut message
	public function setLogoutMessage(){
		$this->setMessage(self::$byeMessage);
	}

	//Returns the string of the message
	public function getMessage(){
		if (isset($_SESSION["succesfull"])) {
			// Detta medför ett sträng beroende mellan memberDAL och loginView,
			// Men kunde inte komma på ett smidigare sätt att skicka med meddelandet och namnet.
			// Login.php rad 68.
	        // LoginView.php rad 83 & 193
	        // memberDAL.php rad 29
			return $_SESSION["succesfull"];
		}
		return $this->errorMessage;
	}

	//Returns a string with the html form, either login or logut
	public function getHTMLForm($message){
		if ($this->checkCookie()) {
			return $this->generateLogoutButtonHTML($message);
		}
		else if ($this->loginModel->getSession()) {
			return $this->generateLogoutButtonHTML($message);
		}
		return $this->generateLoginFormHTML($message);
	}
}
