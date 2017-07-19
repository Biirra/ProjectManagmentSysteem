<?php

include_once("includes/database.php");
global $config;
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form
    $mypassword = "";
    $myemail = mysqli_real_escape_string($db, $_POST['email']);
    if (!empty($_POST['password']))
        $mypassword = mysqli_real_escape_string($db, $_POST['password']);


    $sql = "SELECT id, password, geactiveerd FROM users WHERE email = '$myemail' LIMIT 1";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($row['geactiveerd'] != "0000-00-00 00:00:00") {
        // Hashing the password with its hash as the salt returns the same hash
        if (hash_equals($row['password'], crypt($mypassword, $row['password']))) {
            $_SESSION['login_user'] = $myemail;

            $sql = "UPDATE users SET pass_reset_token = '' WHERE email = '$myemail'";
            mysqli_query($db, $sql);

            header("location: " . $config['homeURL']);
            exit;
        } else {
            $error = "Uw email of wachtwoord is incorrect.";
        }
    }else{
        $verifyUrl = $config['homeURL']."?state=verivicatie-send&id=".$row['id'];
        $error = "Je account is nog niet geactiveerd.<a href='$verifyUrl'>geen mail ontvangen?</a>";
    }
}
?>



<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>Login</b></div>

        <div class="form-container-inner">

            <form action="" method="post">

                <table class="formtable">
                    <tr>
                        <td>
                            <label>Email :</label>
                        </td>
                        <td>
                            <input type="email" name="email" class="box"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Password :</label>
                        </td>
                        <td>
                            <input type="password" name="password" class="box"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" value=" Login! "/><br/>
                        </td>
                        <td>
                            <a href="<?php echo $config['homeURL']; ?>?state=wwvergeten">Wachtwoord vergeten?</a>
                        </td>
                    </tr>
                </table>

            </form>

            <div style="font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

        </div>

    </div>

</div>
