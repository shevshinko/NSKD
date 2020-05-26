<?php
# starten van een user sessie, voor onder andere het bijhouden van de view counter
session_start();
# bestand voor de database connectie importeren
include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Website titel, karakterformaat, beeldschermbreedte (responsiveness van het design) instellen -->
    <title>OpenPhoto</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- importeren van de stylesheets, scripts en bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel=”stylesheet” id=”font-awesome-css” href=”//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css” type=”text/css” media=”screen”>
</head>
<body>
    <!-- navigatiebar, zwart, gelockt aan de bovenkant van de webpagina. Gehele browserview breedte. -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <!-- NSDK- OpenPhoto in de navigatiebalk weergeven, en meerdere gegevens afhankelijk ervan of er een sessie bestaat -->
    <span class="navbar-brand mb-0 h1">NSKD - OpenPhoto</span>
    <ul class="navbar-nav mr-auto">
        <?php
        # Als er geen sessie bestaat > registratie of login optie weergeven (zie else)
        if (isset($_SESSION['user_id']) && !empty ($_SESSION['user_id'])){
            echo "<li class='nav-item active'><span class='navbar-text'>Welkom, <span class='font-italic'>", $_SESSION['user_firstname'] ,"</span>!</span></li>";
            echo "<li class='nav-item'><a class='nav-link' href=\"logout.php\">Log out</a></li>";
            echo "</ul>";
            echo '<ul class="navbar-nav ml-auto">';
            # Bij een ingelogde gebruiker met user_type groter dan 5 (site administrator of hoger) wordt naast een upload optie ook het admin menu weergeven.
            if ($_SESSION['user_type'] > 5) {
                echo '<li class="nav-item"><a href="admin/index.php" class="btn btn-danger navbar-btn" role="button">Admin area</a></li>';
                echo '<li class=nav-item><span class="navbar-text"><pre> </pre></span></li>';
            } # anders wordt een upload button weergeven. 
            if ($_SESSION['user_type'] > 1) {
                echo '<li class="nav-item"><a href="upload.php" class="btn btn-primary navbar-btn" role="button">Upload</a></li>';
                echo '</ul>';
            }
        } else {
            echo "<li class='nav-item'><a class='nav-link' href=\"register.php\">Register</a></li>";
            echo "<li class='nav-item'><a class='nav-link' href=\"login.php\">Log in</a></li>";
            echo "</ul>";
        }

        ?>

</nav>

<!-- De foto's in een DIV wrap -->
<div class="container-fluid">

    <?php
    if (!empty($_GET)){ //als de get-request niet leeg is, voer zetten we $iamgeOffset naar het getal in de GET request
        if (is_numeric($_GET['offset'])){
            $imageOffset = $_GET['offset'];
        }
    } else {
        $imageOffset = 0; // issie leeg, dan 0
    }
    # SQL query voor de foto ID, naam, en bestandsnaam. Aflopend gesorteerd naar ID, gelimiteerd tot 5 met een variabele offset gebaseerd op paginanummer.
    $sql = "SELECT photo_id, name_photo, name_file FROM photos ORDER BY photo_id DESC LIMIT 5 OFFSET " . $imageOffset;
    $result = mysqli_query($db, $sql);

    # per afbeelding een DIV
    echo '<div align="center">';
    # while loop, om de data (foto id, naam, bestandsnaam) per foto uit de DB te trekken. 
    while($row = mysqli_fetch_array($result)) {
        $fotoNaam = $row['name_photo'];
        $fotoFile = $row['name_file'];
        $fotoID = $row['photo_id'];
        #de naam van de foto als titel
        echo "<h3> $fotoNaam </h3>";
        # De foto als link naar de foto in oorspronkelijke grootte
        echo '<div class="photobox">';
        echo "<a href='viewphoto.php?photo=$fotoID'><img class='img-fluid' src='$fotoFile'></a>";
        echo '</div>';


    }
    echo '</div>';
    # Bij het bereiken van de laatste 5 bijdragen wordt er een footer weergeven. Bij meer dan 5 bijdragen een knop naar de volgende rij met afbeeldingen.
    $count = mysqli_num_rows($result);
    if ($count < 5) {
        ?>
        <div align="center">
        <blockquote class="blockquote" >
            <br>
            <br>
            <p>You have finally reached the end of the internet! There's nothing more to see, no more links to visit. This is the very last page on the very last server at the very far end of the internet.</p>
            <footer class="blockquote-footer">End of the Internet, <a target="_blank" href="https://hmpg.net/">hmpg.net</a></footer>
        </blockquote>
        </div>
        <?php
    } else {
        # De footer met de knop naar de volgende rij met afbeeldingen
        ?>
        <div align="center">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <input type="text" name="offset" hidden="hidden" value="<?php echo $imageOffset + 5; ?>"/>
                <input type="submit" class="btn" value="Next">
        </form>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>
