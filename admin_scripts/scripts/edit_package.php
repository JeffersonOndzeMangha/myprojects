<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

include './mongodb_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => false));
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$pcode = '';
$pname = '';
$pprice ='';
$ptype = '';
$pcategory = '';
$pfeatured = '';
$pstatus = '';
$pdescription = '';
$psize = '';

// checking code is set and then filtered
if(isset($data['packageCode'])){
    $pcode = test_input($data['packageCode']);
    $errorsArray = [];

    $document = $packagesCol->findOne(['_id' => md5($pcode)]);

    // checking name is set and then filtered
    if(isset($data['packageName'])){
        $pname = test_input($data['packageName']);
        if(empty($pname)){
            array_push($errorsArray,
                [
                    'noname' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else {
            //do nothing
        }
    }

    // checking price is set then filtering
    if(isset($data['packagePrice'])){
        $pprice = $data['packagePrice'];
        if(empty($pprice)){
            array_push($errorsArray,
                [
                    'noprice' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else{
            // do nothing
        }
    }

    // checking type is set and then filtering
    if(isset($data['packageType'])){
        $ptype = test_input($data['packageType']);
        if(empty($ptype)){
            array_push($errorsArray,
                [
                    'notype' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else{
            //do nothing
        }
    }

    // checking size is set and then filtering
    if(isset($data['packageSize'])){
        $psize = test_input($data['packageSize']);
        if(empty($psize)){
            array_push($errorsArray,
                [
                    'notype' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else{
            //do nothing
        }
    }

    // checking if status is set then filtering
    if(isset($data['packageStatus'])){
        $pstatus = $data['packageStatus'];
        if(is_bool($pstatus)){
            //do nothing
        }else{
            array_push($errorsArray,
                [
                    'nostatus' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }
    }

    // checking category is set and then filtering
    if(isset($data['packageCategory'])){
        $pcategory = test_input($data['packageCategory']);
        if(empty($pcategory)){
            array_push($errorsArray,
                [
                    'nocategory' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else{
            //do nothing
        }
    }

    // checking if description is set and then filtering
    if(isset($data['packageDescription'])){
        $pdescription = test_input($data['packageDescription']);
        if(empty($pdescription)){
            array_push($errorsArray,
                [
                    'nodescription' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else{
            //do nothing
        }
    }

    // checking instock is set then filtering
    if(isset($data['packageFeatured'])){
        $pfeatured = $data['packageFeatured'];
        if(is_bool($pfeatured)){
            //do nothing
        }else{
            array_push($errorsArray,
                [
                    'nostatus' => [
                        'status' => false,
                        'empty' => true
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }
    }

    if(count($errorsArray) == 0){
        $packagesCol->updateOne(
            $document,
            ['$set' => [
                'name' => $pname,
                'price' => $pprice,
                'featured' => $pfeatured,
                'type' => $ptype,
                'active' => $pstatus,
                'category' => $pcategory,
                'description' => $pdescription,
                'size' => $psize
            ]]
        );
    }


}else {
    echo json_encode(array(
        'status' => false,
        'msg' => 'Couldn\'t find the document'
    ));
}

?>