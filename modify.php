<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "stylesheet.css">
<title>TheUrbanPenguin PHP LDAP Add</title>
</head?
<body>
<h1> change password</h1>
<hr>
<?php
$cn = htmlspecialchars($_POST['cn']);
$uid = htmlspecialchars($_POST['uid']);
//$eduPersonAffiliation = htmlspecialchars($_POST['eduPersonAffiliation']);
$newPassword = $_POST['userPassword'];

echo "modify user:" .$uid ."\n";
ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

if ($ds) {
$r = ldap_bind($ds,"cn=admin,dc=aspete,dc=gr","12345");
//echo "test";
$info["cn"] = $cn;
$info["uid"] = $uid;
$info["eduPersonAffiliation"] = $eduPersonAffiliation;
$info["userPassword"] = $userPassword;
$info["objectClass"] = "aspetePerson";
// $r = ldap_add($ds,"cn=$cn,ou=aspetePerson,ou=people,dc=aspete,dc=gr",$info);
//$r = ldap_add($ds,"uid=$uid,ou=aspetePerson,ou=people,dc=aspete,dc=gr",$info);
$sr = ldap_search($ds,"dc=aspete,dc=gr","uid=$uid");
$entries = ldap_get_entries($ds,$sr);

mt_srand((double)microtime()*1000000);
$salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
echo "\nPassword: " .$newPassword ." Salt: " .$salt ."\n";
$hash = "{SSHA}" . base64_encode(pack("H*", sha1($newPassword . $salt)) . $salt);
echo "hash" .$hash;

//echo $newEntry;
$newEntry["userPassword"]=$hash;

$dn=$entries[0]["dn"];

if(ldap_mod_replace($ds, $dn, $newEntry))
    print "<p>succeded</p>";
else
    print "<p>failed</p>";


ldap_close($ds);
} else {
    echo "Unable to connect to LDAP server";
}

?>
<hr>
<a href = "modify.html">Modify another user</a>
</body>
</html>
