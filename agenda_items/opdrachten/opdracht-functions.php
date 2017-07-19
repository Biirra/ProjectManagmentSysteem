<?php
global $config;
//include_once('../../includes/database.php');
// vraag alle informatie op die te maken heeft met de opdracht. geselecteerd op id.
if (isset($_GET['id'])) {
    $opdrachtId = "";
    $opdrachtNaam = "";
    $datumAangemaakt = "";
    $deadline = "";
    $status = "";
    $bedrijfNaam = "";
    $omschrijvingOpdracht = "";
    $aanleidingOpdracht = "";
    $omschrijvingDoelgroep = "";
    $opdrachtEmail = "";
    $sql = "SELECT opdrachten.opdracht_id, 
                opdrachten.opdracht_naam, 
                opdrachten.email_xternit,
                opdrachten.datum_aangemaakt, 
                opdrachten.opleverings_datum, 
                opdrachten.goedgekeurd, 
                opdrachten.non_profit, 
                opdracht_status.status,
                 opdrachten.omschrijving_opdracht,
                 opdrachten.aanleiding_opdracht,
                 opdrachten.omschrijving_doelgroep,
                bedrijf.naam,
                 opdrachten.bedrijf_id,
                 bedrijf.bedrijf_id
        FROM opdrachten, opdracht_status, bedrijf 
        WHERE opdracht_id = " . $_GET['id'] . " 
        AND bedrijf.bedrijf_id = opdrachten.bedrijf_id 
        AND opdracht_status.id = opdrachten.status 
        LIMIT 1;";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    // als er informatie bestaat van de opdracht zet ze dan in variabelen.
    if ($result->num_rows == 1) {
        $opdrachtId = $row['opdracht_id'];
        $opdrachtNaam = $row['opdracht_naam'];
        $datumAangemaakt = $row['datum_aangemaakt'];
        $deadline = $row['opleverings_datum'];
        $status = $row['status'];
        $bedrijfNaam = $row['naam'];
        $omschrijvingOpdracht = $row['omschrijving_opdracht'];
        $aanleidingOpdracht = $row['aanleiding_opdracht'];
        $omschrijvingDoelgroep = $row['omschrijving_doelgroep'];
        $bedrijfId = $row['bedrijf_id'];
        $opdrachtEmail = $row['email_xternit'];

        // verwissel de 0 met nee en de 1 met ja. elk ander getal dat uit de database komt wordt onbekend.
        if ($row['goedgekeurd'] == 1) {
            $gekeurd = "Ja";
        } else if ($row['goedgekeurd'] == 0) {
            $gekeurd = "Nee";
        }

        if ($row['non_profit'] == 1) {
            $nonProfit = "Ja";
        } else if ($row['non_profit'] == 0) {
            $nonProfit = "Nee";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['contactpersoon-koppelen'])) {
        setContactpersoon($db, $_GET['id'], $_POST['contactpersoonid']);
    }
    if (!empty($_POST['personeel-koppelen'])) {
        setUitvoerendPersoneel($db, $_GET['id'], $_POST['personeelid']);
    }
    if (!empty($_POST['contactpersoon-verwijderen'])) {
        deleteContactpersoon($db, $_GET['id'], $_POST['contactpersoonid']);
    }
    if (!empty($_POST['personeel-verwijderen'])) {
        deleteUitvoerendPersoneel($db, $_GET['id'], $_POST['personeelid']);
    }
}


function returnGekoppeldeContactpersoon($db, $opdrachtId)
{
    $output = "";
    $sql = "SELECT user_info.naam, user_info.achternaam, users.id
            FROM user_info, contactpersoon, bedrijf_personeel, users
            WHERE contactpersoon.opdracht_id = $opdrachtId
            AND contactpersoon.bedrijf_personeel_id = bedrijf_personeel.id
            AND bedrijf_personeel.user_id = users.id
            AND users.id = user_info.user_id";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $userNaam = $row['naam'];
            $userAchternaam = $row['achternaam'];
            $contactPersoonid = $row['id'];
            $output .= "<option value='$contactPersoonid'>$userNaam $userAchternaam</option>";
        }
    }
    return $output;
}

/*
 * geeft een variable met contactpersoon informatie. verpakt in een options tag.
 * te gebruiken in een select tag.
 */
function returnOptionsContactpersonen($db, $opdrachtId)
{
    $opties = "";
    $sql ="SELECT users.id, bedrijf.naam AS bedrijfsnaam, user_info.naam AS usernaam, user_info.achternaam
            FROM bedrijf_personeel, bedrijf, users, user_info
            WHERE bedrijf_personeel.user_id = users.id
            AND bedrijf_personeel.bedrijf_id = bedrijf.bedrijf_id
            AND users.id = user_info.user_id
            AND bedrijf_personeel.bedrijf_id = (SELECT opdrachten.bedrijf_id 
                                                  FROM opdrachten
                                                  WHERE opdrachten.opdracht_id = $opdrachtId)
            AND bedrijf_personeel.user_id NOT IN (SELECT bedrijf_personeel.user_id
                                                  FROM bedrijf_personeel, contactpersoon
                                                  WHERE bedrijf_personeel.id = contactpersoon.bedrijf_personeel_id
                                                  AND contactpersoon.opdracht_id = $opdrachtId)";

    /*$sql = "SELECT bedrijf.naam AS bedrijfsnaam, users.id, user_info.naam AS usernaam, user_info.achternaam
            FROM bedrijf, users, user_info, bedrijf_personeel, opdrachten
            WHERE users.id = user_info.user_id
            AND bedrijf.bedrijf_id = bedrijf_personeel.bedrijf_id
            AND bedrijf_personeel.user_id != users.id
            AND opdrachten.bedrijf_id = bedrijf.bedrijf_id
            AND opdrachten.opdracht_id = $opdrachtId";*/
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $contactUsernaam = $row['usernaam'];
            $contactUserAchternaam = $row['achternaam'];
            $contactBedrijf = $row['bedrijfsnaam'];
            $contactUserId = $row['id'];
            $opties .= "<option value='$contactUserId'>$contactBedrijf: $contactUsernaam $contactUserAchternaam</option>";
        }
    } else {
        echo "0 results";
    }

    return $opties;
}

function returnTableRowContactpersoon($db, $opdrachtId)
{
    global $config;
    $output = "";
    $sql = "SELECT user_info.naam, user_info.achternaam, users.id
            FROM user_info, contactpersoon, bedrijf_personeel, users
            WHERE contactpersoon.opdracht_id = $opdrachtId
            AND contactpersoon.bedrijf_personeel_id = bedrijf_personeel.id
            AND bedrijf_personeel.user_id = users.id
            AND users.id = user_info.user_id";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $userNaam = $row['naam'];
            $userAchternaam = $row['achternaam'];
            $userid = $row['id'];
            $accountInfoURL = $config['accountURL'] . "?state=account-info&id=" . $userid;
            $output .= "<tr>
                            <td>
                                <a href='$accountInfoURL'>$userNaam $userAchternaam</a>
                            </td>
                        </tr>";
        }
    } else {
        $output = "Er is nog geen contactpersoon gekoppeld.";
    }
    return $output;
}
// verwijder een contactpersson van een opdracht
function deleteContactpersoon($db, $opdrachtId, $contactpersoonId)
{
    $sql = "SELECT id FROM bedrijf_personeel WHERE user_id = '$contactpersoonId'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $bedrijf_personeel_id = $row['id'];

    $sql = "DELETE FROM contactpersoon
            WHERE opdracht_id = $opdrachtId
            AND bedrijf_personeel_id = $bedrijf_personeel_id";
    mysqli_query($db, $sql);
}
// voeg een contactpersoon toe aan een opdracht.
function setContactpersoon($db, $opdrachtId, $contactpersoonId)
{
    // link contactpersoon met bedrijf_personeel
    $sql = "SELECT id FROM bedrijf_personeel WHERE user_id = '$contactpersoonId'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $bedrijf_personeel_id = $row['id'];

    $sql = "INSERT INTO contactpersoon (opdracht_id, bedrijf_personeel_id)
                        VALUES ('$opdrachtId', '$bedrijf_personeel_id')";
    mysqli_query($db, $sql);
}
// krijg alle mogelijke statussen wat een opdracht kan hebben.
function getPossibleStatus($db, $opdrachtId)
{
    $output = "";
    $sql = "SELECT status 
            FROM opdrachten 
            WHERE opdracht_id = $opdrachtId";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $currentStatus = $row['status'];

    $sql = "SELECT opdracht_status.id, opdracht_status.status
            FROM opdracht_status";
    $result = mysqli_query($db, $sql);
    while ($row = $result->fetch_assoc()) {
        $statusId = $row['id'];
        $statusOmschrijving = $row['status'];
        if ($currentStatus == $statusId) {
            $output .= "<option value='$statusId' selected>$statusOmschrijving</option>";
        } else {
            $output .= "<option value='$statusId' >$statusOmschrijving</option>";
        }
    }
    return $output;
}
// voeg uitvoeren personeel toe aan opdracht.
function setUitvoerendPersoneel($db, $opdrachtId, $personeelId)
{
    $sql = "INSERT INTO uitvoerend_personeel (user_id, opdracht_id)
                        VALUES ('$personeelId', '$opdrachtId')";
    mysqli_query($db, $sql);
}
// verwijder uitvoeren personeel van een opdracht.
function deleteUitvoerendPersoneel($db, $opdrachtId, $personeelId)
{
    $sql = "DELETE FROM uitvoerend_personeel 
            WHERE user_id = $personeelId 
            AND opdracht_id = $opdrachtId";
    mysqli_query($db, $sql);
}

function returnGekoppeldePersoneel($db, $opdrachtId)
{
    $output = "";
    $sql = "SELECT user_info.naam, user_info.achternaam, users.id
            FROM user_info, users, uitvoerend_personeel
            WHERE user_info.user_id = users.id
            AND uitvoerend_personeel.user_id = users.id
            AND uitvoerend_personeel.opdracht_id = $opdrachtId";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $userNaam = $row['naam'];
            $userAchternaam = $row['achternaam'];
            $personeelId = $row['id'];
            $output .= "<option value='$personeelId'>$userNaam $userAchternaam</option>";
        }
    }
    return $output;
}

function returnOptionsPersoneel($db, $opdrachtId)
{
    $opties = "";

    $sql = "SELECT users.id, user_info.naam, user_info.achternaam
            FROM user_info, users
            WHERE users.id = user_info.user_id
            AND users.bevoegdheid != 2
            AND users.geactiveerd != '0000-00-00 00:00:00'
            AND users.id NOT IN (SELECT uitvoerend_personeel.user_id 
                                  FROM uitvoerend_personeel
                                  WHERE uitvoerend_personeel.opdracht_id = $opdrachtId)";

    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $personeelUsernaam = $row['naam'];
            $personeelUserAchternaam = $row['achternaam'];
            $personeelUserId = $row['id'];
            $opties .= "<option value='$personeelUserId'>$personeelUsernaam $personeelUserAchternaam</option>";
        }
    } else {
        echo "0 results";
    }

    return $opties;
}

function returnTableRowPersoneel($db, $opdrachtId)
{
    global $config;
    $output = "";
    $sql = "SELECT user_info.naam, user_info.achternaam, users.id
            FROM user_info, users, uitvoerend_personeel
            WHERE user_info.user_id = users.id
            AND uitvoerend_personeel.user_id = users.id
            AND uitvoerend_personeel.opdracht_id = $opdrachtId";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $userNaam = $row['naam'];
            $userAchternaam = $row['achternaam'];
            $userid = $row['id'];
            $accountInfoURL = $config['accountURL'] . "?state=account-info&id=" . $userid;
            $output .= "<tr>
                            <td>
                                <a href='$accountInfoURL'>$userNaam $userAchternaam</a>
                            </td>
                        </tr>";
        }
    } else {
        $output = "Er is nog geen personeel gekoppeld.";
    }
    return $output;
}

function returnTableRowGerelateerdeDocumenten($db, $opdrachtId, $login_id, $functieBevoegdheid)
{
    $output = "";
    $sql = "SELECT file_naam, file_path, user_id
            FROM opdracht_document, opdrachten 
            WHERE opdrachten.opdracht_id = opdracht_document.opdracht_id
            AND opdrachten.opdracht_id = $opdrachtId";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $fileNaam = $row['file_naam'];
            $filepath = $row['file_path'];
            $output .= "
                        <tr >
                            <td>
                                <a href='$filepath' download>$fileNaam</a>
                            </td>";
            if (($login_id == $row['user_id'] && $_GET['state'] == "opdracht-info") || ($_GET['state'] == "opdracht-info" && $functieBevoegdheid >= 3)) {
                $output .= "<td align='center'>
                                <form action='agenda_items/opdrachten/delete-file.php' method='post'>
                                <input type='hidden' name='lastHistory' value='" . $_SERVER['REQUEST_URI'] . "'>
                                <input type='hidden' name='deleteFile' value='$filepath'>
                                <button type='submit' name='submit' style='margin-bottom: -15px'><i class='fa fa-trash-o' aria-hidden='true'></i></button>
                                </form>
                            </td>";
            }
            $output .= "</tr>";
        }
    } else {
        $output = "<tr><td>Geen gerelateerde documenten gevonden.</td></tr>";
    }
    return $output;
}

function getFactuur($db, $opdrachtId, $functie_bevoegdheid)
{
    $sql = "SELECT factuur_file_naam, factuur_file_url FROM opdrachten WHERE opdracht_id = $opdrachtId";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $filenaam = $row['factuur_file_naam'];
    $filePath = $row['factuur_file_url'];
    if ($filenaam != "" || $filePath != "") {
        if ($functie_bevoegdheid >= 2) {
            return "<a href='$filePath' download>$filenaam</a>";
        } else {
            return $filenaam;
        }
    } else {
        return "Geen factuur gevonden.";
    }
}

function getTags($db, $opdrachtId)
{
    global $config;
    $output = "";
    $sql = "SELECT DISTINCT tag FROM tags WHERE opdracht_id = $opdrachtId ORDER BY tag ASC";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tag = $row['tag'];
            $url = $config['beheerURL'] . "?state=opdrachten-op-tags&tag=" . $tag;
            $output .= "<a href='$url'>$tag</a>, ";
        }
    } else {
        $output = "Geen tags gevonden";
    }
    return $output;
}

function getOpdrachtByTag($db, $tag)
{
    global $config;
    $sql = "SELECT opdrachten.opdracht_id, opdrachten.opdracht_naam, opdrachten.opleverings_datum, opdrachten.goedgekeurd,opdracht_status.status 
            FROM opdrachten, opdracht_status, tags 
            WHERE opdrachten.status = opdracht_status.id
            AND tags.opdracht_id = opdrachten.opdracht_id
            AND tag = '$tag'";
    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        while ($row = $result->fetch_assoc()) {
            $opdrachtId = $row['opdracht_id'];
            $opdrachtNaam = $row['opdracht_naam'];
            $opdrachtDeadline = reverseDate($row['opleverings_datum']);
            if ($row['goedgekeurd'] == 0) {
                $opdrachtAccepted = "Nee";
            } elseif ($row['goedgekeurd'] == 1) {
                $opdrachtAccepted = "Ja";
            } else {
                $opdrachtAccepted = "Onbekend";
            }
            $opdrachtStatus = $row['status'];

            $output .= "
<tr>
    <td>
        <a class='select-row-a' href='" . $config['opdrachtURL'] . "?state=opdracht-info&id=$opdrachtId'> $opdrachtId </a>
    </td>
    <td>
        <a class='select-row-a' href='" . $config['opdrachtURL'] . "?state=opdracht-info&id=$opdrachtId'>Opdracht-$opdrachtNaam</a>
    </td>
    <td>
        <a class='select-row-a' href='" . $config['opdrachtURL'] . "?state=opdracht-info&id=$opdrachtId'>$opdrachtDeadline</a>
    </td>
    <td>
        <a class='select-row-a' href='" . $config['opdrachtURL'] . "?state=opdracht-info&id=$opdrachtId'>$opdrachtAccepted</a>
    </td>
    <td>
        <a class='select-row-a' href='" . $config['opdrachtURL'] . "?state=opdracht-info&id=$opdrachtId'>$opdrachtStatus</a>
    </td>
</tr>
";
        }
    } else {
        echo "0 results";
    }
    return $output;
}

function getAllTags($db)
{
    global $config;
    $output = "";
    $sql = "SELECT DISTINCT tag FROM tags ORDER BY tag ASC";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tag = $row['tag'];
            if ($tag != "") {
                $url = $config['beheerURL'] . "?state=opdrachten-op-tags&tag=" . $tag;
                $output .= "<a href='$url'>$tag</a>, ";
            }
        }
    } else {
        $output = "Geen tags gevonden";
    }
    return $output;
}