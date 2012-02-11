<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Channel Data Utility
 *
 * A data utility for performing varies common task
 *
 * @package		Channel Data
 * @subpackage	Libraries
 * @category	Drivers
 * @author		Justin Kimbrell
 * @link		http://www.objectivehtml.com/libraries/channel_data
 * @version		0.6.5
 * @build		20120211
 */
 
class Channel_data_utility {
	
	/**
	 * Add a prefix to an result array or a single row.
	 * Must pass an array.
	 *
	 * @access	public
	 * @param	string	The prefix
	 * @param	array	The data to prefix
	 * @param	string	The delimiting value
	 * @return	array
	 */
	 public function add_prefix($prefix, $data, $delimeter = ':')
	 {
	 	$new_data = array();
	 	
	 	foreach($data as $data_index => $data_value)
	 	{
	 		if(is_array($data_value))
	 		{
	 			$new_row = array();
	 			
	 			foreach($data_value as $inner_index => $inner_value)
	 			{
	 				$new_row[$prefix . $delimeter . $inner_index] = $inner_value;
	 			}
	 			
	 			$new_data[$data_index] = $new_row;
	 		}
	 		else
	 		{
	 			$new_data[$prefix . $delimeter . $data_index] = $data_value;
	 		}
	 	}
	 	
	 	return $data;	
	 }	
}