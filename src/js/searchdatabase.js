
/**
 * call AJAX searchres
 * shows 
 * @author Mikael Holmbom
 */

/**
 * send AJAX request and display the result
 * @param str
 * @param target
 * @param searchType
 * @author Mikael Holmbom
 */
function showSearchRes(str, target, searchType){
    var target = document.getElementById(target);

    var xmlhttp;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            var response = xmlhttp.responseText;
            if(target !== null){
                target.innerHTML = response; 
            }
        }
    };

    var link = "/webb/ajax/searchres/type="+searchType+"&value=" + str;
    console.log("search link "+link);
    
    xmlhttp.open("POST", link, true);
    xmlhttp.send();
}

/**
 * send a searchstring if radiobutton is currently set to user
 * @param s search value
 */
function search(s)
{
    var radio_btns = document.getElementsByName("type");

    var searchType = "";
    for (var i = 0; i < radio_btns.length; i++){
        if(radio_btns[i].checked == true){
            searchType = radio_btns[i].value;
        }
    }

    showSearchRes(s, "searchres", searchType);
}
