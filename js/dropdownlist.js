
/**

add functions to all elements of class dropbtn:
make related dropdown-content visible 

@author Mikael Holmbom
*/
var ddbtns = document.getElementsByClassName("dropbtn");

for(var i = 0; i < ddbtns.length; i++){
	ddbtns[i].onclick = function(){
		var cont =  this.parentNode.getElementsByClassName("dropdown-content");

		cont[0].style.display = "inherit";

	}
}

