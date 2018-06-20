<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

include './mongodb_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(array('status' => false));
    exit;
}

$data = $packagesCol->find([], array('sort' => ['date'=>1]))->toArray();
echo json_encode($data);

?>