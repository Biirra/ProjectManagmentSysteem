<?php
function reverseDate($date){
    $originalDate = $date;
    $newDate = date("d-m-Y", strtotime($originalDate));
    return $newDate;
}
function sendVerifyMail($db, $userId){
    global $config;
    global $verifyAccount;
    global $mailItems;
    require 'vendor/autoload.php';

    $sql = "SELECT email FROM users WHERE id = $userId";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    $userEmail = $row['email'];

// Create the unique user password reset key
    $verifykey = $userEmail;

    $verifykeyEncrypt = encryptPass($verifykey);
// Create a url which we will direct them to reset their password
    $verifyrUrl = $config['fullURL'] . $config['homeURL'] . "?state=verificatie-succes&q=" . $verifykeyEncrypt;

// Mail them their key
    $mailbody = $verifyAccount['body'] . $verifyrUrl . "\n\nThanks,\nThe Administration";

    $sql = "UPDATE users SET account_activation_token = '$verifykeyEncrypt' WHERE email = '$userEmail'";
    mysqli_query($db, $sql);

    $from = new SendGrid\Email("Example User", $mailItems['from']);
    $subject = $verifyAccount['subject'];
    $to = new SendGrid\Email("Example User", $userEmail);
    $content = new SendGrid\Content("text/plain", $mailbody);
    $mail = new SendGrid\Mail($from, $subject, $to, $content);
    $apiKey = $config['apiKey'];

    $sg = new \SendGrid($apiKey);
    try {
        $response = $sg->client->mail()->send()->post($mail);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

function encryptPass($password)
{
    // A higher "cost" is more secure but consumes more processing power
    $cost = 10;

    // Create a random salt
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

    // Prefix information about the hash so PHP knows how to verify it later.
    // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
    $salt = sprintf("$2a$%02d$", $cost) . $salt;

    // Value:
    // $2a$10$eImiTXuWVxfM37uY4JANjQ==

    // Hash the password with the salt
    $hash = crypt($password, $salt);
    // Value:
    // $2a$10$eImiTXuWVxfM37uY4JANjOL.oTxqp7WylW7FCzx2Lc7VLmdJIddZq

    return $hash;

}
?>

