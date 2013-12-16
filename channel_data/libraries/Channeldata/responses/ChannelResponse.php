<?php namespace ChannelData\Response;

use ChannelData\Component\ChannelCollection;
use ChannelData\Component\ChannelEntriesParser;

// namespace ChannelData;

class ChannelResponse extends QueryResponse {

	public function entryIds()
	{
		$ids = array();

		return $this->result()->ids();
	}

	public function entries()
	{
		$parser = new ChannelEntriesParser();

		if($this->count() == 0)
		{
			return ee()->entries_lib->no_results();
		}
		
		return $parser->entries(array(
			'entry_id' => implode('|', $this->entryIds())
		));
	}

	protected function _collection($data)
	{
		if($this->model) {
			return new ChannelCollection($data, $this, $this->model);
		}

		return $data;
	}
}