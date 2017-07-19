<?php
include('bedrijf/bedrijf_functies.php');


?>
<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>Informatie bedrijf </b></div>

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
                            Bedrijf naam:
                        </label>
                    </td>
                    <td>
                        <?php echo $bedrijfNaam; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Straat + huisnr:
                        </label>
                    </td>
                    <td>
                        <?php echo $bedrijfStraatHuisnummer; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Postcode:
                        </label>
                    </td>
                    <td>
                        <?php echo $bedrijfPostcode; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            Plaats:
                        </label>
                    </td>
                    <td>
                        <?php echo $bedrijfPlaats; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Land:</label>
                    </td>
                    <td>
                        <?php echo $bedrijfLand; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Telefoon:</label>
                    </td>
                    <td>
                        <?php echo $bedrijfTelefoon; ?>
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
                    <td class="stayTop">
                        <label>
                            Bijzonderheden:
                        </label>
                    </td>
                    <td>
                        <?php echo $bedrijfNotities; ?>
                    </td>
                </tr>

            </table>
            <table class="table-container-fancy">
                <tr class="table-title">
                    <td>
                        <span>Bedrijf opdrachten</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="stayTop">
                        <label>
                            Alle opdrachten van dit bedrijf:
                        </label>
                    </td>
                    <td>
                        <table>
                            <?php echo getOpdrachtenVanBedrijfTable($db, $_GET['id']); ?>
                        </table>
                    </td>
                </tr>
            </table>
            <?php if($functie_bevoegdheid > 2){?>
            <form method="post" action="" >
                <input type="submit" value="Verwijder bedrijf" name="bedrijf-verwijderen">
            </form>
            <?php } ?>
            <div style="font-size:11px; color:#cc0000; margin-top:10px">
                <ul>
                    <?php echo $error; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

