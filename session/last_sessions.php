<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$limit = $_GET['limit'];
if ($limit == ""){
    echo "Error : Search empty.";
    return;
}
$page = $_GET['page'];
$debut = $page * $elem_par_page;
if ($page >= 1){
    $debut = ($page-1) * $elem_par_page;
}
$query = "SELECT id_movie, id_room, date_begin FROM movie_schedule ORDER BY id DESC LIMIT $limit";
$total = "SELECT COUNT(*) FROM movie_schedule INNER JOIN movie ON movie.id=movie_schedule.id_movie WHERE date_begin LIKE '%$date%'";
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $sth = $pdo->prepare($query);
    $sth->execute();
    $result = $sth->fetchAll();
    echo "Movies added from the newest to the oldest : " . $date . " :" . "<br>" . "<br>" . "<br>";
    $tab = [];
    foreach($result as $array) {
        $tab[] = "Session added => " . "id_movie : " . $array['id_movie'] . " | id_room : " . $array['id_room'] . " | date & hour : " . $array['date_begin'] . "<br>";
    }
    $tab = array_unique($tab);
    $count_tab = count($tab);
    foreach($tab as $value) {
        ?> <tr>
            <td><?php echo $value . "<br>"; ?></td>
        </tr>
        <?php
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>