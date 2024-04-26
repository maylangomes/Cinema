<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
$nbr_affiche = $_GET['nbr_affiche'];
$setNull = "ALTER TABLE membership MODIFY COLUMN id_subscription INT DEFAULT NULL";
$verif = "SELECT user.firstname, user.lastname FROM membership RIGHT JOIN user ON membership.id_user=user.id WHERE firstname='$firstname' AND lastname='$lastname'";
$query = "UPDATE membership RIGHT JOIN user ON membership.id_user=user.id SET id_subscription=NULL WHERE firstname='$firstname' AND lastname='$lastname'";
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $sth_null = $pdo->prepare($setNull);
    $sth_null->execute();
    $sth = $pdo->prepare($query);
    $sth->execute();
    $sth_verif = $pdo->prepare($verif);
    $sth_verif->execute();
    $tab_verif = $sth_verif->fetchAll();
    if (empty($tab_verif)){
        echo "Error : Member doesn't exist";
        return;
    } else {
        echo "Subscription delete successfully !";
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>