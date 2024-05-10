// Get elements
var formmodal = document.getElementById("formModal");
var openBtn = document.getElementById("AccountModalOpen");
var closeBtn = document.getElementsByClassName("close-buttonprofile")[0];
var dropdowns = document.getElementById("DrpDwnProfile");
var DropDownClicked = false;
//Theft modal
var BikeModal = document.getElementById("TrackerModal");
var BikeOpnBtn = document.getElementById("TrackerModalOpen");
var BikecloseBtn = document.getElementsByClassName("close-buttonTracker")[0];

window.onclick = function(event) {
    if (event.target == formmodal) { //If the user clicks off the AccountModal
        formmodal.style.display = "none";
    }
    if (event.target == BikeModal) { //If the user clicks off the BikeModal
        BikeModal.style.display = "none";
    } //If the user clicks anywhere but the dropdown list
    if (event.target !== dropdowns && document.getElementById("DrpDwnProfile").style.display === 'block' && DropDownClicked == true) {
        console.log("Style none");
        document.getElementById("DrpDwnProfile").style.display = 'none';
    }
    DropDownClicked = false;
}
function ProfileClick() {
    document.getElementById("DrpDwnProfile").style.display = 'block';
    DropDownClicked = true;
    event.stopPropagation(); // Stop event bubbling

}
openBtn.onclick = function() {// Open Account modal function
    formmodal.style.display = "flex";
}
closeBtn.onclick = function() {// Close Account modal function
    formmodal.style.display = "none";
}
BikeOpnBtn.onclick = function() { //Open Edit Tracker Modal
    console.log("Open Bike modal");
    BikeModal.style.display = "flex";
}
BikecloseBtn.onclick = function() {// Close Edit Tracker modal
    console.log("Close Bike modal");
    BikeModal.style.display = "none";
}


