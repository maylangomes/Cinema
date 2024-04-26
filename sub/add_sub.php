<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$id_member = $_GET['id_member'];
if ($id_member == "") {
    echo "Error : id_member is empty.";
    return;
} elseif ($id_member > 300) {
    echo "Error : id_member too high.";
    return;
} elseif ($id_member == 0) {
    echo "Error : id_member '0' dosn't exist.";
    return;
}
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
$verif = "SELECT * FROM membership  WHERE id_user=$id_member";
$query = "INSERT INTO membership(id_user, id_subscription, date_begin) VALUES ('$id_member', '$sub', NOW())";
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $stv = $pdo->prepare($verif);
    $stv->execute();
    $result = $stv->fetchAll();
    foreach($result as $array) {
        foreach($array as $rows) {
            if ($array['id_user'] == $id_member){
                echo "This member already exist, update subscription if you want to change it";
                return;
            }
        }
    }
    $sth = $pdo->prepare($query);
    $sth->execute();
    echo "Subscription added successfully !";
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>