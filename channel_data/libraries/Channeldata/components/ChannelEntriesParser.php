<?php namespace ChannelData\Component;

class ChannelEntriesParser {
	
	private $channel = FALSE;
	
	public function set_no_results($tagdata = NULL)
	{
		ee()->TMPL->no_results = $tagdata;
	}
	
	public function cache_no_results()
	{
		ee()->session->set_cache('entries_lib', 'no_results', ee()->TMPL->no_results);
	}
	
	public function reset_no_results($cache = TRUE)
	{
		if($cache)
		{
			$this->cache_no_results();
		}
		
		ee()->TMPL->no_results = NULL;
	}
	
	public function restore_no_results()
	{		
		if(isset(ee()->session->cache['entries_lib']['no_results']))
		{
			$this->reset_no_results(FALSE);
			$this->set_no_results(ee()->session->cache['entries_lib']['no_results']);
		}
		
		ee()->session->set_cache('entries_lib', 'no_results', array());
		
		return $this->get_params();
	}
	
	public function no_results()
	{
		return $this->get_no_results();
	}
	
	public function get_no_results()
	{
		return ee()->TMPL->no_results;
	}
	
	public function set_param($param, $value = FALSE)
	{
		$this->set_params(array(
			$param => $value
		));
	}
	
	public function set_params($params = array())
	{
		if(is_array($params))
		{
			foreach($params as $param => $value)
			{
				if($value !== FALSE)
				{
					ee()->TMPL->tagparams[$param] = $value;
				}
				else
				{
					unset(ee()->TMPL->tagparams[$param]);
				}
			}
		}
		else
		{			
			ee()->TMPL->tagparams = FALSE;
		}
	}
	
	public function get_params()
	{
		return ee()->TMPL->tagparams;
	}
	
	public function get_param($param)
	{
		return ee()->TMPL->fetch_param($param);
	}
	
	public function cache_params()
	{
		ee()->session->set_cache('entries_lib', 'params', $this->get_params());
	}
	
	public function reset_params($cache = TRUE)
	{	
		if($cache)
		{
			$this->cache_params();
		}
		
		ee()->TMPL->tagparams = array();
	}
	
	public function restore_params()
	{
		if(isset(ee()->session->cache['entries_lib']['params']))
		{
			$this->reset_params(FALSE);
			$this->set_params(ee()->session->cache['entries_lib']['params'], TRUE);
		}
		
		ee()->session->set_cache('entries_lib', 'params', array());
		
		return $this->get_params();
	}
	
	public function cache_tagdata()
	{
		ee()->session->set_cache('entries_lib', 'tagdata', $this->get_tagdata());
	}
	
	public function reset_tagdata()
	{
		$this->cache_tagdata();
		
		ee()->TMPL->tagdata = FALSE;
	}
	
	public function restore_tagdata()
	{
		if(isset(ee()->session->cache['entries_lib']['tagdata']))
		{
			$this->set_tagdata(ee()->session->cache['entries_lib']['tagdata']);
		}
		
		ee()->session->set_cache('entries_lib', 'tagdata', FALSE);
		
		return $this->get_tagdata();
	}
	
	public function get_tagdata()
	{
		return ee()->TMPL->tagdata;
	}
	
	public function set_tagdata($tagdata)
	{
		ee()->TMPL->tagdata = $tagdata;
	}
	
	public function channel($params = array())
	{
		require_once APPPATH.'modules/channel/mod.channel.php';
		
		unset(ee()->channel);
		
		$channel = new \Channel();

		$this->set_params($params);
		
		$this->channel = $channel;
		
		return $channel;
	}
	
	public function entries($params = array(), $channel = FALSE)
	{	
		$default_params = array(
			'dynamic' => 'no',
			'disable' => 'member_data|categories|category_fields|pagination'
		);
		
		if($enable = $this->get_param('enable'))
		{
			$enable = explode('|', $enable);
			
			
			$default_params['disable'] = str_replace($enable, '', $default_params['disable']);
		}
		
		foreach($default_params as $param => $value)
		{
			$user_param = $this->get_param($param);
			
			if($user_param)
			{
				$params[$param] = $user_param;
			}
			else
			{
				$params[$param] = $value;
			}
		}
		
		$tagdata = $this->get_tagdata();
		
		$this->set_params($params);
		
		if($channel)
		{
			$this->channel = $channel;
		}
		else
		{
			$this->channel();
		}

		if($prefix = $this->get_param('prefix'))
		{		
			$this->set_tagdata(preg_replace('/(('.LD.'|\/)|(|if)\s)'.$prefix.'\d*/', '$1', $this->get_tagdata()));
			
			foreach(ee()->TMPL->var_single as $index => $value)
			{
				unset(ee()->TMPL->var_single[$index]);	
				ee()->TMPL->var_single[str_replace($prefix, '', $index)] = str_replace($prefix, '', $value);
			}
			
			foreach(ee()->TMPL->var_pair as $index => $value)
			{
				unset(ee()->TMPL->var_pair[$index]);	
			
				ee()->TMPL->var_pair[str_replace($prefix, '', $index)] = $value !== FALSE ? str_replace($prefix, '', $value) : FALSE;
			}	
			
			$tagdata = ee()->TMPL->tagdata;
			
			foreach(ee()->TMPL->tag_data as $index => $tag_data)
			{
				$block   = $tag_data['block'];
				//ee()->TMPL->tag_data[$index]['chunk'] = str_replace($block, $tagdata, $tag_data['chunk']);
				//ee()->TMPL->tag_data[$index]['block'] = $tagdata;
			}
			
			if(preg_match('/\\'.LD.'if no_results\\'.RD.'.*?\\'.LD.'\\/if\\'.RD.'/us', $this->get_tagdata(), $matches))
			{
				ee()->TMPL->no_results = ee()->TMPL->parse_variables_row($matches[0], array(
					'no_results' => 1
				));
			}			
		}	 
		
		$entries = trim($this->channel->entries());
			
		$this->set_tagdata($tagdata);
		
		return $entries;
	}
	
	public static function noResults()
	{
		$obj = new static;

		$prefix = '';

		if($obj->get_param('prefix'))
		{
			$prefix = $obj->get_param('prefix');
			
			$obj->set_tagdata(preg_replace('/(('.LD.'|\/)|(|if)\s)'.$prefix.'\d*/', '$1', $obj->get_tagdata()));
		}

		if(preg_match('/\\'.LD.'if no_results\\'.RD.'.*?\\'.LD.'\\/if\\'.RD.'/us', $obj->get_tagdata(), $matches))
		{
			ee()->TMPL->no_results = ee()->TMPL->parse_variables_row($matches[0], array(
				'no_results' => 1
			));
		}

		return ee()->TMPL->no_results();
	}
}	