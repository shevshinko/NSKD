<?php
include '../database.php';
include 'admincheck.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Website titel, karakterformaat, beeldschermbreedte (responsiveness van het design) instellen -->
    <title>Admin Interface - OpenPhoto</title>

    <?php
    include '../functions.php';
    $statusmsg = "";
    $editID = "";
    $namephoto = "";
    $desc = "";
    $category = "";

    if (isset($_POST['add'])){
        if (!empty($_POST['photo'])) {
            $photoID = testInput($_POST['photo']);
            $photoName = testInput($_POST['photoName']);
            $photoDesc = testInput($_POST['photoDesc']);
            $photoCat = testInput($_POST['photoCat']);
            $sql = "UPDATE photos SET photo_id = '$photoID', name_photo = '$photoName', description = '$photoDesc' , category_id = '$photoCat' WHERE photo_id = '$photoID'";
            if ($db->query($sql) === TRUE) {
                $statusmsg = "Edited!";
            } else {
                $statusmsg = $sql . "<br>" . $db->error;
            }
        }

    } elseif (isset($_POST['delete'])) {
        $photoID = testInput($_POST['editID']);
        $sql = "DELETE FROM photos WHERE photo_id = $photoID";
        if ($db->query($sql) === TRUE) {
            $statusmsg = "Deleted!";
        } else {
            $statusmsg = $sql . "<br>" . $db->error;
        }
    } elseif (isset($_POST['edit'])) {
        $editID = testInput($_POST['editID']);
        $namephoto = testInput($_POST['namephoto']);
        $desc = testInput($_POST['desc']);
        $category = testInput($_POST['category']);
    }?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>NSKD</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- importeren van de stylesheets, scripts en bootstrap -->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="../style/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

        <link rel=”stylesheet” id=”font-awesome-css” href=”//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css” type=”text/css” media=”screen”>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>
<body>
<div class="container">
    <div class="jumbotron">

        <h1>OpenPhoto admin interface</h1>
        <p>Welkom op de adminpage, <?php echo $_SESSION['user_firstname'];?></p>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <nav class="nav flex-column bg-light navbar-light">
                    <a class="nav-link" href="index.php">User-Manager</a>
                    <a class="nav-link" href="category.php">Category-Manager</a>
                    <a class="nav-link" href="photo.php">Photo-Manager</a>
                    <a class="nav-link" href="../index.php">Back to website</a>
                </nav>
            </div>
        </div>
    </div>

    <h1>Photo Manager</h1>
    <p><?php echo $statusmsg; ?></p>
    <hr>
    <h2>Edit or Remove Photos</h2>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <div class="form-group">
            <label for="photo">Photo ID:</label>
            <input type="text" class="form-control" name="photo" id="photo" disabled value="<?php echo $editID; ?>">
            <input type="text" class="form-control" name="photo" id="photo" hidden="hidden" value="<?php echo $editID; ?>">
        </div>
        <div class="form-group">
            <label for="catName">Photo Name:</label>
            <input type="text" class="form-control" name="photoName" id="photoName" value="<?php echo $namephoto; ?>">
            <label for="catName">Description:</label>
            <input type="text" class="form-control" name="photoDesc" id="photoDesc" value="<?php echo $desc; ?>">
            <label for="catName">Category:</label>
            <?php
            # Stukje PHP voor het invullen van de categorienlijst
            # SQL query voor het opvragen van de categorie ID en naam van table categorien
            $sql = "SELECT category_id, category_name FROM categories";
            # Deze query wordt vervolgens in een variable gestopt die verbinding maakt met de database,
            # en de eerder gemaakte query uitvoert. De resultaten zijn vervolgens
            # in de variable te vinden dmv een array.
            $result = mysqli_query($db, $sql);
            # Het drop down menu
            echo '<select class="form-control" name="photoCat">';
            # while loop voor het trekken van de data uit de array incl opmaak
            while($row = mysqli_fetch_array($result)) {
                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
            }
            echo '</select><br>'
            ?>
        </div>
        <input type="submit" class="btn btn-primary" name="add" value="Edit">
    </form>
    <hr>
    <h2>Photos</h2>
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Views</th>
            <th>Downloads</th>
            <th>Username</th>
            <th>Category</th>
            <th style="width: 10%;"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT photo_id, name_photo, name_file, description, counter_view, counter_download, username, category_name
                FROM photos JOIN users USING(user_id)
                JOIN categories USING(category_id)
                ORDER BY photo_id ASC;";
        $result = mysqli_query($db, $sql);
        while($row = mysqli_fetch_array($result)) {
            $editID = $row['photo_id'];
            $namephoto = $row['name_photo'];
            $desc = $row['description'];
            $view = $row['counter_view'];
            $download = $row['counter_download'];
            $username = $row['username'];
            $category = $row['category_name'];
            echo '<tr>';
            echo "<td>$editID</td>";
            echo "<td>$namephoto</td>";
            echo "<td>$desc</td>";
            echo "<td>$view</td>";
            echo "<td>$download</td>";
            echo "<td>$username</td>";
            echo "<td>$category</td>";
            echo "<form action='photo.php' method='post'><input type='text' value='$editID' name='editID' hidden='hidden'>
                                                             <input type='text' value='$namephoto' name='namephoto' hidden='hidden'>
                                                             <input type='text' value='$desc' name='desc' hidden='hidden'>
                                                             <input type='text' value='$category' name='category' hidden='hidden'>
                                                             <td><div class='btn-group'><input type='submit' name='edit' value='Edit' class='btn btn-warning'>";
            echo "<input type='submit' name='delete' value='Delete' class='btn btn-danger'></div></td></form>";
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>