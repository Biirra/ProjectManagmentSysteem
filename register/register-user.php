<?php
include_once("includes/functions.php");


//de error variable.
$error = "";

//persoonlijke informatie.
$naam = "";
$achternaam = "";

$straatnaam = "";
$huisNummer = "";
$postcode = "";
$plaatsnaam = "";

$telefoonNummerHuis = "";
$telefoonNummerMobiel = "";

$geboortedatum = "";

$useremail = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // vul alle gegevens in variablen. geef error terug wanneer het niet is ingevuld.
    if (!empty($_POST['naam'])) {
        $naam = mysqli_real_escape_string($db, $_POST['naam']);
    } else {
        $error .= "<li>Je hebt geen naam ingevuld.</li>";
    }
    if (!empty($_POST['achternaam'])) {
        $achternaam = mysqli_real_escape_string($db, $_POST['achternaam']);
    } else {
        $error .= "<li>Je hebt geen achternaam ingevuld.</li>";
    }

    if (!empty($_POST['telefoon-nummer-huis']) || !empty($_POST['telefoon-nummer-mobiel'])) {
        $telefoonNummerHuis = mysqli_real_escape_string($db, $_POST['telefoon-nummer-huis']);
        $telefoonNummerMobiel = mysqli_real_escape_string($db, $_POST['telefoon-nummer-mobiel']);
    } else {
        $error .= "<li>Je hebt geen telefoon nummer ingevuld.</li>";
    }


    if (!empty($_POST['geboortedatum'])) {
        $geboortedatum = mysqli_real_escape_string($db, $_POST['geboortedatum']);
    } else {
        $error .= "<li>Je hebt geen geboortedatum ingevuld.</li>";
    }


    if (!empty($_POST['email'])) {
        $useremail = mysqli_real_escape_string($db, $_POST['email']);
    } else {
        $error .= "<li>Je hebt geen email ingevuld.</li>";
    }
    // vergelijk de passwords. extra beveiliging dat de gebruiker niet zijn wachtwoord opslat met een spelfout erin.
    if (!empty($_POST['password']) && !empty($_POST['password-nogmaals'])) {
        if (strlen($_POST['password']) > 5) {
            if ($_POST['password'] == $_POST['password-nogmaals']) {
                $password = mysqli_real_escape_string($db, $_POST['password']);
            } else {
                $error .= "<li> Je wachtwoorden komen niet overeen. </li>";
            }
        } else {
            $error .= "<li> Je wachtwoord is te kort. gebruik tenminste 6 karakters.</li>";
        }
    } else {
        $error .= "<li>Je hebt geen wachtwoord ingevuld.</li>";
    }


    if (!empty($_POST['bevoegdheid'])) {
        // extra check zodat mensen niet simpel via de browser gegevens kan vervangen.
        // werdt wel heel makkelijk om stiekem een admin account aan te maken.
        if ($functie_bevoegdheid <= 2 && $_POST['bevoegdheid'] > 2) {
            $error .= "<li>Leuk geprobeerd.</li>";
        } else {
            $bevoegdheid = mysqli_real_escape_string($db, $_POST['bevoegdheid']);
        }

        if ($_POST['bevoegdheid'] != 2) {
            if (!empty($_POST['straatnaam'])) {
                $straatnaam = mysqli_real_escape_string($db, $_POST['straatnaam']);
            } else {
                $error .= "<li>Je hebt geen straatnaam ingevuld.</li>";
            }
            if (!empty($_POST['huisnummer'])) {
                $huisNummer = mysqli_real_escape_string($db, $_POST['huisnummer']);
            } else {
                $error .= "<li>Je hebt geen huisnummer ingevuld.</li>";
            }
            if (!empty($_POST['postcode'])) {
                $postcode = mysqli_real_escape_string($db, $_POST['postcode']);
            } else {
                $error .= "<li>Je hebt geen postcode ingevuld.</li>";
            }
            if (!empty($_POST['plaatsnaam'])) {
                $plaatsnaam = mysqli_real_escape_string($db, $_POST['plaatsnaam']);
            } else {
                $error .= "<li>Je hebt geen plaatsnaam ingevuld.</li>";
            }
            if (!empty($_POST['geboortedatum'])) {
                $geboortedatum = mysqli_real_escape_string($db, $_POST['geboortedatum']);
            } else {
                $error .= "<li>Je hebt geen geboortedatum ingevuld.</li>";
            }
        }else{
            $straatnaam = "";
            $huisNummer = "";
            $postcode = "";
            $plaatsnaam = "";
            $geboortedatum = date('Y-m-d');
        }
    }


    // kijkt of de email al in de database bestaat.
    $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$useremail';");

    $passEncrypted = encryptPass($password); // encrypt de password. hierdoor staat het op die random manier in de database.

    if ($result->num_rows < 1) { // check of de email al in de database bekend is.
        if (empty($error)) {
            // als er geen errors zijn sla de email en password op in de database.
            mysqli_query($db, "INSERT INTO users (email, password, bevoegdheid) VALUES ('$useremail', '$passEncrypted', '$bevoegdheid');");

            //id opvragen om de tabellen te linken.
            $result = mysqli_query($db, "SELECT id FROM users WHERE email = '$useremail';");
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $userid = $row['id'];

            // verdere informatie invullen in de database.
            $sql = "INSERT INTO user_info (user_id, naam, achternaam, straat_naam, huis_nummer, postcode, plaats_naam, telefoon_huis, telefoon_mobiel, geboorte_datum)
                VALUES ('$userid', '$naam', '$achternaam', '$straatnaam', '$huisNummer', '$postcode', '$plaatsnaam', '$telefoonNummerHuis', '$telefoonNummerMobiel', '$geboortedatum')";
            mysqli_query($db, $sql);
            $verificationUrl = $config['homeURL'] . "?state=verivicatie-send&id=$userid";
            header("Location: " . $verificationUrl);
        } else {
            $error .= "<li>Nog niet alles is ingevuld.</li>";
        }
    } else {
        $error = "<li>De email die je gekozen hebt bestaat al.</li>";
    }
}


?>

<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>Register</b></div>

        <div class="form-container-inner">

            <form action="" method="post">
                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Persoons gegevens</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Naam:
                            </label>
                        </td>
                        <td>
                            <input type="text" name="naam" class="box" value="<?php echo $naam; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Achternaam:
                            </label>
                        </td>
                        <td>
                            <input type="text" name="achternaam" class="box" value="<?php echo $achternaam; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Ik ben :
                            </label>
                        </td>
                        <td>
                            <select style="max-width:198px;" name="bevoegdheid" id="functie-registratie">
                                <option value="1"> een student/personeel.</option>
                                <option value="2"> een vertegenwoordiger voor mijn bedrijf.</option>
                                <?php if ($functie_bevoegdheid >= 3) { ?>
                                    <option value="3"> beheer</option>
                                <?php }
                                if ($functie_bevoegdheid == 4) { ?>
                                    <option value="4"> admin</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr id="input-geboortedatum">
                        <td>
                            <label>
                                geboortedatum:
                            </label>
                        </td>
                        <td>
                            <input  type="date" name="geboortedatum" class="box" value="<?php echo $geboortedatum ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Telefoonnummer:
                            </label>
                        </td>
                        <td>
                            <input type="text" name="telefoon-nummer-huis" class="box" maxlength="10"
                                   value="<?php echo $telefoonNummerHuis ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                *Tweede telefoonnummer:<br/>
                                <small style="font-size: smaller">* optioneel</small>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="telefoon-nummer-mobiel" class="box" maxlength="10"
                                   value="<?php echo $telefoonNummerMobiel ?>">
                        </td>
                    </tr>
                </table>
                <table id="adres-gegevens-input" class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Adres gegevens</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Straatnaam + Huisnummer:
                            </label>
                        </td>
                        <td>
                            <input type="text" name="straatnaam" style="width: 145px" class="box"
                                   value="<?php echo $straatnaam; ?>">
                            <input style="width: 50px" type="text" name="huisnummer" class="box"
                                   value="<?php echo $huisNummer ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Postcode + Plaats:</label>
                        </td>
                        <td>
                            <input style="width: 65px" type="text" name="postcode" class="box" maxlength="6"
                                   value="<?php echo $postcode ?>">
                            <input style="width: 130px" type="text" name="plaatsnaam" class="box"
                                   value="<?php echo $plaatsnaam ?>">
                        </td>
                    </tr>
                </table>
                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Account gegevens</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Email :</label>
                        </td>
                        <td>
                            <input type="email" name="email" class="box" value="<?php echo $useremail ?>"/>
                        </td>
                    </tr>
                    <!-- input registreren passwoord -->
                    <tr>
                        <td>
                            <label>Wachtwoord :</label>
                        </td>
                        <td>
                            <input type="password" name="password" class="box"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Wachtwoord nogmaals:</label>
                        </td>
                        <td>
                            <input type="password" name="password-nogmaals" class="box">
                        </td>
                    </tr>
                </table>

                <table class="formtable">


                    <!-- input registreren email -->


                </table>
                <input type="submit" value=" Register! "/><br/>


            </form>
            <!-- error gedeelte. hier wordt de error weergeven in de html. -->
            <div style="font-size:11px; color:#cc0000; margin-top:10px">
                <ul>
                    <?php echo $error; ?>
                </ul>
            </div>

        </div>
    </div>
</div>