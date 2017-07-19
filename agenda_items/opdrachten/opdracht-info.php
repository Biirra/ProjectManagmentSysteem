<?php
$opdrachtId = mysqli_escape_string($db, $_GET['id']);
include_once('agenda_items/opdrachten/opdracht-functions.php');
include_once('files_uploaden/upload.php');
?>
<div align="center">
    <div class="form-container-outer">
        <div class="form-container-title" align="left"><b>Informatie Opdracht </b></div>

        <div class="form-container-inner">
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
                            Id:
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
                        <?php echo $gekeurd; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Status:
                        </label>
                    </td>
                    <td>
                        <?php echo $status; ?>
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
                        <?php
                        echo getFactuur($db, $opdrachtId, $functie_bevoegdheid);
                        ?>
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
                        <a href="<?php echo $config['accountURL']; ?>?state=bedrijf-info&id=<?php echo $bedrijfId; ?>"><?php echo $bedrijfNaam ?></a>
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
                            <?php echo returnTableRowContactpersoon($db, $_GET['id']) ?>

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
                        <?php echo $opdrachtEmail; ?>
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
                        <?php echo reverseDate($deadline); ?>
                    </td>
                </tr>
                <tr>
                    <td class="stayTop">
                        <label>
                            Omschrijving opdracht:
                        </label>
                    </td>
                    <td>
                        <?php echo $omschrijvingOpdracht; ?>
                    </td>
                </tr>
                <tr>
                    <td class="stayTop">
                        <label>Aanleiding voor deze opdracht:</label>
                    </td>
                    <td>
                        <?php echo $aanleidingOpdracht; ?>
                    </td>
                </tr>
                <tr>
                    <td class="stayTop">
                        <label>
                            Omschrijving doelgroep:
                        </label>
                    </td>
                    <td>
                        <?php echo $omschrijvingDoelgroep; ?>
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
                <tr>
                    <td class="stayTop">
                        <label>File uploaden:</label>
                    </td>
                    <td>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="file" name="fileToUpload" id="fileToUpload">
                            <input type="submit" value="Upload file" name="submit">
                        </form>
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
                    <td class="stayTop">
                        <label>Tags:</label>
                    </td>
                    <td>
                        <?php echo getTags($db, $opdrachtId); ?>
                    </td>
                </tr>

            </table>
            <div>
                <a href="<?php echo $config['beheerURL']; ?>?state=opdracht-wijzigen&id=<?php echo $opdrachtId; ?>"
                   align="left">wijzigen</a>
            </div>
        </div>
    </div>
</div>