<!DOCTYPE html>
<html>
    <head>
      <title>NSKD - Open Photo</title>
      <!-- karakterset op UTF-8 vastleggen -->
      <meta charset="utf-8">
      <!-- Responsive maken van het layout, content breedte = browserscherm breedte -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- De stylesheets importeren -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="style/style.css">        
      <!-- Scripts voor bootstrap + bootstrap zelf importeren -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
<body>

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
    
<!-- het registratieformulier --> 
<!-- De gegevens van het formulier worden naar het huidige venster gePOST. --> 
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
                <input class="input" type="text" placeholder="Enter Username" name="username" required value="<?php echo $username ?>"><p class="required">*</p><span class="red"><?php echo $userErr; ?></span>
            </td>
        </tr>
        <tr>    
            <td>
                <!-- invoerveld voor de voornaam  -->    
                <label for="firstname"><b>First name</b></label> 
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
                <input class="input" type="text" placeholder="Enter Firstname" name="firstname" required value="<?php echo $firstname; ?>"><p class="required">*</p><span class="red"><?php echo $firstNameErr; ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- invoerveld voor de voornaam  -->    
                <label for="lastname"><b>Last name</b></label>
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
                <input class="input" type="text" placeholder="Enter Lastname" name="lastname" required value="<?php echo $lastname; ?>"><p class="required">*</p><span class="red"><?php echo $lastNameErr?></span> 
            </td>
        </tr>
        <tr>
            <td>
                <!-- invoerveld voor de geboortedatum  -->    
                <label for="date"><b>Date of birth</b></label>
            </td>
            <td>
                <!-- inputcontrole van de gebruikersnaam -->
                <input class="input" type="text" placeholder="Enter date of birth" name="date" required>  
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
                <input class="input" type="text" placeholder="Enter Email" name="email" required value="<?php echo $email; ?>"><span class="error"><p class="required">*</p><span class="red"><?php echo $emailErr;?></span></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- eerste invoerveld voor het ww -->
                <label for="psw"><b>Password</b></label>
            </td>
            <td>
                <!-- controle van input -->
                <input class="input" type="password" placeholder="Enter Password" name="password" required><span calss="error"><p class="required">*</p><span class="red"><?php echo $passwordErr;?></span></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- 2e invoerveld van wachtwoord -->
                <label for="psw-repeat"><b>Repeat Password</b></label>
            </td>
            <td>
                <!-- controle van input -->
                <input class="input" type="password" placeholder="Repeat Password" name="repeat" required><span class="error"><p class="required">*</p><span class="red"><?php echo $repeatErr;?></span></span>
            </td>
        </tr>
        <tr>
            <td>
                <!-- gebruikerstype, radio buttons incl inputcontrole -->
                <label for="usertype"><b>User Type</b><span class="red"><?php echo $usertypeErr; ?></span></label>  
            </td>
            <td>
                <input type= "radio" name="usertype" value="normal" checked="true"> Normal User 
                <input type= "radio" name="usertype" value="photographer"> Photographer 
            </td>
        </tr>
        <tr>
            <td>

            </td>
            <td>
                <label>
                    <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
                </label>                
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
                  <button type="button" class="cancelbtn" onclick="javascript:window.location='https://gitea.quantaloupe.tech/fotowebsite/';">Cancel</button>
                  <button type="submit" class="signupbtn">Registrate</button>
                </div>
            </td>
            <td>
            </td>
        </tr>
    </table>
  <!-- </div> -->
</form>
</body>
</html>
