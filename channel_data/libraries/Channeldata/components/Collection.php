<?php namespace ChannelData\Component;

// namespace ChannelData;

class Collection {

	protected $builder;
	
	protected $model;

	protected $response;

	protected $items = array();

	public function __construct($data, $response, $model)
	{
		$this->response = $response;
		$this->model    = $model;

		foreach($data as $index => $row)
		{
			$this->items[] = new $this->model($row);
		}
	}

	public function ids()
	{
		$ids = array();

		foreach($this->items as $item)
		{
			$ids[] = $item->id();
		}

		return $ids;
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

	public function all()
	{
		return $this->items();
	}

	public function get($index = FALSE, $default = NULL)
	{
		return $index !== FALSE && isset($this->items[$index]) ? $this->items[$index] : NULL;
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