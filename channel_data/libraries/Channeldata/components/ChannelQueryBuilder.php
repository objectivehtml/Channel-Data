<?php namespace ChannelData\Component;

// namespace ChannelData;

use ChannelData\Response\ChannelResponse;

class ChannelQueryBuilder extends QueryBuilder {
	
	public function future($date = FALSE)
	{
		if(!$date)
		{
			$date = time();
		}

		return $this->where('entry_date', '>', $date);
	}

	public function past($date = FALSE)
	{
		if(!$date)
		{
			$date = time();
		}

		return $this->where('entry_date', '<', $date);
	}

	public function expired($date = FALSE)
	{
		if(!$date)
		{
			$date = time();
		}

		return $this->where('expired_entries', '<=', $date);
	}

	public function open()
	{
		return $this->where('status', 'open');
	}

	public function closed()
	{
		return $this->where('status', 'closed');
	}

	public function notClosed()
	{
		return $this->where('status', '!=', 'closed');
	}

	public function notOpen()
	{
		return $this->where('status', '!=', 'open');
	}

	public function get()
	{
		return new ChannelResponse($this, $this->model);
	}

}