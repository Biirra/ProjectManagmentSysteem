<?php
include_once ('agenda_items/opdrachten/opdracht-functions.php');
?>

<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>personeel verwijderen</b></div>
        <div class="form-container-inner">
            <form action="" method="post">
                <table>
                    <tr>
                        <td>
                            <labe>
                                Personeel:
                            </labe>
                        </td>
                        <td>
                            <select name="personeelid">
                                <option selected disabled>Selecteer contactpersoon</option>
                                <?php echo returnGekoppeldePersoneel($db, $_GET['id']) ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo $config['beheerURL']; ?>?state=opdracht-wijzigen&id=<?php echo $opdrachtId; ?>">terug</a>
                        </td>
                        <td>
                            <input type="submit" value="verwijder personeel" name="personeel-verwijderen">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>