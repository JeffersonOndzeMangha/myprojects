<?php
/*
Content: Contains the Employer class, including all functions,
         and variables, that can be accessed by other pages,
         in order to carry out Job Seeker related events.
Author(s): Jefferson Ondze Mangha
CopyRightOf: justhourly.com
Date: 09/31/2017
V: 1.0.0
*/

//Include JobSeekerInterface
require './interfaces/JobSeekerInterface.php';
/**
 * JobSeekerClass
 */
class JobSeeker_ extends functions_ implements JobSeekerInterface
{
  public $errors = [];
  public $successes = [];
  public $user_db_connection;
  public $user_information;

  public function __construct()
  {
  }

  public function SignUp(dbConnection_ $dbConnection_, $newjobseekeremail, $newjobseekerpassword, $newjobseekerzipcode){
    $adminConnect = $dbConnection_->adminConnect();
    $db = $adminConnect->justhourly_qa_db;
    $col = $db->JobSeekersProfileInfo_Col;

    $JobSeekerIdentity = new MongoDB\BSON\ObjectId();

    //Creat User command, to add user to database
    $createUserCommand = array(
      "createUser"=> $this->emailStripper($newjobseekeremail),
      "pwd" => $this->dataEncryption($newjobseekerpassword),
      'customData' =>
      [
        "UserIdentity" => $JobSeekerIdentity, //$identity,
        "Email" => $newjobseekeremail
      ],
      "roles" =>
      [
          ["role" => 'JustHourlyJobSeeker', "db" => 'justhourly_qa_db']
      ]
    );

    //Creates user profile to enter into the database collection (done first)
    $userProfileDocument = array(
      "_id" => $JobSeekerIdentity,
      "User_id" => $this->emailStripper($newjobseekeremail),
      "PersonalInfo" => array(
        "Name" => array(
          "FirstName" => "",
          "LastName" => ""
        ),
        "Educatiaon" => array(
          array(
            "Category" => "", //college, highschool, graduate school etc...
            "InstitutionName" => "", //i.e UT Arlington
            "GraduationDate" => "", //month/year
            "Degree" => "", //high school diploma, Undergrad
          )
        ),
        "ContactInfo" => array(
          "PhoneNumber" => "",
          "Email" => $newjobseekeremail,
          "Address" => array(
            "Street" => "",
            "City" => "",
            "State" => "",
            "ZipCode" => $newjobseekerzipcode
          )
        ),
        "DOB" => ""
      ),
      "Inbox" => array(),//check to see if needed
      "ResumeInfo" => array(
        "Eligible" => "", //true || false,
        "CriminalOffense" => "",// ture|| false
        "EmployementType" => "",//"FullTime" || "PartTime" || "Any" || "Seasonal" || "Temporary"
          "Schedule" => array(
            "AbleToWork" => "",//["Day", "Afternoon", "Evening", "Night", "Graveyard", "Any"]
            "OverTime" => "", //true || false
            "CanStart" => "", //2 days after being hired
            "Availability" => array(
              "Monday" => "",
              "Tuesday" => "",
              "Wednesday" => "",
              "Thursday" => "",
              "Friday" => "",
              "Saturday" => "",
              "Sunday" => ""
            ),
        )
      ),
      "AccountInfo" => array(
        "UserIdentity" => $JobSeekerIdentity,
        "JobSeekerProfiles" => array(),
        "JobSeekerApplications" => array(),
        "JobSeekerSavedJobs" => array(),
        "Recommendations" => array(),
        "ProfileDetails" => array(
          "Active" => true,
          "Verified" =>  false,
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

  public function SignIn(dbConnection_ $dbConnection_, $jobseekeremail, $jobseekerpassword){
    $dbConnect = $dbConnection_->dbConnect($this->emailStripper($jobseekeremail), $this->dataEncryption($jobseekerpassword));
    if($dbConnect){
      $this->user_db_connection = $dbConnect;
      $db = $dbConnect->justhourly_qa_db;
      $col = $db->JobSeekersProfileInfo_Col;
      $doc = $col->findOne(array("User_id" => $this->emailStripper($jobseekeremail)));
      $this->user_information = (array)$doc;
      $this->successes["user_login"] = ["status" => true, "message" => "Login successful"];
      //return true;
    }else {
      $this->errors["user_login"] = ["status" => false, "message" => "Login failed"];
      return false;
    }
  }

  public function JobApply($user_db_connection, $JobSeekerIdentity, $postedjobidentity){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->JobSeekersProfileInfo_Col->findOne(array("_id" => $JobSeekerIdentity)); //find users' profile information
    $job = (array)$db->PostedJobsInfo_Col->findOne(array("_id"=> $postedjobidentity)); //find the jobs' information

    //$userapplications = (array)$user["AccountInfo"]["JobSeekerApplications"];// get all current user applications
    //array_Push($userapplications, $postedjobidentity);// update user applications array
    //$jobapplicants = (array)$job["Applicants"];//array of applicants for the posted job
    //array_Push($jobapplicants, $JobSeekerIdentity);//add current user to the applicants array/list

    //update user profile in the database with new information
    try {
      $db->JobSeekersProfileInfo_Col->updateOne([
          "_id"=>$JobSeekerIdentity
        ],
        [
          '$addToSet' =>  ["AccountInfo.JobSeekerApplications" => $postedjobidentity ] //update users applications array/list with new application
        ]
      );
      $db->PostedJobsInfo_Col->updateOne([
          "_id"=>$postedjobidentity
        ],
        [
          '$addToSet' => ["Applicants" => $JobSeekerIdentity]
        ]
      );
      $this->successes["job_applied"] = ["status" => true, "message" => "Application successeful"];
      return true;
    } catch (Exception $e) {
      $this->errors["job_applied"] = ["status" => false, "message" => "An error occured, please try again"];
      return false;
    }
  }

  public function BuildIndustySpecificProfile($user_db_connection, $JobSeekerIdentity, $UserJobProfile, $IndustryIdentity){
    $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
    $user = (array)$db->JobSeekersProfileInfo_Col->findOne(array("_id" => $JobSeekerIdentity)); //find users' profile information

    $ProfileIdentity = new MongoDB\BSON\ObjectId();

    //creating the profile information for the user
    $ProfileInfo = array(
      "id" => $ProfileIdentiy,
      'Industry' => $IndustryIdentity,
      "CreatedDate" => date('dd mm yyyy'),
      "LastUpdateDate" => date('l M j Y')
    );

    //$userprofiles = (array)$user["AccountInfo"]["JobSeekerProfiles"];// get all current user industry based profiles
    //array_Push($userprofiles, $ProfileInfo);//add profile to user profiles array/list
    //$user["AccountInfo"]["JobSeekerProfiles"] = $userprofiles;//update user profiles array

    try {
      $db->JobSeekersProfileInfo_Col->updateOne([
          "_id"=>$JobSeekerIdentity
        ],
        [
          '$addToSet' => ["AccountInfo.JobSeekerProfiles" => [$ProfileIdentity => $ProfileInfo]]
        ]
      );
      $db->UserJobProfiles_Col->insertOne(
        array(
          "_id" => $ProfileIdentity,
          "Industry" => $IndustryIdentity,
          "Data" => $UserJobProfile,
          "JobSeekerId" => $JobSeekerIdentity
        )
      );
      $this->successes["industryprofile_created"] = ["status" => true, "message" => "Profile created"];
      return true;
    } catch (Exception $e) {
      $this->errors["industryprofile_created"] = ["status" => true, "message" => "Profile could not be created"];
    }

  }

  public function UpdateJobSeekerPersonalProfileInfo($user_db_connection, $JobSeekerIdentity, $NewJobSeekerInfo){
  $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
  $user = (array)$db->JobSeekersProfileInfo_Col->findOne(array("_id" => $JobSeekerIdentity)); //find users' profile information

  try {
    $db->JobSeekersProfileInfo_Col->updateOne([
        "_id"=>$JobSeekerIdentity
      ],
      [
        '$set' => [
          "PersonalInfo.Name.FirstName" => $NewJobSeekerInfo["Name"]["FirstName"],
          "PersonalInfo.Name.LastName" => $NewJobSeekerInfo["Name"]["LastName"],
          "PersonalInfo.ContactInfo.PhoneNumber" => $NewJobSeekerInfo["ContactInfo"]["PhoneNumber"],
          "PersonalInfo.ContactInfo.Address.Street" => $NewJobSeekerInfo["ContactInfo"]["Address"]["Street"],
          "PersonalInfo.ContactInfo.Address.City" => $NewJobSeekerInfo["ContactInfo"]["Address"]["City"],
          "PersonalInfo.ContactInfo.Address.State" => $NewJobSeekerInfo["ContactInfo"]["Address"]["State"],
          "DOB" => $NewJobSeekerInfo["DOB"]
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

  public function UpdateJobSeekerResumeScheduleProfileInfo($user_db_connection, $JobSeekerIdentity, $NewJobSeekerInfo){
  $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
  $user = (array)$db->JobSeekersProfileInfo_Col->findOne(array("_id" => $JobSeekerIdentity)); //find users' profile information

    try {
      $db->JobSeekersProfileInfo_Col->updateOne([
        "_id" => $JobSeekerIdentity
      ],
      [
        '$set' => [
          'ResumeInfo.Eligible' => $NewJobSeekerInfo["Eligible"],
          'ResumeInfo.CriminalOffense' => $NewJobSeekerInfo["CriminalOffense"],
          'ResumeInfo.EmployementType' => $NewJobSeekerInfo["EmployementType"],
          'ResumeInfo.Schedule.AbleToWork' => $NewJobSeekerInfo["Schedule"]["AbleToWork"],
          'ResumeInfo.Schedule.OverTime' => $NewJobSeekerInfo["Schedule"]["OverTime"],
          'ResumeInfo.Schedule.CanStart' => $NewJobSeekerInfo["Schedule"]["CanStart"],
          'ResumeInfo.Schedule.Availability' => $NewJobSeekerInfo["Schedule"]["Availability"]
        ]
      ]);
      $this->successes["resumeinfo_updated"] = ["status" => true, "message" => "Resume info updated"];
      return true;
    } catch (Exception $e) {
      $this->errors["resumeinfo_updated"] = ["status" => false, "message" => "Update failed"];
      return false;
    }

}

  public function UpdateIndustrySpecificProfile($user_db_connection, $JobSeekerIndentity, $ProfileIdentity, $NewJobSeekerProfileInfo){
  $db = $user_db_connection->justhourly_qa_db; //connect to the correct database
  $user = (array)$db->JobSeekersProfileInfo_Col->findOne(array("_id" => $JobSeekerIdentity)); //find users' profile information

  try {
    $db->JobSeekersProfileInfo_Col->updateOne([
      "_id" => $JobSeekerIdentity
    ],
    [
      '$set' => [
        'AccountInfo.JobSeekerProfiles.'.$ProfileIdentiy.'.LastUpdateDate' => date('l M j Y')
      ]
    ]);
    $db->UserJobProfiles_Col->updateOne([
      ["_id" => $ProfileIdentity],
      [
        '$set' => ["Data" => $NewJobSeekerProfileInfo]
      ]
    ]);

    $this->successes["industryprofile_updated"] = ["status" => true, "message" => "Profile info updated"];
    return true;
  } catch (Exception $e) {
    $this->errors["industryprofile_updated"] = ["status" => false, "message" => "Update failed"];
    return false;
  }
}

  public function FollowUpWithEmployer($user_db_connection, $JobSeekerIndentity, $PostedJobIdentity, $Message, $EmployerIdentity, $ChatId = null, $UserName) {// function for Job Seekers to follow up with employers directly from our system (will have a phone link for phone contact)
    if ($ChatId == null) {//check if chat already exists
      $db = $user_db_connection->justhourly_qa_db; //connect to db
      $ChatId = new MongoDB\BASON\ObjectId(); //new chat ID created
      //create a new chat in the chat's collection
      try {
        $db->Chats_Col->insertOne(
          ["_id" => $ChatId],
          [
            "Chat_info" => [
              "JobSeeker" => $JobSeekerIdentity,
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
                "SentTime" => time(),
                "Read" => false
              ]
            ]
          ]
        );
        $db->JobSeekersProfileInfo_Col->updateOne(
          ["_id" => $JobSeekerIdentity],
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

 ?>
