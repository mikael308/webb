<?php
  /**
   * sections containing site navigation
   * @author Mikael Holmbom
   * @version 1.0
   */

  /**
   * get page links as ul list
   * @return as html
   */
  function getPageNav(){
    $user = getAuthorizedUser();
    $pagenavitems =
    	listitem(
    		getNavButton(
    			$GLOBALS["index_page"],
    	 		"start")
    	)
    	. listitem(
    		getNavButton(
    			$GLOBALS["forum_page"],
    			"forum")
    	)
    	. listitem(
    		getNavButton(
    			getDisplayUserLink(
            $user == NULL ? "" : $user->getPrimaryKey()
          ),
    			"my page")
    	);

    if($user != NULL && $user->isAdmin()){
    	$pagenavitems .=
    		getNavButton(
    			$GLOBALS["admin_page"],
    			"admin"
        );
    }

    return
    	"<ul id='page_nav'>"
    	. 	$pagenavitems
    	. "</ul>";
  }
  /**
  * get user navigation ul list
  * if user is null empty string is returned
  * @return as html
  */
  function getUserNav($user){
    if($user == NULL)
    	return "";

    $dropdown_btn =
    	"<div class='dropbtn clickable'>"
    	.		$user->getName()
    	.		"<i class='dropbtn material-icons clickable'>expand_more</i>"
    	.	"</div>";
    $dropdown_list =
    	array(
    		"my page" => getDisplayUserLink($user->getPrimaryKey()),
    		"logout" => $GLOBALS["about_page"] . "?d=about"
    	);

    return
    	dropDownList(
    		$dropdown_btn,
    		$dropdown_list,
    		"user_dropdown"
    	);
  }

  /**
  * get meta navigation content as ul list
  * contains navigation commands:
  * * about
  * * search
  * @return as html
  */
  function getMetaNav(){
    $dropdown_btn_about =
    	"<i class='dropbtn material-icons clickable'>info_outline</i>";
    $dropdown_list_about =
    	array(
    		"faq" => $GLOBALS['about_page'] . "?d=faq",
    		"about" => $GLOBALS['about_page'] . "?d=about"
    	);

    return
    	"<ul id='meta_nav'>"
    	. listitem(
    			toolTip(
    				dropDownList(
    					$dropdown_btn_about,
    					$dropdown_list_about
    				),
    				"about"
    			)
    		)
    	. listitem(
    			toolTip(
    				getIconButton(
    					"search",
    					"onclick='openSearchPanel()'"
    				),
    				"search"
    			)
    		)
    	. "</ul>";
  }


?>
