<?php
db.createUser(
  {
    user: "JustHourlyUserAdmin",
    pwd: "37ea04e0fe6a72726a203a84febb5e23",
    customData:
    {
      UserType: "UserAdmin",
      Description: "In charge of signing up new users on the website and creating profile documents in the different collections."
    },
    roles:
    [
      { role: "JustHourlyUserAdmin", db: "justhourly_qa_db" },
    ]
  },
  { w: "majority" , wtimeout: 5000 }
)

db.createRole(
   {
     role: "JustHourlyUserAdmin",
     privileges: [
       { resource: { db: "justhourly_qa_db", collection: "JobSeekersProfileInfo_Col" }, actions: [ "insert", "find", "update" ] }
     ],
     roles:
     [
       { role: "userAdmin", db: "justhourly_qa_db" },
     ]
   }
)

db.createRole(
   {
     role: "JustHourlyJobSeeker",
     privileges: [
       { resource: { db: "justhourly_qa_db", collection: "JobSeekersProfileInfo_Col" }, actions: [ "insert", "find", "update" ] },
       { resource: { db: "justhourly_qa_db", collection: "UserJobProfiles_Col" }, actions: [ "insert", "find", "update" ] },
       { resource: { db: "justhourly_qa_db", collection: "EmployerProfileInfo_Col" }, actions: ["find"] },
       { resource: { db: "justhourly_qa_db", collection: "PostedJobsInfo_Col" }, actions: ["find", "update"] },
       { resource: { db: "justhourly_qa_db", collection: "" }, actions: ["changeOwnPassword", "changeOwnCustomData"] }
     ],
     roles: []
   }
)

db.createRole(
   {
     role: "JustHourlyEmployer",
     privileges: [
       { resource: { db: "justhourly_qa_db", collection: "JobSeekersProfileInfo_Col" }, actions: [ "find" ] },
       { resource: { db: "justhourly_qa_db", collection: "EmployerProfileInfo_Col" }, actions: ["find", "insert", "update"] },
       { resource: { db: "justhourly_qa_db", collection: "PostedJobsInfo_Col" }, actions: ["insert", "update", "find"] },
       { resource: { db: "justhourly_qa_db", collection: "TempPostedJobs_Col" }, actions: ["insert", "update", "find"] },
       { resource: { db: "justhourly_qa_db", collection: "ExpiredJobsInfo_Col" }, actions: ["update", "find"] },
       { resource: { db: "justhourly_qa_db", collection: "" }, actions: ["changeOwnPassword", "changeOwnCustomData"] }
     ],
     roles: []
   }
)

 ?>
