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
 * @version		2.0 
 * @build		20110922
 */
 
class Channel_data {
	
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	/**
	 * Get a single category by passing a category id. 
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	string
	 */
	
	public function get_category($category_id, $select = array('*'))
	{
		return $this->get_categories($select, array('category_id' => $category_id));
	}
	
	/**
	 * Get categories using on a series of polymorphic parameters that
	 * returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_categories($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('categories');
	}
	
	/**
	 * Get category field by passing a field id.
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_category_field($field_id, $select = array('*'))
	{
		return $this->get_category_fields($select, array('field_id' => $field_id));
	}
	
	/**
	 * Get category fields using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_category_fields($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('category_fields');
	}
	
	/**
	 * Get category field data using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_category_field_data($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('category_field_data');
	
	}
	
	/**
	 * Get category groups using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	 
	public function get_category_group($group_id, $select = array('*'))
	{
		return $this->EE->db->get($select, array('group_id' => $group_id));
	}
	
	/**
	 * Get category groups by using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_category_groups($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('category_groups');
	
	}
	
	/**
	 * Get the category posts by an entry id.
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_category_post($entry_id, $select = array('*'))
	{
		return $this->get_category_posts($select, array('entry_id' => $entry_id));
	}
	
	/**
	 * Get the category posts using a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_category_posts($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('category_posts');
	}
	
	/**
	 * Gets a channel by passing a channel_id
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	object
	 */
	 
	public function get_channel($channel_id, $select = array('*'))
	{
		return $this->get_channels($select, array('channel_id' => $channel_id));
	}
	
	/**
	 * Get channel fields using a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_channels($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('channels');
	}
	
	/**
	 * Get custom field from a specified field_id.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	 
	public function get_channel_field($field_id, $select = array('*'))
	{
		return $this->get_channel_fields($select, array('field_id' => $field_id));
	}
	
	/**
	 * Get custom fields using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	 
	public function get_channel_fields($select = array('*'), $where = array(), $order_by = 'field_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		return $this->EE->db->get('channel_fields');
	}
	
	/**
	 * Get a custom field using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_custom_field($field_id, $select = array('*'))
	{
		return $this->get_channel_field($field_id, $select);
	}
	
	/**
	 * Get custom fields using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_custom_fields($select = array('*'), $where = array(), $order_by = 'field_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{
		return $this->get_channel_fields($select, $where, $order_by, $sort, $limit, $offset);
	}
	
	/**
	 * An alias to get_channel_fields and get_custom_fields.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_fields($select = array('*'), $where = array(), $order_by = 'field_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{
		return $this->get_channel_fields($select, $where, $order_by, $sort, $limit, $offset);
	}
	
	/**
	 * An alias to get_channel_field and get_custom_field.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	 
	public function get_field($field_id, $select = array('*'))
	{
		return $this->get_channel_field($field_id, $select);
	}
 
	/**
	 * Plugin Name
	 *
	 * Plugin description
	 *
	 * @access	public
	 * @return	string
	 */
	
	public function get_fields_by_group($group_id, $select = array('*'))
	{
		return $this->get_channel_fields($select, array('group_id' => $group_id));
	}	  
		  
	
	/**
	 * Get channel member groups by either a group_id or channel_id
	 *
	 * @access	public
	 * @param	int
	 * @param	int
	 * @return	string
	 */
	
	public function get_channel_member_group($group_id = FALSE, $channel_id = FALSE)
	{
		$params = array();
		
		if($group_id !== FALSE)
			$params['where']['group_id'] = $group_id;
		
		if($channel_id !== FALSE)
			$params['where']['channel_id'] = $channel_id;
		
		$this->convert_params($params);
		
		return $this->EE->db->get('channel_member_groups');
	}
	
	/**
	 * Get the channel member group using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_channel_member_groups($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		
		return $this->EE->db->get('channel_member_groups');
	}
	
	/**
	 * Get channel title using an entry id
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_channel_title($entry_id, $select = array('*'))
	{
		return $this->get_channel_titles($select, array('entry_id' => $entry_id));
	}
	
	/**
	 * Get channel titles using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_channel_titles($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('channel_titles');
	}
	
	/**
	 * Get channel data using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_channel_data($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
				
		return $this->EE->db->get('channel_data');
	}
	
	/**
	 * Get a single entry passing an entry id
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_entry($entry_id, $select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status'))
	{
		$entry = $this->get_channel_title($entry_id)->row();
				
		return $this->get_entries($entry->channel_id, $select, array('channel_data.entry_id' => $entry_id));
	}
	
	/**
	 * Get entries by specifying a channel id. Polymorphic paramerts are
	 * also accepted. The channel id is required.
	 *
	 * @access	public
	 * @return	string
	 */
		
	public function get_entries($channel_id, $select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status'), $where = array(), $order_by = 'channel_titles.channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{		
		if($channel_id !== FALSE)
		{
			$channel = $this->get_channel($channel_id)->row();
			$fields	 = $this->get_channel_fields('*', array('group_id' => $channel->field_group))->result();
					
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
			
			$this->EE->db->where($where);			
			$this->EE->db->join('channel_data', 'channel_titles.entry_id = channel_data.entry_id');
		}
		
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('channel_titles');		
	}
	
	/**
	 * Get a single relationship by passing an relationship id
	 *
	 * @access	public
	 * @param	int
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_relationship($rel_id, $select = array('*'))
	{
		return $this->get_relationships($select, array('rel_id' => $rel_id));
	}
	
	/**
	 * Get relationships by using on a series of polymorphic parameters 
	 * that returns an active record object.
	 * 
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	
	public function get_relationships($select = array(), $where = array(), $order_by = 'channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	{			
		$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
		
		return $this->EE->db->get('relationships');
	}	
	
	/**
	 * Convert Params
	 * 
	 * Converts polymorphic parameters into an array that is used to
	 * build the active record sequence.
	 *
	 * Example: 
	 *
	 * 	$this->convert_params(array(
	 * 		'select' 	=> array('channel_id', 'channel_name'),
	 *		'where'	 	=> array('channel_id' => 1),
	 *		'order_by'	=> 'channel_id'
	 * 	));
	 *
	 * 	$this->convert_params(array(
	 * 		'select' 	=> '*',
	 *		'order_by'	=> array('channel_description', 'channel_name'),
	 *		'sort'		=> 'ASC'
	 * 	));
	 *
	 * $this->convert_params(array('*'), array('channel_id' => 1), 'channel_id', 'DESC', 1, 1);
	 *
	 * There are number of combinations possible that aren't even shown.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @param	mixed
	 * @return	array
	 */
	 
	public function convert_params($select, $where, $order_by, $sort, $limit, $offset)
	{
		$reserved_terms = array(
			'select', 'like', 'or_like', 
			'or_where', 'where', 'where_in', 
			'order_by', 'sort', 'limit', 
			'offset'
		);
		
		$params	= array(
			'select' 	=> $select,
			'where'		=> $where,
			'order_by'	=> $order_by,
			'sort'		=> $sort,
			'limit'		=> $limit,
			'offset'	=> $offset
		);
		
		foreach($reserved_terms as $term)
		{
			if(is_array($select) && isset($select[$term]))
				$params[$term] = $select[$term];
		}	
		
		foreach($reserved_terms as $term)
		{
			if(isset($params[$term]) && $params[$term] !== FALSE)
			{
				$param = $params[$term];
				
				switch ($term)
				{
					case 'select': 
						$this->EE->db->select($param);
						break;
						
					case 'where': 
						$this->EE->db->where($param);
						break;
						
					case 'where_in': 
						$this->EE->db->where_in($param);
						break;
						
					case 'or_where': 
						$this->EE->db->or_where($param);
						break;
						
					case 'order_by':
						if(!is_array($param))
							$param = array($param);
						
						foreach($param as $param)
						{ 
							$sort = isset($param) ? $param : 'DESC';
							$this->EE->db->order_by($param, $sort);
						}
						
						break;
						
					case 'limit': 
						if(!is_array($param))
							$param = array($param);
						
						foreach($param as $param)
						{
							$offset = isset($param) ? $param : 0;					
							$this->EE->db->limit($param, $offset);
						}
						
						break;
				}
			}
		}	
		
		return $params;		
	}
}