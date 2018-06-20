<?php
require_once ("../../vendor/autoload.php");
include "../functions.php";

$username = '';
$password = '';

$tuser = email2username('');
$tpass = (test_input1('');


$db_conn = new MongoDB\Client('mongodb://'.$username.':'.$password.'@***.***.**.**');

if($db_conn) {

    $Admins = $db_conn->admins;
    $adminsCol = $fbAdmins->adminsCol;
    $customData = [
        'fname' => 'Jefferson',
        'lname' => 'Ondze Mangha',
        'title' => 'CEO & CMO & CTO',
        'sh' => 'JO',
        'age' => 20,
        'DOB' =>[
            'm'=> 08,
            'd' => 25,
            'y' => 1890
        ]

    ];

    $command = array
    (
        "createUser" => $tuser,
        "pwd"        => $tpass,
        'customData' => $customData,
        "roles"      => array
        (
            array("role" => "readWrite", "db" => 'admins'),
            array("role" => "readWrite", "db" => 'trueDB'),
            array('role' => 'read', 'db' => 'usersDB')
        )
    );

    if($fbAdmins){
        $fbAdmins->command($command);
        $adminsCol->insertOne(array(
            '_id' => md5(base64_encode($tuser.':admin:')),
            'userInfo' => $customData,
            'authToken' => base64_encode(base64_encode($tuser.':admin:'.$tpass))
        ));

    }

}


?>
