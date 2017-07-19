<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/database.php");
include_once "../login/session.php";
/*
 * vult de lijst met alle opdrachten die er zijn.
 */

   // vraag alle opdrachten op die in de database staan.
if($functie_bevoegdheid < 3){
    $sql = "SELECT opdrachten.opdracht_id, opdrachten.opdracht_naam, opdrachten.datum_aangemaakt, opdrachten.werkelijke_oplever_datum
            FROM opdrachten, uitvoerend_personeel, users 
            WHERE goedgekeurd = 1 
            AND opdrachten.opdracht_id = uitvoerend_personeel.opdracht_id
            AND uitvoerend_personeel.user_id = users.id
            AND users.id = $login_id";
}else{
    $sql = "SELECT * FROM opdrachten WHERE goedgekeurd = 1;";
}

    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $output[$i]['opdracht-id'] = $row['opdracht_id'];
            $output[$i]['opdracht-naam'] = $row['opdracht_naam'];
            $output[$i]['datum-aangemaakt'] = $row['datum_aangemaakt'];
            $output[$i]['werkelijke-oplever-datum'] = $row['werkelijke_oplever_datum'];
            $i++;
        }
    }
    else {
        $output[0]['opdracht-naam'] = "0 results";
    }

echo json_encode($output);

?>

