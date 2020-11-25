<?php
// Start the session
session_start();
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css">

    <title></title>
</head>
<body>
<?php
    if( $_SESSION["login"] != "true") {
        header("Location: login.php");
        die();
    }
?>
    <h1 class="headline">Daten bearbeiten</h1>
        <div class = "center">
        <form name="contact" action="handler.php" method="post">
            <label for="name" class="myLabel">Name</label>
            <input id="name" name="name" class="myInput" type="text" value= "<?php echo $_SESSION['name']?>"/> <br>

            <label for="ort" class="myLabel">Geburtsort</label>
            <input id="ort" name="ort" class="myInput" type="text" /> <br>

            <label for="datum" class="myLabel">Geburtsdatum</label>
            <input id="datum" name="datum" class="myInput" type="text" /> <br>

            <button onclick="window.location.href = 'https://website.com/my-account';" type="submit" class="primary" name="action-name" value="send">speichern</button>
            <button onclick="location.href='index.php'" type="button">abbrechen</button>
        </form>
        </div>

</body>
</html>