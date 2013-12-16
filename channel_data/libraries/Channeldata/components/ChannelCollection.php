<?php namespace ChannelData\Component;

class ChannelCollection extends Collection {

	public function entries()
	{
		return $this->response->entries();
	}

}