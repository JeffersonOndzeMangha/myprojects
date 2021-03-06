<?php

public function SignUp(dbConnection_ $dbConnection_, $newjobseekeremail, $newjobseekerpassword, $newjobseekerzipcode){
  //User sign up function, will create new user in the mongodb database
  $adminConnect = $dbConnection_->adminConnect();
  $db = $adminConnect->justhourly_qa_db;
  $col = $db->JobSeekersProfileInfo_Col;

  $createUserCommand = array(
    "createUser"=> $this->emailStripper($newjobseekeremail),
    "pwd" => $this->dataEncryption($newjobseekerpassword),
    'customData' =>
    [
      "UserIdentity" => "", //$identity,
      "Email" => $newjobseekeremail
    ],
    "roles" =>
    [
        ["role" => 'JustHourlyJobSeeker', "db" => 'justhourly_qa_db']
    ]
  );


  $userProfileDocument = array(// creates a user document, to be inserted into the mongodb user profiles collection
    "PersonalInfo" => array(
      "Name" => array(
        "FirstName" => "",
        "LastName" => ""
      ),
      "ContactInfo" => array(
        "PhoneNumber" => "",
        "Email" => "",//$newjobseekeremail,
        "Address" => array(
          "Street" => "",
          "City" => "",
          "State" => "",
          "ZipCode" => "",//$newjobseekerzipcode
        ),
        "DOB" => array(
          "d" => "",
          "m" => "",
          "y" => ""
        )
      ),
    ),
    "AccountInfo" => array(
      "UserIdentity" => "",
      "JobSeekerProfiles" => array(),
      "JobSeekerApplications" => array(),
      "JobSeekerSavedJobs" => array(),
      "Recommendations" => array(),
      "ProfileDetails" => array(
        "CreatedDate" => array(
          "d" => date('d'),
          "m" => date('m'),
          "y" => date('y')
        )
      )
    ),

  );

  echo("<br><br>");


  $doc = $col->findOne(["_id"=>1001]);//testing

  echo json_encode($doc);//teting
}

 ?>
