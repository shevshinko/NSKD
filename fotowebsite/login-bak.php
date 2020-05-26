<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Website titel, karakterformaat, beeldschermbreedte (responsiveness van het design) instellen -->
  <title>NSKD - Open Photos</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- importeren van de stylesheets, scripts en bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style/style.css">       
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>  
    
</head>
<body>
<?php
# bestand voor de database connectie importeren
include 'database.php';
# starten van een user sessie, voor onder andere het bijhouden van de view counter
session_start();
# variable $err initialiseren
$err = "";

# Als er wat gePOST is, wordt dit stuk code uitgevoerd. Daarbij wordt het ingevoerde wachtwoord gehast dmv MD5, en de username gestript van slashes om escapen te voorkomen.  
if (!empty($_POST)) {
    $enteredPassword = md5(stripslashes($_POST['password']));
    $enteredUsername = stripslashes($_POST['username']);
    # SQL query die de user id, user name, naam, geboortedatum, mobiel nr, email en geslacht van de tabel users ophaalt. Voor de selectie wordt de ingevoerde username en wachtwoord gebruikt.
    $sql = "SELECT user_id, username, firstname, lastname, date, mobile, email, gender, usertype FROM users WHERE username = '$enteredUsername' AND password='$enteredPassword'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    # De geretourneerde waarden worden nu gepost, om het in de website zelf te kunnen gebruiken.
    $count = mysqli_num_rows($result);
        if($count == 1) {
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_login'] = $enteredUsername;
            $_SESSION['user_firstname'] = $row['firstname'];
            $_SESSION['user_lastname'] = $row ['lastname'];
            $_SESSION['user_date'] = $row ['date'];
            $_SESSION['user_mobile'] = $row ['mobile'];
            $_SESSION['user_email'] = $row ['email'];
            $_SESSION['user_gender'] = $row['gender'];
            $_SESSION['user_type'] = $row['usertype'];
            echo "Login succesvol.. U wordt doorgelinkt naar de homepagina.";
            header("location: index.php");
        } else {
            # bij een fout wachtwoord wordt er een melding weergeven. 
            $err = "Username or Password is invalid!";
        }
        # na het uitvoeren van de query wordt de database verbinding gesloten. 
    mysqli_close($db); // Closing Connection
    }
echo $err;
?>

<!-- Het loginscherm zelf, dit formulier POST naar zichzelf.
    Username, wachtwoord, en een checkbox om de username te onthouden.
    Om het af te ronden een submitknop met de tekst "Log In" -->
<div class="container">
    <div class="prof-box1">
  <h2>Please Log in here:</h2>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="form-group">
      <label for="email">Username:</label>
      <input type="text" class="form-control" name="username" placeholder="Enter username">
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" name="password" placeholder="Enter password">
    </div>
    <div class="checkbox">
      <label><input type="checkbox" name="remember"> Remember me</label>
    </div>
    <button type="submit" class="btn btn-default">Log In</button>
  </form>
</div>
</div>
</body>
</html>