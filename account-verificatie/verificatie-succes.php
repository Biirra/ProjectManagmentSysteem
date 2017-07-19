<?php include_once("includes/database.php");
include_once("includes/functions.php");


// Gather the post data
$hash = mysqli_real_escape_string($db, $_GET["q"]);


$sql = "SELECT account_activation_token, email FROM users WHERE account_activation_token = '$hash';";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$email = $row["email"];
// Does the new reset key match the old one?
if (hash_equals($row['account_activation_token'], crypt($email, $row['account_activation_token']))) {

    $today = date("Y-m-d H:i:s");
    // Update the user's password en zet de pass reset token op leeg zodat hij niet weer gebruikt kan worden.
    $sql = "UPDATE users SET geactiveerd = '$today', account_activation_token = '' WHERE email = '$email';";
    mysqli_query($db, $sql);
    $conn = null;

} else
    echo "je verificatie key is niet geldig.";

?>

<label>Uw account is met succes geverifieerd. u kunt nu gebruik maken van onze service.</label>
