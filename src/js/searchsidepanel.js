/**
 * functions to show/hide the searchpanel
 * @author Mikael Holmbom
 */

 function openSearchPanel() {
    var panel = document.getElementById("search_sidepanel");
    panel.classList.toggle("visible_sidepanel");
    document.getElementById("searchbar").focus();
}

function closeSearchPanel() {
    var panel = document.getElementById("search_sidepanel");
    panel.classList.toggle("visible_sidepanel");
}
