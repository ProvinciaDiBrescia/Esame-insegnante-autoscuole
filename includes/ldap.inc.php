<?php
	global $adldap;
	
	//include the class
	require_once("adLDAP.php");
	
	//create the LDAP connection
	$adldap = new adLDAP();
?>