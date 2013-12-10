<?php namespace ChannelData\Base;


use ChannelData\Component\QueryBuilder;

// namespace ChannelData;

abstract class BaseModel extends \CI_Model {
	
	public $exists = FALSE;

	protected $table;

	protected $idField = 'id';
	
	protected $uidField = NULL;
	
	protected $deleted = FALSE;

	protected $prefix = FALSE;

	protected $columns = FALSE;

	protected $required = array();

	protected $fillable = array();

	protected $guarded = array('id');

	protected $hidden = array('id');

	public function __construct($data = array(), $setId = TRUE)
	{
		$this->fill($data);

		if($setId && isset($data->{$this->idField}))
		{
			if($this->idField)
			{
				$this->setAttribute($this->idField, $data->{$this->idField});	
			}

			if($this->uidField)
			{
				$this->setAttribute($this->uidField, $data->{$this->uidField});			
			}
		}

		if($this->getAttribute($this->idField))
		{
			$this->exists = TRUE;
		}
		else
		{
			$this->setAttribute($this->uidField, md5(uniqid()));
		}
	}

	public function __call($method, $args)
	{
		$prefix = $this->prefix ? $this->prefix : NULL;

		if(isset($this->{$prefix.$method}))
		{
			return $this->{$prefix.$method};
		}

		return NULL;
	}

	public function __get($property)
	{
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
        else
        {
        	$class = self::caller();

        	throw new \Exception("The {$property} property does not exist within the {$class} class.");
        }
    }

	public function fill($data)
	{
		if(is_object($data))
		{
			$data = (array) $data;
		}

		if(is_array($data) && count($data) > 0)
		{
			foreach($data as $index => $value)
			{
				if( in_array($index, $this->fillable) ||
				    in_array($index, $this->required))
				{
					$this->setAttribute($index, $value);

				}
			}
		}
	}

	public function save($data = FALSE)
	{
		if($data)
		{
			$this->fill($data);
		}

		if(!$this->exists)
		{
			return $this->_createRecord($data);
		}
		else
		{
			return $this->_updateRecord($data);
		}
	}

	public function toArray()
	{
		$array = array();

		foreach($this->getAttributes() as $index => $value)
		{
			if(!in_array($index, $this->hidden))
			{
				$array[$index] = $this->$index;
			}
		}

		return $array;
	}

	public function toJson()
	{
		return json_encode($this->toArray());
	}

	public function update($array = FALSE)
	{
		if($array)
		{
			$this->fill($array);
		}

		return $this->save();
	}

	public function delete()
	{
		if($this->exists)
		{
			ee()->db->where($this->idField, $this->id());
			ee()->db->delete($this->table);

			$this->exists  = FALSE;
			$this->deleted = TRUE;
		}

		return $this;
	}

	public function getAttributes()
	{
		$return = array();

		foreach($this->columns() as $index)
		{
			if(property_exists($this, $index))
			{
				$return[$index] = $this->$index;
			}
		}

		return $return;
	}

	public function getAttribute($name)
	{
		if(property_exists($this, $name))
		{
			return $this->$name;
		}

		return NULL;
	}

	public function setAttributes($data)
	{
		foreach($data as $index => $value)
		{
			$this->setAttribute($index, $value);
		}
	}

	public function setAttribute($prop, $value)
	{
		if(!empty($prop))
		{
			$this->$prop = $value;
		}
	}

	protected function _createRecord($data = array())
	{
		if(count($data))
		{
			$this->fill($data);
		}

		$data = $this->data();

		if($this->uidField)
		{
			$data[$this->uidField] = md5(uniqid());
		}

		ee()->db->insert($this->table, $data);

		$this->exists = TRUE;
		$this->setAttribute($this->idField, ee()->db->insert_id());

		return $this;
	}

	protected function _updateRecord($data = array())
	{
		if(count($data))
		{
			$this->fill($data);
		}

		ee()->db->where($this->idField, $this->id());
		ee()->db->update($this->table, $this->data());

		return $this;
	}

	public function id()
	{
		if($id = $this->{$this->idField})
		{
			return $id;
		}

		return NULL;
	}

	public function uid()
	{
		if(property_exists($this, 'uidField'))
		{
			$uid = $this->{$this->uidField};

			if($uid !== NULL)
			{
				return $uid;
			}
		}

		return NULL;
	}

	public function data()
	{
		$data = array();

		foreach($this->columns() as $field)
		{
			$value = $this->getAttribute($field);

			if($value !== NULL && !in_array($field, $this->guarded))
			{
				$data[$field] = $value;
			}
		}

		return $data;
	}

	public function columns()
	{
		if($this->columns)
		{
			return $this->columns;
		}

		return ee()->db->list_fields($this->table);
	}

	public static function create($array)
	{
		$class = get_called_class();
		$obj   = new $class($array);

		$obj->save();

		return $obj;
	}

	public static function find($id)
	{
		$class  = self::caller();
		$obj    = self::instantiate();

		return $class::where($obj->table.'.'.$obj->idField, $id)->get()->first();
	}

	public static function findByUid($uid)
	{
		$class  = self::caller();
		$obj    = self::instantiate();

		return $class::where($obj->uidField, $uid)->get()->first();
	}

	public static function all()
	{
		$class = self::caller();

		return $class::query()->result();
	}

	public static function select()
	{
		$class = self::caller();

		return $class::call($class::query(), 'select', func_get_args());
	}

	public static function where($subject, $operator = NULL, $value =  NULL, $concat = 'AND')
	{
		$class = self::caller();

		return $class::query()->where($subject, $operator, $value, $concat);
	}

	public static function orWhere($subject, $operator = NULL, $value =  NULL)
	{
		$class = self::caller();

		return $class::where($subject, $operator, $value, 'OR');
	}

	public static function andWhere($subject, $operator = NULL, $value =  NULL)
	{
		$class = self::caller();

		return $class::where($subject, $operator, $value, 'AND');
	}

	public static function having($subject, $operator = NULL, $value =  NULL, $concat = 'AND')
	{
		$class = self::caller();

		return $class::query()->having($subject, $operator, $value, $concat);
	}

	public static function orHaving($subject, $operator = NULL, $value =  NULL)
	{
		$class = self::caller();

		return $class::having($subject, $operator, $value, 'OR');
	}

	public static function andHaving($subject, $operator = NULL, $value =  NULL)
	{
		$class = self::caller();

		return $class::having($subject, $operator, $value, 'AND');
	}

	public static function call($obj, $method, $args = array())
	{
		return call_user_func_array(array($obj, $method), $args);
	}

	public static function query()
	{
		$class = self::instantiate();

		$query = new QueryBuilder();
		$query->model = get_class($class);

		$query->from($class::table());
		$query->select('*');

		return $query;
	}

	public static function init($result)
	{
		$class = self::caller();

		if($result->num_rows() == 0)
		{
			return NULL;
		}

		$return = new $class();
		$return->exists = TRUE;
		$return->setAttributes($result->row());

		return $return;	
	}

	public static function table()
	{
		$class = self::caller();

		return $class::instantiate()->table;
	}

	public static function caller()
	{
		return get_called_class();
	}

	public static function instantiate()
	{
		$class = self::caller();
		
		return new $class();
	}
}