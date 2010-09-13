<?php
	// Displays the user login box and sends it to the correct user function
	// for login and log out
	
	// Main entry point
	function loginDotPHP(){
		if(getURLVar("action") == "enter"){
			login();
			header('Location:' . fullURL(""));
			die();
		} else if (getURLVar("action") == "exit"){
			logout();
			header('Location:' . fullURL(""));
			die();
		} else if (check()){
			printHTML("you are already logged in silly!", "\n");
		} else {
			loginBox();
		}
	}
	
	// Generates the login box
	function loginBox() { ?>
		<div id="loginBox">
			<form name="login" id="login" action="<?php printHTML(fullURL(getLangVar("loginURL"), "enter")); ?>" method="POST"> 
				<div>
					<h1><?php printHTML(getLangVar("login")); ?></h1>
				</div> 
				<div class="userEditName fl">
					<label><?php printHTML(getLangVar("email")); ?></label>
				</div> 
				<div class="userEditField fr">
					<input name="email" id="email" type="text">
				</div> 
				<div class="userEditName fl">
					<label><?php printHTML(getLangVar("password")); ?></label>
				</div> 
				<div class="userEditField fr">
					<input name="password" id="password" type="password">
				</div> 
				<div class="userEditName fl">
				</div> 
				<div class="userEditField fr">
					<input name="login" id="login" type="submit" value="<?php printHTML(getLangVar("login")); ?>">
				</div>
				<div class="userEditName fl">
				</div> 
				<div class="userEditField fr">
					<a href="<? printHTML(fullURL(getLangVar("userURL"), "create")); ?>">Register</a>
				</div> 
			</form>
		</div>
<?php
	}
?>