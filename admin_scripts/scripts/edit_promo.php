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


// checking code is set and then filtered
if(isset($data['promoCode'])){
    $pcode = test_input($data['promoCode']);

    $errorsArray = [];

    $document = $promotionsCol->findOne(['_id' => md5($pcode)]);

    // checking name is set and then filtered
    if(isset($data['promoName'])){
        $pname = test_input($data['promoName']);
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
    if(isset($data['promoType'])){
        $ptype = test_input($data['promoType']);
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
    if(isset($data['promoPackageType'])){
        $ppackagetype = $data['promoPackageType'];
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
    if(isset($data['promoCategory'])){
        $pcategory = $data['promoCategory'];
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
    if(isset($data['promoApplyTo'])){
        $papplyto = test_input($data['promoApplyTo']);
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
    if(isset($data['promoStatus'])){
        $pstatus = $data['promoStatus'];
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
    if(isset($data['promoValue'])){
        $pvalue = test_input($data['promoValue']);
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


    // checking if description is set and then filtering
    if(isset($data['promoDescription'])){
        $pdescription = test_input($data['promoDescription']);
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
        $promotionsCol->updateOne(
            $document,
            ['$set' => [
            'name' => $pname,
            'type' => $ptype,
            'value' => $pvalue,
            'packageType' => $ppackagetype,
            'category' => $pcategory,
            'featured' => $pstatus,
            'applyTo' => $papplyto,
            'active' => $pstatus,
            'description' => $pdescription
            ]]
        );
    }

}

?>