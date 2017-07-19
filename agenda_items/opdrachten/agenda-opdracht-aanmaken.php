<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once("login/session.php");
include_once('agenda_items/opdrachten/opdracht-functions.php');
$error = "";
$opdrachtNaam = '';
$omschrijvingOpdracht = "";
$aanleidingOpdracht = "";
$omschrijvingDoelgroep = "";
$contactpersoonBedrijf = 0;
function askMijnBedrijven($db, $login_id)
{
    $opties = "";
    $sql = "SELECT * 
            FROM bedrijf, bedrijf_personeel, users 
            WHERE bedrijf.bedrijf_id = bedrijf_personeel.bedrijf_id 
            AND bedrijf_personeel.user_id = users.id 
            AND users.id = $login_id";
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

/*
 * vraagt alle mogelijke contactpersonen op die bij het bedrijf horen van de persoon die is ingelogd.
 * wordt alleen gebruikt in opdracht aanmaken.
 */
function askMogelijkeContactpersonen($db, $bedrijf_id)
{
    /*
     * doet het nog niet omdat sub query meer dan 1 resultaat terug kan geven. dit breekt de query.
     * oplossing voor zoeken.
     * idee:    maak de opdracht aan in twee stappen. eerst de opdracht uploaden en later een contactpersoon toevoegen.
     */
    $sql = "SELECT users.id, user_info.naam AS usernaam, user_info.achternaam, bedrijf.naam AS bedrijfnaam
            FROM users, bedrijf_personeel, user_info, bedrijf
            WHERE users.id = bedrijf_personeel.user_id 
            AND bedrijf.bedrijf_id = bedrijf_personeel.bedrijf_id
            AND users.id = user_info.user_id
            AND bedrijf_personeel.bedrijf_id = '$bedrijf_id'";
    $result = mysqli_query($db, $sql);
    return returnOptionsContactpersonen($result);
}


if ($functie_bevoegdheid > 1) {

    $sql = "SELECT * FROM users, bedrijf_personeel WHERE users.id = bedrijf_personeel.user_id AND users.id = '$login_id';";

    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        /*
         * genereerd automatisch een naam voor de opdracht. deze naam is niet verplicht.
         * Dit werkt nog niet na behoren. Moet nog na gekeken worden.
         */

        $sql = "SELECT MAX(opdracht_id) as opdracht_id FROM opdrachten";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $year = date("Y");

        $opdrachtnummer = intval($row['opdracht_id'] + 1);
        $tempOpdrachtnummer = $opdrachtnummer . "";
        while (strlen($tempOpdrachtnummer) < 4) {
            $tempOpdrachtnummer = "0" . $tempOpdrachtnummer;
        }
        $opdrachtNaam = $year . $tempOpdrachtnummer;


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $opdrachtNaam = mysqli_real_escape_string($db, $opdrachtNaam); // de naam van de opdracht.  aanpassen!

            if (!empty($_POST['bedrijf_id'])) {
                $bedrijfId = mysqli_real_escape_string($db, $_POST['bedrijf_id']);
            }
            if (!empty($_POST['oplever-datum'])) {
                $opleverDatum = mysqli_real_escape_string($db, $_POST['oplever-datum']);
            } else {
                $error .= "<li>er is geen aanvangs datum ingevuld</li>";
            }
            if (!empty($_POST['aanvang-datum'])) {
                $aanvangDatum = mysqli_real_escape_string($db, $_POST['aanvang-datum']);
            } else {
                $error .= "<li>er is geen oplever datum ingevuld</li>";
            }
            if (!empty($_POST['omschrijving-opdracht'])) {
                $omschrijvingOpdracht = mysqli_real_escape_string($db, $_POST['omschrijving-opdracht']);
            }
            if (!empty($_POST['aanleiding-opdracht'])) {
                $aanleidingOpdracht = mysqli_real_escape_string($db, $_POST['aanleiding-opdracht']);
            }
            if (!empty($_POST['omschrijving-doelgroep'])) {
                $omschrijvingDoelgroep = mysqli_real_escape_string($db, $_POST['omschrijving-doelgroep']);
            }
            if (!empty($_POST['tags'])){
                $tags = mysqli_real_escape_string($db, $_POST['tags']);
                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tags))  // zorgt ervoor dat er geen rare informatie meekomt wat dingen kan verpesten.
                {
                    $error .= "<li>De tags moeten geschijden worden met een spatie. Vreemde tekens zijn niet tegestaan.</li>";
                }else{
                    $allTags = explode(" ", $tags);
                }
            }


            $nonProfit = mysqli_real_escape_string($db, $_POST['non-profit']);

            $aangemaaktDatum = date('Y-m-d');

            if (empty($error)) {
                // wanneer er geen errors zijn stop alle informatie in de database.
                $sql = "INSERT INTO opdrachten (opdracht_naam, bedrijf_id, opleverings_datum, werkelijke_oplever_datum, datum_aangemaakt, omschrijving_opdracht, aanleiding_opdracht, omschrijving_doelgroep, non_profit) 
                        VALUES ('$opdrachtNaam', '$bedrijfId', '$opleverDatum', '$aanvangDatum', '$aangemaaktDatum', '$omschrijvingOpdracht', '$aanleidingOpdracht', '$omschrijvingDoelgroep', '$nonProfit');";
                mysqli_query($db, $sql);

                // krijg de id van de opdracht die net is aangemaakt.
                $sqlOpdrachtId = "SELECT opdracht_id FROM opdrachten WHERE opdracht_naam = '$opdrachtNaam'";
                $result = mysqli_query($db, $sqlOpdrachtId);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $opdrachtId = $row['opdracht_id'];
                if(count($allTags) > 0){
                    for($i = 0; $i < count($allTags); $i++){
                        if($allTags[$i] != ""){
                            $sql = "INSERT INTO tags (opdracht_id, tag)
                              VALUES ('$opdrachtId','$allTags[$i]')";
                            mysqli_query($db, $sql);
                        }
                    }
                }
                header("location: " . $config['opdrachtURL'] . "?state=contactpersoon-toevoegen&id=" . $opdrachtId);
            }
        }
    } else {
        $koppelbedrijf = $config['accountURL'];
        $error .= "<li>Je hebt nog geen bedrijf aan jou account gekoppeld. <br> <a href=\"$koppelbedrijf?state=bedrijf-koppelen&bedrijf=select\"> bedrijf koppelen </a></li>";
    }
    ?>

    <div align="center">
        <div class="form-container-outer" >
            <div class="form-container-title" align="left"><b>Opdracht aanmaken</b></div>
            <div class="form-container-inner">

                <form action="" method="post">

                    <table class="table-container-fancy">
                        <tr class="table-title">
                            <td>
                                <span>Opdracht naam</span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Opdracht naam:
                                </label>
                            </td>
                            <td>
                                <label><?php echo $opdrachtNaam; ?></label>
                            </td>
                        </tr>
                    </table>

                    <table class="table-container-fancy">
                        <tr class="table-title">
                            <td>
                                <span>Bedrijf's gegevens</span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Wordt gemaakt voor:
                                </label>
                            </td>
                            <td>
                                <select name="bedrijf_id">
                                    <?php echo askMijnBedrijven($db, $login_id) ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    is de opdracht non-profit:
                                </label>
                            </td>
                            <td>
                                <input type="radio" name="non-profit" value="1"><label>Ja</label>
                                <input type="radio" name="non-profit" value="0" checked><label>Nee</label>
                            </td>
                        </tr>
                    </table>

                    <table class="table-container-fancy">
                        <tr class="table-title">
                            <td>
                                <span>Opdracht informatie</span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Gewenste aanvangdatum:
                                </label>
                            </td>
                            <td>
                                <input type="date" name="aanvang-datum">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Gewenste opleverdatum:
                                </label>
                            </td>
                            <td>
                                <input type="date" name="oplever-datum">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Omschrijving opdracht/product:
                                </label>
                            </td>
                            <td>
                                <textarea name="omschrijving-opdracht" rows="3"
                                          cols="24"><?php echo $omschrijvingOpdracht; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Aanleiding van de opdracht <br>vanuit de organisatie/het bedrijf:
                                </label>
                            </td>
                            <td>
                                <textarea name="aanleiding-opdracht" rows="3"
                                          cols="24"><?php echo $aanleidingOpdracht; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Omschrijving doelgroep:
                                </label>
                            </td>
                            <td>
                                <textarea name="omschrijving-doelgroep" rows="1"
                                          cols="24"><?php echo $omschrijvingDoelgroep; ?></textarea>
                            </td>
                        </tr>
                    </table>

                    <table class="table-container-fancy">
                        <tr class="table-title">
                            <td>
                                <span>Tags</span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <label>Voeg tags toe:</label>
                            </td>
                            <td>
                                <input type="text" name="tags" placeholder="voorbeeld1 voorbeeld2 voorbeeld3">
                            </td>
                        </tr>
                    </table>

                    <input type="submit" value=" Opdracht aanmaken! "/><br/>
                </form>

                <div style="font-size:11px; color:#cc0000; margin-top:10px">
                    <ul>
                        <?php echo $error; ?>
                    </ul>
                </div>

            </div>

        </div>

    </div>

    <?php
} else {
    echo "je bent niet bevoegd hier te komen.";
}
?>