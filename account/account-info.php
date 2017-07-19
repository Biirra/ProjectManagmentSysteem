<?php
$sql = "SELECT  users.id, 
                users.email,
                users.bevoegdheid,
                user_info.naam,
                user_info.achternaam,
                user_info.straat_naam,
                user_info.huis_nummer,
                user_info.postcode,
                user_info.plaats_naam,
                user_info.geboorte_datum,
                user_info.telefoon_huis,
                user_info.telefoon_mobiel,
                bevoegdheden.omschrijving
        FROM users, user_info, bevoegdheden
        WHERE users.bevoegdheid = bevoegdheden.bevoegdheid_type
        AND users.id = user_info.user_id
        AND users.id = ".$_GET['id']." 
        LIMIT 1";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);



// als er informatie bestaat van de opdracht zet ze dan in variabelen.
if($result->num_rows == 1){
    $user_id = $row['id'];
    $user_naam = $row['naam'];
    $user_achternaam = $row['achternaam'];
    $user_straatnaam = $row['straat_naam'];
    $user_huisnummer = $row['huis_nummer'];
    $user_postcode = $row['postcode'];
    $user_plaats = $row['plaats_naam'];
    $user_email = $row['email'];
    $user_geboortedatum = $row['geboorte_datum'];
    $user_bevoegdheid = $row['omschrijving'];
    $user_tel_huis = $row['telefoon_huis'];
    $user_tel_mob = $row['telefoon_mobiel'];

}
function getMijnOpdrachten($db, $login_id){
    global $config;
    $output = "";
    $sql = "SELECT opdrachten.opdracht_id, opdrachten.opdracht_naam
            FROM opdrachten, uitvoerend_personeel, users 
            WHERE goedgekeurd = 1 
            AND opdrachten.opdracht_id = uitvoerend_personeel.opdracht_id
            AND uitvoerend_personeel.user_id = users.id
            AND users.id = $login_id";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $opdrachtNaam = $row['opdracht_naam'];
            $opdrachtUrl = $config['opdrachtURL']."?state=opdracht-info&id=".$row["opdracht_id"];
            $output .= "<tr>
                            <td>
                                <a href='$opdrachtUrl'>$opdrachtNaam</a>
                            </td>
                        </tr>";
        }
    } else {
        $output = "Geen opdrachten gevonden.";
    }
    return $output;
}

function getMijnBedrijven($db){
    global $config;
    $output = "";
    $sql = "SELECT bedrijf.naam, bedrijf.bedrijf_id FROM bedrijf, bedrijf_personeel WHERE bedrijf.bedrijf_id = bedrijf_personeel.bedrijf_id AND bedrijf_personeel.user_id = ".$_GET['id'];
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $bedrijfnaam = $row['naam'];
            $bedrijfUrl = $config['accountURL']."?state=bedrijf-info&id=".$row["bedrijf_id"];
            $output .= "<tr>
                            <td>
                                <a href='$bedrijfUrl'>$bedrijfnaam</a>
                            </td>
                        </tr>";
        }
    } else {
        $output = "Geen bedrijven gevonden.";
    }
    return $output;
}

?>
<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b> Account informatie </b></div>

        <div class="form-container-inner">

            <table class="table-container-fancy">
                <tr class="table-title">
                    <td>
                        <span>Gebruikers informatie</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th>
                        <label>
                            id:
                        </label>
                    </th>
                    <th>
                        <?php echo $user_id; ?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <label>
                            Naam:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_naam; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Achternaam:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_achternaam; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Geboorte datum:
                        </label>
                    </td>
                    <td>
                        <?php echo reverseDate($user_geboortedatum); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Fucntie:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_bevoegdheid; ?>
                    </td>
                </tr>
            </table>

            <?php if($functie_bevoegdheid != 2){?>
            <table class="table-container-fancy">
                <tr class="table-title">
                    <td>
                        <span>Adres gegevens</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label>
                            straatnaam + huisnr:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_straatnaam ." ". $user_huisnummer; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            postcode:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_postcode; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            plaatsnaam:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_plaats; ?>
                    </td>
                </tr>
            </table>
            <?php } ?>
            <table class="table-container-fancy">
                <tr class="table-title">
                    <td>
                        <span>Bereikbaar</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Telefoonnummer:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_tel_huis; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Tweede telefoonnummer:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_tel_mob; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Email:
                        </label>
                    </td>
                    <td>
                        <?php echo $user_email; ?>
                    </td>
                </tr>
            </table>

            <table class="table-container-fancy">
                <tr class="table-title">
                    <td>
                        <span>Opdracten</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="stayTop">
                        <label>
                            Gekoppelde Opdrachten:
                        </label>
                    </td>
                    <td>
                        <table>
                            <?php echo getMijnOpdrachten($db, $login_id); ?>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="table-container-fancy">
                <tr class="table-title">
                    <td>
                        <span>Bedrijven</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="stayTop">
                        <label>
                            Gekoppelde bedrijven:
                        </label>
                    </td>
                    <td>
                        <table>
                            <?php echo getMijnBedrijven($db); ?>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>