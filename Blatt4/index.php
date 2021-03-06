﻿<?php
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
if(isset($_SESSION["name"])) {
   
} else {
    $_SESSION["name"] = "Bart Simpson";
    $_SESSION["ort"] = "Springfield";
    $_SESSION["datum"] = "1. April 1980";
}
?>

    <h1 class="headline">Willkommen auf der Homepage von Bart Simpson!</h1>
    <div class="blackbox">

        <div class="tabbed">
            <input checked="checked" id="tab1" type="radio" name="tabs" />
            <input id="tab2" type="radio" name="tabs" />
            <input id="tab3" type="radio" name="tabs" />

            <nav>
                <label for="tab1">Das bin ich</label>
                <label for="tab2">Meine Vergangenheit</label>
                <label for="tab3">Was ich mag</label>
            </nav>

            <figure>
                <div class="tab1">
                    <h3>Mein Steckbrief:</h3> <br />

                    <div class="row">
                        <div class="column">
                            <img src="bart.gif" alt="bart">
                        </div>
                        <div class="column">
                            <table id="steckbrief">
                                <tr>
                                    <th>Name</th>
                                    <th> <?php echo $_SESSION["name"]?> </th>
                                </tr>
                                <tr>
                                    <th>Geburtsdatum</th>
                                    <th> <?php echo $_SESSION["datum"]?> </th>
                                </tr>
                                <tr>
                                    <th>Ort</th>
                                    <th> <?php echo $_SESSION["ort"]?> </th>
                                </tr>
                                <tr>
                                    <th>Hobbies</th>
                                    <th>Skateboard, TV, Comics </th>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="tab2">
                    <p>
                        1974 waren Homer und Marge fast mit der Highschool fertig, als sie sich beim Nachsitzen begegnen. Homer, weil er auf dem Klo geraucht hat und Marge weil sie, für die Emanzipation kämpfend, einen BH verbrannte. Homer ist sofort verliebt, doch Marge lehnt zunächst ein Date ab; er sei nicht ihr Typ und sie habe keine Zeit, da sie im Debatier-Club (den Homer nun auch beitritt) ist und Französisch-Nachhilfe gibt. Obwohl er gar nicht Französisch belegt, fragt er, ob sie ihm dabei helfen könnte. Schließlich treffen sie sich abends zu Hause bei Homer. Sie verbringen einen schönen Abend und schließlich fragt Homer, ob Marge mit ihm zum Ball gehen würde, sie bejaht. Darauf erzählt er ihr, dass er gar kein Französisch hätte, worauf Marge sauer wird, da Homer genau wusste, dass sie am morgigen Tag einen wichtigen Debattier-Wettbewerb hat. Trotz alledem geht Homer davon aus, dass sie mit ihm zu der Tanzveranstaltung gehen wird - doch die hat inzwischen die Einladung von Artie Ziff.png - Artie Ziff angenommen. Als Homer sie schließlich abholen will, muss er feststellen, dass er alleine mit seiner angemieteten Limousine zum Ball fahren muss. Marge und Ziff werden das Ballpaar, während Homer traurig den Abend verbringt und schließlich nach Hause läuft. Währenddessen befummelt Ziff Marge im Auto, was diese aber nicht zulässt. Ziff fährt sie nach Hause, auf dem Weg sieht diese Homer am Straßenrand laufen. Sie fährt selbst noch einmal los, um ihn einsteigen zu lassen und als sie zu Hause ankommen sind, küssen sie sich.
                    </p>
                </div>
                <div class="tab3">
                    <p>
                        Skateboarden,
                        Mit Filzstiften auf Homer rummalen und Graffitti sprayen,
                        Fanartikel von Krusty kaufen,
                        Münzen sammeln,
                        Radioactive Man (Comic) lesen.
                    </p>
                </div>
            </figure>
        </div>
    </div>

    <div class="wrapper">

        <form action="login.php">
            <input type="submit" value="Angaben ändern" />
        </form>
    </div>
</body>
</html>
