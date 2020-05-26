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
    <!-- navigatiebar, zwart, gelockt aan de bovenkant van de webpagina. Gehele browserview breedte. -->
<header class="header_area">    
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand logo_h" href="index.php"><img src="images/logo.png" alt="NSKD-logo"></a>
    <!-- NSDK- OpenPhoto in de navigatiebalk weergeven, en meerdere gegevens afhankelijk ervan of er een sessie bestaat -->
    <span class="navbar-brand mb-0 h1">NSKD - OpenPhoto</span>
    <ul class="navbar-nav ml-auto">
        <?php
        # Als er geen sessie bestaat > registratie of login optie weergeven (zie else)
        if (isset($_SESSION['user_id']) && !empty ($_SESSION['user_id'])){
            echo "<li class='nav-item active'><span class='navbar-text'>Welkom, <span class='font-italic'>", $_SESSION['user_firstname'] ,"</span>!</span></li>";
            echo "<li class='nav-item'><a class='nav-link' href=\"logout.php\">Log out</a></li>";
            echo "</ul>";
            echo '<ul class="navbar-nav ml-auto">';
            # Bij een ingelogde gebruiker met user_type groter dan 5 (site administrator of hoger) wordt naast een upload optie ook het admin menu weergeven.
            if ($_SESSION['user_type'] > 5) {
                echo '<li class="nav-item"><a href="admin/index.php" class="btn btn-danger navbar-btn" role="button" style="margin-top:6px">Admin area</a></li>';
                echo '<li class=nav-item><span class="navbar-text"><pre> </pre></span></li>';
            } # anders wordt een upload button weergeven. 
            if ($_SESSION['user_type'] > 1) {
                echo '<li class="nav-item"><a href="upload.php" class="btn btn-primary navbar-btn" role="button" style="margin-top:6px; margin-right:7px">Upload</a></li>';
                echo '</ul>';
            }
        } else {
            echo "<li class='nav-item'><a class='nav-link' href=\"register.php\">Register</a></li>";
            echo "<li class='nav-item'><a class='nav-link' href=\"login.php\">Log in</a></li>";
            echo "</ul>";
        }

        ?>
    </ul>

    </nav>
</header>
            <section class="home_banner_area">
            <div class="banner_inner d-flex align-items-center">
            	<div class="overlay bg-parallax" data-stellar-ratio="0.9" data-stellar-vertical-offset="0" data-background=""></div>
				<div class="container">
					<div class="banner_content text-center">
						<h2>NSKD Photography</h2>
						<p>If you are looking at an open photo platform on the web, you are in the right place.</p>
						<a href="#gallery" class="btn theme_btn">Explore Gallery</a>
					</div>
				</div>
            </div>
        </section><br/>
    
<!-- De foto's in een DIV wrap -->
<section id="gallery" class="home_gallery_area p_120">    
<div class="container">
<div class="container box_1620">
    <div class="gallery_f_inner row imageGallery1">
    <?php
    if (!empty($_GET)){ //als de get-request niet leeg is, voer zetten we $iamgeOffset naar het getal in de GET request
        if (is_numeric($_GET['offset'])){
            $imageOffset = $_GET['offset'];
        }
    } else {
        $imageOffset = 0; // issie leeg, dan 0
    }
    # SQL query voor de foto ID, naam, en bestandsnaam. Aflopend gesorteerd naar ID, gelimiteerd tot 10 met een variabele offset gebaseerd op paginanummer.
    $sql = "SELECT photo_id, name_photo, name_file FROM photos ORDER BY photo_id DESC LIMIT 10 OFFSET " . $imageOffset;
    $result = mysqli_query($db, $sql);

    # per afbeelding een DIV
    echo '<div class="row">';
    # while loop, om de data (foto id, naam, bestandsnaam) per foto uit de DB te trekken. 
    while($row = mysqli_fetch_array($result)) {
        $fotoNaam = $row['name_photo'];
        $fotoFile = $row['name_file'];
        $fotoID = $row['photo_id'];
        #de naam van de foto als titel
        //echo "<h3> $fotoNaam </h3>";
        # De foto als link naar de foto in oorspronkelijke grootte
        echo '<div class="col-lg-3">';
        echo "<a href='viewphoto.php?photo=$fotoID'><img class='img-fluid' src='$fotoFile'></a>";
        echo '</div>';


    }
    echo '</div>';
    # Bij het bereiken van de laatste 10 bijdragen wordt er een footer weergeven. Bij meer dan 10 bijdragen een knop naar de volgende rij met afbeeldingen.
    $count = mysqli_num_rows($result);
    if ($count < 10) {
        ?>
        <div align='center'>
            <form action='<?php echo $_SERVER['PHP_SELF'] ; ?>' method='get'>
                <input type='text' name='offset' hidden='hidden' value='<?php echo $imageOffset - 10; ?>'/>
                <input type='submit' class='btn btn-default' value='Previous'>
        </form>
        </div>
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
        <div style="display: block;margin-left: auto;margin-right: auto;">
            <form action='<?php echo $_SERVER['PHP_SELF'] ; ?>' method='get'>
                <input type='text' name='offset' hidden='hidden' value='<?php echo $imageOffset + 10; ?>'/>
                <br/><input type='submit' class='btn btn-default' value='Next'>
        </form>
        </div>
    <?php    
    }?>
   
    </div >
    </div>
    </div>
</section>
    
            <!--================Instagram Area =================-->
        <section class="instagram_area">
        	<div class="container box_1620">
        		<div class="insta_btn">
        			<br/><a class="btn theme_btn" href="#instagram">Follow us on social media</a>
        		</div>
        		<div id="instagram" class="instagram_image row m0">
        			<a href="https://www.instagram.com/shafikhoshan/"><img src="images/ins-1.jpg" alt="Sahfik Hoshan"></a>
        			<a href=""><img src="images/ins-2.jpeg" alt="Xander"></a>
        			<a href="https://github.com/langoor2"><img src="images/ins-3.jpeg" alt="Niels"></a>
        			<a href="https://www.facebook.com/arkojan"><img src="images/ins-4.jpeg" alt="Karen"></a>
        			<a href="#"><img src="images/ins-5.jpeg" alt="Stephan"></a>
        		</div>
        	</div>
        </section>
        <!--================End Instagram Area =================-->
            
        <!--================Footer Area =================-->
        <footer class="footer_area p_120">
        	<div class="container">
        		<div class="row footer_inner">
        			<div class="col-lg-5 col-sm-6">
        				<aside class="f_widget ab_widget">
        					<div class="f_title">
        						<h3>About Us</h3>
        					</div>
        					<p>We are IT students at Hanze hoogschool Groningen. </p>
        					<p>
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="#" target="_blank">ITV1D-Students</a>
</p>
        				</aside>
        			</div>
      
        			<div class="col-lg-2">
        				<aside class="f_widget social_widget">
        					<div class="f_title">
        						<h3>Follow Us</h3>
        					</div>
        					<p>Let us be social</p>
        					<ul class="list">
                                
                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
        						<li><a href="#"><i class="fab fa-twitter"></i></a></li>
        						<li><a href="#"><i class="fab fa-instagram"></i></a></li>
        						<li><a href="#"><i class="fab fa-linkedin"></i></a></li>
        						
        					</ul>
        				</aside>
        			</div>
        		</div>
        	</div>
        </footer>
        <!--================End Footer Area =================-->
</body>
</html>
