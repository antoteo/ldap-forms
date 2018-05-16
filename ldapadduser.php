<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "stylesheets/stylesheet.css">
<title>TheUrbanPenguin PHP LDAP Add</title>
</head?
<body>
<h1> Adding LDAP User</h1>
<hr>
<?php
$cn = htmlspecialchars($_POST['cn']);
$uid = htmlspecialchars($_POST['uid']);
$eduPersonAffiliation = htmlspecialchars($_POST['eduPersonAffiliation']);
$userPassword = htmlspecialchars($_POST['userPassword']);

echo "Adding user: $cn " . '<br>';
ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

if ($ds) {

$r = ldap_bind($ds,"cn=admin,dc=aspete,dc=gr","12345");

$salt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',4)),0,4);

//mt_srand((double)microtime()*1000000);
//$salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
echo "\nPassword: " .$userPassword ." Salt: " .$salt ."\n";
$hash = "{SSHA}" . base64_encode(pack("H*", sha1($userPassword . $salt)) . $salt);
echo "hash" .$hash;
echo '<br>' .$userPassword;
$info["userPassword"] = $hash;

//echo "test";
$info["cn"] = $cn;
$info["uid"] = $uid;
$info["eduPersonAffiliation"] = $eduPersonAffiliation;
$info["objectClass"] = "aspetePerson";
$r = ldap_add($ds,"uid=$uid,ou=aspetePerson,ou=people,dc=aspete,dc=gr",$info);
$sr = ldap_search($ds,"dc=aspete,dc=gr","cn=$cn");
$info = ldap_get_entries($ds,$sr);
echo "The user:<span class='result'> " . $info[0]["dn"] . "</span> has been created. <br>";

ldap_close($ds);
} else {
    echo "Unable to connect to LDAP server";
}
?>
<hr>
<a href = "ldapadduser.html">Add another user</a>
</body>
</html>
