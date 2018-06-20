<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

include './mongodb_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(array('status' => false));
    exit;
}
$age =[
    'name' => 1
];

$data = $usersCol->find([], array('sort'=>$age))->toArray();

echo json_encode($data);

?>