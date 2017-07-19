<?php
// Connect to MySQL
include_once ("includes/database.php");
include_once ("login/session.php");
include_once("includes/functions.php");

global $config;
global $mailItems;

// Was the form submitted?
if (isset($_POST["ForgotPassword"])) {

    // Harvest submitted e-mail address
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email = mysqli_real_escape_string($db, $_POST["email"]);

    }else{
        echo "email is not valid";
        exit;
    }

    // Check to see if a user exists with this e-mail
    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $sql);
    $userExists = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($userExists["email"])
    {
        require 'vendor/autoload.php';


        // Create the unique user password reset key
        $resetkey = $userExists["email"];

        $resetkeyEncrypt = encryptPass($resetkey);
        // Create a url which we will direct them to reset their password
        $pwrurl = $config['fullURL'].$config['homeURL']."?state=wwreset&q=".$resetkeyEncrypt;

        // Mail them their key
        $mailbody =  $mailItems['body'] . $pwrurl . "\n\nThanks,\nThe Administration";


        $userEmail = $userExists["email"];
        $sql = "UPDATE users SET pass_reset_token = '$resetkeyEncrypt' WHERE email = '$userEmail'";
        mysqli_query($db, $sql);

        $from = new SendGrid\Email("Example User", $mailItems['from']);
        $subject = $mailItems['subject'];
        $to = new SendGrid\Email("Example User", $userEmail);
        $content = new SendGrid\Content("text/plain", $mailbody);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);
        $apiKey = $config['apiKey'];

        $sg = new \SendGrid($apiKey);
        try {
            $response = $sg->client->mail()->send()->post($mail);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        echo "Your password recovery key has been sent to your e-mail address.";

    }
    else
        echo "No user with that e-mail address exists.";
}
?>
