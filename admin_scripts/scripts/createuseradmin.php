<?php
require_once ("../../vendor/autoload.php");

$username = '';
$password = '';

$adminUser = '';
$adminpassword = '';


$db_conn = new MongoDB\Client('mongodb://'.$username.':'.$password.'@***.***.**.**/admin');

if($db_conn) {

    $fbAdmins = $db_conn->admins;
    $admindb = $db_conn->trueDB;
    $adminsCol = $fbAdmins->adminsCol;
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
                '_id' => md5(base64_encode($adminUser.':admin:')),
                'userInfo' => $customData,
                'authToken' => base64_encode(base64_encode($adminUser.':admin:'.$adminpassword))
            ));
        }
    }

}

?>
