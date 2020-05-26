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
$username = "";
$firstname = "";
$lastname = "";
$date = "";
$mobile = "";
$email = "";
$gender = "";
$usertype = "";

if (isset($_POST['add'])){
    if (!empty($_POST['user'])) {
        $userID = testInput($_POST['user']);
        $userName = testInput($_POST['userName']);
        $userFirstname = testInput($_POST['userFirstname']);
        $userLastname = testInput($_POST['userLastname']);
        $userDate = testInput($_POST['userDate']);
        $userMobile = testInput($_POST['userMobile']);
        $userEmail = testInput($_POST['userEmail']);
        $userGender = testInput($_POST['userGender']);
        $userType = testInput($_POST['userType']);
        $userPassword = testInput(md5($_POST['userPassword']));
        if(empty($userPassword)){
            $sql = "UPDATE users SET user_id = '$userID',username = '$userName', firstname = '$userFirstname', lastname = '$userLastname', date = '$userDate', mobile = '$userMobile', email = '$userEmail', gender = '$userGender', usertype = '$userType'  WHERE user_id = $userID ";
        }else{
            $sql = "UPDATE users SET user_id = '$userID',username = '$userName', firstname = '$userFirstname', lastname = '$userLastname', date = '$userDate', mobile = '$userMobile', email = '$userEmail', gender = '$userGender', password = '$userPassword', usertype = '$userType'  WHERE user_id = $userID ";
        }
        if ($db->query($sql) === TRUE) {
            $statusmsg = "Edited!";
        } else {
            $statusmsg = $sql . "<br>" . $db->error;
        }
    }

} elseif (isset($_POST['delete'])) {
    $userID = testInput($_POST['editID']);
    $sql = "UPDATE users SET firstname = 'Deleted User', lastname = '', date = '', mobile = '', email = '', gender = '',password = '', usertype = ''  WHERE user_id = $userID ";
    if ($db->query($sql) === TRUE) {
        $statusmsg = "Deleted!";
    } else {
        $statusmsg = $sql . "<br>" . $db->error;
    }
} elseif (isset($_POST['edit'])) {
    $editID = testInput($_POST['editID']);
    $username = testInput($_POST['username']);
    $firstname = testInput($_POST['firstname']);
    $lastname = testInput($_POST['lastname']);
    $date = testInput($_POST['date']);
    $mobile = testInput($_POST['mobile']);
    $email = testInput($_POST['email']);
    $gender = testInput($_POST['gender']);
    $usertype = testInput($_POST['usertype']);
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

        <h1>User Manager</h1>
        <p><?php echo $statusmsg; ?></p>
        <hr>
        <h2>Edit or Remove Users</h2>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <div class="form-group">
                <label for="user">User ID:</label>
                <input type="text" class="form-control" name="user" id="user" disabled value="<?php echo $editID; ?>">
                <input type="text" class="form-control" name="user" id="user" hidden="hidden" value="<?php echo $editID; ?>">
            </div>
            <div class="form-group">
                <label for="catName">Username:</label>
                <input type="text" class="form-control" name="userName" id="userName" value="<?php echo $username; ?>">
                <label for="catName">First name:</label>
                <input type="text" class="form-control" name="userFirstname" id="userFirstname" value="<?php echo $firstname; ?>">
                <label for="catName">Last name:</label>
                <input type="text" class="form-control" name="userLastname" id="userLastname" value="<?php echo $lastname; ?>">
                <label for="catName">Date:</label>
                <input type="text" class="form-control" name="userDate" id="userDate" value="<?php echo $date; ?>">
                <label for="catName">Mobile:</label>
                <input type="text" class="form-control" name="userMobile" id="userMobile" value="<?php echo $mobile; ?>">
                <label for="catName">Email:</label>
                <input type="text" class="form-control" name="userEmail" id="userEmail" value="<?php echo $email; ?>">
                <label for="catName">Gender:</label>
                <input type="text" class="form-control" name="userGender" id="userGender" value="<?php echo $gender; ?>">
                <label for="catName">Usertype:</label>
                <input type="text" class="form-control" name="userType" id="userType" value="<?php echo $usertype; ?>">
                <label for="catName">Change Password:</label>
                <input type="text" class="form-control" name="userPassword" id="userPassword">
            </div>
            <input type="submit" class="btn btn-primary" name="add" value="Edit">
        </form>
        <hr>
        <h2>Huidige users</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Fullname</th>
                <th>Date</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Usertype</th>
                <th style="width: 10%;"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM users ORDER BY user_id ASC";
            $result = mysqli_query($db, $sql);
            while($row = mysqli_fetch_array($result)) {
                $editID = $row['user_id'];
                $username = $row['username'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $date = $row['date'];
                $mobile = $row['mobile'];
                $email = $row['email'];
                $gender = $row['gender'];
                $usertype = $row['usertype'];
                echo '<tr>';
                echo "<td>$editID</td>";
                echo "<td>$username</td>";
                echo "<td>$firstname $lastname</td>";
                echo "<td>$date</td>";
                echo "<td>$mobile</td>";
                echo "<td>$email</td>";
                echo "<td>$gender</td>";
                echo "<td>$usertype</td>";
                echo "<form action='index.php' method='post'><input type='text' value='$editID' name='editID' hidden='hidden'>
                                                             <input type='text' value='$username' name='username' hidden='hidden'>
                                                             <input type='text' value='$firstname' name='firstname' hidden='hidden'>
                                                             <input type='text' value='$lastname' name='lastname' hidden='hidden'>
                                                             <input type='text' value='$date' name='date' hidden='hidden'>
                                                             <input type='text' value='$mobile' name='mobile' hidden='hidden'>
                                                             <input type='text' value='$email' name='email' hidden='hidden'>
                                                             <input type='text' value='$gender' name='gender' hidden='hidden'>
                                                             <input type='text' value='$usertype' name='usertype' hidden='hidden'>
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