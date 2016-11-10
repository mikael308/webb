/**
 * functions to show/hide the searchpanel
 * @author Mikael Holmbom
 */


 /* Set the width of the side navigation to 250px */ 
 function openSearchPanel() {
    var panel = document.getElementById("search_sidepanel");
    
    panel.classList.toggle("visible_sidepanel");
    
    // focus on the searchbar text input
    document.getElementById("searchbar").focus();
    
}

/* Set the width of the side navigation to 0 */
function closeSearchPanel() {
    var panel = document.getElementById("search_sidepanel");
    
    panel.classList.toggle("visible_sidepanel");

}