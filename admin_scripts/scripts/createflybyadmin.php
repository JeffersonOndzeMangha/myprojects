<?php
require_once ("../../vendor/autoload.php");
include "../functions.php";

$username = 'root';
$password = 'J3ff3rs0n';

$tuser = email2username('jefferson@flybyclean.com');
$tpass = md5(base64_encode(md5(test_input1('J3ff3rs0n!'))));


$db_conn = new MongoDB\Client('mongodb://'.$username.':'.$password.'@132.148.64.36');

if($db_conn) {

    $fbAdmins = $db_conn->flybyadmins;
    $adminsCol = $fbAdmins->admins;
    $customData = [
        'fname' => 'Jefferson',
        'lname' => 'Ondze Mangha',
        'title' => 'CEO & CMO & CTO',
        'sh' => 'JO',
        'age' => 20,
        'DOB' =>[
            'm'=> 03,
            'd' => 25,
            'y' => 1997
        ]

    ];

    $command = array
    (
        "createUser" => $tuser,
        "pwd"        => $tpass,
        'customData' => $customData,
        "roles"      => array
        (
            array("role" => "readWrite", "db" => 'flybyadmins'),
            array("role" => "readWrite", "db" => 'flybydb'),
            array('role' => 'read', 'db' => 'flybyusers')
        )
    );

    if($fbAdmins){
        $fbAdmins->command($command);
        $adminsCol->insertOne(array(
            '_id' => md5(base64_encode($tuser.':flybyadmin:')),
            'userInfo' => $customData,
            'authToken' => base64_encode(base64_encode($tuser.':flybyadmin:'.$tpass))
        ));

    }

}


?>