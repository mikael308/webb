
var valid_color = "rgb(204, 255, 204)";
var invalid_color = "rgb(255, 179, 179)";

/**
* validates password and sets password hints
* @param password the password to validate
*/
function validatePasswordHint(password){
	var clean_password = password.replace(" ", "");

	validateLength(clean_password);
	validateContent(clean_password);
	
}
/**
* set the password hint to state.
* @param elem_id element id
* @param valid the valid state of the hint
*/
function setHint(elem_id, valid){
	document.getElementById(elem_id).style.backgroundColor = valid ? valid_color : invalid_color;

}
/**
* validate the length of the password
* @param password the password to validate
*/
function validateLength(password){
	var valid = false;
	if (password.length >= 6){
		valid = true;
	}

	setHint("password_length", valid);
	
}
/**
* validate the content of the password
* @param password the password to validate
*/
function validateContent(password){
	var valid = false;
	if(/[a-z]+/.test(password)
		&& /[A-Z]+/.test(password)
		&& /[0-9]+/.test(password)
		&& /[\\.!_\\-]+/.test(password)){
		valid = true;
	}

	setHint("password_content", valid);

}
