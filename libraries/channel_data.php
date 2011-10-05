<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Channel Data Class
 *
 * Channel Data is a convenience class designed to easily retrieve
 * channel data from plugins. By using this one class, you don't 
 * have to worry about loading models.
 *
 * @package		ExpressionEngine
 * @category	Library
 * @author		Justin Kimbrell
 * @copyright	Copyright (c) 2011, SaucePan Creative
 * @link 		http://www.inthesaucepan.com
 * @version		1.0 
 * @build		20110922
 */
 
class Channel_data {
	
	public function __construct()
	{
		$this->EE =& get_instance();
		
		$this->EE->load->model('category_model');
		$this->EE->load->model('channel_model');
		$this->EE->load->model('channel_entries_model');
		$this->EE->load->model('field_model');
		$this->EE->load->model('entries_model');
	}
		
	/**
	 * Get Channel Info
	 *
	 * Gets info for a single channel
	 *
	 * @access	public
	 * @param	mixed	A channel id
	 * @param	array 	An array of fields to select in the query
	 * @return	string
	 */
	
	public function get_channel_info($channel_id, $select = array())
	{
		return $this->EE->channel_model->get_channel_info($channel_id, $select);
	}
	
	
	/**
	 * Get Channels
	 *
	 * Gets the channels
	 *
	 * @access	public
	 * @param	array	An array of fields to select in the query
	 * @param 	array	An associative array to build the where conditional
	 * @param	mixed	A site id
	 * @return	string
	 */
	
	public function get_channels($select = array(), $where = array(), $site_id = NULL)
	{
		return $this->EE->channel_model->get_channels($site_id, $select, array($where));
	}
	
	
	/**
	 * Get Channel Fields
	 *
	 * Get the channel fields.
	 *
	 * @access	public
	 * @param	mixed	Field group can be an int or string
	 * @param	array	An array of fields to select in the query
	 * @return	string
	 */
	
	public function get_channel_fields($field_group, $select = array())
	{
		return $this->EE->channel_model->get_channel_fields($field_group, $select);
	}
	
	
	/**
	 * Get Entries
	 *
	 * Gets channel entries with easy filtering. This function is
	 * different than the native functionality in that is gets the fields
	 * names and  
	 *
	 * @access	public
	 * @param	mixed	An array of fields to select in the query
	 * @param	array	An associative array to build the where conditional
	 * @return	string
	 */
	
	public function get_entries($channel_id, $select = array(), $where = array())
	{
		$channel = $this->get_channel_info($channel_id);
		
		if(!$channel) return FALSE;
		
		$channel = $channel->row();
		$fields	 = $this->get_fields($channel->field_group)->result();
								
		foreach($fields as $field)
		{
			$select[] = 'field_id_'.$field->field_id.' as \''.$field->field_name.'\'';
			
			foreach($where as $index => $value)
			{
				if($field->field_name == $index)
				{
					unset($where[$index]);
					$where['field_id_'.$field->field_id] = $value;
				}
			}			
		}
		
		return $this->EE->entries_model->get_entries($channel->channel_id, $select, $where);
	}
	
	
	/**
	 * Get Entry
	 *
	 * Gets a single entry from an entry id. 
	 *
	 * @access	public
	 * @param	mixed	The entry id could be a string or integer
	 * @param	array	An array of fields to select in the query
	 * @return	string
	 */
	
	public function get_entry($entry_id, $select = array())
	{
		$entry		= $this->get_entry_by_id($entry_id);
		
		if($entry->num_rows == 0)
			return FALSE;
		
		$entry		= $entry->row();
		
		$channel_id	= $entry->channel_id;
		$where		= array('channel_data.entry_id'	=> $entry_id);
		
		return $this->get_entries($channel_id, $select, $where);
	}
	
	
	/**
	 * Get Entry By Id
	 *
	 * Gets the entry from a give id.
	 *
	 * @access	public
	 * @param	int		A valid entry id
	 * @return	string
	 */
	
	public function get_entry_by_id($entry_id)
	{
		if (!$entry_id)
		{
			return FALSE;
		}
		
		$this->EE->db->select('*');
		$this->EE->db->where('channel_titles.entry_id', $entry_id);
		
		return $this->EE->db->get('channel_titles');
	}
	
	/**
	 * Get Entry Categories
	 *
	 * Gets all the categories associated with a given entry
	 *
	 * @access	public
	 * @param	int
	 * @return	string
	 */
	 
	 public function get_entry_categories($entry_id)
	 {
	 	$this->EE->db->where('entry_id', $entry_id);
	 	
	 	return $this->EE->db->get('category_posts');
	 }
	
	/**
	 * Get Fields
	 *
	 * Gets the field associated to the group
	 *
	 * @access	public
	 * @param	mixed	A field group id
	 * @param	array	An associative array to build the where conditional
	 * @return	string
	 */
	
	public function get_fields($group_id = '', $where = array())
	{
		return $this->EE->field_model->get_fields($group_id, $where);	
	}
	
	
	/**
	 * Get Field Groups
	 *
	 * Gets the field groups
	 *
	 * @access	public
	 * @param	mixed	An array of fields to select in the query
	 * @param	array	An associative array to build the where conditional
	 * @return	string
	 */
	
	public function get_field_groups($select = FALSE, $where = array())
	{
		if($select) $this->EE->db->select($select);
		
		foreach($where as $row)
		{
			if(is_array($row))
				$this->EE->db->where_in($row);
			else
				$this->EE->db->where($row);
		}
		
		return $this->EE->field_model->get_field_groups();
	}
}
