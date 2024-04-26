<?php

$servername = "localhost";
$database = "cinema;";
$serv = 'mysql:host=localhost;dbname=cinema';
$user = 'Maylan';
$pass = 'day';
$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
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
$query = "SELECT firstname, lastname, id FROM user WHERE firstname LIKE '%$firstname%' AND lastname LIKE '%$lastname%' LIMIT $debut, $elem_par_page";
$total = "SELECT COUNT(*) FROM user WHERE firstname LIKE '%$firstname%' AND lastname LIKE '%$lastname%'";
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
    echo "Member(s) matching your search :" . "<br>" ."<br>";
    foreach($result as $array) {
        foreach($array as $rows) {
            $tab[] = $array['firstname'] . " " . $array['lastname'] . " (id_member : " . $array['id'] . ")";
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
        echo "No member found" . "<br>";
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