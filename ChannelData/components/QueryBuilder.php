<?php namespace ChannelData\Component;

// namespace ChannelData;

use ChannelData\Response\QueryResponse;

class QueryBuilder {
	
	protected $from     = array();
	
	protected $groupBy  = array();
	
	protected $having   = array();
	
	protected $join     = array();
			
	protected $select   = array();
	
	protected $where    = array();

	protected $limit    = FALSE;

	protected $offset   = 0;

	protected $orderBy  = FALSE;

	protected $sort     = FALSE;

	protected $defaultOperator = '=';

	public $model;

	public function __call($method, $args = array())
	{
		if(preg_match('/^get/', $method))
		{
			$prop = strtolower(preg_replace('/^get/', '', $method));

			if(isset($this->$prop))
			{
				return $this->$prop;
			}
		}

		if(preg_match('/^set/', $method))
		{
			$prop = strtolower(preg_replace('/^set/', '', $method));

			if(isset($this->$prop))
			{
				return $this->$prop = $args[0];
			}
		}

		throw new \Exception("{$method} is not a valid method");
	}

	public function from($table)
	{
		if(!is_object($table))
		{
			$table = QueryString::table($table);
		}

		$this->from[] = (string) $table;
	}

	public function join($table, $subject = NULL, $operator = NULL, $value = NULL, $type = 'JOIN')
	{
		if(is_closure($table))
		{
			$this->join[] = $this->_closure('join', $table, ' ', FALSE);
		}
		else if(is_object($table) && $subject === NULL && $operator === NULL)
		{
			$this->join[] = $type.' '.(string) $table;
		}
		else
		{
			$this->join[] = $type.' '.
							QueryString::table($table).' ON '.
							QueryString::table($subject).' '.
							QueryString::operator($operator).' '.
							QueryString::table($value);
		}

		return $this;
	}

	public function leftJoin($table, $subject = NULL, $operator = NULL, $value = NULL)
	{
		return $this->join($table, $subject, $operator, $value, 'LEFT JOIN');
	}

	public function rightJoin($table, $subject = NULL, $operator = NULL, $value = NULL)
	{
		return $this->join($table, $subject, $operator, $value, 'RIGHT JOIN');
	}

	public function innerJoin($table, $subject = NULL, $operator = NULL, $value = NULL)
	{
		return $this->join($table, $subject, $operator, $value, 'INNER JOIN');
	}
	
	public function outerJoin($table, $subject = NULL, $operator = NULL, $value = NULL)
	{
		return $this->join($table, $subject, $operator, $value, 'OUTER JOIN');
	}

	private function _closure($prop, $closure, $glue, $encapsulate = TRUE)
	{
		$builder = new QueryBuilder();
		$method  = 'get'.ucfirst($prop);

		$closure($builder);

		$return = QueryString::clean(implode($glue, $builder->$method()));

		if($encapsulate)
		{
			$return = '('.$return.')';
		}

		return $return;
	}

	public function select($select, $as = NULL, $table = FALSE)
	{
		if(is_array($select))
		{
			foreach($select as $field)
			{
				$this->select($this->protect($field), $as, $table);
			}
		}
		else if(is_closure($select))
		{
			$this->select[] = $this->_closure('select', $select, ', ');
		}
		else
		{
			$select = $this->protect($select);

			if($table)
			{
				$select = $this->table($table) . '.' .  $select;
			}

			if($as)
			{
				$select .= ' as '.$this->escape($as);
			}

			$this->select[] = $select;
		}

		return $this;
	}

	public function _conditional($type, $subject, $operator = NULL, $value =  NULL, $concat = 'AND')
	{
		if(is_closure($subject))
		{
			$append = trim($concat) . ' ' . $this->_closure($type, $subject, ' ');
		}
		else if(is_array($subject))
		{
			$append = NULL;

			if($operator === NULL)
			{
				$operator = '=';
			}

			foreach($subject as $field => $value)
			{
				$this->$type($field, $operator, $value, $concat);
			}
		}
		else if(is_object($subject) && $operator === NULL && $value === NULL)
		{
			$append = $concat.' ('.(string) $subject.')';
		}
		else
		{
			if(strtoupper($value) == 'AND' || strtoupper($value) == 'OR')
			{
				$concat = $value;
				$value  = NULL;
			}

			if(!is_null($operator) && is_null($value))
			{
				if($subject != 'entry_date')
				{				}

				$value    = $operator;
				$operator = $this->defaultOperator;
			}

			if(is_array($value))
			{
				foreach($value as $index => $val)
				{
					$value[$index] = $this->escape($val);
				}

				$value = QueryString::raw('('.implode(', ', $value).')');
			}

			$append = $concat.' ('.
							 $this->protect($subject).' '.
			 			     $this->operator($operator).' '.
			 				 $this->escape($value).')';
		}

		$this->$type = array_merge($this->$type, array($append));

		return $this;
	}

	public function where($subject, $operator = NULL, $value =  NULL, $concat = 'AND')
	{
		return $this->_conditional('where', $subject, $operator, $value, $concat);
	}

	public function whereIn($subject, $value =  NULL)
	{
		return $this->where($subject, QueryString::raw('IN'), $value, 'AND');
	}

	public function orWhereIn($subject, $value = NULL)
	{
		return $this->where($subject, QueryString::raw('IN'), $value, 'OR');
	}

	public function andWhereIn($subject, $value =  NULL)
	{
		return $this->where($subject, QueryString::raw('IN'), $value);
	}

	public function andWhere($subject, $operator = NULL, $value = NULL)
	{
		return $this->where($subject, $operator, $value);
	}

	public function orWhere($subject, $operator = NULL, $value = NULL)
	{
		return $this->where($subject, $operator, $value, 'OR');
	}

	public function having($subject, $operator = NULL, $value =  NULL)
	{
		return $this->_conditional('having', $subject, $value, 'AND');
	}

	public function havingIn($subject, $value =  NULL)
	{
		return $this->having($subject, QueryString::raw('IN'), $value, 'AND');
	}

	public function orHavingIn($subject, $value =  NULL)
	{
		return $this->having($subject, QueryString::raw('IN'), $value, 'OR');
	}

	public function andHavingIn($subject, $value =  NULL)
	{
		return $this->having($subject, QueryString::raw('IN'), $value);
	}

	public function andHaving($subject, $operator, $value)
	{
		return $this->having($subject, $operator, $value);
	}

	public function orHaving($subject, $operator, $value)
	{
		return $this->having($subject, $operator, $value, 'OR');
	}

	public function orderBy($field, $sort = FALSE)
	{
		$this->orderBy = $this->protect($field);

		if($sort)
		{
			$this->sort($sort);
		}
	}

	public function sort($sort)
	{
		$this->sort = !is_object($sort) ? strtoupper($sort) : (string) $sort;
	}

	public function limit($limit, $offset = FALSE)
	{
		$this->limit = $limit;

		if($offset !== FALSE)
		{
			$this->offset = $offset;
		}
	}

	public function offset($offset)
	{
		$this->offset = !is_object($offset) ? $offset : (string) $sort;
	}

	public function table($protect)
	{
		return QueryString::table($protect);
	}

	public function protect($protect)
	{
		return QueryString::protect($protect);
	}

	public function operator($operator)
	{
		return QueryString::operator($operator);
	}

	public function escape($value)
	{
		return QueryString::escape($value);
	}

	public function get()
	{
		return new QueryResponse($this, $this->model);
	}

	public function result()
	{
		return $this->get()->result();
	}

	public function sql()
	{
		$query = array();
		
		$select = $this->select;
		$from = NULL;
		$having = NULL;
		$where  = NULL;
		$join   = NULL;
		$orderBy = NULL;
		$limit   = NULL;

		if(empty($this->select))
		{
			$select = '*';
		}
		else
		{
			$select = implode(', ', $this->select);
		}

		if(count($this->having))
		{
			$having = 'HAVING '.QueryString::clean($this->having);
		}

		if(count($this->where))
		{
			$where = 'WHERE '.QueryString::clean($this->where);
		}

		if(count($this->join))
		{
			$join = QueryString::clean($this->join);
		}

		if($this->orderBy)
		{
			$orderBy = trim('ORDER BY '.$this->orderBy.' '.$this->sort);
		}

		if($this->limit)
		{
			$limit = trim('LIMIT '.$this->offset.', '.$this->limit);
		}

		if($this->from)
		{
			$from = 'FROM '.implode(' ', $this->from);
		}

		$sql = trim('
			SELECT 
			'.$select.'
			'.$from.'
			'.$join.'
			'.$where.'
			'.$having.'
			'.$orderBy.'
			'.$limit.'
		');

		return $sql;
	}
}