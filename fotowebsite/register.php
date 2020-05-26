<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Website titel, karakterformaat, beeldschermbreedte (responsiveness van het design) instellen -->
    <title>NSKD</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- importeren van de stylesheets, scripts en bootstrap -->
    <link rel="icon" href="images/icon.ico" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel=”stylesheet” id=”font-awesome-css” href=”https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css” type=”text/css” media=”screen”>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    </head>
<body>
<header class="header_area">    
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand logo_h" href="index.php"><img src="images/logo.png" alt="NSKD-logo"></a>
    <!-- NSDK- OpenPhoto in de navigatiebalk weergeven, en meerdere gegevens afhankelijk ervan of er een sessie bestaat -->
    <span class="navbar-brand mb-0 h1">NSKD - OpenPhoto</span>
    <ul class="navbar-nav ml-auto">
    </ul>
    </nav>
    </header>
    
    
    
<?php
# De bestanden met functies en databaseconnectie importeren
include "database.php";
include "functions.php";

##### Wat doet dit stuk? AUB commentaar bijvoegen
$userErr = $firstNameErr = $lastNameErr = $usertypeErr = $mobileErr = $emailErr = $passwordErr = $repeatErr = "";
$username = $email = $firstname = $lastname = $date = $mobile = $usertype = $gender = $password = $repeat = $hashedpassword =  "";

    # Controleert of het veld username ingevuld is. Zoniet, foutmelding op het scherm weergeven.
    # Indien er wel wat ingevoerd is, dan de input controleren of fouten of SQL injecties o.i.d.
if(!empty($_POST)) {
    if (empty($_POST['username'])) {
        $userErr = "This field may not be empty";
    } else {
        if (!preg_match('/^[A-Za-z]*$/', $_POST['username'])) {
            $userErr = "The username may contain only letters.";
        } else {
            $username = testInput($_POST["username"]);
        }
    }

    # Controleert of het veld firstname ingevuld is. Zoniet, foutmelding op het scherm weergeven.
    # Indien er wel wat ingevoerd is, dan de input controleren of fouten of SQL injecties o.i.d.
    # Dit veld mag alleen bestaan uit alfanumerieke karakters en spaties.
    if (empty($_POST['firstname'])) {
        $firstNameErr = "This field may not be empty";
    } else {
        if (!preg_match('/^[A-Za-z ]*$/', $_POST['firstname'])) {
            $firstNameErr = "The first name may contain only letters and space.";
        } else {
            $firstname = testInput(ucfirst($_POST['firstname']));
        }
    }

    # Controleert of het veld firstname ingevuld is. Zoniet, foutmelding op het scherm weergeven.
    # Indien er wel wat ingevoerd is, dan de input controleren of fouten of SQL injecties o.i.d.
    # Dit veld mag alleen bestaan uit alfanumerieke karakters en spaties.
    if (empty($_POST['lastname'])) {
        $lastNameErr = "This field may not be empty.";
    } else {
        if (!preg_match('/^[A-Za-z ]*$/', $_POST['lastname'])) {
            $lastNameErr = "The last name may contain only letters and space.";
        } else {
            $lastname = testInput(ucfirst($_POST["lastname"]));
        }
    }

    #Controleert het ingevulde geslachtsveld op SQL injecties etc.  
    $gender = testInput(ucfirst($_POST['gender']));
    #Controleert het ingevulde geslachtsveld op SQL injecties etc.  
    $date = testInput(ucfirst($_POST["date"]));
    if (is_numeric($_POST['mobile'])) {
        $mobile = testInput($_POST["mobile"]);
    }
    if (empty($_POST['email'])) {
        $emailErr = "This field may not be empty";
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invaild email format";
        } else {
            $email = testInput($_POST['email']);
        }
    }

    # Controleert of het veld passsword ingevuld is, en wordt op SQL injecties e.d. gecontroleerd.
    # Daarna wordt er gecontroleerd of de wachtwoorden overeenkomen. 
    # Is dit het geval, dan wordt het wachtwoord md5 gehasht. 
    if (empty($_POST['password'])) {
        $passwordErr = "This field may not be empty";
    } else {
        $password = testInput($_POST['password']);
    }
    if (empty($_POST['repeat'])) {
        $repeatErr = "This field may not be empty";
    } else {
        $repeat = testInput($_POST['repeat']);
    }
    if ($password == $repeat) {
        $hashedpassword = md5($password);
    } else {
        $repeatErr = "passwords don't match";
    }

    # Controleert of het veld usertype ingevuld is. Als er niets ingevuld is, verschijnt er een melding
    # op het scherm. Anders een '1' voor een normale gebruiker, en een '2' voor een fotograaf.
    if (empty($_POST['usertype'])) {
        $usertypeErr = "invalid usertype";
    } else {
        if ($_POST['usertype'] == "normal") {
            $usertype = 1;
        } else if ($_POST['usertype'] == "photographer") {
            $usertype = 2;
        } else {
            $usertypeErr = "Invalid usertype";
        }
    }

    # Bij foutmeldingen worden de meldingen op het het scherm weergeven. 
    $errors = $userErr . $lastNameErr . $usertypeErr . $mobileErr . $emailErr . $passwordErr . $repeatErr;
    echo $errors;
    # Als er geen foutmeldingen zijn, wordt het volgende stuk code uitgevoerd: 
    if (empty($errors) == true) {
        # SQL query voor het invoeren van de username, naam, datum van registratie, telefoonnumer, email, geslacht, wachtwoord en het type gebruiker in de database. 
        $sql = "INSERT INTO users(username, firstname, lastname, date, mobile, email, gender, password, usertype) VALUES ('$username','$firstname','$lastname','$date','$mobile','$email','$gender','$hashedpassword','$usertype')";
        # De SQL query uitvoeren en in de variable $results verwerken
        $result = mysqli_query($db, $sql);
        # De SQL query op het scherm weergeven
        echo $sql;
        # Geen foutmelding bij de query > ingevulde informatie op het scherm weergeven. 
        if ($result == TRUE) {
            echo("<div class='prof-box2'> The following information has been entered: <br>");
            echo("<br/> Username:<b> " . $username . "</b><br>");
            echo("<br/> First name:<b> " . $firstname . "</b><br>");
            echo("Last name:<b> " . $lastname . " </b><br>");
            echo("Birth date:<b> " . $date . " </b><br>");
            echo("Mobile number:<b> " . $mobile . " </b><br>");
            echo("Email:<b> " . $email . " </b><br>");
            echo("Gender:<b> " . $gender . " </b><br>");
            echo("Password:<b> " . $password . " </b><br>");
            echo("User Type:<b> " . $usertype . " </b><br>");
            echo "<br> The user $firstname $lastname has been added.";
        } else {
            # Wel een foutmelding bij de SQL query > Query + foutmelding op het scherm printen
            echo $sql;
            echo mysqli_error();
        }
        # Als dit allemaal is afgerond > databaseconnectie sluiten.
        mysqli_close($db);
    }
}
?>
<section class="home_banner_area">   
<div class="banner_inner d-flex align-items-center">
<div class="overlay bg-parallax" data-stellar-ratio="0.9" data-stellar-vertical-offset="0" data-background=""></div>
<div class="container">    
<!-- het registratieformulier --> 
<!-- De gegevens van het formulier worden naar het huidige venster gePOST. --> 
<div class="banner_content">    
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<!-- DIVje om de gegevens bij elkaar te houden --> 
<!--  <div class="prof-box"> --> 
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <table>
        <tr>
            <td>
                <!-- invoerveld voor de gebruikersnaam --> 
                <label for="username"><b>Username</b></label> 
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
                <input class="input" type="text" placeholder="Enter Username" name="username" required value="<?php echo $username; ?>"><span class="red"><?php echo $userErr; ?></span>
            </td>
        </tr>
        <tr>    
            <td>
                <!-- invoerveld voor de voornaam  -->    
                <label for="firstname"><b>First name</b></label> 
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
                <input class="input" type="text" placeholder="Enter Firstname" name="firstname" required value="<?php echo $firstname; ?>"><span class="red"><?php echo $firstNameErr; ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- invoerveld voor de voornaam  -->    
                <label for="lastname"><b>Last name</b></label>
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
                <input class="input" type="text" placeholder="Enter Lastname" name="lastname" required value="<?php echo $lastname; ?>"><span class="red"><?php echo $lastNameErr; ?></span> 
            </td>
        </tr>
        <tr>
            <td>
                <!-- invoerveld voor de geboortedatum  -->    
                <label for="date"><b>Date of birth</b></label>
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
           <input placeholder="Date" class="textbox-n" type="text" onfocus="(this.type='date')"  id="date"> 
            </td>
        </tr>
        <tr>
            <td>      
                <!-- invoerveld voor het telefoonnummer  -->    
                <label for="mobile"><b>Mobile Number</b></label>
            </td>
            <td>
                <input class="input" type="text" placeholder="Enter Mobile number" name="mobile" required value="<?php echo $mobile; ?>"><span class="red"><?php echo $mobileErr; ?></span>
            </td>
        </tr>
        <tr>
            <td>      
                <!-- invoerveld voor het geslacht  -->    
                <label for="gender"><b>Gender</b></label>
            </td>
            <td>
                <!-- radio buttons voor de keuze, standaard mannelijk gekozen -->
                <input type= "radio" name="gender" value="Male" checked="true"> Male 
                <input type= "radio" name="gender" value="Female"> Female 
            </td>
        </tr>
        <tr>
            <td>
                <!-- invoerveld voor het email adres  -->    
                <label for="email"><b>Email</b></label>
            </td>
            <td>
                <!-- controle van het ingevoerde adres -->
                <input class="input" type="text" placeholder="Enter Email" name="email" required value="<?php echo $email; ?>"><span class="error"><span class="red"><?php echo $emailErr;?></span></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- eerste invoerveld voor het ww -->
                <label for="psw"><b>Password</b></label>
            </td>
            <td>
                <!-- controle van input -->
                <input class="input" type="password" placeholder="Enter Password" name="password" required><span calss="error"><span class="red"><?php echo $passwordErr;?></span></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- 2e invoerveld van wachtwoord -->
                <label for="psw-repeat"><b>Repeat Password</b></label>
            </td>
            <td>
                <!-- controle van input -->
                <input class="input" type="password" placeholder="Repeat Password" name="repeat" required><span class="error"><span class="red"><?php echo $repeatErr;?></span></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- gebruikerstype, radio buttons incl inputcontrole -->
                <label for="usertype"><b>User Type</b><span class="red"><?php echo $usertypeErr; ?></span></label>  
            </td>
            <td>
                <input type= "radio" name="usertype" value="normal" checked="true"> Normal User<br/> 
                <input type= "radio" name="usertype" value="photographer"> Photographer 
            </td>
        </tr>
        <tr>
            <td>
                <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <!-- localhost link gewijzigd naar openbaar bereikbare link van de website --> 
                <!-- bij het klikken van regsitreren wordt de info in het huidige scherm gecontroleerd en gepusht-->
                <!-- Bij een klik op afbreken wordt de gebruiker teruggeleid naar de homepage.-->
                <div class="clearfix">
                  <button type="button" class="btn btn-danger" onclick="javascript:window.location='http://localhost/scripts/fotowebsite/index.php';">Cancel</button>
                  <button type="submit" class="btn btn-success">Registrate</button>
                </div>
            </td>
            <td>
            </td>
        </tr>
    </table>
  <!-- </div> -->
    </form></div></div>
    </div>
    </section>
</body>
</html>
