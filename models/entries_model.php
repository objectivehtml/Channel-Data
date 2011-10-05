<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entries Model
 *
 * Extends the native Channel_entries_model to give additional 
 * functionality.
 *
 * @access	public
 * @return	string
 */

class Entries_model extends Channel_entries_model {
	
	/**
	 * Get channel entries
	 *
	 * @access	public
	 * @return	string
	 */
	
	function get_entries($channel_id, $additional_fields = array(), $additional_where = array(), $limit = FALSE, $order_by = 'entry_id', $sort = 'DESC')
	{
		if ( ! is_array($additional_fields))
		{
			$additional_fields = array($additional_fields);
		}

		if (count($additional_fields) > 0)
		{
			$this->db->select(implode(',', $additional_fields));
		}
		
		if($limit !== FALSE) $this->db->limit($limit);
		
		$this->db->order_by($order_by, $sort);

		// default just fecth entry id's
		$this->db->select('channel_titles.entry_id');
		$this->db->join('channel_data', 'channel_titles.entry_id = channel_data.entry_id');
		$this->db->from('channel_titles');
		
		// which channel id's?
		if (is_array($channel_id))
		{
			$this->db->where_in('channel_titles.channel_id', $channel_id);
		}
		else
		{
			$this->db->where('channel_titles.channel_id', $channel_id);
		}

		// add additional WHERE clauses
		foreach ($additional_where as $field => $value)
		{
			if (is_array($value))
			{
				$this->db->where_in($field, $value);
			}
			else
			{
				$this->db->where($field, $value);
			}
		}

		return $this->db->get();
	}	
}