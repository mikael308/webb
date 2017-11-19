
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
            var response = JSON.parse(xmlhttp.responseText);
            console.log(response.items.length);
            if (target !== null) {
                target.innerHTML = "";
                //console.log("response status:"+response.status);
                for (var i = 0; i < response.items.length; i++) {
                    console.log(response.items[i]);
                    target.appendChild(
                        messageToHtml(response.items[i])
                    );
                }
            }
        }
    };

    var link = "/ajax/searchres/type="+searchType+"&value=" + str;
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

function messageToHtml(item)
{
    var searchitem = document.createElement("div");
    searchitem.setAttribute('class', 'searchitem');

    var a = document.createElement('a');
    a.setAttribute("href", item.link);
    var label = document.createElement('span');
    label.setAttribute('class', 'post main');
    label.innerText = item.message;

    var extra = document.createElement('div');
    extra.setAttribute('class', 'post extra');
    var author = document.createElement('span');
    author.setAttribute('class','author');
    author.innerText = item.author_name;
    var topic = document.createElement('span');
    topic.setAttribute('class','topic');
    topic.innerText = item.topic;

    searchitem.appendChild(a);
    searchitem.appendChild(extra);
    a.appendChild(label);
    extra.appendChild(author);
    extra.append(topic);
    return searchitem;
}
