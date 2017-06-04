<?php

	function pagButton($class, $link, $idx){
		return
			'<a class="pag_button '.$class.'" href="'. $link . '">'
		.	$idx
		. '</a>';
	}

	/**
	 * get the pagination indexes buttons
	 * @param currentPage the current displayed page
	 * @param pageWidth the interval amount of pages adjacent to current page
	 * @param n_pages total number of pages
	 * @param link the page pagination button will direct to, without the page value
	 * @return content as html string
	 */
	function pagination($currentPage, $pageWidth, $n_pages, $link){
		if ($currentPage == NULL || $link == NULL) return "";
		$pageWidth = ceil($pageWidth);

		# offset of pagination indexes
		$left_offset 	= $currentPage - $pageWidth;
		$right_offset 	= $currentPage + $pageWidth;

		# expand the upper or lower interval if current page
		# is in beginning or end of page list length
		if($left_offset < 1){
			$right_offset = min($n_pages, ($right_offset+ abs($left_offset-1)));
		}
		if($right_offset > $n_pages){
			$left_offset = max(1, ($left_offset - abs($n_pages-$right_offset)));
		}

		# correct the interval to match the size of the page list length
		$i 		= max($left_offset, 1); # start offset -> min value: 1
		$maxlim = min($right_offset, $n_pages);
		if($maxlim < 2){
			return "";
		}
		# page index
		$pagIdxBtns = "";
		for(; $i <= $maxlim; $i++){
			$class = "";
			if($i == $currentPage){
				$class = " pag_button_current";
			}
			$pagIdxBtns .= pagButton($class, $link."&p=".$i, $i);
		}

		$prevPage 	= max($currentPage-1, 1);
		$nextPage 	= min($currentPage+1, $maxlim);

		return
			'<div id="pag_nav">'
			. 	pagButton(
					"pag_button_dir",
					$link."&p=1",
					"<i id='pag_first' class='material-icons'>first_page</i>")
			. 	pagButton(
					"pag_button_dir",
					$link."&p=".$prevPage,
					"<i id='pag_prev'  class='material-icons'>navigate_before</i>")
			. 	$pagIdxBtns
			. 	pagButton(
					"pag_button_dir",
					$link."&p=".$nextPage,
					"<i id='pag_next'  class='material-icons'>navigate_next</i>")
		 	. 	pagButton(
		 			"pag_button_dir",
		 			$link."&p=".$n_pages,
		 			"<i id='pag_last'  class='material-icons'>last_page</i>")
			. '</div>';
	}

?>
