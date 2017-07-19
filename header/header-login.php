<?php
?>
<div class="" style="float: right">

    <table>
        <?php
        if(empty($_SESSION['login_user'])) { ?>
            <tr>
                <td>
                    <label>Hallo guest!</label>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <a href="<?php echo $config['homeURL']; ?>?state=login"> log in </a>
                            </td>
                            <td>
                                <span style="margin-right: 5px; margin-left: 5px"> / </span>
                            </td>
                            <td>
                                <a href="<?php echo $config['homeURL']; ?>?state=register"> register </a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        <?php }else{ ?>
            <tr>
                <td>
                    <label>Hallo <a href="<?php echo $config['accountURL']; ?>?state=account-info&id=<?php echo $login_id; ?>"><?php echo $userNaam; ?></a></label>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="login/logout.php">Sign Out</a>
                </td>
            </tr>
        <?php } ?>
    </table>


</div>
