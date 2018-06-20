<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

include './mongodb_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => false));
    exit;
}

$path = '../../uploads/images/products/';
$filePath = '';
$code = '';
$new = false;

if (isset($_FILES['file'])) {
    $originalName = $_FILES['file']['name'];
    $ext = '.'.pathinfo($originalName, PATHINFO_EXTENSION);

    if (!is_writable($path)) {
        echo json_encode(array(
            'status' => false,
            'msg'    => 'Destination directory not writable.'
        ));
        exit;
    }

    if (isset($_POST['code'])!= ''){
        $code = $_POST['code'];
        $generatedName = md5($originalName).$ext;

        if (!is_dir($path.$code)) {
            // if directory does not exist, create it
            if(mkdir($path.$code, 0700)){
                if(($productsCol->findOne(['_id' => md5($code)])) == null){
                    $new = true;
                }else{
                    $new = false;
                }
                $filePath = $path.$code.'/'.$generatedName;

                $urlpath = 'http://localhost/'.$filePath.'';
                if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {

                    echo json_encode(array(
                        'status'        => true,
                        'new'           => $new,
                        'originalName'  => $originalName,
                        'generatedName' => $generatedName,
                        'urlpath'       => $urlpath
                    ));
                }
            }else{
                echo json_encode(array(
                    'status' => false,
                    'msg' => 'could not create directory'
                ));
                exit;
            }
        }else{
            // directory exists
            if(is_dir($path.$code)){
                if(($productsCol->findOne(['_id' => md5($code)])) == null){
                    $new = true;
                }else{
                    $new = false;
                }
                $filePath = $path.$code.'/'.$generatedName;

                $urlpath = 'http://localhost/'.$filePath.'';
                if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {

                    echo json_encode(array(
                        'status'        => true,
                        'new'           => $new,
                        'code'          => $code,
                        'originalName'  => $originalName,
                        'generatedName' => $generatedName,
                        'urlpath'       => $urlpath
                    ));
                }
            }
        }

    }else{
        echo json_encode(array(
            'status'        => false,
            'msg'  => 'no code was entered',
        ));
        exit;
    }

}else{
    echo json_encode(array(
        'status'        => false,
        'msg' => 'no files were submited, or you don\'t have access'
    ));
    exit;
}

?>