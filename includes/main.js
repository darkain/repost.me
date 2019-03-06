function showPassword(state) {
	if(state == true) {
		$("#txtPassword").fadeIn(500);
	} else {
		$("#txtPassword").fadeOut(500);
	}
}



// Main
$(document).ready(function() {
	// Raido Public onClick
	$("#radPublic").click(function() {
		showPassword(false);		
	}); 
	// Raido Private onClick
	$("#radPrivate").click(function() {
		showPassword(true);		
	}); 
	
//	$("#txtLongUrl").defaultvalue("Enter URL");
	
	if ($("#radPrivate").attr("checked")) showPassword(true);

 });