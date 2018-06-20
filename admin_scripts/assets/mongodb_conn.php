<?php
/************************************************
*************************************************
** Content: Contains connection class          **
**          to make conections to the mongoDB. **
** Author:  Jefferson Ondze Mangha             **
** CopyRightOf:                                **
** Date: 09/28/2017                            **
** LastUpdated: 09/31/2017                     **
** V: 1.0.0                                    **
*************************************************
************************************************/
/**
 * Connection Class: containes connection methods and types
 */
class dbConnection_
{

  function __construct()
  {
    # code...
  }

  public function dbConnect($username, $password){

      $authsource = 'justhourly_qa_db';
      try {
        $db_connection = new MongoDB\Client('mongodb://'.$username.':'.$password.'@132.148.64.36/'.$authsource);
        return $db_connection;
      } catch (Exception $e) {
        return false;
      }
      return false;

  }

  public function adminConnect(){
      $authsource = 'justhourly_qa_db';

    return $Admindb_connection = new MongoDB\Client('mongodb://JustHourlyUserAdmin:37ea04e0fe6a72726a203a84febb5e23@132.148.64.36/'.$authsource);

  }

}

/*
$db = $db_conn -> flybydb;
$productsCol = $db -> products;
$delproductsCol = $db -> deletedproducts;
$promotionsCol = $db -> promotions;
$delpromotionsCol = $db -> delpromotions;
$packagesCol = $db -> packages;
$delpackagesCol = $db -> delpackages;
$usersdb = $db_conn -> flybyusers;
$usersCol = $usersdb -> users;
*/



?>
