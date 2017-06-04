<?php
	/**
	 * object used to generate pagination
	 * use generateSubject, generateThread
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	 class Pagination {

		 /**
		  * generate pagination for subject
			* @param $subject
			* @param currentPage the current displayed page
		  * @return as html
			*/
		 public static function generateSubject(ForumSubject $subject, $currentPage) {
			 $link = $GLOBALS['forum_page'] . "?s=" . $subject->getPrimaryKey();
			 $max_pages = Count::maxPagesSubject($subject);
			 return Pagination::generate(
				 $subject->getPrimaryKey(),
				 $currentPage,
				 $max_pages,
				 $link
			 );
		 }

 		 /**
 		  * generate pagination for thread
 			* @param $thread
 			* @param currentPage the current displayed page
 		  * @return as html
 			*/
		 public static function generateThread(ForumThread $thread, $currentPage) {
			 $link = $GLOBALS['forum_page'] . "?t=" . $thread->getPrimaryKey();
			 $max_pages = Count::maxPagesThread($thread);
			 return Pagination::generate(
				 $thread->getPrimaryKey(),
				 $currentPage,
				 $max_pages,
				 $link
			 );
		 }

		 	/**
		 	 * get the pagination indexes buttons
			 * @param $id index of the object to display its content
		 	 * @param currentPage the current displayed page
		 	 * @param max_pages total number of pages
		 	 * @param link the page pagination button will direct to, without the page value
		 	 * @return content as html string
		 	 */
		 	private static function generate($id, $currentPage, $max_pages, $link){
		 		if ($id == NULL || $currentPage == NULL
					|| $max_pages == NULL || $link == NULL){
		 			return "";
		 		}

		 		$offset = Pagination::getOffsets($currentPage, $max_pages);

		 		# correct the interval to match the size of the page list length
		 		$i = max($offset["left"], 1); # start offset -> min value: 1
		 		$maxlim = min($offset["right"],	$max_pages);

		 		# generate index buttons html
		 		$pagIdxBtns = "";
		 		for(; $i <= $maxlim; $i++){
		 			$class = "";
		 			if($i == $currentPage){
		 				$class = " pag_button_current";
		 			}
		 			$pagIdxBtns .=
						Pagination::pagButton($link."&p=".$i, $i, $class);
		 		}

		 		return
		 			'<div id="pag_nav">'
					.'<ul>'
		 			. 	Pagination::navButtonLeft($link, $currentPage)
		 			. 	$pagIdxBtns
		 			. 	Pagination::navButtonRight($link, $currentPage, $maxlim, $max_pages)
					. '</ul>'
					. '</div>';
		 	}

			/**
		 	 * get pagination button
		 	 * @param $link ref
		 	 * @param $idx index
		 	 * @param $class optional class
		 	 * @return button as html
		 	 */
		 	private function pagButton($link, $idx, $class=""){
		 		return
				'<li>'
		 		.	'<a class="pag_button pag_button_dir '.$class.'" href="'. $link . '">'
		 		.		$idx
		 		. '</a>'
				. '</li>';
		 	}

		 	/**
		 	 * get navigation button to previous or first page
		 	 * @return as html
		 	 */
		 	private function navButtonLeft($link, $currentPage){
		 		$prevPage = max($currentPage-1, 1);
		 		return
		 			# nav button to first page
		 			Pagination::pagButton(
		 				$link."&p=1",
		 				"<i id='pag_first' class='material-icons'>first_page</i>"
		 			)
		 			# nav button to previous page
		 			.	Pagination::pagButton(
		 				$link."&p=".$prevPage,
		 				"<i id='pag_prev'  class='material-icons'>navigate_before</i>"
		 			);
		 	}

		 	/**
		 	 * get navigation button to next or last page
		 	 * @return as html
		 	 */
		 	private function navButtonRight($link, $currentPage, $maxlim, $n_pages){
		 		$nextPage = min($currentPage+1, $maxlim);
		 		return
		 			# nav button to next page
		 			Pagination::pagButton(
		 				$link."&p=".$nextPage,
		 				"<i id='pag_next' class='material-icons'>navigate_next</i>"
		 			)
		 			# nav button to last page
		 			. Pagination::pagButton(
		 				$link."&p=".$n_pages,
		 				"<i id='pag_last' class='material-icons'>last_page</i>"
		 			);
		 	}

		 	/**
		 	 * get the index offsets of pagination
		 	 * the interval of page links will always be max value of
		 	 * setting value [pag_max_interval]
		 	 * @return as html
		 	 */
		 	private function getOffsets($currentPage, $n_pages){
		 		$pageWidth = (int) readSettings("pag_max_interval");

		 		# offset of pagination indexes
		 		$left_offset = $currentPage - $pageWidth;
		 		$right_offset = $currentPage + $pageWidth;

		 		# expand the upper or lower interval if current page
		 		# is in beginning or end of page list length
		 		if($left_offset < 1){
		 			$right_offset = min(
		 				$n_pages,
		 				($right_offset + abs($left_offset-1))
		 			);
		 		}
		 		if($right_offset > $n_pages){
		 			$left_offset = max(
		 				1,
		 				($left_offset - abs($n_pages-$right_offset))
		 			);
		 		}

		 		return array(
		 			"left" => $left_offset,
		 			"right" => $right_offset
		 		);
		 	}

	 }

?>
