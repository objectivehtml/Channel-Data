<?php namespace ChannelData\Component;

class ChannelEntriesApi {
		
	/**
	 * Submits an entry using the channel entries API
	 *
	 * @access	public
	 * @param	mixed	The channel id
	 * @param	array	The entry data
	 * @return	int
	 */
	public static function submit_entry($channel_id, $data)
	{
		ee()->load->library('api');
		ee()->api->instantiate('channel_entries');
		ee()->api->instantiate('channel_fields');
		
		ee()->session->userdata['group_id'] = 1;

		ee()->api_channel_fields->setup_entry_settings($channel_id, $data);
		
		ee()->api_channel_entries->submit_new_entry($channel_id, $data);

		if(count(ee()->api_channel_entries->errors) > 0)
		{
			return ee()->api_channel_entries->errors;
		}

		return ee()->api_channel_entries->entry_id;
	}

	/**
	 * Updates an entry using the channel entries API
	 *
	 * @access	public
	 * @param	mixed	The channel id
	 * @param	mixed	The entry id
	 * @param	array	The entry data
	 * @return	int
	 */
	public static function update_entry($channel_id, $entry_id, $data)
	{
		ee()->load->library('api');
		ee()->api->instantiate('channel_entries');
		ee()->api->instantiate('channel_fields');
		
		$data['entry_id']   = $entry_id;
		$data['channel_id'] = $channel_id;

		ee()->session->userdata['group_id'] = 1;

		ee()->api_channel_fields->setup_entry_settings($channel_id, $data);
		
		ee()->api_channel_entries->update_entry($entry_id, $data);
		
		if(count(ee()->api_channel_entries->errors) > 0)
		{
			return ee()->api_channel_entries->errors;
		}

		return TRUE;
	}
}