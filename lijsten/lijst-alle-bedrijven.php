<?php

/*
 * vult de lijst met alle opdrachten die er zijn.
 */
function fillBedrijfLijst($db)
{
    global $config;
    // vraag alle opdrachten op die in de database staan.
    $sql = "SELECT bedrijf_id, naam  FROM bedrijf";
    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        while ($row = $result->fetch_assoc()) {
            $bedrijfid = $row['bedrijf_id'];
            $bedrijfNaam = $row['naam'];
            $bedrijfInfoUrl = $config['accountURL']."?state=bedrijf-info&id=".$bedrijfid;
            $output .= "
<tr>
    <td>
        <a class='select-row-a' href='$bedrijfInfoUrl'> $bedrijfid </a>
    </td>
    <td>
        <a class='select-row-a' href='$bedrijfInfoUrl'>$bedrijfNaam</a>
    </td>
</tr>
";

        }
    } else {
        echo "geen geregistreerde bedrijven gevonden.";
    }
    return $output;
}

?>
<body>
<div align="center">
    <div class="form-container-outer filterable" >


        <div class="panel-title form-container-title" align="left"><b>Lijst bedrijven </b></div>
        <div class="pull-right">
            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>
                Filter
            </button>
        </div>

        <div class="form-container-inner">
            <table class="full-list-opdrachten tablebody">
                <thead>
                <tr class="filters">
                    <th class="table-header" style="min-width: 45px"><input type="text" class="form-control" placeholder="#" disabled></th>
                    <th class="table-header"><input type="text" class="form-control" placeholder="Naam:" disabled></th>
                </tr>
                </thead>
                <tbody>
                <?php echo fillBedrijfLijst($db); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>