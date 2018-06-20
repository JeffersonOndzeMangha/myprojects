<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
require_once ("../../vendor/autoload.php");
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => false));
    die();
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username']) && isset($data['password'])){
    $username = email2username($data['username']);
    $password = md5(base64_encode(md5(test_input1($data['password']))));


    $db_conn = new MongoDB\Client('mongodb://'.$username.':'.$password.'@132.148.64.36/flybyadmins');

    if($db_conn){
        $adminsCol = $db_conn->flybyadmins->selectCollection('admins');

        $user = $adminsCol->findOne(array(
            '_id' => md5(base64_encode($username.':flybyadmin:'))
        ));

        echo json_encode($user);
    }else{
        echo json_encode(array(
            'status' => false,
            'msg' => 'no db connection'
        ));
    }
}else{
    echo json_encode(array(
        'status' => false,
        'error' => true,
        'msg' => 'authentication failed',
    ));
    exit;
}




?>