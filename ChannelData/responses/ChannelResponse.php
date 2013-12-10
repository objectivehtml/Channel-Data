<?php namespace ChannelData\Response;

// namespace ChannelData;

class ChannelResponse extends QueryResponse {

	public function entryIds()
	{
		$ids = array();

		foreach(parent::result() as $index => $row)
		{
			$ids[] = $row->entry_id;
		}

		return implode('|', $ids);
	}

	public function entries()
	{
		if($this->num_rows() == 0)
		{
			return ee()->entries_lib->no_results();
		}

		return ee()->entries_lib->entries(array(
			'entry_id' => $this->entryIds()
		));
	}

}