<?php
include_once ("agenda_items/opdrachten/opdracht-functions.php");

?>

<div align="center">
    <div class="form-container-outer filterable" >

        <div class="panel-title form-container-title" align="left" ><b>Opdrachten lijst gefilterd op <?php echo $_GET['tag'];?>  </b></div>
        <div class="pull-right">
            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>
                Filter
            </button>
        </div>


        <div class="form-container-inner">
            <table class="full-list-opdrachten tablebody">
                <thead>
                <tr class="filters">
                    <th><input style="width: 45px" type="text" class="form-control" placeholder="#" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Naam:" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Deadline:" disabled></th>
                    <th><input  style="width: 95px" type="text" class="form-control" placeholder="Goedgekeurd:" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Status:" disabled></th>
                </tr>
                </thead>
                <tbody>
                <?php echo getOpdrachtByTag($db, $_GET['tag']); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
