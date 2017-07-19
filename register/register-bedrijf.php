<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once("login/session.php");
global $config;
$error = "";

// kijk of de gebruiker wel op deze pagina mag komen.
if ($functie_bevoegdheid > 1) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if ($_GET['bedrijf'] == "registreer") {
            /*
             * check of alle informatie over het bedrijf is ingevuld.
             */
            if (!empty($_POST['bedrijf_naam'])) {
                $bedrijfNaam = mysqli_real_escape_string($db, $_POST['bedrijf_naam']);
            } else {
                $error .= "<li>er is geen bedrijf's naam meegegeven</li>";
            }
            if (!empty($_POST['bedrijf_straat'])) {
                $straatnaam = mysqli_real_escape_string($db, $_POST['bedrijf_straat']);
            } else {
                $error .= "<li>er is geen straatnaam meegegeven</li>";
            }
            if (!empty($_POST['bedrijf_postcode'])) {
                $postcode = mysqli_real_escape_string($db, $_POST['bedrijf_postcode']);
            } else {
                $error .= "<li>er is geen postcode meegegeven</li>";
            }
            if (!empty($_POST['bedrijf_plaats'])) {
                $plaatsnaam = mysqli_real_escape_string($db, $_POST['bedrijf_plaats']);
            } else {
                $error .= "<li>er is geen plaatsnaam meegegeven</li>";
            }
            if (!empty($_POST['bedrijf-tel'])){
                $telefoon = mysqli_real_escape_string($db, $_POST['bedrijf-tel']);
            }else{
                $error .= "<li>er is geen telefoonnummer ingevuld</li>";
            }
            if(!empty($_POST['bedrijf_land'])){
                $land = mysqli_real_escape_string($db, $_POST['bedrijf_land']);
            }else{
                $error .= "<li>er is geen land ingevuld.</li>";
            }
            if(!empty($_POST['gevonden-door'])) {
                $gevonden = mysqli_real_escape_string($db, $_POST['gevonden-door']);
            }else{
                $gevonden = "";
            }
            if(!empty($_POST['bedrijf_bijzonderheden'])){
                $bijzonderheden = mysqli_real_escape_string($db, $_POST['bedrijf_bijzonderheden']);
            }else{
                $bijzonderheden = "";
            }

            // kijk of je de bedrijfsnaam kan opvragen van de database. 0 results is verwacht.
            $sql = "SELECT naam FROM bedrijf WHERE naam = '$bedrijfNaam';";
            $result = mysqli_query($db, $sql);
            if ($result->num_rows <= 0) {
                if (empty($error)) {
                    // stop informatie van het bedrijf in de database wanneer hij nog niet bestaat
                    $sql = "INSERT INTO bedrijf (naam, straat_huisnummer, postcode, plaats, land, telefoon, terecht_gekomen, notities) 
                            VALUES ('$bedrijfNaam', '$straatnaam', '$postcode', '$plaatsnaam', '$land', '$telefoon', '$gevonden', '$bijzonderheden' );";
                    mysqli_query($db, $sql);
                    // vraag de bedrijfsid van de pas aangemaakte bedrijf op.
                    $sql = "SELECT bedrijf_id FROM bedrijf WHERE naam = '$bedrijfNaam'";
                    $result = mysqli_query($db, $sql);
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $bedrijfsID = $row['bedrijf_id'];


                    // koppel de bedrijf aan de gebruiker.
                    $sql = "INSERT INTO bedrijf_personeel (user_id, bedrijf_id) VALUES ('$login_id', '$bedrijfsID' )";
                    mysqli_query($db, $sql);
                    header('location: '.$config['accountURL']."?state=bedrijf-koppelen&bedrijf=select");
                }
            } else {
                $error .= "De naam van het bedrijf dat je probeert te registreren bestaat al.";
            }
        } elseif ($_GET['bedrijf'] == "select") {
            // dit wordt uitgevoerd wanneer de gebruiker een bedrijf uit de drop down menu kiest.
            $bedrijfsID = $_POST['bedrijf-id'];

            // koppel de bedrijf aan de gebruiker.
            $sql = "INSERT INTO bedrijf_personeel (user_id, bedrijf_id) VALUES ('$login_id', '$bedrijfsID' )";
            mysqli_query($db, $sql);

        }
    }

    ?>



    <form action="" method="post">
        <?php
        if ($_GET['bedrijf'] == "registreer") { ?>
            <!-- hier komt de gebruiker terecht wanneer hij op de link("Mijn bedrijf staat hier niet tussen.") heeft geklikt -->
            <div align="center">
                <div class="form-container-outer" >
                    <div class="form-container-title" align="left"><b>Registreer jou bedrijf.</b>
                    </div>

                    <div class="form-container-inner">

                        <table class="table-container-fancy">
                            <tr class="table-title">
                                <td>
                                    <span>Bedrijfs gegevens</span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Bedrijfs Naam:
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="bedrijf_naam">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        straat + huisnr:
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="bedrijf_straat">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        postcode + plaats:
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="bedrijf_postcode" maxlength="6" style="width: 65px">
                                    <input type="text" name="bedrijf_plaats" style="width: 130px">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Land:
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="bedrijf_land">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Telefoonnummer:
                                    </label>
                                </td>
                                <td>
                                    <input type="tel" maxlength="10" name="bedrijf-tel">
                                </td>
                            </tr>
                        </table>

                        <table class="table-container-fancy">
                            <tr class="table-title">
                                <td>
                                    <span>Extra informatie</span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Hoe bent u bij Xtern-IT terecht gekomen ?</label>
                                </td>
                                <td>
                                    <select name="gevonden-door">
                                        <option selected disabled>Selecteer optie</option>
                                        <option>google</option>
                                        <option>mond tot mond</option>
                                        <option>anders</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="stayTop">
                                    <label>
                                        Bijzonderheden:
                                    </label>
                                </td>
                                <td>
                                    <textarea name="bedrijf_bijzonderheden" rows="3" cols="24"></textarea>
                                </td>
                            </tr>
                        </table>
                        <table class="table-container-noborder">
                            <tr>
                                <td>
                                    <!-- ga terug naar het keuze menuutje -->
                                    <a href="<?php echo $config['accountURL']; ?>?state=bedrijf-koppelen&bedrijf=select">terug</a>
                                </td>
                                <td>
                                    <input type="submit" value="Koppel bedrijf!">
                                </td>
                            </tr>
                        </table>



                        <div style="font-size:11px; color:#cc0000; margin-top:10px">
                            <ul>
                                <?php echo $error; ?>
                            </ul>
                        </div>

                    </div>

                </div>

            </div>
        <?php } ?>
    </form>
    <?php
} else {
    echo "je bent niet bevoegd hier te komen.";
}
?>