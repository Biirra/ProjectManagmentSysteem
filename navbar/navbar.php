<?php

?>

<div class="col-md-12 navbar-header">
    <table>
        <tr>
            <td>
                <div class="navbar-item">
                    <a href="<?php echo $config['homeURL']; ?>">Home</a>
                </div>
            </td>
            <td>
                <div class="navbar-item">
                    <a href="<?php echo $config['accountURL']; ?>">Account</a>
                </div>
            </td>
            <?php if($functie_bevoegdheid > 2){
                ?>
            <td>
                <div class="navbar-item">
                    <a href="<?php echo $config['beheerURL']; ?>">Beheer</a>
                </div>
            </td>
            <?php } ?>
        </tr>
    </table>
</div>
<script src="navbar/navbar-js.js"></script>


