<?php
 
 
 if(isset($_POST["Import"])){
		
		$filename=$_FILES["file"]["tmp_name"];		
 
 
		 if($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
 

$uid = $getData[0];
$cn = $getData[1];
$eduPersonAffiliation = $getData[2];
$aspeteparking= $getData[3];
$userPassword = $getData[4];

echo "Adding user: $cn " . '<br>';
ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

//$userPassword = $getData[4];

$info["objectClass"] = "aspetePerson";
if ($ds) {

$r = ldap_bind($ds,"cn=admin,dc=aspete,dc=gr","12345");
$salt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',4)),0,4);
$hash = "{SSHA}" . base64_encode(pack("H*", sha1($userPassword . $salt)) . $salt);
//$getData[4] = $hash;
//$getData[0]

$info["userPassword"] = $hash;
$info["cn"] = $cn;
$info["uid"] = $uid;
$info["eduPersonAffiliation"] = $eduPersonAffiliation;
$info["objectClass"] = "aspetePerson";

$r = ldap_add($ds,"uid=$uid,ou=aspetePerson,ou=people,dc=aspete,dc=gr",$info);

$sr = ldap_search($ds,"dc=aspete,dc=gr","cn=$cn");
$result = ldap_get_entries($ds,$sr);
echo "The user:<span class='result'> " . $result[0]["dn"] . "</span> has been created. <br>";


ldap_close($ds);
} else {
    echo "Unable to connect to LDAP server";
}




				if(!isset($result))
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = \"massadd.html\"
						  </script>";		
				}
				else {
					  echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = \"massadd.html\"
					</script>";
				}
	         }
			
	         fclose($file);	
		 }
	}	 
 
 
 ?>
