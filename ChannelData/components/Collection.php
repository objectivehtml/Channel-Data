<?php namespace ChannelData\Component;

// namespace ChannelData;

class Collection {

	protected $builder;
	
	protected $query;
	
	protected $model;

	protected $items = array();

	public function __construct($data, $model)
	{
		$this->model = $model;

		foreach($data as $index => $row)
		{
			$this->items[] = new $this->model($row);
		}
	}

	public function reindex($index)
	{
		$return = array();

		foreach($this->items() as $item)
		{
			$return[$item->$index] = $item->toArray();
		}

		return $return;
	}

	public function each($closure)
	{
		if(is_closure($closure))
		{
			foreach($this->items() as $index => $item)
			{
				$closure($index, $item);
			}
		}
		else
		{
			throw new Exception('Closure is expected, '.ucfirst(gettype($closure)).' given.', 1);
			
		}
	}
	public function items()
	{
		return $this->items;
	}

	public function first()
	{
		return $this->get(0);
	}

	public function last()
	{
		return $this->get($this->count() - 1);
	}

	public function get($index = FALSE)
	{
		return $index !== FALSE && isset($this->items[$index]) ? $this->items[$index] : $this->items;
	}

	public function count()
	{
		return count($this->items);
	}

	public function toArray()
	{
		$response = array();

		foreach($this->items as $item)
		{
			$response[] = $item->toArray();
		}

		return $response;
	}
}