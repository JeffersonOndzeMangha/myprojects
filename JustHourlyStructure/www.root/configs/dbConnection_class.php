<?php
/************************************************
*************************************************
** Content: Contains connection class          **
**          to make conections to the mongoDB. **
** Author:  Jefferson Ondze Mangha             **
** CopyRightOf: justhourly.com                 **
** Date: 09/28/2017                            **
** LastUpdated: 09/31/2017                     **
** V: 1.0.0                                    **
*************************************************
************************************************/
//md5("JustHourlyPassKey")
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
        $db_connection->justhourly_qa_db->JobSeekersProfileInfo_Col->find([]);
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

/*
db.justhourly_qa_db.insert(
   { _id: 10001, item: "box", qty: 20, name: "jobseeker1" }
)*/





}






 ?>
