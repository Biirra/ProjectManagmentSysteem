<?php
include("framework/framework.php");

if ($functie_bevoegdheid >= 2) {

    ?>

    <div class="col-md-2 side-bar-right">
        <table>
            <tr>
                <td>
                    <a href="<?php echo $config['beheerURL']; ?>?state=opdrachten-lijst">lijst bestaande
                        opdrachten</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo $config['beheerURL']; ?>?state=alle-bedrijven">Alle bekende bedrijven</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo $config['beheerURL']; ?>?state=alle-gebruikers">Alle gebruikers</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo $config['beheerURL']; ?>?state=register-gebruiker">Registreer nieuwe
                        gebruiker</a>
                </td>
            </tr>
        </table>
    </div>

    <?php

    switch ($_GET['state']) {
        // als beheerder een bestaande opdracht aanpassen
        case "opdracht-wijzigen":
            include("agenda_items/opdrachten/opdracht-wijzigen.php");
            break;
        // lijsten van allerlij dingen.
        case "opdrachten-lijst":
            include("lijsten/lijst-alle-opdrachten.php");
            break;
        case "alle-gebruikers":
            include('lijsten/lijst-alle-gebruikers.php');
            break;
        case "alle-bedrijven":
            include('lijsten/lijst-alle-bedrijven.php');
            break;
        case "register-gebruiker":
            include('register/register-user.php');
            break;
        case "opdrachten-op-tags":
            include("tags/opdrachten-op-tag.php");
            break;
        default:
            include('tags/alle-tags.php');
            break;
    }
}else{
    header("location: ".$config["homeURL"]);
}
?>
