<?php
class Model_TableInv extends DB_Model {
	
	public $table;
	
	function __construct()
	{
		parent::__construct();	
		$this->prefix	= config_item('db_prefix2');
		$this->table 	= $this->prefix.'table_inventory';
	}
	
	
	function cek(){
		$this->prefix	= config_item('db_prefix2');
		$this->table_inv 	= $this->prefix.'table_inventory';
		$this->table 	= $this->prefix.'table';
		
		$opt_value = array(
			'current_date'
		);
		
		$get_opt = get_option_value($opt_value);
		
		//autodelete_print_monitoring
		$current_date = 0;
		if(!empty($get_opt['current_date'])){
			$current_date = $get_opt['current_date'];
		}else{
			$current_date = strtotime("now");
		}
		
		$date_now = date("Y-m-d", $current_date);
		$date_time_now = date("Y-m-d H:i:s", $current_date);
		$session_user = $this->session->userdata('user_username');	
		
		$available_table = array();
		$this->db->where("tanggal",$date_now);
		$get_inv = $this->db->get($this->table_inv);
		if($get_inv->num_rows() > 0){
			
			foreach($get_inv->result() as $dt){
				if(!in_array($dt->table_id, $available_table)){
					$available_table[] = $dt->table_id;
				}
			}
			
		}
		
		//generate inventory
		$all_table = array();
		$get_table = $this->db->get($this->table);
		if($get_table->num_rows() > 0){
			foreach($get_table->result() as $dt){
				
				if(!in_array($dt->id, $available_table)){
					$all_table[] = array(
						'tanggal'	=> $date_now,
						'table_id'	=> $dt->id,
						'is_active'	=> 1,
						'created'	=> $date_time_now,
						'createdby'	=> $session_user,
						'updated'	=> $date_time_now,
						'updatedby'	=> $session_user
					);
				}
				
			}
			
		}
		
		//echo '<pre>';
		//print_r($all_table);
		//die();
		
		if(!empty($all_table)){
			$this->db->insert_batch($this->table_inv, $all_table);
		}
		
	}
} 