<?php
# Bijhouden van de huidige sessie
session_start();
# Sessie stoppen, en daarbij de gebruiker naar de index.php omleiden
if(session_destroy()){  // Destroying All Sessions
	header("Location: index.php"); // Redirecting To Home Page
}
?>