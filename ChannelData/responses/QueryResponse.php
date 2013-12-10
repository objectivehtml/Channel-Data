<?php namespace ChannelData\Response;

use ChannelData\Component\Collection;
use ChannelData\Component\QueryBuilder;

class QueryResponse {

	protected $builder;

	protected $query;

	protected $model;

	public function __construct(QueryBuilder $builder, $model)
	{
		$this->builder = $builder;
		$this->model = $model;

		$this->query = ee()->db->query($this->builder->sql());
	}

	public function count()
	{
		return $this->query->num_rows();
	}

	public function each($closure)
	{
		return $this->result()->each($closure);
	}

	public function first()
	{
		if($this->count())
		{		
			return $this->_model($this->query->first_row());
		}

		return NULL;
	}

	public function index($index)
	{
		$data = $this->result();

		return isset($data[$index]) ? $this->_model($data[$index]) : NULL;
	}

	public function last()
	{
		return $this->_model($this->query->last_row());
	}

	public function next()
	{
		return $this->_model($this->query->next_row());
	}

	public function prev()
	{
		return $this->_model($this->query->prev_row());
	}

	public function result()
	{
		return $this->_collection($this->query->result());
	}

	public function row()
	{
		return $this->_model($this->query->row());
	}

	private function _model($data)
	{
		if($this->model) {
			return new $this->model($data);
		}

		return $data;
	}

	private function _collection($data)
	{
		if($this->model) {
			return new Collection($data, $this->model);
		}

		return $data;
	}
}