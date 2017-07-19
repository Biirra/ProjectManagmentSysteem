<?php
/*
 * vult de lijst met alle opdrachten die er zijn.
 */

function fillOpdrachtenLijstPersoneel($db, $login_id)
{
    global $config;
    // vraag alle opdrachten op die waar de gebruiker uitvoerend personeel is.
    $sql = "SELECT opdrachten.opdracht_id, opdrachten.opdracht_naam, opdrachten.opleverings_datum, opdrachten.goedgekeurd
            FROM opdrachten, uitvoerend_personeel, users 
            WHERE goedgekeurd = 1 
            AND opdrachten.opdracht_id = uitvoerend_personeel.opdracht_id
            AND uitvoerend_personeel.user_id = users.id
            AND users.id = $login_id";
    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        while ($row = $result->fetch_assoc()) {
            $opdrachtId = $row['opdracht_id'];
            $opdrachtNaam = $row['opdracht_naam'];
            $opdrachtDeadline = $row['opleverings_datum'];
            if ($row['goedgekeurd'] == 0) {
                $opdrachtAccepted = "Nee";
            } elseif ($row['goedgekeurd'] == 1) {
                $opdrachtAccepted = "Ja";
            } else {
                $opdrachtAccepted = "Onbekend";
            }

            $output .= "
<tr>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'> $opdrachtId </a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>Opdracht-$opdrachtNaam</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>".reverseDate($opdrachtDeadline)."</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>$opdrachtAccepted</a>
    </td>
</tr>
";

        }
    } else {
        echo "<tr>
<td></td>
<td>
Geen resultaten gevonden.
</td>
<td></td>
<td></td>
</tr>";
    }
    return $output;
}

function fillOpdrachtenLijstContactpersoon($db, $login_id)
{
    global $config;
    // vraag alle opdrachten op die waar de gebruiker uitvoerend personeel is.
    $sql = "SELECT opdrachten.opdracht_id, opdrachten.opdracht_naam, opdrachten.opleverings_datum, opdrachten.goedgekeurd
            FROM opdrachten, contactpersoon, users, bedrijf_personeel
            WHERE goedgekeurd = 1 
            AND opdrachten.opdracht_id = contactpersoon.opdracht_id
            AND contactpersoon.bedrijf_personeel_id = bedrijf_personeel.id
            AND bedrijf_personeel.user_id = users.id
            AND users.id = $login_id";
    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        while ($row = $result->fetch_assoc()) {
            $opdrachtId = $row['opdracht_id'];
            $opdrachtNaam = $row['opdracht_naam'];
            $opdrachtDeadline = $row['opleverings_datum'];
            if ($row['goedgekeurd'] == 0) {
                $opdrachtAccepted = "Nee";
            } elseif ($row['goedgekeurd'] == 1) {
                $opdrachtAccepted = "Ja";
            } else {
                $opdrachtAccepted = "Onbekend";
            }

            $output .= "
<tr>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'> $opdrachtId </a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>Opdracht-$opdrachtNaam</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>".reverseDate($opdrachtDeadline)."</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>$opdrachtAccepted</a>
    </td>
</tr>
";

        }
    } else {
        echo "<tr>
<td></td>
<td>
Geen resultaten gevonden.
</td>
<td></td>
<td></td>
</tr>";
    }
    return $output;
}

?>
<div align="center">
    <div class="form-container-outer filterable" >

        <div class="panel-title form-container-title" align="left"><b>Lijst opdrachten</b></div>
        <div class="pull-right">
            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>
                Filter
            </button>
        </div>
        <?php if ($functie_bevoegdheid != 2) { ?>
        <div class="form-container-inner">
            <label>voor deze opdrachten bent u personeel:</label>
            <table class="full-list-opdrachten tablebody">
                <thead>
                <tr class="filters">
                    <th class="table-header"><input style="width: 45px" type="text" class="form-control" placeholder="#" disabled></th>
                    <th class="table-header"><input type="text" class="form-control" placeholder="Naam:" disabled></th>
                    <th class="table-header"><input type="text" class="form-control" placeholder="Deadline:" disabled></th>
                    <th class="table-header"><input style="width: 100px" type="text" class="form-control" placeholder="Gekeurd:" disabled></th>
                </tr>
                </thead>
                <tbody>
                <?php echo fillOpdrachtenLijstPersoneel($db, $login_id); ?>
                </tbody>
            </table>
        </div>
        <?php }
        if ($functie_bevoegdheid >= 2) { ?>
        <div style="margin:30px;">
            <label>voor deze opdrachten bent u contactpersoon:</label>
            <table class="full-list-opdrachten tablebody">
                <thead>
                <tr class="filters">
                    <th class="table-header"><input style="width: 45px" type="text" class="form-control" placeholder="#" disabled></th>
                    <th class="table-header"><input type="text" class="form-control" placeholder="Naam:" disabled></th>
                    <th class="table-header"><input type="text" class="form-control" placeholder="Deadline:" disabled></th>
                    <th class="table-header"><input style="width: 100px" type="text" class="form-control" placeholder="Gekeurd:" disabled></th>
                </tr>
                </thead>
                <tbody>
                <?php echo fillOpdrachtenLijstContactpersoon($db, $login_id); ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</div>
