<?php

/*
 * vult de lijst met alle opdrachten die er zijn.
 */

function fillOpdrachtenLijst($db)
{
    global $config;
    // vraag alle opdrachten op die in de database staan.
    $sql = "SELECT opdrachten.opdracht_id, opdrachten.opdracht_naam, opdrachten.opleverings_datum, opdrachten.goedgekeurd,opdracht_status.status 
            FROM opdrachten, opdracht_status 
            WHERE opdrachten.status = opdracht_status.id";
    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        while ($row = $result->fetch_assoc()) {
            $opdrachtId = $row['opdracht_id'];
            $opdrachtNaam = $row['opdracht_naam'];
            $opdrachtDeadline = reverseDate($row['opleverings_datum']);
            if ($row['goedgekeurd'] == 0) {
                $opdrachtAccepted = "Nee";
            } elseif ($row['goedgekeurd'] == 1) {
                $opdrachtAccepted = "Ja";
            } else {
                $opdrachtAccepted = "Onbekend";
            }
            $opdrachtStatus = $row['status'];

            $output .= "
<tr>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'> $opdrachtId </a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>Opdracht-$opdrachtNaam</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>$opdrachtDeadline</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>$opdrachtAccepted</a>
    </td>
    <td>
        <a class='select-row-a' href='".$config['opdrachtURL']."?state=opdracht-info&id=$opdrachtId'>$opdrachtStatus</a>
    </td>
</tr>
";

        }
    } else {
        echo "0 results";
    }
    return $output;
}

?>

<div align="center">
    <div class="form-container-outer filterable" >



            <div class="panel-title form-container-title" align="left"><b>Lijst opdrachten </b></div>
            <div class="pull-right">
                <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>
                    Filter
                </button>
            </div>


        <div class="form-container-inner">
            <table class="full-list-opdrachten tablebody">
                <thead>
                    <tr class="filters">
                        <th class="table-header"><input style="width: 45px" type="text" class="form-control" placeholder="#" disabled></th>
                        <th class="table-header"><input type="text" class="form-control" placeholder="Naam:" disabled></th>
                        <th class="table-header"><input type="text" class="form-control" placeholder="Deadline:" disabled></th>
                        <th class="table-header"><input  style="width: 95px" type="text" class="form-control" placeholder="Gekeurd:" disabled></th>
                        <th class="table-header"><input type="text" class="form-control" placeholder="Status:" disabled></th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo fillOpdrachtenLijst($db); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
