// JavaScript Document
//variables

var theHeader, myContact, dropDown;
	theHeader = document.getElementById("header");
	myContact = document.getElementById("contact");
	dropDown = document.getElementById("drop");


// menu events
window.addEventListener("scroll", scroll)

function scroll(){
	if (window.pageYOffset > 106){
		theHeader.className = "headerscroll";
		myContact.className = "contact2";
	}
	else if (window.pageYOffset < 5){
		theHeader.className = "header";
		myContact.className = "contact";
	}
}

dropDown.addEventListener("click", dropdown)

function dropdown(){
	if (theHeader.className == "header"){
		theHeader.className = "headerdown";
	}
	if (theHeader.className == "headerscroll"){
		theHeader.className = "headerdown";
		myContact.className = "contact";
	}
}


function up(){
	if (theHeader.className == "headerdown"){
		theHeader.className = "header";
	}
	if (window.pageYOffset > 106){
		theHeader.className = "headerscroll";
		myContact.className = "contact2";
	}
}

var  learnmore_Btn; 
learnmore_Btn = document.getElementById("learnmore_btn");

learnmore_Btn.addEventListener("click", learnMore)

function learnMore(){
	var info_Video = document.getElementById("info_video");
	if (info_Video.className =="info_video") {
		info_Video.className = "info_video2";
	}

}

