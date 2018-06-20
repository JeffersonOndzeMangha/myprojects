<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

include './mongodb_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => false));
    exit;
}

//in app global variables

$data = json_decode(file_get_contents('php://input'), true);
$pcode = '';
$pname = '';
$pprice = '';
$ptype = '';
$pcategory = '';
$pfilepath = '';
$pinstock = '';
$pstatus = '';
$pdescription = '';
$errorsArray = [];
$filepaths = [];
$newfilepaths = [];


// checking code is set and then filtered
if(isset($data['addProductCode'])){
    $pcode = test_input($data['addProductCode']);

    if(!empty($pcode)){
        if(($productsCol->findOne(['_id' => md5($pcode)])) != false || ($delproductsCol->findOne(['_id' => md5($pcode)])) != false){
            array_push($errorsArray,
                [
                    'duplicatecode' => [
                        'status' => false,
                        'codeexists' => true,
                        'class' => 'ng-invalid ng-touched ng-dirty'
                    ]
                ]
            );
            echo json_encode($errorsArray);
            exit;
        }else{
            $pcode = test_input($data['addProductCode']);
        }
    }else{
        array_push($errorsArray,
            [
                'nocode' => [
                    'status' => false,
                    'empty' => true
                ]
            ]
        );
        echo json_encode($errorsArray);
        exit;
    }

}

// checking name is set and then filtered
if(isset($data['addProductName'])){
    $pname = test_input($data['addProductName']);
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
       // do nothing at all
    }
}

// checking price is set then filtering
if(isset($data['addProductPrice'])){
    $pprice = $data['addProductPrice'];
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
        // do nothing at all
    }
}

// checking instock is set then filtering
if(isset($data['addProductinStock'])){
    $pinstock = $data['addProductinStock'];
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
        // do nothing at all
    }
}
// checking type is set and then filtering
if(isset($data['addProductType'])){
    $ptype = test_input($data['addProductType']);
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
        // do nothing at all
    }
}

// checking if status is set then filtering
if(isset($data['addProductStatus'])){
    $pstatus = $data['addProductStatus'];
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
if(isset($data['addProductCategory'])){
    $pcategory = test_input($data['addProductCategory']);
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
        // do nothing at all
    }
}

// checking filepaths is set and then filtering
if(isset($data['addProductFilepath'])){
    $pfilepath = $data['addProductFilepath'];
    if(empty($pfilepath)){
        array_push($errorsArray,
            [
                'nofiles' => [
                    'status' => false,
                    'empty' => true
                ]
            ]
        );
        echo json_encode($errorsArray);
        exit;
    }else{
       $filepaths = explode(',', $pfilepath);
        for($i = 0; $i < count($filepaths)-1; $i++ ){
            array_push($newfilepaths, $filepaths[$i]);
        }
    }
}

// checking if description is set and then filtering
if(isset($data['addProductDescription'])){
    $pdescription = test_input($data['addProductDescription']);
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
        // do nothing at all
    }
}

// if the errors array is empty after all the other things have passed through, add to data base
if(count($errorsArray) == 0){
    $document = $productsCol->insertOne([
        '_id' => md5($pcode),
        'code' => $pcode,
        'name' => $pname,
        'price' => $pprice,
        'instock' => $pinstock,
        'type' => $ptype,
        'featured' => $pstatus,
        'category' => $pcategory,
        'images' => $newfilepaths,
        'description' => $pdescription,
        'date' => date('M-d-Y  H:i:s')
    ]);

    print_r(json_encode($document->getInsertedId()));
}

?>
