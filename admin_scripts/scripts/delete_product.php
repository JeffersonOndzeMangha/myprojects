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

// checking code is set and then filtered
if(isset($data['productCode'])){
    $pcode = test_input($data['productCode']);

    if(!empty($pcode)){
        $document = $productsCol->findOne(['_id' => md5($pcode)]);

        $delproductsCol->insertOne($document);
        $productsCol->deleteOne($document);
        rename('../../uploads/images/products/'.$pcode, '../../uploads/images/delproducts/'.$pcode);
        echo json_encode($document);

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


?>