<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$film = $_GET['film'];
$distrib = $_GET['get_distrib'];
$nbr_affiche = $_GET['nbr_affiche'];
if ($nbr_affiche != "") {
    $elem_par_page = $nbr_affiche;
} else {
    $elem_par_page = 10;
}
$page = $_GET['page'];
$debut = $page * $elem_par_page;
if ($page >= 1){
    $debut = ($page-1) * $elem_par_page;
}
if ($distrib === "") {
    $query = "SELECT movie.title, distributor.name FROM movie LEFT JOIN distributor ON movie.id_distributor=distributor.id WHERE movie.title LIKE '%$film%' LIMIT $debut, $elem_par_page";
    $total = "SELECT COUNT(*) FROM movie LEFT JOIN distributor ON movie.id_distributor=distributor.id WHERE movie.title LIKE '%$film%'";
} else {
    $query = "SELECT movie.title, distributor.name FROM movie LEFT JOIN distributor ON movie.id_distributor=distributor.id WHERE movie.title LIKE '%$film%' AND distributor.name LIKE '%$distrib%' LIMIT $debut, $elem_par_page";
    $total = "SELECT COUNT(*) FROM movie LEFT JOIN distributor ON movie.id_distributor=distributor.id WHERE movie.title LIKE '%$film%' AND distributor.name LIKE '%$distrib%'";
}
$error = null;
try {
    $pdo = new PDO ($serv, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $sth = $pdo->prepare($query);
    $sth->execute();
    $result = $sth->fetchAll();
    $tab = [];
    echo "Movies matching your search :" . "<br>" ."<br>";
    foreach($result as $array) {
        foreach($array as $rows){
            $tab[] = $array['title'] . " -> " . $array['name'];
        }
    }
    $tab = array_unique($tab);
    $count_tab = count($tab);
    foreach($tab as $value) {
        ?> <tr>
            <td><?php echo $value . "<br>"; ?></td>
        </tr>
        <?php
    }
    $count = $pdo->prepare($total);
    $count->execute();
    $r_count = $count->fetchAll();
    foreach($r_count as $value) {
        $value_count = $value['COUNT(*)'];
    }
    if ($value_count == 0){
        echo "No movie found." . "<br>";
    }
    echo "<br>(" . $value_count . " results)";
    $nbr_page = ceil($value_count/$elem_par_page);
    ?>
    <div class="pagination">
        <?php
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (str_contains($url, "?")) {
            for($i = 1; $i <= $nbr_page; $i++) {
                if (strpos($url, "page=") == false) {
                    echo "<a href='$url&page=$i' style='font-size: 2rem; margin-left : 1rem; color: gray'>$i</a>&nbsp;";
                } else {
                    $url = substr($url, 0, strpos($url, "&page="));
                    echo "<a href='$url&page=$i' style='font-size: 2rem; margin-left : 1rem; color: gray'>$i</a>&nbsp;";
                }
            }
        }
        ?>
    </div>
    <?php
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}
?>