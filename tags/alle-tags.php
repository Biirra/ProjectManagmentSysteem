<?php
include_once ("agenda_items/opdrachten/opdracht-functions.php");
?>

<div align="center">
    <div class="form-container-outer" >

        <div class="panel-title form-container-title" align="left"><b>Lijst alle tags </b></div>
            <?php echo getAllTags($db); ?>
        <div class="form-container-inner">

        </div>
    </div>
</div>