<?php

include_once('agenda_items/opdrachten/opdracht-functions.php');
include_once('files_uploaden/upload-factuur.php');
$error = '';
if (isset($_POST['deleteOpdracht'])) {
    $sql = "DELETE FROM uitvoerend_personeel WHERE opdracht_id = '$opdrachtId' AND user_id = '$login_id'";
    mysqli_query($db, $sql);
    $sql = "DELETE FROM opdrachten WHERE opdracht_id = '$opdrachtId'";
    mysqli_query($db, $sql);
    header("location: " . $config['homeURL'] . "?state=opdrachten-lijst");
}
if (isset($_POST['saveChanges'])) {

    if (!empty($_POST['opdracht-status'])) {
        $opdrachtStatus = mysqli_real_escape_string($db, $_POST['opdracht-status']);
    }
    if (!empty($_POST['email-xtern-it'])) {
        $opdrachtEmail = mysqli_real_escape_string($db, $_POST['email-xtern-it']);
    }
    if (!empty($_POST['new-deadline'])) {
        $opdrachtDeadline = mysqli_real_escape_string($db, $_POST['new-deadline']);
    }
    if (!empty($_POST['omschrijving-opdracht'])) {
        $opdrachtOmschrijving = mysqli_real_escape_string($db, $_POST['omschrijving-opdracht']);
    }
    if (!empty($_POST['aanleiding-opdracht'])) {
        $opdrachtAanleiding = mysqli_real_escape_string($db, $_POST['aanleiding-opdracht']);
    }
    if (!empty($_POST['omschrijving-doelgroep'])) {
        $opdrachtOmschrijvingDoelgroep = mysqli_real_escape_string($db, $_POST['omschrijving-doelgroep']);
    }
    if ($_POST['gekeurd'] <= 1 && $_POST['gekeurd'] >= 0) {
        $keuren = mysqli_real_escape_string($db, $_POST['gekeurd']);
    } else {
        $error = "<li>Waar denken we mee bezig te zijn ?</li>";
    }
    if($error == "") {
        $sql = "UPDATE opdrachten 
            SET status='$opdrachtStatus', 
                email_xternit='$opdrachtEmail', 
                opleverings_datum='$opdrachtDeadline',
                omschrijving_opdracht='$opdrachtOmschrijving',
                aanleiding_opdracht='$opdrachtAanleiding',
                omschrijving_doelgroep='$opdrachtOmschrijvingDoelgroep',
                goedgekeurd = '$keuren'
            WHERE opdracht_id = '$opdrachtId'";
        mysqli_query($db, $sql);
        header("location: " . $config['opdrachtURL'] . "?state=opdracht-info&id=$opdrachtId");
    }
}


?>
<div align="center">
    <div class="form-container-outer">
        <div class="form-container-title" align="left"><b>Opdracht wijzigen</b></div>

        <div class="form-container-inner">
            <form action="" method="post" enctype="multipart/form-data" id="">

                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Opdracht informatie</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>
                            <label>
                                Opdracht naam:
                            </label>
                        </th>
                        <th>
                            <label>
                                <?php echo $opdrachtNaam; ?>
                            </label>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                id:
                            </label>
                        </td>
                        <td>
                            <?php echo $opdrachtId; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Gekeurd:
                            </label>
                        </td>
                        <td>
                            <select name="gekeurd">
                                <?php if ($gekeurd == "Ja") { ?>
                                    <option value="0">
                                        Nee
                                    </option>
                                    <option value="1" selected>
                                        Ja
                                    </option>
                                <?php } else if ($gekeurd) { ?>
                                    <option value="0" selected>
                                        Nee
                                    </option>
                                    <option value="1">
                                        Ja
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Status:
                            </label>
                        </td>
                        <td>
                            <select name="opdracht-status">
                                <?php echo getPossibleStatus($db, $opdrachtId); ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Betalings gegevens</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Non-profit:
                            </label>
                        </td>
                        <td>
                            <?php echo $nonProfit; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Factuur:
                            </label>
                        </td>
                        <td>
                            <?php if ($nonProfit == "Nee") {
                                echo getFactuur($db, $opdrachtId, $functie_bevoegdheid);
                                ?>
                                <input type="file" name="factuurToUpload" id="factuurToUpload">
                            <?php } else { ?>
                                <p>Opdracht is non-profit.</p>
                            <?php } ?>
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
                                Bedrijf's naam:
                            </label>
                        </td>
                        <td>
                            <?php echo $bedrijfNaam ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="stayTop">
                            <label>
                                Contactpersoon:
                            </label>
                        </td>
                        <td>
                            <table>
                                <?php echo returnTableRowContactpersoon($db, $_GET['id']); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $config['opdrachtURL']; ?>?state=contactpersoon-toevoegen&id=<?php echo $_GET['id']; ?>">
                                            toevoegen
                                        </a>
                                        /
                                        <a href="<?php echo $config['opdrachtURL']; ?>?state=contactpersoon-verwijderen&id=<?php echo $_GET['id']; ?>">
                                            verwijder
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Xtern-IT</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                emailadress Xtern-IT:
                            </label>
                        </td>
                        <td>
                            <input type="email" name="email-xtern-it" value="<?php echo $opdrachtEmail; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="stayTop">
                            <label>
                                Uitvoerend Personeel:
                            </label>
                        </td>
                        <td>
                            <table>
                                <?php echo returnTableRowPersoneel($db, $_GET['id']); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $config['opdrachtURL']; ?>?state=personeel-toevoegen&id=<?php echo $_GET['id']; ?>">
                                            toevoegen
                                        </a>
                                        /
                                        <a href="<?php echo $config['opdrachtURL']; ?>?state=personeel-verwijderen&id=<?php echo $_GET['id']; ?>">verwijder</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Opdracht gegevens</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                datum aangemaakt:
                            </label>
                        </td>
                        <td>
                            <?php echo reverseDate($datumAangemaakt); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Deadline:
                            </label>
                        </td>
                        <td>
                            <input type="date" name="new-deadline" value="<?php echo $deadline; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="stayTop">
                            <label>
                                Omschrijving opdracht:
                            </label>
                        </td>
                        <td>
                            <textarea name="omschrijving-opdracht" rows="3"
                                      cols="24"><?php echo $omschrijvingOpdracht; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="stayTop">
                            <label>Aanleiding voor deze opdracht:</label>
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
                            <span>File's</span>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="stayTop">
                            <label>
                                Gerelateerde documenten:
                            </label>
                        </td>
                        <td>
                            <table>
                                <?php echo returnTableRowGerelateerdeDocumenten($db, $opdrachtId, $login_id, $functie_bevoegdheid); ?>
                            </table>
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
                            <label>Tags:</label>
                        </td>
                        <td>
                            <?php echo getTags($db, $opdrachtId); ?>
                        </td>
                    </tr>
                </table>

                <table class="table-container-fancy">
                    <tr class="table-title">
                        <td>
                            <span>Knoppen</span>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo $config['opdrachtURL']; ?>?state=opdracht-info&id=<?php echo $opdrachtId; ?>">terug</a>
                        </td>
                        <td>
                            <input type="submit" name="saveChanges" value="Veranderingen opslaan" >
                        </td>
                        <td>
                            <input type="submit" name="deleteOpdracht" value="Delete deze opdracht"
                                   onclick="confirmeDelete()">
                            <script language="javascript">
                                function confirmeDelete() {
                                    if (!confirm("WAARSCHUWING!\nJe bent van plan om deze opdracht volledig te verwijderen.\nWeet u zeker dat u door wil gaan ?"))
                                        history.go(-1);
                                    return " "
                                }
                            </script>
                        </td>
                    </tr>
                </table>
            </form>
            <div style="font-size:11px; color:#cc0000; margin-top:10px">
                <ul>
                    <?php echo $error; ?>
                </ul>
            </div>
        </div>
    </div>
</div>