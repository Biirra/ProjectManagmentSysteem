<?php
include ('bedrijf/bedrijf_functies.php');
if ($functie_bevoegdheid > 1) {
    ?>

    <body bgcolor="#FFFFFF">
<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>Ontkoppel jou bedrijf.</b></div>

        <div class="form-container-inner">

            <form action="" method="post">
                <select name="bedrijfId">
                    <?php echo askMijnBedrijven($db, $login_id); ?>
                </select>
                <input type="submit" value="ontkoppel" name="ontkoppel-bedrijf"/><br/>
            </form>
            <div style="font-size:11px; color:#cc0000; margin-top:10px">
                <ul>
                    <?php echo $error; ?>
                </ul>
            </div>
        </div>

    </div>

</div>
    <?php
} else {
    echo "je bent niet bevoegd hier te komen.";
}