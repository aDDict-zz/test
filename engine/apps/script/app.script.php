<?php
class script extends application {

	function __construct( $app, &$obj ){
		parent::__construct( $app, &$obj );
	}

	public function script(){
		$this->content = "
			<script>
				jQuery.noConflict();
			</script>
		";
	}
}

