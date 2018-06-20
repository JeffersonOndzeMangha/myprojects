<?php
/*
Content: Contains the Employer class, including all functions,
         and variables, that can be accessed by other pages,
         in order to carry out Job Seeker related events.
Author(s): Jefferson Ondze Mangha, 
Contributor: David Alton Myers
CopyRightOf: justhourly.com
Date: 09/31/2017
V: 1.0.0
*/

//Include EmployerInterface
require './interfaces/EmployerInterface.php';
/**
 * EmployerClass
 */
class Employer_ extends functions_ implements EmployerInterface
{
  public $errors = [];
  public $successes = [];
  public $user_db_connection;
  public $user_information;

  public function __construct()
  {
  }

  public function SignUp(dbConnection_ $dbConnection_, $newEmployeremail, $newEmployerpassword, $newEmployerzipcode){
    $adminConnect = $dbConnection_->adminConnect();
    $db = $adminConnect->justhourly_qa_db;
    $col = $db->EmployersProfileInfo_Col;

    $EmployerIdentity = new MongoDB\BSON\ObjectId();

    //Creat User command, to add user to database
    $createUserCommand = array(
      "createUser"=> $this->emailStripper($newEmployeremail),
      "pwd" => $this->dataEncryption($newEmployerpassword),
      'customData' =>
      [
        "UserIdentity" => $EmployerIdentity, //$identity,
        "Email" => $newEmployeremail
      ],
      "roles" =>
      [
          ["role" => 'JustHourlyEmployer', "db" => 'justhourly_qa_db']
      ]
    );

    //Creates user profile to enter into the database collection (done first)
    $userProfileDocument = array(
      "_id" => $EmployerIdentity,
      "Company_id" => $this->emailStripper($newEmployeremail),
      "AdminPersonalInfo" => array(
        "Name" => array(
          "FirstName" => "",
          "LastName" => ""
        ),
        "ContactInfo" => array(
          "PhoneNumber" => "",
          "Email" => $newEmployeremail,
        )
      ),
      "CompanyInfo" => array(
        "CompanyName" => "",
        "Address" => array(
          "Street" => "",
          "City" => "",
          "State" => "",
          "ZipCode" => $newEmployerzipcode
        ),
        "Industry" => "",
      ),
      "Inbox" => array(),//check to see if needed
      "AccountInfo" => array(
        "CompanyIdentity" => $EmployerIdentity,
        "PostedJobs" => array(),
        "ExpiredJobs" => array(),
        "SavedJobSeekerProfiles" => array(),
        "ProfileDetails" => array(
          "Active" => true,
          "Verified" => false,
          "AccountType" => "",
          "CreatedDate" => date('dd mm yyyy')
        ),
      )
    );

    try {
      $db->command($createUserCommand);
      $this->successes["user_created"] = ["status" => true, "message" => "User created"];
      try {
        $col->insertOne($userProfileDocument);
        $this->successes["profile_created"] = ["status" => true, "message" => "Profile created"];
        return true;
      } catch (Exception $e) {
        $this->errors["profile_created"] = ["status" => false, "message" => $e->getMessage()];
        return false;
      }
    } catch (Exception $e) {
      $this->errors["user_created"] = ["status" => false, "message" => $e->getMessage()];
      return false;
    }
    //$doc = $col->findOne(["_id"=>1001]);

    //echo json_encode($doc);
  }

/*  public function SignIn(dbConnection_ $dbConnection_, $Employeremail, $Employerpassword){
    $dbConnect = $dbConnection_->dbConnect($this->emailStripper($Employeremail), $this->dataEncryption($Employerpassword));
    if($dbConnect){
      $this->user_db_connection = $dbConnect;
      $db = $dbConnect->justhourly_qa_db;
      $col = $db->EmployersProfileInfo_Col;
      $doc = $col->findOne(array("User_id" => $this->emailStripper($Employeremail)));
      $this->user_information = (array)$doc;
      $this->successes["user_login"] = ["status" => true, "message" => "Login successful"];
      //return true;
    }else {
      $this->errors["user_login"] = ["status" => false, "message" => "Login failed"];
      return false;
    }
  }

  public function JobApply($user_db_connection, $EmployerIdentity, $postedjobidentity){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->EmployersProfileInfo_Col->findOne(array("_id" => $EmployerIdentity)); //find users' profile information
    $job = (array)$db->PostedJobsInfo_Col->findOne(array("_id"=> $postedjobidentity)); //find the jobs' information

    //$userapplications = (array)$user["AccountInfo"]["EmployerApplications"];// get all current user applications
    //array_Push($userapplications, $postedjobidentity);// update user applications array
    //$jobapplicants = (array)$job["Applicants"];//array of applicants for the posted job
    //array_Push($jobapplicants, $EmployerIdentity);//add current user to the applicants array/list

    //update user profile in the database with new information
    try {
      $db->EmployersProfileInfo_Col->updateOne([
          "_id"=>$EmployerIdentity
        ],
        [
          '$addToSet' =>  ["AccountInfo.EmployerApplications" => $postedjobidentity ] //update users applications array/list with new application
        ]
      );
      $db->PostedJobsInfo_Col->updateOne([
          "_id"=>$postedjobidentity
        ],
        [
          '$addToSet' => ["Applicants" => $EmployerIdentity]
        ]
      );
      $this->successes["job_applied"] = ["status" => true, "message" => "Application successeful"];
      return true;
    } catch (Exception $e) {
      $this->errors["job_applied"] = ["status" => false, "message" => "An error occured, please try again"];
      return false;
    }
  }

  public function BuildIndustySpecificProfile($user_db_connection, $EmployerIdentity, $UserJobProfile, $IndustryIdentity){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->EmployersProfileInfo_Col->findOne(array("_id" => $EmployerIdentity)); //find users' profile information

    $ProfileIdentity = new MongoDB\BSON\ObjectId();

    //creating the profile information for the user
    $ProfileInfo = array(
      "id" => $ProfileIdentiy,
      'Industry' => $IndustryIdentity,
      "CreatedDate" => date('dd mm yyyy'),
      "LastUpdateDate" => date('l M j Y')
    );

    //$userprofiles = (array)$user["AccountInfo"]["EmployerProfiles"];// get all current user industry based profiles
    //array_Push($userprofiles, $ProfileInfo);//add profile to user profiles array/list
    //$user["AccountInfo"]["EmployerProfiles"] = $userprofiles;//update user profiles array

    try {
      $db->EmployersProfileInfo_Col->updateOne([
          "_id"=>$EmployerIdentity
        ],
        [
          '$addToSet' => ["AccountInfo.EmployerProfiles" => [$ProfileIdentity => $ProfileInfo]]
        ]
      );
      $db->UserJobProfiles_Col->insertOne(
        array(
          "_id" => $ProfileIdentity,
          "Industry" => $IndustryIdentity,
          "Data" => $UserJobProfile,
          "EmployerId" => $EmployerIdentity
        )
      );
      $this->successes["industryprofile_created"] = ["status" => true, "message" => "Profile created"];
      return true;
    } catch (Exception $e) {
      $this->errors["industryprofile_created"] = ["status" => true, "message" => "Profile could not be created"];
    }

  }

  public function UpdateEmployerPersonalProfileInfo($user_db_connection, $EmployerIdentity, $NewEmployerInfo){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->EmployersProfileInfo_Col->findOne(array("_id" => $EmployerIdentity)); //find users' profile information

    try {
      $db->EmployersProfileInfo_Col->updateOne([
          "_id"=>$EmployerIdentity
        ],
        [
          '$set' => [
            "PersonalInfo.Name.FirstName" => $NewEmployerInfo["Name"]["FirstName"],
            "PersonalInfo.Name.LastName" => $NewEmployerInfo["Name"]["LastName"],
            "PersonalInfo.ContactInfo.PhoneNumber" => $NewEmployerInfo["ContactInfo"]["PhoneNumber"],
            "PersonalInfo.ContactInfo.Address.Street" => $NewEmployerInfo["ContactInfo"]["Address"]["Street"],
            "PersonalInfo.ContactInfo.Address.City" => $NewEmployerInfo["ContactInfo"]["Address"]["City"],
            "PersonalInfo.ContactInfo.Address.State" => $NewEmployerInfo["ContactInfo"]["Address"]["State"],
            "DOB" => $NewEmployerInfo["DOB"]
          ]
        ]
      );
      $this->successes["personalinfo_updated"] = ["status" => true, "message" => "Personal info updated"];
      return true;
    } catch (Exception $e) {
      $this->errors["personalinfo_updated"] = ["status" => false, "message" => "Update failed"];
      return false;
    }

  }

  public function UpdateEmployerResumeScheduleProfileInfo($user_db_connection, $EmployerIdentity, $NewEmployerInfo){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->EmployersProfileInfo_Col->findOne(array("_id" => $EmployerIdentity)); //find users' profile information

      try {
        $db->EmployersProfileInfo_Col->updateOne([
          "_id" => $EmployerIdentity
        ],
        [
          '$set' => [
            'ResumeInfo.Eligible' => $NewEmployerInfo["Eligible"],
            'ResumeInfo.CriminalOffense' => $NewEmployerInfo["CriminalOffense"],
            'ResumeInfo.EmployementType' => $NewEmployerInfo["EmployementType"],
            'ResumeInfo.Schedule.AbleToWork' => $NewEmployerInfo["Schedule"]["AbleToWork"],
            'ResumeInfo.Schedule.OverTime' => $NewEmployerInfo["Schedule"]["OverTime"],
            'ResumeInfo.Schedule.CanStart' => $NewEmployerInfo["Schedule"]["CanStart"],
            'ResumeInfo.Schedule.Availability' => $NewEmployerInfo["Schedule"]["Availability"]
          ]
        ]);
        $this->successes["resumeinfo_updated"] = ["status" => true, "message" => "Resume info updated"];
        return true;
      } catch (Exception $e) {
        $this->errors["resumeinfo_updated"] = ["status" => false, "message" => "Update failed"];
        return false;
      }

  }

  public function UpdateIndustrySpecificProfile($user_db_connection, $EmployerIndentity, $ProfileIdentity, $NewEmployerProfileInfo){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->EmployersProfileInfo_Col->findOne(array("_id" => $EmployerIdentity)); //find users' profile information

    try {
      $db->EmployersProfileInfo_Col->updateOne([
        "_id" => $EmployerIdentity
      ],
      [
        '$set' => [
          'AccountInfo.EmployerProfiles.'.$ProfileIdentiy.'.LastUpdateDate' => date('l M j Y')
        ]
      ]);
      $db->UserJobProfiles_Col->updateOne([
        ["_id" => $ProfileIdentity],
        [
          '$set' => ["Data" => $NewEmployerProfileInfo]
        ]
      ]);

      $this->successes["industryprofile_updated"] = ["status" => true, "message" => "Profile info updated"];
      return true;
    } catch (Exception $e) {
      $this->errors["industryprofile_updated"] = ["status" => false, "message" => "Update failed"];
      return false;
    }
  }

  public function FollowUpWithEmployer($user_db_connection, $EmployerIndentity, $PostedJobIdentity, $Message, $EmployerIdentity, $ChatId = null, $UserName) {// function for Job Seekers to follow up with employers directly from our system (will have a phone link for phone contact)
    if ($ChatId == null) {//check if chat already exists
      $db = $user_db_connection->justhourly_qa_db; //connect to db
      $ChatId = new MongoDB\BASON\ObjectId(); //new chat ID created
      //create a new chat in the chat's collection
      try {
        $db->Chats_Col->insertOne(
          ["_id" => $ChatId],
          [
            "Chat_info" => [
              "Employer" => $EmployerIdentity,
              "Employer" => $EmployerIdentity,
              "New" => true,
              "CreatedDate" => date()
            ],
            "Messages" => array()
          ]
        );
        $db->Chats_Col->updateOne(
          ["_id" => $ChatId],
          [
            '$addToSet' => [
              "Messages" => [
                "Message" => $Message,
                "From" => $UserName,
                "SentDate" => date("dd mm yyyy"),
                "SentTime" => time()
                "Read" => false
              ]
            ]
          ]
        );
        $db->EmployersProfileInfo_Col->updateOne(
          ["_id" => $EmployerIdentity],
          [
            '$addToSet' => [
              "Inbox" => $ChatId
            ]
          ]
        );

      } catch (Exception $e) {

      }


    }else {//if chat doesn't already exist

    }
  }

}

*/ //block is commented for testing purposes

 ?>
