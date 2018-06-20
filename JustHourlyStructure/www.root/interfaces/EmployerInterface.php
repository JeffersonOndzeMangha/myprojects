<?php
/*
Content: Contains interface/outline of the Emplyer class
         with all the methods that can be used in the class.
Author: Jefferson Ondze Mangha
CopyRightOf: justhourly.com
Date: 09/29/2017
V: 1.0.0
*/


/**
 * EmployerInterface
 */
interface EmployerInterface
{

  public function SignUp(dbConnection_ $adminConnection, $JobSeekerUsername, $JobSeekerPassword, $JobSeekerZipCode);// self explainatory, function for signing up new JobSeekers into our system.

  //public function SignIn(dbConnection_ $dbConnection, $JobSeekerUsername, $JobSeekerPassword);// self explainatory, function for returning JobSeekers to sign in.

  //public function JobApply($userdbconnection, $JobSeekerIndentity, $PostedJobIdentity); // function for JobSeekers to applie for jobs

  //public function BuildIndustySpecificProfile($user_db_connection, $jobseekerindentity, $UserJobProfile, $IndustryIdentity); //function for building industry specific profiles i.e. Retail, Restaurant, etc...

  //public function UpdateJobSeekerPersonalProfileInfo($user_db_connection, $JobSeekerIndentity, $NewJobSeekerInfo); //Once signed up/logged in, can update personal profile info to give us more details

  //public function UpdateJobSeekerResumeScheduleProfileInfo($user_db_connection, $JobSeekerIndentity, $NewJobSeekerInfo); //Once signed up/logged in, can update resume profile info to give us more details

  //public function UpdateIndustrySpecificProfile($user_db_connection, $JobSeekerIndentity, $ProfileIdentiy, $NewJobSeekerProfileInfo);// basically update resume information (in this case the industrial profile)

  //public function FollowUpWithEmployer($user_db_connection, $JobSeekerIndentity, $PostedJobIdentity, $Message, $EmployerIdentity, $ChatID = null, $UserName); // function for Job Seekers to follow up with employers directly from our system (will have a phone link for phone contact)

  //public function RecommendOthers($JobSeekerIndentity, $RecommendPersonEmail); // function for Job Seekers to recommend their friends and family to our system, in order to earn points

}






 ?>
