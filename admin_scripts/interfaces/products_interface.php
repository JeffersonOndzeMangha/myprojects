<?php
/*Products Interface, for adding, editing, deleting and getting products form the database.
Author: Jefferson Ondze Mangha
Date: 03/18/2018
*/

/**
 *
 */
  interface ProductsInterface
  {
    public function AddProduct($data);
    public function DeleteProduct($data);
    public function EditProduct($data);
    public function GetProducts();
  }

?>
