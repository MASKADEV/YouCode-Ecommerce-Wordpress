<?php

namespace wpautoterms\legal_pages;

class Page {
	/**
	 * @var string, page id, define template with the same id (file name) if page is not paid.
	 */
	public $id;
	public $group;
	public $title;
	public $page_title;
	public $description;
	public $is_paid;

	/**
	 * Page constructor.
	 *
	 * @param $id
	 * @param Group $group
	 * @param $title string: wizard and listing title.
	 * @param $description
	 * @param $is_paid bool
	 * @param $page_title false|string: generated page title, false if equals to wizard title.
	 */
	public function __construct( $id, Group $group, $title, $description, $is_paid, $page_title = false ) {
		$this->id = $id;
		$this->group = $group;
		$this->title = $title;
		$this->description = $description;
		$this->is_paid = $is_paid;
		$this->page_title = $page_title === false ? $title : $page_title;
	}
}
