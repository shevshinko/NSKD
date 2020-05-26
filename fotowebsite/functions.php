<?php

# Functie testInput, deze functie haalt de backslashes '\' weg van de ingevoerde gegevens. 
# Daarnaast worden speciale karakters omgezet naar HTML entiteiten.
function testInput($data){ # de invoer wordt aan variable $data gekoppelt
    $data = trim(stripslashes(htmlspecialchars($data)));
    return $data; # De verwerkte variable wordt teruggegeven aan de code die deze functie oproept.
}
?>