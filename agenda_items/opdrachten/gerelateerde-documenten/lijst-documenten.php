<?php
$opdrachtId = mysqli_escape_string($db, $_GET['id']);
function returnTableRowAllGerelateerdeDocumenten($db, $opdrachtId, $login_id){
    $output = "";
    $sql = "SELECT file_naam, file_path, user_id
            FROM opdracht_document, opdrachten 
            WHERE opdrachten.opdracht_id = opdracht_document.opdracht_id
            AND opdrachten.opdracht_id = $opdrachtId";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $fileNaam = $row['file_naam'];
            $filepath = $row['file_path'];
            $userId = $row['user_id'];
            $output .= "<tr>";

            if($userId == $login_id){
                $output .= "<td>
                                <input type='checkbox'>
                            </td>";
            }else{
                $output .= "<td></td>";
            }
            $output .= "<td>
                                <a href='$filepath' download>$fileNaam</a>
                            </td>
                        </tr>";
        }
    }else{
        $output = "<tr><td>Geen gerelateerde documenten gevonden.</td></tr>";
    }
    return $output;
}
?>
<div align="center">
    <div class="form-container-outer" align="left">
        <div class="form-container-title"><b> </b></div>

        <div class="form-container-inner">
            <table class="full-list-opdrachten">
                <thead>
                <tr>
                    <th style="min-width: 45px">id:</th>
                    <th>naam:</th>
                </tr>
                </thead>
                <tbody>
                <?php echo returnTableRowAllGerelateerdeDocumenten($db, $opdrachtId, $login_id); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
