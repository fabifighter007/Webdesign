<?php
session_start();

echo "Your \$_SESSION['name'] var is ".$_SESSION['name'];
echo " <br> Your \$_SESSION['name'] var is ".$_SESSION['ort'];
echo "<br> Your \$_SESSION['name'] var is ".$_SESSION['datum'];

        $action = @$_POST["action-name"];
        $_SESSION["name"] = @$_POST["name"];
        $_SESSION["ort"] = @$_POST["ort"];
        $_SESSION["datum"] = @$_POST["datum"];
        $errors = array();
		
		header("Location: index.php");
	die();
?>