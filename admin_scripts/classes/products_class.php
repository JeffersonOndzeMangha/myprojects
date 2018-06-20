<?php
/* Product class, for adding, deleting, editing and getting products to/from the database
Author: Jefferson Ondze Mangha
Date: 03/18/2018
*/

/**
 *
 */
class Products_ implements ProductsInterface
{

  function __construct()
  {
    # code...
  }

  public function AddProduct($data){
    // checking data is set and ready to process
    if(isset($data)){
      $pname = $data['addProductName'];
      $pprice = $data['addProductPrice'];
      $pinstock = $data['addProductinStock'];
      $ptype = $data['addProductType'];
      $pstatus = $data['addProductStatus'];
      $pcategory = $data['addProductCategory'];
      $pfilepath = $data['addProductFilepath'];
      $pdescription = $data['addProductDescription'];

      try {
        $productsCol->insertOne([
          '_id' => new MongoDB\BSON\ObjectId(),
          'name' => $pname,
          'price' => $pprice,
          'instock' => $pinstock,
          'type' => $ptype,
          'featured' => $pstatus,
          'category' => $pcategory,
          'image' => $pfilepath,
          'description' => $pdescription,
          'date' => date('M-d-Y  H:i:s')
        ]);
        return true;
      } catch (\Exception $e) {
        return false;
      }

    }else {
      return false;
    }
  }

  public function DeleteProduct($data){
    if(isset($data['_id'])){
        $pcode = $data['_id'];

        if(!empty($pcode)){
            $document = $productsCol->findOne(['_id' => $pcode]);

            $delproductsCol->insertOne($document);
            $productsCol->deleteOne($document);
            rename('../../uploads/images/products/'.$pcode, '../../uploads/images/delproducts/'.$pcode);
            return true;
        }else{
            return false;
        }
    }
  }

  public function EditProduct($data){
    if(isset($data['_id'])){
        $pcode = $data['_id'];

        $document = $productsCol->findOne(['_id' => $pcode]);
        try {
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
          return true;
        } catch (\Exception $e) {
          return false;
        }
    }else {
        echo json_encode(array(
            'status' => false,
            'msg' => 'Couldn\'t find the document'
        ));
    }
  }

  public function GetProducts(){
    $age =[
        'date' => 1
    ];
    try {
      $data = $productsCol->find([], array('sort'=>$age))->toArray();
      return $data;
    } catch (\Exception $e) {
      return false;
    }
  }

}





 ?>
