<?php

# De bestanden benodigd voor de functies, de database verbinding, en het verwerken van sessies importeren
# git pull test
include 'functions.php';
include 'database.php';
include 'session.php';

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>NSKD</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>

    <div class="container">

    <div class="jumbotron">
        <h1>Upload afbeelding</h1>

        <br>
<!-- Het formulier voor het uploaden van de fotos -->
<!-- de uploads worden naar upload.php gestuurd en niet gecodeerd ivm data upload -->
<form action="upload.php" method="post" enctype="multipart/form-data">
    <!-- om de form netjes te maken worden de elementen in een tabel verwerkt -->
    <div class="form-group">
            <!-- Invoerveld voor de naam van de afbeelding -->
            <label for="picName">Naam:</label>
            <input type="text" class="form-control" name="picName" id="picName">
    </div>
    <div class="form-group">
            <!-- Invoerveld voor de omschrijving van de afbeelding -->
            <label for="picDesc">Omschrijving:</label>
             <input type="text" class="form-control" name="picDesc" id="picDesc">

            <!-- Selectieveld voor de categorie van de afbeelding -->
    </div>
    <div class="form-group">
        <label for="picCat">Categorie:</label>
                <?php
                    # Stukje PHP voor het invullen van de categorienlijst
                    # SQL query voor het opvragen van de categorie ID en naam van table categorien
                    $sql = "SELECT category_id, category_name FROM categories";
                    # Deze query wordt vervolgens in een variable gestopt die verbinding maakt met de database, 
                    # en de eerder gemaakte query uitvoert. De resultaten zijn vervolgens
                    # in de variable te vinden dmv een array.
                    $result = mysqli_query($db, $sql);
                    # Het drop down menu
                    echo '<select class="form-control" name="picCat">';
                        # while loop voor het trekken van de data uit de array incl opmaak
                        while($row = mysqli_fetch_array($result)) {
                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                        }
                    echo '</select><br>'
          ?>
    </div>
            <!-- Selectieveld voor de voor het bestand -->
        <label for="fileToUpload">Bestand: </label>
            <input type="file" name="fileToUpload" id="fileToUpload"><br>

                <!-- Submitknop -->
                <input type="submit" class="btn btn-primary" value="Upload image" name="submit">

    </form>
    </div>
    </div>
    </body>
</html>

<?php
    # Stuk PHP voor het behandelen van ingevoerde data, dit stuk wordt alleen uitgevoerd als er
    # data gePOST is. 
    if (!empty($_POST)) {
        # gegevens ophalen die we op moeten slaan
        $userid = $_SESSION['user_id']; # een user heeft de foto gepost, dus, userID van de ingelogde user halen uit de sessie
        $picName = testInput($_POST['picName']); # data wat in het form is ingevuld door testInput halen
        $picDesc = testInput($_POST['picDesc']);
        $picCat = testInput($_POST['picCat']);

        $fileExtension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION); # Extensie van het oorspronkelijke bestand opslaan
        $targetFile = tempnam('uploads/', ''); # unieke naam genereren voor het bestand wat opgeslagen moet worden
        $fullPath = str_replace(".tmp", "", $targetFile . "." . $fileExtension); # Kut windows, ik haat je, .tmp er achter weg halen en dan de namen samenvoegen, MET oorspronkelijke extensie
        $originalFile = $_FILES['fileToUpload']['tmp_name']; # original bestand (uit post) opslaan
        move_uploaded_file($originalFile, $fullPath); # bestand uit post verplaatsen naar nieuwe naam
        unlink($targetFile); # oude bestand verwijderen

        $pathFromWeb = str_replace('\\', '/', strstr($fullPath, 'uploads')); # alkdfj7w4e7 windows is kut met z'n backslashes (deze er dus uithalen)

        /*
        # De verzonden data nog een keer weergeven, nadat het ontvangen is door de server
        echo "<html>";
        # De naam van de afbeelding in groot weergeven
        echo "<h1>$picName</h1>";
        echo "<br>";
        # De verzonden afbeelding in 200x200px weergeven
        echo '<img src="', $pathFromWeb, '" width="200px" height="200px">';
        # De omschrijving iets kleiner dan de afbeeldingsnaam weergeven
        echo '<h2>Omschrijving</h2>';
        echo $picDesc;
        # De categorie iets kleiner weergeven dan de afbeeldingsnaam
        echo '<h2>Catagorie</h2>';
        echo $picCat;
        echo '</html>';
        */




        # En al deze gegevens in de database verwerken, d.m.v. een SQL Query
        $query = "INSERT INTO photos (name_file, name_photo, description, counter_download, counter_view, user_id, category_id) VALUES ('$pathFromWeb', '$picName', '$picDesc', 0, 0, '$userid', '$picCat')";
        # If voor het checken van de query. Als het verwerken van de query successvol is verlopen, verschijnt "New record created successfully"
        # Zo niet, dan de error code met query. 
        if ($db->query($query) === TRUE) {
            $query = "SELECT LAST_INSERT_ID();";
            $result = mysqli_fetch_array(mysqli_query($db, $query));
            $photoID = $result['0'];
            header('Location: viewphoto.php?photo='. $photoID );
          //  echo "New record created successfully";
        } else {
            echo "Error: " . $query . "<br>" . $db->error;
        }
        $db->close();


    }
?>