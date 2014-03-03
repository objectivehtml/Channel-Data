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

	public function entries($fixedOrder = FALSE)
	{
		$parser = new ChannelEntriesParser();

		if($this->count() == 0)
		{
			return ChannelEntriesParser::noResults();
		}
		
		$ids = implode('|', $this->entryIds());
		
		$params = array(
			'entry_id'    => $ids
		);

		if($fixedOrder)
		{
			$params['fixed_order'] = $ids;
		}

		return $parser->entries($params);
	}

	protected function _collection($data)
	{
		if($this->model) {
			return new ChannelCollection($data, $this, $this->model);
		}

		return $data;
	}
}