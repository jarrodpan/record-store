<?php

require_once("../config.php");

$db = new Database();
$conn = $db->connect();

$p = new Item($conn);

$sd = $p->getByID(1);
var_dump($sd);
echo $sd->json();
//echo json_encode($sd);
//var_dump($p->getTagsByID(1));

//$x = $db->conn->query("call sp_items_and_tags(1)");
//var_dump($x);

 ?>
