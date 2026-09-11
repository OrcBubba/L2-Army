<?php

class Settings
{
    private $settings = [];
    private $original_settings = [];
    private $db;
    public function __construct()
    {
        global $db_game;
		$this->db = $db_game;
		$sql = 'SELECT * FROM acm_settings';
		$rows = $db_game->fetch($sql);
		foreach($rows as $row){
			$this->original_settings[$row->name] = $row->value;
			$this->settings[$row->name] = $row->value;
		}
    }
	
	public function set($key, $value){
		$this->settings[$key] = $value;
	}
	
	public function get($key){
		if(!(empty($this->settings[$key])))
			return $this->settings[$key];
		return null;
	}
	
	public function check($key){
		if(!(empty($this->settings[$key]))){
			if($this->settings[$key] == '1' || $this->settings[$key] === 1)
				return true;
		}
		return false;
	}
	
	public function remove($key){
		if(isset($this->settings[$key]))
			unset($this->settings[$key]);
	}
	
	public function has($key){
		if(!(empty($this->settings[$key])))
			return true;
		return false;
	}
	
	public function store(){
		foreach($this->settings as $key=>$value){
			if(isset($this->original_settings[$key]) && $this->original_settings[$key] == $value)
				continue;
			elseif(isset($this->original_settings[$key])){
				$this->db->update('acm_settings', array('value'=>$value), array('name'=>$key));
			}
			else {
				$this->db->insert('acm_settings', array('name'=>$key, 'value'=>$value));
			}
		}
		foreach($this->original_settings as $key=>$value){
			if(empty($this->settings[$key]))
				$this->db->delete('acm_settings', array('name'=>$key));
		}
	}
}
