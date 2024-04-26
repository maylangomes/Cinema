<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$id_movie = $_GET['id_movie'];
if ($id_movie == "" || $id_movie == 0 || $id_movie > 2413) {
    echo "Error : no valid id_movie was selected !";
    return;
}
$id_room = $_GET['id_room'];
if ($id_room == "" || $id_room == 0 || $id_room > 17) {
    echo "Error : no valid id_room was selected !";
    return;
}
$date = $_GET['date'];
if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date) == false) {
    echo "Error : invalid date !";
    return;
}
$dateTime = $date . " " . $_GET['time'];
$verif = "SELECT id_movie, id_room, date_begin FROM movie_schedule WHERE id_movie=$id_movie AND id_room=$id_room AND date_begin='$dateTime'";
$query = "INSERT INTO movie_schedule(id_movie, id_room, date_begin) VALUES('$id_movie', '$id_room', '$dateTime')";
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $sth_verif = $pdo->prepare($verif);
    $sth_verif->execute();
    $tab_verif = $sth_verif->fetchAll();
    if(!empty($tab_verif)) {
        echo "This session already exist !";
        return;
    }
    $sth = $pdo->prepare($query);
    $sth->execute();
    echo "Session added successfully !";
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>