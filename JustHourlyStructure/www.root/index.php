<?php
/* For TESTING perpuses */

require '../vendor/autoload.php';
require './configs/dbConnection_class.php';
require './configs/functions_class.php';
//require './interfaces/JobSeekerInterface.php';
require './classes/JobSeeker_Class.php';

//$JobSeeker = new JobSeeker_();

//$JobSeeker->SignUp(new dbConnection_(), "test2@test1.com", "test1", 76014);
//$JobSeeker->SignUp(new dbConnection_(), "test2@test.com", "test2", 76014);
//echo "<br>";
//var_dump($JobSeeker->errors);
//echo "<br>";
//var_dump($JobSeeker->successes);

//$JobSeeker->SignIn(new dbConnection_(), "test1@test.com", "test1");
/*$data = [
  "Name" => "test",
  "Experience" => 4,
  "Industry" => "Restaurant"
];*/

$data = array(
  "Name" => array(
    "FirstName" => "Jefferson",
    "LastName" => "Ondze Mangha"
  ),
  "ContactInfo" => array(
    "PhoneNumber" => "**********",
    "Address" => array(
      "Street" => "T801 Salem Dr",
      "City" => "FakeTown",
      "State" => "Texas",
    )
  ),
  "Age" => 20,
  "DOB" => "07/25/1980"
);
//$industry_id = 105;
//$JobSeeker->JobApply($JobSeeker->user_db_connection, $JobSeeker->user_information["_id"], "justfake10");
//$JobSeeker->BuildIndustySpecificProfile($JobSeeker->user_db_connection, $JobSeeker->user_information["_id"], $data, $industry_id);
//$JobSeeker->UpdateJobSeekerPersonalProfileInfo($JobSeeker->user_db_connection, $JobSeeker->user_information["_id"], $data);
//echo($JobSeeker->user_information["PersonalInfo"]["Name"]["FirstName"]);

//$JobSeeker->SignIn(new dbConnection_(), "test1@test.com", "test1");
//var_dump($JobSeeker->errors);
//echo("<br>");
//var_dump($JobSeeker->user_information["User_id"]);
//echo("<br>");
//var_dump($JobSeeker->successes);

//$JobSeeker->FollowUpWithEmployer("jefferson", "job54", "I want to meet", "employer4", "chat54");

?>
