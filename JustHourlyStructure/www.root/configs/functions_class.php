<?php
/************************************************
*************************************************
** Content: Contains functions class           **
**          for reused functions and others.   **
** Author:  Jefferson Ondze Mangha             **
** CopyRightOf: justhourly.com                 **
** Date: 10/05/2017                            **
** LastUpdated:                                **
** V: 1.0.0                                    **
*************************************************
************************************************/
//md5("JustHourlyPassKey")
/**
 * Connection Class: containes connection methods and types
 */
class functions_
{

  function __construct()
  {
    # code...
  }

  protected function dataEncryption($data){
    return md5(base64_encode($data));
  }
  protected function emailStripper($data){
    return preg_replace("/[^a-z0-9]/", "", strtolower($data));
  }

/*
db.justhourly_qa_db.insert(
   { _id: 10001, item: "box", qty: 20, name: "jobseeker1" }
)*/





}






 ?>
