
/**
 * call AJAX get_user_list
 * shows 
 * @author Mikael Holmbom
 */

/**
 * get suggestions from gethint.php
 * @param str search string
 * @param elem_id the element for output
 */
function showSuggestedUsers(str, elem_id){

	var target = document.getElementById(elem_id);

	if(str.length == 0){
		target.innerHTML = "";
		target.style.border="none";
		return;
		
	} else {

		var xmlhttp;
		if (window.XMLHttpRequest) {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {  // code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
	    	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	    		var response = xmlhttp.responseText;
	    		if(target != null){
					target.innerHTML = response; 
					target.style.border="2px solid #A5ACB2";
				}

		    }
		};
		xmlhttp.open("GET", "get_user_list.php?id=" + str, true);
		xmlhttp.send();

	}
}
		

/**
 * send a searchstring if radiobutton is currently set to user
 * @param s search value
 */
function searchUser(s){

	var radio_btns = document.getElementsByName("search_type");
	for (var i = 0; i < radio_btns.length; i++){
			
			if(radio_btns[i].checked == true
				&& radio_btns[i].value =="user"){
				showSuggestedUsers(s, "suggestionlist");
				break;
			}
	}
	
}
		

