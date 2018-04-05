class Person {
 var fName;
 var lName;
 var age;

  constructor(fName, lName, age) {
    $(this).fName = "david";
    $(this).lName = "myers";
    $(this).age = 22;
  }

public function ChangeName(){
  $(this).fName = "Jefferson";
  alert('Name Changed');
}

}
