<?php
class Model_DataShift extends DB_Model {
	
	public $table;
	
	function __construct()
	{
		parent::__construct();	
		$this->table = $this->prefix.'shift';
	}
	
	

} 