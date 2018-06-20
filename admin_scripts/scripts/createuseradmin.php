<?php
require_once ("../../vendor/autoload.php");

$username = 'root';
$password = 'J3ff3rs0n';

$adminUser = 'userAdmin';
$adminpassword = 'FlybyCl3an';


$db_conn = new MongoDB\Client('mongodb://'.$username.':'.$password.'@132.148.64.36/admin');

if($db_conn) {

    $fbAdmins = $db_conn->flybyadmins;
    $admindb = $db_conn->admin;
    $adminsCol = $fbAdmins->admins;
    $customData = [
        'fname' => 'userAdmin',
        'lname' => 'User Admin',
        'title' => 'User Manager',
        'sh' => 'FA',
        'age' => 0,
        'DOB' =>[
            'm'=> 0,
            'd' => 0,
            'y' => 0
        ]

    ];

    $command = array
    (
        "createUser" => $adminUser,
        "pwd"        => $adminpassword,
        'customData' => $customData,
        "roles"      => array
        (
            array('role' => 'userAdminAnyDatabase', 'db' => 'admin')
        )
    );

    if($admindb){
        if($admindb->command($command)){
            $adminsCol->insertOne(array(
                '_id' => md5(base64_encode($adminUser.':flybyadmin:')),
                'userInfo' => $customData,
                'authToken' => base64_encode(base64_encode($adminUser.':flybyadmin:'.$adminpassword))
            ));
        }
    }

}

?>