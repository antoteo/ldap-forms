<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "stylesheets/stylesheet.css">
<title>TheUrbanPenguin PHP LDAP</title>
</head>
<body>
<h1> Search LDAP User</h1>
<hr>
<?php
$cn = htmlspecialchars($_POST['username']);
echo "Search user: $cn " . '<br>';
 ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");
if ($ds) {
$r = ldap_bind($ds,"cn=admin,dc=aspete,dc=gr","12345");
$sr = ldap_search($ds,"dc=aspete,dc=gr","cn=$cn");
$info = ldap_get_entries($ds,$sr);
//echo $info["count"]." entries returned\n";

//$data = ldap_get_entries($ldapconn, $info);
print_r($info);   

//$dn = $info[0]["dn"];
//ldap_delete($ds,$dn);


 for ($i=0; $i<$info["count"]; $i++) {
        echo "dn is: " . $info[$i]["dn"] . "<br />";
        echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />";
        echo "uid is: " . $info[$i]["uid"][0] . "<br /><hr />";
        echo "parking is: " . $info[$i]["aspeteparking"][0] . "<br /><hr />";
        echo "Affiliation is: " . $info[$i]["eduPersonAffiliation"][0] . "<br /><hr />";
         echo "password is: " . $info[$i]["userpassword"][0] . "<br />";
    }
}
ldap_close($ds);
?>
<hr>
<a href = "search.html">Search another user</a>
</body>
</html>
