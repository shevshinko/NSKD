<?php
include 'database.php';
include 'functions.php';
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

    <div class="container h-100">
        <div class="jumbotron h-100 justify-content-center align-items-center" >
<?php
if (isset($_GET['photo'])) {
    $photoid = testinput($_GET['photo']);
    $sql = "SELECT photo_id, name_photo, name_file, description, counter_view, counter_download, u.firstname, u.lastname, c.category_name FROM photos p JOIN categories c ON c.category_id = p.category_id JOIN users u ON u.user_id = p.user_id WHERE photo_id = '$photoid'";
    $result = mysqli_fetch_array(mysqli_query($db, $sql));
    $photoURL = $result['name_file'];
    echo "<h1>" . $result['name_photo'] . "</h1><br>";
    echo "<img class='img-fluid' src='$photoURL'><br>";
    echo "<i class='fa fa-eye' aria-hidden='true'></i> " . $result['counter_view'];
    echo " <i class=\"fa fa-download\" aria-hidden=\"true\"></i> " . $result['counter_download'];
    echo " <i class=\"fa fa-user\" aria-hidden=\"true\"></i> " . $result['firstname'] . " " . $result['lastname'];
    echo " <i class=\"fa fa-tag\" aria-hidden=\"true\"></i> " . $result['category_name'] . "<br><br>";
    echo $result['description'] . "<br><br>";


    echo '<form action="download.php" method="get">';
    echo '<a href="index.php" class="btn btn-secondary" role="button">Back</a>';
    echo '<input type="submit" name="download" class="btn btn-primary" value="Download">';

    echo '<input type="text" value="'. $photoURL . '" name="file" hidden="hidden">';

    echo '</form>';
    $sql = "UPDATE photos SET counter_view = counter_view + 1 WHERE photo_id = $photoid";
    $db->query($sql);
} else {
    echo "no photo given to view..";
}
?>
        </div>
    </div>
    </body>
