<?php
class pager{

	public $main;
	public $itemsPerPage;
	public $itemsInPager;
	public $maxPageQuery;
	public $currentPageQuery;

	function __construct( &$obj ){
		$this->init( &$obj );
		$this->main = &$obj;
	}
	public function render(){

	}
}

