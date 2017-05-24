/**
 * Toggle between adding and removing the "active" and "show"
 * classes when the user clicks on one of the "accordion" buttons.
 */
 
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
    }
}
