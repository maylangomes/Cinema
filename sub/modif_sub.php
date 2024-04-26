<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
$sub = $_GET['get_abonnement'];
if ($sub === 'VIP') {
    $sub = 1;
} elseif ($sub === 'GOLD') {
    $sub = 2;
} elseif ($sub === 'Classic') {
    $sub = 3;
} elseif ($sub === 'Pass_Day') {
    $sub = 4;
}
$verif = "SELECT user.firstname, user.lastname, membership.id_user FROM membership RIGHT JOIN user ON membership.id_user=user.id WHERE firstname='$firstname' AND lastname='$lastname'";
$query = "UPDATE membership RIGHT JOIN user ON membership.id_user=user.id SET id_subscription=$sub WHERE firstname='$firstname' AND lastname='$lastname'";
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $sth_verif = $pdo->prepare($verif);
    $sth_verif->execute();
    $tab_verif = $sth_verif->fetchAll();
    if (empty($tab_verif)){
        echo "Error : Member doesn't exist";
        return;
    }
    $sth = $pdo->prepare($query);
    $sth->execute();
    echo "Subscription changed successfully !";
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>