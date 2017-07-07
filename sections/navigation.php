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
    			$GLOBALS["pagelink"]["index"],
    	 		"start")
    	)
    	. listitem(
    		getNavButton(
    			$GLOBALS["pagelink"]["forum"],
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
    			$GLOBALS["pagelink"]["admin"],
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
  function getUserNav($user=NULL){
    if($user == NULL)
      return "";

    $dropdown_btn =
      "<div class='dropbtn clickable'>"
      .	 $user->getName()
      .	 "<i class='dropbtn material-icons clickable'>expand_more</i>"
      .	"</div>";
    $dropdown_list =
      array(
        "my page" => getDisplayUserLink($user->getPrimaryKey()),
        "logout" => $GLOBALS["pagelink"]["about_about"] # TODO fixa logout
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
        "about"   => $GLOBALS["pagelink"]["about_about"],
        "faq"     => $GLOBALS["pagelink"]["about_faq"]
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

  /**
   * get the navigation content
   * @return as html
   */
  function getNavContent(){
    return
      "<nav>"
      .   getPageNav()
      .   "<ul id='user_nav'>"
      .     listitem(getUserNav(getAuthorizedUser()))
      .   "</ul>"
      . "</nav>";
  }

  /**
   * get the subnavigation content
   * @return as html
   */
  function getSubNavContent(){
    return
      "<div id='sub_nav'>"
      .     getSubNavLeft()
      .     getMetaNav()
      . "</div>";
  }

  /**
   * get subnav aligned to the left
   * @return as html
   */
  function getSubNavLeft(){
    return
      "<ul id='sub_nav_left'>"
      .   "<li>"
      .   "</li>"
      . "</ul>";
  }

  function getAboutNav(){
    return
      "<ul>"
      .   listitem(
        "<a href='".$GLOBALS["pagelink"]["about_about"]."'><div class='button clickable'>about</div></a>"
        )
      .   listitem(
        "<a href='".$GLOBALS["pagelink"]["about_faq"]."'><div class='button clickable'>faq</div></a>"
        )
      . "</ul>";
  }

?>
