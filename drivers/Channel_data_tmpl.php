<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Channel Data Template Parser
 *
 * A helper class to make life easier when parsing fieldtype tags
 *
 * @package		Channel Data
 * @subpackage	Libraries
 * @category	Library
 * @author		Justin Kimbrell
 * @copyright	Copyright (c) 2012, Justin Kimbrell
 * @link 		http://www.objectivehtml.com/libraries/channel_data
 * @version		0.8.21
 * @build		20130413
 */
 
class Channel_data_tmpl extends Channel_data_lib {
	
	public function __construct()
	{
		parent::__construct();
				
		if(!isset($this->EE->TMPL))
		{
			$this->init();
		}
	}
		
	public function init($tagdata = FALSE)
	{
		$orig_settings = FALSE;
		
		if(isset($this->EE->api_channel_fields->settings))
		{
			$orig_settings = $this->EE->api_channel_fields->settings;
		}
		
		$this->EE->load->library('typography');
		$this->EE->load->library('api');
		$this->EE->api->instantiate('channel_fields');
				
		$fields = $this->EE->api_channel_fields->fetch_custom_channel_fields();

		if($orig_settings)
		{
			$this->EE->api_channel_fields->settings = $orig_settings;
		}
		
		$parse_object = (object) array();
	}
	
	public function create_alias($tagdata = NULL)
	{	
		$this->init();
			
		$obj = new EE_Template();
		
		if(isset($this->EE->TMPL))
		{
			$TMPL = $this->EE->TMPL;
		}
		else
		{
			$TMPL = $obj;
		}
		
		$this->EE->TMPL = $obj;
		
		$this->EE->TMPL->template = $tagdata ? $tagdata : $TMPL->tagdata;
		
		return $TMPL;
	}
			
	public function reset($TMPL)
	{
		$this->EE->TMPL = $TMPL;
	}
	
	public function parse_switch($tagdata, $count = 1)
	{
		// Match {switch="foo|bar"} variables			
		$switch = array();

		if (preg_match_all("/".LD."(switch\s*=.+?)".RD."/i", $tagdata, $matches, PREG_SET_ORDER))
		{
			foreach ($matches as $match)
			{
				$sparam = $this->EE->functions->assign_parameters($match[1]);

				if (isset($sparam['switch']))
				{
					$sopt = explode("|", $sparam['switch']);

					$switch[$match[1]] = $sopt;
				}
			}
		}
		
		$row = array();
		
		// Set {switch} variable values
		foreach ($switch as $key => $val)
		{
			$row[$key] = $switch[$key][($count + count($val) -1) % count($val)];
		}
		
		return $this->EE->TMPL->parse_variables_row($tagdata, $row, FALSE);	
	}
	
	public function parse_array($parse_array = array(), $parse_vars = array(), $entry_data  = array(), $channels = FALSE, $channel_fields = array(), $prefix = '')
	{
		if(!$channel_fields)
		{
			$channel_fields = $this->get_channel_fields()->result_array();
			$channel_fields = $this->EE->channel_data->utility->reindex($channel_fields, 'field_name');
		}
		
		if(!$channels)
		{
			$channels = $this->get_channels()->result_array();
			$channels = $this->EE->channel_data->utility->reindex($channels, 'channel_id');
		}
		
		if(is_object($parse_array))
		{
			$parse_array = (array) $parse_array;	
		}
		
		foreach($parse_array as $index => $value)
		{
			if(!empty($value) && is_string($value))
			{
				$parse_array[$index] = $this->parse($parse_vars, $entry_data, $channels, $channel_fields, $value, $prefix, $index);
				
			}
		}
		
		return $parse_array;
	}
	
	public function parse($parse_vars = array(), $entry_data = array(), $channels = FALSE, $channel_fields = FALSE, $tagdata = FALSE, $prefix = '', $index = FALSE)
	{
		
		if(!$tagdata)
		{
			$tagdata = $this->EE->TMPL->template;
		}
		
		if(!$channels)
		{
			$channels = $this->get_channels()->result_array();
			$channels = $this->EE->channel_data->utility->reindex($channels, 'channel_id');		
		}
		
		if(!$channel_fields)
		{
			$channel_fields = $this->get_channel_fields()->result_array();
			$channel_fields = $this->EE->channel_data->utility->reindex($channel_fields, 'field_name');
		}
		
		if(is_object($parse_vars))
		{
			$parse_vars = (array) $parse_vars;	
		}
			
		if(!isset($parse_vars[0]))
		{
			$parse_vars = array($parse_vars);
		}
		
		$TMPL = $this->EE->channel_data->tmpl->create_alias($tagdata);
		
		$this->EE->TMPL->template = $this->EE->functions->prep_conditionals($this->EE->TMPL->template, array_merge($parse_vars, (array) $entry_data));
		
		$this->EE->TMPL->template = $this->EE->TMPL->parse_variables($this->EE->TMPL->template, $parse_vars);
		
		$this->EE->TMPL->template = $this->parse_fieldtypes($entry_data, $channels, $channel_fields, $this->EE->TMPL->template, $prefix, $index);	
		
		$this->EE->TMPL->parse($this->EE->TMPL->template);
		
		$this->EE->TMPL->template = $this->EE->TMPL->parse_globals($this->EE->TMPL->template);
		
		$return = $this->EE->TMPL->template;
		
		$this->EE->channel_data->tmpl->reset($TMPL);
		
		return $return;
	}
	
	public function parse_string($string, $parse_vars = array(), $entry_data = array(),  $channels = array(), $channel_fields = array())
	{
		return $this->parse($parse_vars, $entry_data, $channels, $channel_fields, $string);
	}
	
	public function parse_fieldtypes($entry_data = array(), $channels = array(), $channel_fields = array(), $tagdata = FALSE, $prefix = '', $index = FALSE)
	{
		$parse_data = $entry_data;
						
		if(!$tagdata)
		{
			$tagdata = $this->EE->TMPL->template;
		}
		
		$vars = $this->EE->functions->assign_variables($tagdata);
		
		$tagdata = $this->parse_single_vars($vars, $entry_data, $channels, $channel_fields, $tagdata, $prefix, $index);
		
		$tagdata = $this->parse_var_pairs($vars, $entry_data, $channels, $channel_fields, $tagdata, $prefix, $index);
		
		return $tagdata;	
	}
	
	public function parse_path_variables($vars = array(), $entry_data = array(), $tagdata = FALSE, $prefix = '')
	{		
		foreach($vars as $key => $value)
		{	
			//  parse URL title path
			if(strncmp($key, $prefix.'url_title_path', 14) == 0)
			{
				$path = ($this->EE->functions->extract_path($key) != '' AND $this->EE->functions->extract_path($key) != 'SITE_INDEX') ? $this->EE->functions->extract_path($key).'/'.$entry_data->{$prefix.'url_title'} : $entry_data->{$prefix.'url_title'};
				
				$tagdata = $this->EE->TMPL->swap_var_single($key, $this->EE->functions->create_url($path, FALSE), $tagdata);
			}
			
			//  parse title permalink
			if (strncmp($key, $prefix.'title_permalink', 15) == 0)
			{
				$path = ($this->EE->functions->extract_path($key) != '' AND $this->EE->functions->extract_path($key) != 'SITE_INDEX') ? $this->EE->functions->extract_path($key).'/'.$entry_data->{$prefix.'url_title'} : $entry_data->{$prefix.'url_title'};

				$tagdata = $this->EE->TMPL->swap_var_single($key, $this->EE->functions->create_url($path, FALSE), $tagdata);
			}

			//  parse permalink
			if (strncmp($key, $prefix.'permalink', 9) == 0)
			{
				$path = ($this->EE->functions->extract_path($key) != '' AND $this->EE->functions->extract_path($key) != 'SITE_INDEX') ? $this->EE->functions->extract_path($key).'/'.$entry_data->{$prefix.'entry_id'} : $entry_data->{$prefix.'entry_id'};

				$tagdata = $this->EE->TMPL->swap_var_single($key, $this->EE->functions->create_url($path, FALSE), $tagdata);
				
			}
			
		}
		
		/*
		if (count($channel_fields) > 0)
		{
  			foreach($entry_data as $key => $value)
  			{
				//  parse URL title path
				if (strncmp($prefix.$key, $prefix.'url_title_path', 14) == 0)
				{
  				var_dump($key);exit();
  				
					$path = ($this->EE->functions->extract_path($key) != '' AND $this->EE->functions->extract_path($key) != 'SITE_INDEX') ? $this->EE->functions->extract_path($key).'/'.$row['url_title'] : $row['url_title'];
		
					$tagdata = $this->EE->TMPL->swap_var_single($key, $this->EE->functions->create_url($path), $tagdata);
				}
			}
		}
		*/
		return $tagdata;
	}
	
	public function parse_custom_date_fields($entry_data = array(), $channels = array(), $channel_fields = array(), $tagdata = FALSE, $prefix = '')
	{		
		$custom_date_fields = array();

		if (count($channel_fields) > 0)
		{
  			foreach($channel_fields as $key => $value)
  			{
  				if (strpos($tagdata, LD.$prefix.$key) === FALSE) continue;

				if (preg_match_all("/".LD.$prefix.$key."\s+format=[\"'](.*?)[\"']".RD."/s", $tagdata, $matches))
				{
					
					for ($j = 0; $j < count($matches[0]); $j++)
					{
						$matches[0][$j] = str_replace(array(LD,RD), '', $matches[0][$j]);
						
						$dkey = $matches[0][$j];
						$val  = $matches[1][$j];
						$custom_date_fields[$matches[0][$j]] = $this->EE->localize->fetch_date_params($matches[1][$j]);
					}
					
					
					if(isset($entry_data->{$prefix.$key}))
					{
						$temp_val = $entry_data->{$prefix.$key};
						$field_id = $value->field_id;
						
						$localize = TRUE;
						
						if (isset($entry_data->{$prefix.'field_dt_'.$field_id}) AND !empty($entry_data->{$prefix.'field_dt_'.$field_id}))
						{
							$temp_val = $this->EE->localize->simpl_offset($temp_val, $entry_data->{$prefix.'field_dt_'.$field_id});
							$localize = FALSE;
						}
					
						$val = str_replace($custom_date_fields[$dkey], $this->EE->localize->convert_timestamp($custom_date_fields[$dkey], $temp_val, $localize), $val);
	
						$tagdata = $this->EE->TMPL->swap_var_single($dkey, $val, $tagdata);
					}		
				}
			}
		}
		
		return $tagdata;
	}
	
	public function parse_single_vars($vars, $entry_data = array(), $channels = array(), $channel_fields = array(), $tagdata = FALSE, $prefix = '', $index = FALSE)
	{
		$entry_data   = (object) $entry_data;
		$prefix_entry = (object) $this->EE->channel_data->utility->add_prefix($prefix, $entry_data, '');
		
		if(!$tagdata)
		{
			$tagdata = $this->EE->TMPL->template;
		}
					
		$tagdata = $this->parse_path_variables($vars['var_single'], $entry_data, $tagdata, $prefix);

		foreach($vars['var_single'] as $single_var => $single_var_value)
		{	
			$tagdata = $this->parse_custom_date_fields($entry_data, $channels, $channel_fields, $tagdata, $prefix);
			
			$params = $this->EE->functions->assign_parameters($single_var);

			$single_var_array = explode(' ', $single_var);
			
			$field_name  = preg_replace('/^'.$prefix.'|:.*/us', '',  $single_var_array[0]);	
							
			$call_method = preg_replace("/(^((?!:).)*$)|(^.*:)/us", "", preg_replace('/^'.$prefix.'/us', '', $single_var_array[0]));	
			
			$call_method = 'replace_'.(!empty($call_method) ? $call_method : 'tag');
				
			$entry = FALSE;

			if(isset($channel_fields[$field_name]))
			{
				if(is_array($channel_fields[$field_name]))
				{
					$channel_fields[$field_name] = (object) $channel_fields[$field_name];
				}

				$field_type = $channel_fields[$field_name]->field_type;
				$field_id   = $channel_fields[$field_name]->field_id;
				
				if(isset($entry_data->{$single_var_array[0]}))
				{
					$field_name = $single_var_array[0];
				}
				
				if(isset($entry_data->$field_name) || isset($entry_data->{'field_id_'.$field_id}))
				{
					$data  = isset($entry_data->$field_name) ? $entry_data->$field_name : $entry_data->{'field_id_'.$field_id};
					
					if($this->EE->api_channel_fields->setup_handler($field_id))
					{
						$channel = isset($entry_data->{$prefix.'channel_id'}) ? $channels[$entry_data->{$prefix.'channel_id'}] : $channels[$entry_data->channel_id];
						
						$row = array_merge((array) $channel, (array) $entry_data);
						
						foreach($channel_fields as $channel_field)
						{
							$channel_field = (array) $channel_field;
								
							if(isset($row[$prefix.$channel_field['field_name']]) ||
							   isset($row[$channel_field['field_name']]))
							{
								$row['field_id_'.$channel_field['field_id']] = $data;
								$row['field_ft_'.$channel_field['field_id']] = $channel_field['field_fmt'];
								unset($row[$channel_field['field_name']]);
							}
							
						}
						
						$this->EE->api_channel_fields->apply('_init', array(array('row' => $row)));
						// Preprocess
						$data = $this->EE->api_channel_fields->apply('pre_process', array($row['field_id_'.$field_id]));
		
						$entry = $this->EE->api_channel_fields->apply($call_method, array($data, $params, FALSE));
						
						$tagdata = $this->EE->TMPL->swap_var_single($single_var, $entry, $tagdata);
							
						$tagdata = $this->EE->TMPL->parse_variables_row($tagdata, array($prefix.$field_name => $data));
							
					}
				}
			}
			else
			{
				$var_name = preg_replace('/\s.*$/', '', $single_var);
				
				if(isset($prefix_entry->$var_name))
				{
					$tagdata = $this->EE->TMPL->parse_variables_row($tagdata, array(
						$var_name => $prefix_entry->$var_name
					));
				}
			}
		}			
		
		return $tagdata;
	}
	
	public function parse_var_pairs($vars, $entry_data = array(), $channels = array(), $channel_fields = array(), $tagdata = FALSE, $prefixes = '', $index = FALSE)
	{
		$entry_data = (array) $entry_data;
		
		if(!$tagdata)
		{
			$tagdata = $this->EE->TMPL->template;
		}
		
		if(!is_array($prefixes))
		{
			$prefixes = array($prefixes);
		}
					
		$pair_vars = array();

		foreach($vars['var_pair'] as $pair_var => $params)
		{		
			if(preg_match("/exp(:\w*)*/", $pair_var))
			{
				continue;
			}

			foreach($prefixes as $prefix)
			{		
			
				$pair_var_array = explode(' ', $pair_var);
				
				$field_name  = preg_replace('/^'.$prefix.'|:.*/us', '',  $pair_var_array[0]);												
				$call_method = preg_replace("/(^((?!:).)*$)|(^.*:)/us", "", preg_replace('/^'.$prefix.'/us', '', $pair_var_array[0]));				
				$call_method = 'replace_'.(!empty($call_method) ? $call_method : 'tag');
	
	
				$offset = 0;
				
				while (($end = strpos($tagdata, LD.'/'.$prefix.$field_name.RD, $offset)) !== FALSE)
				{
					if (preg_match("/\\".LD."{$prefix}{$field_name}(.*?)".RD."(.*?)".LD.'\/'.$prefix.$field_name.RD."/s", $tagdata, $matches, 0, $offset))
					{
						$chunk  = $matches[0];
						$params = $matches[1];
						$inner  = $matches[2];
						
						// We might've sandwiched a single tag - no good, check again (:sigh:)
						if ((strpos($chunk, LD.$prefix.$field_name, 1) !== FALSE) && preg_match_all("/\\".LD."{$prefix}{$field_name}(.*?)".RD."/s", $chunk, $match))
						{
							// Let's start at the end
							$idx = count($match[0]) - 1;
							$tag = $match[0][$idx];
							
							// Reassign the parameter
							$params = $match[1][$idx];
	
							// Cut the chunk at the last opening tag (PHP5 could do this with strrpos :-( )
							while (strpos($chunk, $tag, 1) !== FALSE)
							{
								$chunk = substr($chunk, 1);
								$chunk = strstr($chunk, LD.$prefix.$field_name);
								$inner = substr($chunk, strlen($tag), -strlen(LD.'/'.$prefix.$field_name.RD));
							}
						}
						
						$pair_vars[$field_name] = array($inner, $this->EE->functions->assign_parameters($params), $chunk);						
					}
					
					$offset = $end + 1;
				}
			}

			foreach($pair_vars as $field_name => $pair_var)
			{		
				if(!isset($channel_fields[$field_name]))
				{
					continue;
				}
				else
				{					
					$channel_fields[$field_name] = (object) $channel_fields[$field_name];
				}
						
				$field_id   = isset($channel_fields[$field_name]->field_id) ? $channel_fields[$field_name]->field_id : 0;
				
				if((isset($channel_fields[$field_name]) || isset($channel_fields['field_id_'.$field_id])) && (isset($channel_fields[$field_name]->field_type) || isset($channel_fields['field_id_'.$field_id]->field_type)))
				{
					$field_name = isset($channel_fields[$field_name]) ? $field_name : 'field_id_'.$field_id;
					
					$entry_data = (array) $entry_data;
					$field_type = $channel_fields[$field_name]->field_type;
					$field_id   = $channel_fields[$field_name]->field_id;

					$data       = isset($entry_data[$field_name]) ? $entry_data[$field_name] : NULL;
					
					if($this->EE->api_channel_fields->setup_handler($field_id))
					{
						$row = $entry_data;
							
						foreach($entry_data as $index => $value)
						{
							if(isset($channel_fields[$index]) && is_object($channel_fields[$index]) && isset($channel_fields[$index]->field_id) && !isset($row['field_id_'.$channel_fields[$index]->field_id]))
							{
								$row['field_id_'.$channel_fields[$index]->field_id] = $value;
								$row['field_ft_'.$channel_fields[$index]->field_id] = $channel_fields[$index]->field_fmt;	
							}
							else
							{
								$row[$index] = $value;
							}
						}
											
						$this->EE->api_channel_fields->apply('_init', array(array('row' => $row)));
						$entry = $this->EE->api_channel_fields->apply($call_method, array($data, $pair_var[1], $pair_var[0]));
						
						$test = $entry_data;
						$tagdata = $this->parse_string(str_replace($pair_var[2], $entry, $tagdata), $entry_data,  $channels, $channel_fields);
						
						
					}
				}
			}

			$entry = FALSE;
		}
		
		return $tagdata;
	}

	/* @deprecated */	
	public function parse_entry($entry_data, $channels = array(), $channel_fields = array(), $tagdata = FALSE, $count = 1)
	{
		$TMPL = $this->EE->channel_data->tmpl->create_alias($tagdata);
			
		if(is_string($entry_data) || is_int($entry_data))
		{
			$entry_data = $this->get_channel_entry($entry_data)->row();
		}
		
		if(is_array($entry_data))
		{
			$entry_data = (object) $entry_data;
		}		
		
		$this->EE->TMPL->template = $this->EE->TMPL->parse_variables_row($this->EE->TMPL->template, (array) $entry_data);
		
		$this->EE->TMPL->parse($this->EE->TMPL->template);
			
		$this->EE->TMPL->template = $this->parse_fieldtypes($channels, $channel_fields);
		
		$return = $this->EE->channel_data->tmpl->parse_switch($this->EE->TMPL->template, $count);
		
		$this->EE->channel_data->tmpl->reset($TMPL);
			
		return $return;
	}
}