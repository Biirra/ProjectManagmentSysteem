<?php

/*
 * vult de lijst met alle opdrachten die er zijn.
 */
function fillGebruikerLijst($db)
{
    global $config;
    // vraag alle opdrachten op die in de database staan.
    $sql = "SELECT users.id, user_info.naam, user_info.achternaam, users.email, bevoegdheden.omschrijving
            FROM users , user_info, bevoegdheden 
            WHERE users.id = user_id AND users.bevoegdheid = bevoegdheden.bevoegdheid_type";
    $result = mysqli_query($db, $sql);
    $output = "";
    if ($result->num_rows > 0) {
        // zolang er opdrachten zijn verwerk de informatie.
        while ($row = $result->fetch_assoc()) {
            $userid = $row['id'];
            $userNaam = $row['naam'];
            $userAchternaam = $row['achternaam'];
            $userEmail = $row['email'];
            $userBevoegdheid = $row['omschrijving'];
            $accountInfoURL = $config['accountURL']."?state=account-info&id=".$userid;
            $output .= "
<tr>
    <td>
        <a class='select-row-a' href='$accountInfoURL'> $userid </a>
    </td>
    <td>
        <a class='select-row-a' href='$accountInfoURL'>$userNaam</a>
    </td>
    <td>
        <a class='select-row-a' href='$accountInfoURL'>$userAchternaam</a>
    </td>
    <td>
        <a class='select-row-a' href='$accountInfoURL'>$userEmail</a>
    </td>
    <td>
        <a class='select-row-a' href='$accountInfoURL'>$userBevoegdheid</a>
    </td>
</tr>
";

        }
    } else {
        echo "0 resultaten";
    }
    return $output;
}

?>
<body>
<div align="center">
    <div class="form-container-outer filterable"  >

        <div class="panel-title form-container-title" align="left"><b>Lijst gebruikers </b></div>
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
                    <th class="table-header"><input type="text" class="form-control" placeholder="Achternaam:" disabled></th>
                    <th class="table-header"><input type="text" class="form-control" placeholder="Email:" disabled></th>
                    <th class="table-header"><input style="width: 100px" type="text" class="form-control" placeholder="Bevoegdheid:" disabled></th>
                </tr>
                </thead>
                <tbody>
                <?php echo fillGebruikerLijst($db); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>