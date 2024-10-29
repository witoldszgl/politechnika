<?php
//----------------------------------------------------------------------------//
//
//   Data table model 
//
//----------------------------------------------------------------------------//
class Datatable
{
protected $db, $table, $names, $key, $autoincrement;

	// method declaration
	// Constructor
	// &$db - reference to PDO database handler
	// $table - name of data table
	// $names - array of data fields names
	// $filename - name of file to store the data
	// $key - unic primary key identifier field name
	// $autoincrenent - if true $key value will be autoincrement by insert()
	//
	public function __construct( &$db, $table, $names, $key='id', $autoincrement=true) {
		$this->db = $db;
    	$this->table=$table;
    	$this->names = $names;
    	$this->key = $key;
    	$this->autoincrement = $autoincrement;

    	$query="CREATE TABLE IF NOT EXISTS ".$this->table." ( ";
    	foreach( $this->names as $v )
		{
			if( $this->autoincrement )
			{ 
				if($this->key==$v) $query .= " $v INTEGER PRIMARY KEY AUTOINCREMENT, ";
				else $query .= " $v TEXT, ";
			}else
			{
				if($this->key==$v) $query .= " $v TEXT PRIMARY KEY, ";
				else $query .= " $v TEXT, ";
			}
		}
		$query = substr($query,0, strlen($query)-2);  
		$query.=" )";
		try{  $this->db->exec($query); }
		catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode(); exit; }
	}

	protected function query_insert($data)
	{
		$query="insert into ".$this->table." ( ";
		foreach( $this->names as $v )
		{
			if( $this->autoincrement and ($this->key==$v) ) continue; 
			$query .= " $v, ";
		}
		$query = substr($query,0, strlen($query)-2);
		$query.=" ) values ( ";
		foreach( $this->names as $v )
		{
			if( $this->autoincrement and ($this->key==$v) ) continue; 
			$query .= " '$data[$v]', ";
		}
		$query = substr($query,0, strlen($query)-2);
		$query.=" )";
		return $query;
	}    

	public function insert($data)
	{
		$query = $this->query_insert($data);
		try{ $r = $this->db->exec($query); }
		catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query";  exit;}
		return $r;           
    }

	public function getAll($val=false,$key=false, $order="") {
		if(!$key) $key=$this->key;
		if($val) $query="select * from ".$this->table." where $key='$val'".(($order)?" ORDER BY $order ":"");
		else $query="select * from ".$this->table.(($order)?" ORDER BY $order ":""); 
		try{ $r = $this->db->query($query); }
		catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query"; exit;}
		$result=array();
		while( $data = $r->fetch(\PDO::FETCH_ASSOC) )
		{
			$result[$data[$this->key]] = $data;
		}
		return $result;           
	}

	public function getNames(){ return $this->names; }
    
	//------ do zaimplementowania ------//    
	public function update($data)
	{
		$keyValue = $data[$this->key];
		$setClause = '';
		foreach($this->names as $name)
		{
			if ($name !== $this->key)
			{
				$setClause .= "$name = '{$data[$name]}',";
			}
		}
		$setClause = rtrim($setClause, ',');
		$query = "UPDATE {$this->table} SET $setClause WHERE {$this->key} = '$keyValue'";
		try
		{ 
			$this->db->exec($query); 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query";  
			exit;
		}
	}

	public function delete($id, $key = false)
	{
		$key = $key ?: $this->key;
		$query = "DELETE FROM {$this->table} WHERE $key = '$id'";
		try
		{ 
			$this->db->exec($query); 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query";  
			exit;
		}
	}

	public function get($val, $key = false)
	{
		$key = $key ?: $this->key;
		$query = "SELECT * FROM {$this->table} WHERE $key = '$val'";
		try
		{ 
			$r = $this->db->query($query); 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query";  
			exit;
		}
		$result = $r->fetch(\PDO::FETCH_ASSOC);
		if (!$result)
		{
			return null;
		}
		return $result;
	}
    
	public function getLastItem($key = "date")
	{
		$query = "SELECT * FROM ".$this->table." ORDER BY $key DESC LIMIT 1";
		try
		{
			$result = $this->db->query($query);
			if ($result && $result->rowCount() > 0)
			{
				return $result->fetch(\PDO::FETCH_ASSOC);
			} else {
				return null;
			}
		} catch(PDOException $e)
		{
			echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query";
			exit;
		}
	}
// end of class datatable
}