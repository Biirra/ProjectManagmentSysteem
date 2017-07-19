<?php
$error = '';
// vraag alle informatie op die te maken heeft met de opdracht. geselecteerd op id.
if (isset($_GET['id'])) {
    $bedrijfId = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT * FROM bedrijf WHERE bedrijf_id = $bedrijfId";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    // als er informatie bestaat van de opdracht zet ze dan in variabelen.
    if ($result->num_rows == 1) {
        $bedrijfNaam = $row['naam'];
        $bedrijfStraatHuisnummer = $row['straat_huisnummer'];
        $bedrijfPostcode = $row['postcode'];
        $bedrijfPlaats = $row['plaats'];
        $bedrijfLand = $row['land'];
        $bedrijfTelefoon = $row['telefoon'];
        $bedrijfNotities = $row['notities'];
    }
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['bedrijf-verwijderen'])){
        $_POST['bedrijf-verwijderen'] = "";
    }
    if(empty($_POST['ontkoppel-bedrijf'])){
        $_POST['ontkoppel-bedrijf'] = "";
    }
    if($_POST['bedrijf-verwijderen'] === "Verwijder bedrijf"){
        if(deleteBedrijf($db, $_GET['id'])){
            header("location: " . $config['homeURL'] . "?state=opdrachten-lijst");
        }else{
            $error = "Er is iets mis gegaan.\n<li>Het bedrijf heeft nog gekoppeld personeel of er staan nog opdrachten in het systeem</li>";
        }
    }
    if($_POST['ontkoppel-bedrijf'] === "ontkoppel"){
        if(bedrijfLoskoppelen($db,$_POST['bedrijfId'], $login_id)){
            header("location: " . $config['homeURL'] . "?state=opdrachten-lijst");
        }else{
            $error = "Er is iets mis gegaan. check of je nog ergens staat aangeschreven als contactpersoon voordat je jezelf ontkoppeld.";
        }
    }
}
function deleteBedrijf($db, $bedrijfId)
{
    $sql = "DELETE FROM bedrijf WHERE bedrijf_id = $bedrijfId;";
    $result = mysqli_query($db, $sql);
    return $result;
}

function bedrijfLoskoppelen($db, $bedrijfId, $login_id){
    $sql = "DELETE FROM bedrijf_personeel WHERE bedrijf_id = '$bedrijfId' AND user_id = '$login_id';";
    $result = mysqli_query($db, $sql);
    return $result;
}
function askMijnBedrijven($db, $login_id){
    $opties = "";
    $sql = "SELECT bedrijf.bedrijf_id, bedrijf.naam  
            FROM bedrijf, bedrijf_personeel 
            WHERE bedrijf.bedrijf_id = bedrijf_personeel.bedrijf_id 
            AND bedrijf_personeel.user_id = $login_id";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $bedrijfId = $row['bedrijf_id'];
            $bedrijfNaam = $row['naam'];
            $opties .= "<option value='$bedrijfId'>$bedrijfNaam</option>";
        }
    } else {
        echo "0 results";
    }
    return $opties;
}
function askBedrijven($db, $login_id)
{
    $opties = "";
    $sql = "SELECT bedrijf_id, naam  
            FROM bedrijf
            WHERE bedrijf_id NOT IN (SELECT bedrijf.bedrijf_id 
                                    FROM bedrijf, bedrijf_personeel 
                                    WHERE bedrijf.bedrijf_id = bedrijf_personeel.bedrijf_id 
                                    AND bedrijf_personeel.user_id = $login_id)";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $bedrijfId = $row['bedrijf_id'];
            $bedrijfNaam = $row['naam'];
            $opties .= "<option value='$bedrijfId'>$bedrijfNaam</option>";
        }
    } else {
        echo "0 results";
    }
    return $opties;
}
function getOpdrachtenVanBedrijfTable($db, $bedrijfId){
    global $config;
    $output = "";
    $sql = "SELECT opdrachten.opdracht_naam, opdrachten.opdracht_id FROM opdrachten WHERE bedrijf_id = $bedrijfId";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $opdrachtNaam = $row['opdracht_naam'];
            $opdrachtId = $row['opdracht_id'];
            $opdrachtUrl = $config['opdrachtURL']."?state=opdracht-info&id=".$opdrachtId;
            $output .= "<tr>
                            <td>
                                <a href='$opdrachtUrl'>$opdrachtNaam</a>
                            </td>
                        </tr>";
        }
    } else {
        $output = "<tr><td>geen opdrachten gevonden.</td></tr>";
    }

    return $output;
}
?>