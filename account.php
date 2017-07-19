<?php
include("framework/framework.php");
if ($functie_bevoegdheid != 0) {
    ?>

    <div class="col-md-2 side-bar-right">
        <table>
            <?php if ($functie_bevoegdheid >= 2) { ?>
            <tr>
                <td>
                    <a href="<?php echo $config['accountURL']; ?>?state=bedrijf-koppelen&bedrijf=select">bedrijf
                        koppelen</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo $config['accountURL']; ?>?state=bedrijf-ontkoppelen&bedrijf=ontkoppel">ontkoppel
                        een
                        bedrijf</a>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td>
                    <a href="<?php echo $config['accountURL']; ?>?state=mijn-opdrachten">Mijn Opdrachten</a>
                </td>
            </tr>
        </table>
    </div>

    <?php

    switch ($_GET['state']) {
        case "bedrijf-toevoegen":
            include("register/register-bedrijf.php");
            break;
        case "account-info":
            include('account/account-info.php');
            break;
        case "bedrijf-info":
            include("bedrijf/bedrijf-info.php");
            break;
        case "bedrijf-ontkoppelen":
            include("bedrijf/bedrijf-ontkoppelen.php");
            break;
        case "bedrijf-koppelen":
            include('bedrijf/bedrijf-koppelen.php');
            break;
        case "mijn-opdrachten":
            include("lijsten/lijst-gebruiker-opdrachten.php");
            break;
        default:
            include('lijsten/lijst-gebruiker-opdrachten.php');
            break;
    }
}else{
    header("location: ". $config["homeURL"]);
}
?>
</body>
