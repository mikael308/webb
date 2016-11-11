<?php
/**
 * helper functions to display the content of the main forum
 * @author Mikael Holmbom
 */

	/**
	 * display list of subjects
	 * @param subjects list of subjects to display
	 * @return content as html string
	 */
	function forumContentListSubjects($subjects){
		$cont = '';

		foreach($subjects as $subject){
			$cont .= forumSubjectView($subject);
		}
		return $cont;
	}

	/**
	 * display a forumsubject link
	 * @param subject the forumsubject to display
	 * @return content as html string
	 */
	function forumSubjectView(ForumSubject $subject){
		if ($subject == NULL) 
			return errorMessage("could not display subject");

		$cont = 
			  '<div class="forum_content_listitem forum_subject">' 	
			. 	'<a href="forum_page.php?s='.$subject->getPrimaryKey().'&p=1">'
			. 		'<div class="topic">'.$subject->getTopic().'</div>'
			.		'<div class="info">'
			.			'<div class="subtitle">'.$subject->getSubtitle().'</div>'
			. 		'</div>' 
			. 	'</a>'
			. '</div>';

		return $cont;
	}

?>
