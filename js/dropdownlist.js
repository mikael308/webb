
/**
 *
 * add functions to all elements of class dropbtn:
 * make related dropdown-content visible 
 * 
 * @author Mikael Holmbom
 */

/**
 * close all opened dropdown-content lists
 * @author Mikael Holmbom
 * @version 1.0
 */
function closeAllDropdownlists(){
	var ddContents = 
		document
		.getElementsByClassName("dropdown-content");

	for(var j = 0; j < ddContents.length; j++){
		var ddc = ddContents[j];

		if (ddc.style.display != "none")
			ddc.style.display = "none";
	}
}


var ddbtns = document.getElementsByClassName("dropbtn");

for(var i = 0; i < ddbtns.length; i++){

	ddbtns[i].onclick = function(){
		var cont =  this.parentNode.parentNode.getElementsByClassName("dropdown-content")[0];

		cont.style.display = "inherit";
		// listen for outside click to close dropdown-content
		window.onclick = function(event) {
			if (!event.target.matches('.dropbtn')) {
				var ddc = document.getElementsByClassName("dropdown-content")[0];
				
				if(ddc.style.display != "none")
					ddc.style.display = "none";

			}
		}
	}
}

