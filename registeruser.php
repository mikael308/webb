<?php
	/**
	 * page used for register new users
	 * this page has permitted access to non-authorized users
	 * if user is already authorized, user is redirected
	 *
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	require_once "Page.php";
	require_once "./config/pageref.php";
	require_once "./database/database.php";
	require_once "./sections/elements.php";
	require_once "./sections/main.php";
	require_once "./sections/views.php";
	require_once "./session/authorization.php";
	require_once "./session/main.php";

	autoloadDAO();
	startSession();

	if(loginListener()){
		header("Location: " . $GLOBALS["pagelink"]["index"]);
		exit();
	}

	if(userIsAuthorized())
	{
		header("Location: ". $GLOBALS["pagelink"]["index"]);
		exit();
	}

	$_SESSION["registeruser_errmsg"] = "";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		# listen for user register post
		if(isset($_POST["user_register"])){
			register();

		}
	}


	$page = new Page();

	# HEAD
	##########################
	$page->setHead(
		getScript("validpasswordhint.js")
		. setTitle("register")
		. getStylesheet("register_user.css")
	);
	# HEADER
	##########################
	$page->setHeader(

	);
	# MAIN
	##########################
	$mainContent = "";
	if(isset($_SESSION["registeruser_errmsg"])){
		$mainContent .= "<p id='registeruser_errmsg'>" . $_SESSION["registeruser_errmsg"] . "</p>";
	}
	# register article
	$mainContent .=
		"<article id='register'>"
			. getRegisterUserForm()
			. getValidPasswordHint()
		. "</article>";

	# login article if already member
	$mainContent .=
		"<article id='login'>"
			. "<p id='login_h'>already a member?</p>"
			. getLoginForm()
		."</article>";
	$page->setMain(
		$mainContent
	);
	# FOOTER
	##########################
	$page->setFooter(

	);

	echo $page->toHtml();




	#####################################
	# page functions
	#####################################

	/**
	 * get form used as to register user\n
	 * use user_register to access post attributes
	 */
	function getRegisterUserForm(){
		#preset values
		$pre_name = isset($_SESSION["input_name"]) ?
		 $_SESSION["input_name"] : "" ;
		$pre_email = isset($_SESSION["input_email"]) ?
		 $_SESSION["input_email"] : "";

		return
		"<div id='user_reg_table'>"
		 . "<form method='POST' action='". htmlspecialchars($_SERVER['PHP_SELF']) ."'>"
		 . "<table>"
				. "<th>user registration</th>"
				. tr(
						td("<label for='user_name'>name</label>")
					.	td("<input type='text' id='user_name' value='". $pre_name ."'name='user_name' autofocus required>"))
				. tr(
						td("<label for='user_email'>email</label>")
					.	td("<input type='text' id='user_email' value='". $pre_email ."' name='user_email' required>"))
				. tr(
						td("<label for='user_password'>password</label>")
					.	td("<input type='password' id='user_password' name='user_password' onkeyup='validatePasswordHint(this.value)' required>"))
				. tr(
						td("<label for='user_password_confirm'>confirm password</label>")
					.	td("<input type='password' id='user_password_confirm' name='user_password_confirm' required>"))
				. tr(
						td("")
					.	td("<input type='submit' id='user_register' name='user_register' value='register'>"))
		. "</table>"
		. "</form></div>";

	}

	/**
	 * get valid passwordhint div
	 */
	function getValidPasswordHint(){
		return
			"<div id='valid_password_hint'>"
			. "<fieldset>"
			. "<legend>valid password</legend>"
				. "<ul>"
					. "<li class='valid_password_hint_rule' id='password_length'>at least 6 characters</li>"
					. "<li class='valid_password_hint_rule' id='password_content'>contain digits, upper and lowercase letters and special character (!._-)</li>"
				. "</ul>"
			. "</fieldset>"
			. "</div>";

	}

	/**
	* determine if password is valid
	* @param password the password to validate
	* @return True if password is valid
	*/
	function validPassword($password){
		if(validLength($password)
			&& validContent($password)){
			return True;
		}
		return False;
	}
	/**
	* determine if password is of correct length\n
	* password need to be above 6 characters
	* @param password the password to validate
	* @return True if password is valid length
	*/
	function validLength($password){
		if (strlen($password) >= 6){
			return True;
		}
		return False;
	}
	/**
	* validates the content of a password
	* @param password the password to validate
	* @return True if password is valid  of content
	*/
	function validContent($password){
		if (preg_match("/[a-z]/", $password) == 0
				|| preg_match("/[A-Z]/", $password) == 0
				|| preg_match("/[0-9]/", $password) == 0
				|| preg_match("/[\\.!_\\-]/", $password) == 0){
			return False;
		}
		return True;
	}

	/**
	 * if post user is valid send persistrequest to database
	 */
	function register(){
			$name 			= clean_input($_POST["user_name"]);
			$email 			= clean_input($_POST["user_email"]);
			$passw 			= clean_input($_POST["user_password"]);
			$passw_conf = clean_input($_POST["user_password_confirm"]);

			if(! validUser($name, $email, $passw, $passw_conf)){
				# set form input fields to previous value
				$_SESSION["input_name"] = $name;
				$_SESSION["input_email"] = $email;
				return;
			}

			$user = new ForumUser();
			$user->setName($name);
			$user->setEmail($email);
			$user->setRole(2); # set to user role
			$user->setBanned(False);
			$user->setRegistered(date($GLOBALS["timestamp_format"]));

			if(exists($user)){
				$_SESSION["registeruser_errmsg"] = "email already registered";
				return;
			}

			if(persist::forumUser($user, $passw)){
				# user is successfully registered in database
				$user = read::forumUser($user->getPrimaryKey());
				$_SESSION["authorized_user"] = $user;
				
				header("Location: " 
					. pagelinkUser($user->getPrimaryKey()));
			}
	}
	/**
	 * determine if user fields is valid
	 * @return True if valid
	 */
	function validUser($name, $email, $passw, $passw_conf){

		# validate input fields
		##########################

		if (str_replace(" ", "", $name) == ""){
			$_SESSION["registeruser_errmsg"]
				= "name cannot be empty";
			return False;

		} elseif (str_replace(" ", "", $email) == ""){
			$_SESSION["registeruser_errmsg"]
				= "email cannot be empty";
			return False;

		} elseif (str_replace(" ", "", $passw) == ""
		|| str_replace(" ", "", $passw_conf) == ""){
		 	$_SESSION["registeruser_errmsg"]
		 		= "password cannot be empty";
			return False;

		} elseif (str_replace(" ", "", $passw_conf) == ""){
			$_SESSION["registeruser_errmsg"]
				= "password confirmation cannot be empty";
			return False;
		}

		# validate password
		#####################

		if (! validPassword($passw)){
			$_SESSION["registeruser_errmsg"]
				= "password not valid";
			 return False;
		}
		if ($passw != $passw_conf){
			 $_SESSION["registeruser_errmsg"]
			 	= "passwords not equal";
			 return False;
		}

		return True;
	}

	/**
	 * cleans string\n
	 * meaning translate htmlspecial chars, slashes and initial or ending whitespaces
	 * @return the clean string
	 */
	function clean_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}


?>
