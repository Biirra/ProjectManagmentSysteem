<?php
include_once("includes/database.php");
include_once("includes/functions.php");

// Was the form submitted?
if (isset($_POST["ResetPasswordForm"]))
{
    // Gather the post data
    $email = mysqli_real_escape_string($db, $_POST["email"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);
    $confirmpassword = mysqli_real_escape_string($db, $_POST["confirmpassword"]);
    $hash = mysqli_real_escape_string($db, $_POST["q"]); // maakt dit misschien de hash kapot ?


    $sql = "SELECT pass_reset_token FROM users WHERE email = '$email';";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    // Does the new reset key match the old one?
    if ( hash_equals($row['pass_reset_token'], crypt($email, $row['pass_reset_token'])) ) {
        if ($password == $confirmpassword)
        {
            //hash and secure the password
            $password = encryptPass($password);

            // Update the user's password en zet de pass reset token op leeg zodat hij niet weer gebruikt kan worden.
            $sql = "UPDATE users SET password = '$password', pass_reset_token = '' WHERE email = '$email';";
            mysqli_query($db, $sql);
            $conn = null;
            echo "Your password has been successfully reset.";
        }
        else
            echo "Your password's do not match.";
    }
    else
        echo "Your password reset key is invalid.";
}

?>