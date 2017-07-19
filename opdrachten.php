<?php
include ("framework/framework.php");
if ($functie_bevoegdheid != 0) {
    ?>

    <div class="col-md-2 side-bar-right">
        <table>
            <tr>
                <td>
                    <a href="<?php echo $config['opdrachtURL']; ?>?state=opdracht-aanmaken">Maak nieuwe opdracht aan</a>
                </td>
            </tr>
        </table>
    </div>
    <?php
    switch ($_GET['state']) {
        case "opdracht-aanmaken":
            include("agenda_items/opdrachten/agenda-opdracht-aanmaken.php");
            break;
        case "opdracht-info":
            include("agenda_items/opdrachten/opdracht-info.php");
            break;
        case "contactpersoon-toevoegen":
            include('agenda_items/opdrachten/contactpersoon-toevoegen.php');
            break;
        case "contactpersoon-verwijderen":
            include("agenda_items/opdrachten/contactpersoon-verwijderen.php");
            break;
        case "personeel-toevoegen":
            include('agenda_items/opdrachten/personeel-toevoegen.php');
            break;
        case "personeel-verwijderen":
            include("agenda_items/opdrachten/personeel-verwijderen.php");
            break;
        case "document":
            include('agenda_items/opdrachten/gerelateerde-documenten/lijst-documenten.php');
            break;

    }
}else{
    header("location: ".$config["homeURL"]);
}
?>
</body>
