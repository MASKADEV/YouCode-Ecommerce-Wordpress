<?php

namespace wpautoterms\legal_pages;

class Group {
	public $id;
	public $title;

	public function __construct( $id, $title ) {
		$this->id = $id;
		$this->title = $title;
	}
}
