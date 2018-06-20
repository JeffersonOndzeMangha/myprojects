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
$ptype = '';
$ppackagetype = '';
$pcategory = '';
$papplyto = '';
$pvalue = '';
$pstatus = '';
$pdescription = '';
$errorsArray = [];
$filepaths = [];
$newfilepaths = [];



// checking code is set and then filtered
if(isset($data['addPromoCode'])){
    $pcode = test_input($data['addPromoCode']);

    if(!empty($pcode)){
        if(($promotionsCol->findOne(['_id' => md5($pcode)])) != false || ($promotionsCol->findOne(['_id' => md5($pcode)])) != false){
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
            $pcode = test_input($data['addPromoCode']);
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
if(isset($data['addPromoName'])){
    $pname = test_input($data['addPromoName']);
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

// checking type is set and then filtered
if(isset($data['addPromoType'])){
    $ptype = test_input($data['addPromoType']);
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

// checking package type is set then filtering
if(isset($data['addPromoPackageType'])){
    $ppackagetype = $data['addPromoPackageType'];
    if($ppackagetype == '0'){
        array_push($errorsArray,
            [
                'nopackagetype' => [
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
if(isset($data['addPromoCategory'])){
    $pcategory = $data['addPromoCategory'];
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
// checking type is set and then filtering
if(isset($data['addPromoApplyTo'])){
    $papplyto = test_input($data['addPromoApplyTo']);
    if($papplyto == '0'){
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
if(isset($data['addPromoStatus'])){
    $pstatus = $data['addPromoStatus'];
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
if(isset($data['addPromoValue'])){
    $pvalue = test_input($data['addPromoValue']);
    if(empty($pvalue)){
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
if(isset($data['addPromoFilepath'])){
    $pfilepath = $data['addPromoFilepath'];
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
if(isset($data['addPromoDescription'])){
    $pdescription = test_input($data['addPromoDescription']);
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
    $document = $promotionsCol->insertOne([
        '_id' => md5($pcode),
        'code' => $pcode,
        'name' => $pname,
        'type' => $ptype,
        'value' => $pvalue,
        'packageType' => $ppackagetype,
        'category' => $pcategory,
        'featured' => $pstatus,
        'applyTo' => $papplyto,
        'active' => $pstatus,
        'images' => $newfilepaths,
        'description' => $pdescription,
        'data' => date('M-d-Y  H:i:s')
    ]);

    print_r(json_encode($document->getInsertedId()));
}

?>