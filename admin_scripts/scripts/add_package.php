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
$pcategory = '';
$ptype = '';
$psize = '';
$pprice = '';
$pstatus = '';
$pfeatured = '';
$pdescription = '';

$errorsArray = [];
$filepaths = [];
$newfilepaths = [];



// checking code is set and then filtered
if(isset($data['addPackageCode'])){
    $pcode = test_input($data['addPackageCode']);

    if(!empty($pcode)){
        if(($packagesCol->findOne(['_id' => md5($pcode)])) != false || ($delpackagesCol->findOne(['_id' => md5($pcode)])) != false){
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
            $pcode = test_input($data['addPackageCode']);
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
if(isset($data['addPackageName'])){
    $pname = test_input($data['addPackageName']);
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


// checking instock is set then filtering
if(isset($data['addPackageCategory'])){
    $pcategory = $data['addPackageCategory'];
    if( $pcategory == '0'){
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

// checking type is set and then filtered
if(isset($data['addPackageType'])){
    $ptype = test_input($data['addPackageType']);
    if($ptype == '0'){
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
    }else {
        // do nothing at all
    }
}

// checking type is set and then filtering
if(isset($data['addPackageSize'])){
    $psize = test_input($data['addPackageSize']);
    if($psize == '0'){
        array_push($errorsArray,
            [
                'noapplication' => [
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
if(isset($data['addPackageStatus'])){
    $pstatus = $data['addPackageStatus'];
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

// checking if status is set then filtering
if(isset($data['addPackageFeatured'])){
    $pfeatured = $data['addPackageFeatured'];
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

// checking value is set and then filtering
if(isset($data['addPackagePrice'])){
    $pprice = test_input($data['addPackagePrice']);
    if(empty($pprice)){
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
if(isset($data['addPackageFilepath'])){
    $pfilepath = $data['addPackageFilepath'];
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
if(isset($data['addPackageDescription'])){
    $pdescription = test_input($data['addPackageDescription']);
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
if((count($errorsArray) == 0) && (count($newfilepaths) != 0)){
    $document = $packagesCol
        ->insertOne([
            '_id' => md5($pcode),
            'code' => $pcode,
            'name' => $pname,
            'category' => $pcategory,
            'type' => $ptype,
            'price' => $pprice,
            'active' => $pstatus,
            'featured' => $pfeatured,
            'size' => $psize,
            'images' => $newfilepaths,
            'data' => date('M-d-Y  H:i:s'),
            'description' => $pdescription
        ]);

    print_r(json_encode($document->getInsertedId()));
}

?>