<?php

/**
 * TODO: Docs
 */
class Master_Model extends CI_Model
{
	/**
	 * Class name
	 * @var string
	 */
	public static $class_name = __CLASS__;
	
	/**
	 * Various fields
	 * @var array
	 */
	public $fields;
	
	/**
	 * Various filters
	 * @var array
	 */
	public $filters;
	
	/**
	 * Loaded
	 * @var array
	 */
	static private $loaded = array();
	
	/**
	 * Placeholder classes
	 * @var array
	 */
	static private $placeholders = array();
	
	/**
	 * Class constructor
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Magic setter
	 * @param	string	$key
	 * @param	mixed	$value
	 * @return void
	 */
	public function __set($key, $value)
	{
		if (is_callable(array($this, 'set_' . $key))) 
		{
			call_user_func(array($this, 'set_' . $key), $value);
		}
		else
		{
			$this->$key = $value;
		}
		
	}
	
	/**
	 * Magic getter
	 * @param	string	$key
	 * @param	mixed	$value
	 * @return void
	 */
	public function __get($key)
	{
		return (is_callable(array($this, 'get_' . $key))) 
		        ? call_user_func(array($this, 'get_' . $key)) 
		        : (isset($this->$key)) 
		          ? $this->$key 
		          : parent::__get($key);
	}
	
	public function setByArray(Array $array)
	{
		foreach($array as $property => $value)
		{
			$this->$property = $value;
		}
	}
	
	/**
	 * Generic delete
	 * 
	 * @return void
	 */
	public function delete()
	{
		$table = strtolower(get_class($this));
		$fields = $this->getFields();		
		$this->db->where('id', element('id', $fields))->delete($table);
	}
	
	/**
	 * Generic save function
	 * 
	 * @return BIT_Model
	 */
	public function save()
	{
		$table = strtolower(get_class($this));		
		$fields = $this->getFields();
		foreach($fields as $var => $value)
		{
			if(isset($this->ignoredFields) && in_array($var, $this->ignoredFields))
			{
				unset($fields[$var]);
			}
		}
		if (element('id', $fields))
		{
			$this->db->where('id', element('id', $fields))->update($table, $fields);
		}
		else
		{
			$this->db->insert($table, $fields);
			$this->id = $this->db->insert_id();
		}
		
		return $this;
	}
	
	
	/**
	 * Get database fields for model
	 * 
	 * @return array
	 */
	protected function getFields()
	{	
		$fields = array();
		$vars = get_class_vars(get_class($this));
		$parent_vars = get_class_vars(get_class());
		foreach (array_diff(array_keys($vars), array_keys($parent_vars)) as $var)
		{
			$fields[$var] = $this->$var;
		}
		return $fields;
	}
	
	/**
	 * Load data into model
	 * 
	 * @param	BIT_Model	$model
	 * @param	array	$data
	 * @return void
	 */
	static public function load(BIT_Model $model, Array $data)
	{
		$fields = array_keys($model->getFields());
		
		foreach ($fields as $field)
		{
			if (isset($data[$field]))
			{
				$model->$field = $data[$field];
			}
		}
		
		foreach ($data as $key => $value)
		{
			if (strstr($key, '__'))
			{
				list($rel, $relf) = explode('__', $key);
				if (!isset($model->$rel))
				{
					$classname = ucwords($rel);
					$model->$rel = new $classname();
					$model->$rel->$relf = $value;					
				}
				else
				{
					$model->$rel->$relf = $value;
				}
			}
		}
	}
	
	/**
	 * Find all private function
	 * 
	 * @return	array of Category
	 */
	public function _findAll($options = false)
	{
		$table = strtolower(get_class($this));		
		$fields = array_keys($this->getFields());
		
		if($options !== false)
		{
			if(is_array($options))
			{
				foreach($options as $option => $value)
				{
					$this->db->$option($value);
				}
			}
			else
			{
				die('Options has to be set as an array.' . __CLASS__ . " --- " . __METHOD__);
			}
		}
		elseif (in_array('id', $fields))
		{
			get_instance()->db->order_by('id', 'asc');
		}
		$r = get_instance()->db->get($table)->result_array();
		if ($r)
		{
			foreach ($r as $row)
			{
				$class = get_class($this);
				$m = new $class;
				self::load($m, $row);
				$all[] = $m;
			}
		}				
		return (isset($all)) ? $all : array();
	}
	
	/**
	 * Find all wrapper
	 * 
	 * @return array of BIT_Model
	 */
	static public function findAll($options = false)
	{
		$class = new static::$class_name;
		return $class->_findAll($options);
	}
	
	/**
	 * Find by id wrapper
	 * 
	 * @return BIT_Model or false on failure
	 */
	static public function findById($id)
	{
		$class = new static::$class_name;
		return $class->_findById($id);		
	}
	
	/**
	 * Find by id
	 * 
	 * @return BIT_Model or false on failure
	 */
	public function _findById($id)
	{
		$table = strtolower(get_class($this));			
		
		$r = $this->db->where('id', $id)->get($table)->result_array();		
		
		if ($r)
		{
			$item = new static::$class_name;
			$this->load($item, current($r));
		}
		
		return (isset($item)) ? $item : false;
	}
	
	/**
	 * Find by field wrapper
	 * 
	 * @return BIT_Model or false on failure
	 */
	static public function findByField(Array $params, $options = false)
	{
		$class = new static::$class_name;
		return $class->_findByField($params, $options);		
	}
	
	/**
	 * Find by field
	 * 
	 * @return array of BIT_Model
	 */
	public function _findByField(Array $params, $options = false)
	{
		$items = array();
		$table = strtolower(get_class($this));			
		
		foreach ($params as $field => $value)
		{
			$this->db->where($field, $value);
		}
		
		if($options !== false)
		{
			if(is_array($options))
			{
				foreach($options as $option => $value)
				{
					$this->db->$option($value);
				}
			}
			else
			{
				die('Options has to be set as an array.' . __CLASS__ . " --- " . __METHOD__);
			}
		}
		
		$r = $this->db->get($table)->result_array();		
		
		if ($r)
		{
			foreach ($r as $row)
			{
				$item = new static::$class_name;
				$this->load($item, $row);
				if(count($r) > 1) 
				{
					$items[] = $item;
				}
				else
				{
					$items = $item;
				}
			}
		}
		
		return $items;
	}

	/**
	 * Find all where they item is not in the selected table
	 * 
	 * @param string $table
	 * @return BIT_Model
	 */
	static public function findAllWhereNotIn($table, $customSelect = FALSE)
	{
		$className 	= strtolower(static::$class_name);
		$class 		= new static::$class_name;
		return $class->_findAllWhereNotIn($table, $className, $customSelect);
	}

	/**
	 * Find all where they item is not in the selected table
	 * 
	 * @param string $table
	 * @return BIT_Model
	 */
	public function _findAllWhereNotIn($table, $className, $customSelect)
	{
		//$fields = array_keys($this->getFields());
		//var_dump($fields);die();
		$relation 	= strtolower($className) . '_id';
		if(!$customSelect)
		{
			$this->db->select(strtolower($className) . '.*');
		}
		else
		{
			$this->db->select($customSelect);
		}
		$this->db->join($table, $table . '.' . $relation . ' = ' . $className . '.id', 'LEFT');
		$this->db->where('`' . $table . '`.`' . $relation . '` IS NULL');
		$result = $this->db->get($className)->result_array();

		$items = array();
		if($result)
		{
			foreach($result as $row)
			{
				$item = new static::$class_name;
				$this->load($item, $row);
				if(count($result) > 1) 
				{
					$items[] = $item;
				}
				else
				{
					$items = $item;
				}
			}
		}

		return $items;
	}
	
	
	/**
	 * Find or create by name
	 * 
	 * @return BIT_Model
	 */
	static public function findIdCreateByName($field, $name)
	{
		$class = new static::$class_name;
		return $class->_findIdCreateByName($field, $name);
	}
	
	
	/**
	 * Find or create by name wrapper
	 * EARLY RETURN
	 * 
	 * @return BIT_Model
	 */
	public function _findIdCreateByName($field, $name = NULL)
	{
		if (!strlen($name)) return NULL;
		
		$table = strtolower(get_class($this));		
		
		$r = $this->db->where("{$field} LIKE '{$this->db->escape_like_str($name)}'")->get($table)->result_array();
		
		if (!$r)
		{
			$this->db->insert($table, array(
				$field 		=> "$name",
				'active' 	=> 1,
				'created'	=> date('Y-m-d H:i:s'),
				'updated'	=> date('Y-m-d H:i:s')
			));
			$id = $this->db->insert_id();
		}
		else
		{
			$row = current($r);
			$id = $row['id'];
		}
		
		return $id;
	}
	
	/**
	 * Load one item by id
	 * @param  int   $id
	 * @return BIT_Model
	 */
	static public function find($id)
	{
		$class = new static::$class_name;
		return $class->_find($id);
	}
	
	/**
	 * Load one item by id
	 * @param  int   $id
	 * @return BIT_Model
	 */
	public function _find($id)
	{
		$table = strtolower(get_class($this));			
		
		$r = $this->db->where('id', $id)->get($table)->row_array();
		
		if ($r)
		{
			$item = new static::$class_name;
			$this->load($item, $r);
		}
		
		return (isset($item)) ? $item : false;
	}
	
	public function get($query = FALSE) 
	{
		$class = new static::$class_name;
		return $class->_get($query);
	}
	
	public function _get($query) 
	{
		if($query)
		{
			$result = $this->db->query($query)->result_array();
		}
		else
		{
			$result = $this->db->get(strtolower(get_class($this)))->result_array();
		}
		foreach($result as $row) 
		{
			$class = get_class($this);
			$model = new $class;
			self::load($model, $row);
			$all[] = $model;
		}
		return (isset($all)) ? $all : false;
	}

	static function setJoin($table, $column, $relation, $type)
	{
		$class 		= new static::$class_name; 
		$class->_setJoin($table, $column, $relation, $type);
	}

	public function _setJoin($table, $column, $relation, $type)
	{
		$this->db->join($table, $table . '.' . $column . ' = ' . $relation, $type);
	}
	
	/**
	 * Add filter function for search utility
		*
	 * @param array $filters
	 * @return false on failure
	 */
	public function add_filter($filter = FALSE, $column = FALSE, $value = '') {
		if(!$filter) 
		{
			return false;
		}
		switch($filter) 
		{
			case "limit":
				$this->filters['limit'] = $value;
				break;
			case "offset":
				$this->filters['offset'] = $value;
				break;
			case "group_by":
				$this->filters['group_by'] = $value;
				break;
			default:
				$this->filters[$filter][$column][] = $value;
				break;
		}
	}
	
	
	/**
	 * Runs the search query based on the assigned filters
	 * @return  void
	 */
	public function apply_filters() {
		if($this->filters !== null && is_array($this->filters)) 
		{
			foreach($this->filters as $statement => $arguments) 
			{
				if(is_array($arguments)) 
				{
					foreach($arguments as $column => $values) 
					{
						foreach($values as $value) 
						{
							if(!$value && $value !== 0)
							{
								$this->db->$statement($column);
							}
							else
							{
								$this->db->$statement($column, $value);
							}
						}
					}
				} 
				else 
				{
					$this->db->$statement($arguments);
				}
			}
		}
	}
	
	public function reset_filters() {
		$this->filters = null;
	}
	
	public function getAutoIncrementValue()
	{
		$query = 'SHOW TABLE STATUS LIKE "' . strtolower(static::$class_name) . '"';
		return current($this->db->query($query)->result())->Auto_increment;
		
	}

	/**
	 * Convert to json
	 * 
	 * @return	string
	 */
	public function json()
	{
		$fields = $this->getFields();
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value)
		{
			if ($value instanceof BIT_Model)
			{
				$fields[$key] = $value->json();
			}
		}
		return json_encode($fields);
	}
	
	/**
	 * Convert to array
	 * 
	 * @return array
	 */
	public function arr()
	{
		$fields = $this->getFields();
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value)
		{
			if ($value instanceof BIT_Model)
			{
				$fields[$key] = $value->arr();
			}
		}
		return $fields;
	}
}
