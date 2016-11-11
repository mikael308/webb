
/**

add functions to all elements of class dropbtn:
make related dropdown-content visible 

@author Mikael Holmbom
*/


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

