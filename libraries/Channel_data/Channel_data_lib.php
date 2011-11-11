<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Channel Data Library
 *
 * This class is the core library to interact with ExpressionEngine's
 * channel data. This class has been abstracted out of the loading
 * logic to make it easier manager and more modular.
 *
 * @package		Channel Data
 * @subpackage	Libraries
 * @category	Library
 * @author		Justin Kimbrell
 * @copyright	Copyright (c) 2011, Justin Kimbrell
 * @link 		http://www.objectivehtml.com/libraries/channel_data
 * @version		0.3.3 
 * @build		20111111
 */

if(!class_exists('Channel_data_lib'))
{
	class Channel_data_lib {
		
		private $reserved_terms = array(
			'select', 'like', 'or_like', 'or_where', 'where', 'where_in', 
			'order_by', 'sort', 'limit', 'offset'
		);
			
		/**
		 * Construct
		 *
		 * Gets the instance variable
		 *
		 * @param	array	Additional parameters used to instatiate the object
		 * @return	void
		 */	
		
		public function __construct($params = array())
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
		 * Gets a channel by passing a channel_name
		 *
		 * @access	public
		 * @param	string	A string containing a channel name
		 * @param	mixed	An array or string of fields to select. Default: '*'
		 * @return	object
		 */
		 
		public function get_channel_by_name($channel_name, $select = array('*'))
		{
			return $this->get_channels($select, array('channel_name' => $channel_name));
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
			return $this->get_fields($select, array('field_id' => $field_id));
		}
		
		/**
		 * Gets the custom fields by the group_id. This somehwat mimics the
		 * the native get_channel_fields method.
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
		 
		public function get_channel_fields($group_id, $order_by = 'field_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
		{
			return $this->get_fields(array('*'), array('group_id' => $group_id), $order_by, $sort, $limit, $offset);
		
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
			$this->convert_params($select, $where, $order_by, $sort, $limit, $offset);
			
			return $this->EE->db->get('channel_fields');
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
		
		public function get_entries($select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status'), $where = array(), $order_by = 'channel_titles.channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
		{
			return $this->get_channel_entries(FALSE, $select, $where = array(), $order_by, $sort, $limit, $offset);
		}
		
		/**
		 * Get a single entry passing an entry id
		 *
		 * @access	public
		 * @param	int
		 * @param	mixed
		 * @return	mixed
		 */
		
		public function get_channel_entry($entry_id, $select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status'))
		{
			$entry = $this->get_channel_title($entry_id);
			
			if($entry->num_rows() == 1)
			{
				$entry->row();
			
				return $this->get_channel_entries($entry->row('channel_id'), $select, array('channel_data.entry_id' => $entry_id));
			}
			
			return FALSE;
		}
		
		/**
		 * Get entries by specifying a channel id. Polymorphic paramerts are
		 * also accepted. The channel id is required.
		 *
		 * @access	public
		 * @return	string
		 */
			
		public function get_channel_entries($channel_id, $select = array(), $where = array(), $order_by = 'channel_titles.channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
		{	
			
			//Default fields to select
						
			$default_select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status');
			
			
			// If the parameter is polymorphic, then the variables are extracted
			
			if($this->is_polymorphic($select) && $polymorphic = $select)
			{
				extract($select);
				
				foreach($this->reserved_terms as $term)
				{
					if(!isset($polymorphic[$term]) && isset($$term) || isset($polymorphic[$term]))
					{
						if($term == 'select')
							$$term = $default_select;
						else
							$$term = isset($polymorphic[$term]) ? $polymorphic[$term] : $$term;
					}
				}
			}
			
			// If the channel_id is not false then only the specified channel fields are
			// appended to the query. Otherwise, all fields are appended.
			
			if($channel_id !== FALSE)
			{
				$channel = $this->get_channel($channel_id)->row();
				$fields	 = $this->get_channel_fields($channel->field_group)->result();
				$where['channel_data.channel_id'] = $channel_id;
			}
			else
			{
				$fields  = $this->get_fields()->result();
				$select	 = array();
			}
			
			
			// Selects the appropriate field name and converts where converts
			// where parameters to their corresponding field_id's
			
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
			
			// Joins the channel_data table
					
			$this->EE->db->join('channel_data', 'channel_titles.entry_id = channel_data.entry_id');
			
			// Converts the params into active record methods
			
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
			$params	= array(
				'select' 	=> $select,
				'where'		=> $where,
				'order_by'	=> $order_by,
				'sort'		=> $sort,
				'limit'		=> $limit,
				'offset'	=> $offset
			);
			
			foreach($this->reserved_terms as $term)
			{
				if(is_array($select) && isset($select[$term]))
					$params[$term] = $select[$term];
			}	
			
			foreach($this->reserved_terms as $term)
			{
				if(isset($params[$term]) && $params[$term] !== FALSE)
				{
					
					$param = $params[$term];
					
					switch ($term)
					{
						case 'select':
						
							if(!is_array($param))
								$param = array($param); 
							
							foreach($param as $select)
								$this->EE->db->select($select);
							
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
								$sort = isset($sort) ? $sort : 'DESC';
								
								$this->EE->db->order_by($param, $sort);
							}
							
							break;
							
						case 'limit': 
							if(!is_array($param))
								$param = array($param);
							
							$offset = isset($param['offset']) ? $param['offset'] : $offset;
							$offset	= $offset !== FALSE ? $offset : 0;	
							
							foreach($param as $param)							
								$this->EE->db->limit($param, $offset);
							
							
							break;
					}
				}
			}	
			
			return $params;		
		}
		
		/**
		 * Is Polymorphic
		 *
		 * Determines if a parameter is polymorphic
		 *
		 * @access	public
		 * @param	mixed	A mixed value variable
		 * @return	bool
		 */
			
		public function is_polymorphic($param)
		{
			if(is_array($param))
			{
				foreach($this->reserved_terms as $term)
				{
					if(isset($param[$term]))
					{
						return TRUE;
					}
				}
			
			}
			
			return FALSE;
		}
	}
}