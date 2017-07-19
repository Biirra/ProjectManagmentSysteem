<?php global $config; ?>

<div align="center">
    <div class="form-container-outer" >
        <div class="form-container-title" align="left"><b>Reset Wachtwoord</b></div>

        <div class="form-container-inner">

            <form action="<?php echo $config['baseURL']; ?>?state=resetpw" method="POST">
                <table class="formtable">
                    <tr>
                        <td>
                            <label>
                                E-mail Address:
                            </label>
                        </td>
                        <td>
                            <input type="text" name="email" size="20"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                New Password:
                            </label>
                        </td>
                        <td>
                            <input type="password" name="password" size="20"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                Confirm Password:
                            </label>
                        </td>
                        <td>
                            <input type="password" name="confirmpassword" size="20"/><br/>
                        </td>
                    </tr>
                </table>
                <?php echo '<input type="hidden" name="q" value="';
                if (isset($_GET["q"])) {
                    echo $_GET["q"];
                }
                ?>
                <input type="submit" name="ResetPasswordForm" value=" Reset Password "/>
            </form>
        </div>
    </div>
</div>
