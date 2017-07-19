<?php global $config ?>

<div align="center">
    <div class="form-container-outer filterable" >
        <div class="form-container-title" align="left"><b>Wachtwoord vergeten</b></div>

        <div class="form-container-inner">
            <form action="<?php echo $config['baseURL']; ?>?state=wwverander" method="POST">
                <table>
                    <tr>
                        <td>
                            <label>E-mail Address: </label>
                        </td>
                        <td>
                            <input type="text" name="email" size="20"/>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="ForgotPassword" value=" Request Reset "/>
            </form>
        </div>
    </div>
</div>
