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
$name = "";

if (isset($_POST['add'])){
    if (empty($_POST['editID'])) {
        $catName = testInput($_POST['catName']);
        $sql = "INSERT INTO categories (category_name) VALUES ('$catName')";
        if ($db->query($sql) === TRUE) {
            $statusmsg = "Toegevoegd!";
        } else {
            $statusmsg = $sql . "<br>" . $db->error;
        }
    } else {
        $catName = testInput($_POST['catName']);
        $catID = testInput($_POST['editID']);
        $sql = "UPDATE categories SET category_name = '$catName' WHERE category_id = $catID ";
        if ($db->query($sql) === TRUE) {
            $statusmsg = "Aangepast!";
        } else {
            $statusmsg = $sql . "<br>" . $db->error;
        }
    }

} elseif (isset($_POST['delete'])) {
    $catID = testInput($_POST['id']);
    $sql = "DELETE FROM categories WHERE category_id = $catID";
    if ($db->query($sql) === TRUE) {
        $statusmsg = "Deleted!";
    } else {
        $statusmsg = $sql . "<br>" . $db->error;
    }
} elseif (isset($_POST['edit'])) {
    $editID = testInput($_POST['id']);
    $name = testInput($_POST['name']);
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

        <h1>Category manager</h1>
        <p><?php echo $statusmsg; ?></p>
        <hr>
        <h2>Category toevoegen</h2>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <div class="form-group">
                <label for="catID">ID:</label>
                <input type="text" class="form-control" name="catID" id="catID" disabled value="<?php echo $editID; ?>">
                <input type="text" class="form-control" name="editID" id="editID" hidden="hidden" value="<?php echo $editID; ?>">
            </div>
              <div class="form-group">
                  <label for="catName">Naam:</label>
                  <input type="text" class="form-control" name="catName" id="catName" value="<?php echo $name; ?>">
              </div>
            <input type="submit" class="btn btn-primary" name="add" value="Toevoegen">
        </form>
        <hr>
        <h2>Huidige category's</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Category ID</th>
                <th>Category naam</th>
                <th style="width: 10%;"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM categories ORDER BY category_id ASC";
            $result = mysqli_query($db, $sql);
            while($row = mysqli_fetch_array($result)) {
                $id = $row['category_id'];
                $name = $row['category_name'];
                echo '<tr>';
                echo "<td>$id</td>";
                echo "<td>$name</td>";
                echo "<form action='category.php' method='post'><input type='text' value='$id' name='id' hidden='hidden'>
                                                                <input type='text' value='$name' name='name' hidden='hidden'>
                                                                <td><div class='btn-group'><input type='submit' name='edit' value='Edit' class='btn btn-warning'>";
                echo "<input type='submit' name='delete' value='Delete' class='btn btn-danger'></div></td></form>";
                echo '</tr>';       }
            ?>
            </tbody>
        </table>
</div>
</body>
</html>
