<?php

	require_once "./config/pageref.php";
	require_once "./sections/authorization.php";
	require_once "./sections/elements.php";
	require_once "./sections/forms.php";
	require_once "./sections/main.php";
	require_once "./sections/navigation.php";
	require_once "./sections/views.php";


	/**
	 * contains standard page\n
	 * use setters to define each section\n
	 * use toHtml() to get complete html as string
	 * @author Mikael Holmbom
	 * @version 1.0
	 */
	class Page {

		# instance defined sections
		private $defHead = "";
		private $defHeader = "";
		private $defMain = "";
		private $defFooter = "";


		/**
		 *
		 */
		public function __construct(){
			autoloadDAO();
			startSession();
		}
		/**
		 * get the standard section with the defined section
		 * @return section as html
		 */
		private function getHeadContent(){
		return
			"<head>"
			.	getMainHeadContent()
			.	$this->defHead
			. "</head>";
		}
		/**
		 * get the standard section with the defined section
		 * @return section as html
		 */
		private function getHeaderContent(){
			return
				"<header>"
				.		getMainHeaderContent()
				.		$this->defHeader
				. "</header>";
		}
		/**
		 * get the standard section with the defined section
		 * @return section as html
		 */
		private function getMainContent(){
			return
				"<main>"
				.		$this->defMain
				. "</main>";
		}
		/**
		 * get the standard section with the defined section
		 * @return section as html
		 */
		private function getFooterContent(){
			return
				"<footer>"
				.	getMainFooterContent()
				.	$this->defFooter
				. "</footer>";
		}
		/**
		 * define this head section
		 */
		public function setHead($head=""){
			$this->defHead = $head;
		}
		/**
		 * define this header section
		 */
		public function setHeader($header=""){
			$this->defHeader = $header;
		}
		/**
		 * define this main section
		 */
		public function setMain($main=""){
			$this->defMain =$main;
		}
		/**
		 * define this footer section
		 */
		public function setFooter($footer=""){
			$this->defFooter = $footer;
		}
		/**
		 * @return all sections as html string
		 */
		public function toHtml(){
			return
				"<!DOCTYPE html>"
				.	"<html>"
				.		$this->getHeadContent()
				.		"<body>"
				.			$this->getHeaderContent()
				.			$this->getMainContent()
				.			$this->getFooterContent()
				.		"</body>"
				.	"</html>";
		}

	}


 ?>
