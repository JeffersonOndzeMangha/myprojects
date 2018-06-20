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
    $pinstock = '';
    $pstatus = '';
    $pdescription = '';

// checking code is set and then filtered
if(isset($data['productCode'])){
    $pcode = test_input($data['productCode']);
    $errorsArray = [];

    $document = $productsCol->findOne(['_id' => md5($pcode)]);

    // checking name is set and then filtered
    if(isset($data['productName'])){
        $pname = test_input($data['productName']);
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
    if(isset($data['productPrice'])){
        $pprice = $data['productPrice'];
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

    // checking instock is set then filtering
    if(isset($data['productinStock'])){
        $pinstock = $data['productinStock'];
        if(empty($pinstock)){
            array_push($errorsArray,
                [
                    'noinstock' => [
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

    // checking type is set and then filtering
    if(isset($data['productType'])){
        $ptype = test_input($data['productType']);
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

    // checking if status is set then filtering
    if(isset($data['productStatus'])){
        $pstatus = $data['productStatus'];
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
    if(isset($data['productCategory'])){
        $pcategory = test_input($data['productCategory']);
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
    if(isset($data['productDescription'])){
        $pdescription = test_input($data['productDescription']);
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

    if(count($errorsArray) == 0){
        $productsCol->updateOne(
            $document,
            ['$set' => [
                'name' => $pname,
                'price' => $pprice,
                'instock' => $pinstock,
                'type' => $ptype,
                'featured' => $pstatus,
                'category' => $pcategory,
                'description' => $pdescription
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