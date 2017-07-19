<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once("includes/database.php");
include_once("login/session.php");
include("bedrijf/bedrijf_functies.php");
/*
 * wanneer iemand uit een bedrijf een account aanmaakt kan hij hier zijn bedrijf aan zijn account koppelen.
 * als zijn bedrijf er niet tussen staat kan hij hem later nog toevoegen.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bedrijfsid = mysqli_real_escape_string($db, $_POST['bedrijf-id']);

    $sql = "INSERT INTO bedrijf_personeel (user_id, bedrijf_id) VALUES ($login_id, $bedrijfsid);";
    mysqli_query($db, $sql);
}

if ($functie_bevoegdheid > 1) {
    ?>

    <body bgcolor="#FFFFFF">
<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>Koppel jou bedrijf.</b></div>

        <div class="form-container-inner">
            <form action="" method="post">
                <table class="register-bedrijf">
                    <tr>
                        <td>
                            <label>Selecteer jou bedrijf:</label>
                        </td>
                        <td>
                            <select name="bedrijf-id" size="">
                                <?php echo askBedrijven($db, $login_id); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!-- wanneer het bedrijf niet in het keuzemenuutje staat. -->
                            <a href="<?php echo $config['accountURL']; ?>?state=bedrijf-toevoegen&bedrijf=registreer">Mijn
                                bedrijf staat hier niet tussen.</a>
                        </td>
                        <td>
                            <input type="submit" value="Koppel bedrijf!">
                        </td>

                    </tr>
                </table>
            </form>
        </div>

    </div>

</div>
    <?php
} else {
    echo "je bent niet bevoegd hier te komen.";
}
?>