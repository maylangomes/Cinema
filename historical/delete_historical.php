<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$id_member = $_GET['id_member'];
if ($id_member == "" || $id_member == 0 || $id_member > 153) {
    echo "Error : no valid id_member was selected !";
    return;
}
$id_session = $_GET['id_session'];
if ($id_session == "" || $id_session == 0 || $id_session > 2093) {
    echo "Error : no valid id_session was selected !";
    return;
}
$query = "DELETE FROM membership_log WHERE id_membership=$id_member AND id_session=$id_session";
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $sth = $pdo->prepare($query);
    $sth->execute();
    echo "Movie delete succesfully !";
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>