<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "stylesheets/stylesheet.css">
<title>TheUrbanPenguin PHP LDAP</title>
</head>
<body>
<h1> Deleting LDAP User</h1>
<hr>
<?php
$cn = htmlspecialchars($_POST['username']);
echo "Deleting user: $cn " . '<br>';
 ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");
if ($ds) {
$r = ldap_bind($ds,"cn=admin,dc=aspete,dc=gr","12345");
$sr = ldap_search($ds,"dc=aspete,dc=gr","cn=$cn");
$info = ldap_get_entries($ds,$sr);
$dn = $info[0]["dn"];
ldap_delete($ds,$dn);
}
ldap_close($ds);
?>
<hr>
<a href = "ldapuserdel.html">Delete another user</a>
</body>
</html>
