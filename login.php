<?php
/*
 * Checks if the user is logged in or performs a loggin at the moment
 *
 * returns true if a user is logged in and false if not
 */

/* @var $ldap LDAP */

if ($ldap->isBound()){
	$ldap->cd("cn=users,");
	$userID = array_read_key("userID", $_SESSION, false);
	$username = array_read_key("username", $_POST, "");
	$password = array_read_key("password", $_POST, "");
	$user = false;
	if ($userID){
		$user = $ldap->getUserById($userID);
	}
	else {
		if ($username){
			$searchName = preg_replace("/^.*\\\\/", "", $username);
			if (
				strpos($username, "@") === false &&
				strpos($username, "\\") === false
			){
				$username = $config->DOMAIN . "\\" . $username;
			}
			if ($ldap->bind($username, $password)){
				$search = $ldap->search(
					$ldap->resolvePath($ldap->userDN),
					"(&(memberof=" . $ldap->defaultGroup->dn . ")(|" .
						"(sAMAccountName=$searchName)" .
						"(mail=$searchName)" .
						"(cn=$searchName)" .
						"(uid=$searchName)" .
					"))",
					LDAP::SCOPE_ONE_LEVEL,
					array("cn", "uidNumber")
				);
				if ($search && ($user = $search->getFirstEntry())){
					$_SESSION["userID"] = $user->uidNumber[0];
					$user = $ldap->getUserByDN($user->dn);
				}
			}
		}
	}
	
//	if ($user && !$workgroup->isMember($user)){
//		$user = false;
//	}

	if (!$user){
		unset($_SESSION["userID"]);
		$temp->content .= '<h1>Login</h1>
			<form method="POST">' . createHiddenFields($_POST) . '
				<label>Username: <input name="username" value="' . htmlentities($username, ENT_QUOTES, "UTF-8") . '"></label><br>
				<label>Password: <input name="password" type="password"</label><br>
				<input type="submit" value="login">
			</form>';
		return false;
	}
	else {
		return true;
	}
}
else {
	$temp->content .= "No connection to login server.";
	return false;
}

?>
