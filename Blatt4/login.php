<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Willkommen auf der Homepage von Bart Simpson</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">

</head>
<body class="sansserif">

    <?php
        $action = @$_POST["action-name"];
        $password = @$_POST["password"];
        $geheim = "123";
        
        $errors = array();

        if($action == "send") {

            if(($password)!=($geheim)) {
                $errors[] = "Passwort falsch";
            } else {
                $_SESSION["login"] = "true";
                header("Location: bearbeiten.php");
                die();
            }
        }
        // Only show form, if no action is available or any error occures
        if($action != "send" || count($errors) > 0) {
    ?>


    <h1 class="headline">Bitte loggen Sie sich ein!</h1>
    <form name="contact" action="login.php" method="post">
        <div class = "center">
            <label for="password">Passwort:</label>
            <input id="password" name="password" type="password" value="<?= $password ?>" />
            <button type="submit" class="primary" name="action-name" value="send">Absenden</button> <br>
        </div>

        <div class="error">
            <?php
            if(count($errors) > 0) {
                echo "<div class='error'>";
                foreach ($errors as $error) {
                    echo $error;
                }
                echo "</div>";
            }
            ?>
        </div>
    </form>

    <?php
        // Show good bye message
        } else {
           
    ?>
        <h1>Vielen Dank, <?= $password ?>!</h1>
        <p>Login erfolgreich!</p>

        
    <?php   
        }
    ?>

</body>
</html>