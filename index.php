<?php
include_once ("framework/framework.php");
if($functie_bevoegdheid != 0) {
    ?>
    <div class="col-md-2 side-bar-right">
        <?php if($functie_bevoegdheid >= 2) { ?>
        <table>
            <tr>
                <td>
                    <a href="<?php echo $config['opdrachtURL']; ?>?state=opdracht-aanmaken">Maak nieuwe opdracht aan</a>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
    <?php
}
switch ($_GET['state']) {
    case "login":
        include("login/login.php");
        break;
    case "register";
        include("register/register-user.php");
        break;
    case "verivicatie-send":
        include("account-verificatie/verificatie-mail-send.php");
        break;
    case "verificatie-succes":
        include ("account-verificatie/verificatie-succes.php");
        break;

    //wachtwoord vergeten
    case "wwvergeten":
        include ("wachtwoord_vergeten/forgot_password.php");
        break;
    case "wwverander":
        include ("wachtwoord_vergeten/change.php");
        break;
    case "wwreset":
        include ("wachtwoord_vergeten/reset_password.php");
        break;
    case "resetpw":
        include ("wachtwoord_vergeten/reset.php");
        break;
    default:
        if($functie_bevoegdheid == 0){
            include("login/login.php");
        }else{
            include("calendar/calendar.php");
            include("calendar/opdracht_selectie/selectie-scherm.php");
        }
        break;
}

?>
</body>
</html>
