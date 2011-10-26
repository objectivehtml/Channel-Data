Channel Data
============

### Version 0.2.0 - 20111025

#### By Justin Kimbrell

Channel Data is designed to give you access to a concise and memorable syntax. The current API's to retrieve channel data seem cumbersome and often require many lines of code just to get your entry data along with the custom fields. Native API's also have parameters that aren't consistent, resulting in code that is repeatedly looked up for what should be basic tasks.

Simply put, Channel Data just works and speeds add-on development up tremendously. Single lines of code now replace what use to take upwards to 10. I have personally used it in the development of custom add-ons for clients for a while and it has been a real life saver. I make many add-ons that bridge the gaps in ExpressionEngine for our clients that aren't available to the public. I make them all with Channel Data.

### New in v0.2.0

-	The entire API has changed for the most part. I have removed the library from any First Party provided API's and models. I really believe the parameters were a limitation. I constantly found myself forgetting what each parameter did. I wanted a polymorphic solution.

-	And the documentation is new and now written in Markdown. This really makes life easier and the project a lot better.

### Polymorphic Parameters

Many of the methods have polymorphic parameters. Anytime there is a select parameter, you can use it to select fields in a query, or you can define an active record array. It's really just a preference, and sometimes I have reasons to use both.

#### Example
	
This example uses the standard parameters are select, where, order_by, sort, limit, and offset. When present, all these parameters are always in this order. Unless a method is designed to pull a single entry, it will _almost_ always contain these parameters.

	$channel_id = 1;
	$select 	= array('*');
	$where 		= array('channel_data.entry_id >' => 5); //Note, on shared columns you must specify a table name
	$order_by 	= 'title';
	$sort		= 'ASC';
	$limit		= 1;
	$offset 	= 5;
	
	$entries = $this->EE->channel_data->get_entries($channel_id, $select, $where, $order_by, $sort, $limit, $offset);
		
Or... You can use the alternative syntax.

	$entries = $this->EE->channel_data->get_entries($channel_id, array(
		'select'	=> array('*'),
		'where'		=> array('channel_data.entry_id >' => 5),
		'order_by'	=> 'title',
		'sort'		=> 'ASC',
		'limit'		=> 1,
		'offset'	=> 5
	));

The returned object would look something like, or an active record object...
	
	object(CI_DB_mysql_result)#45 (7) {
	  ["conn_id"]=>
	  resource(36) of type (mysql link)
	  ["result_id"]=>
	  resource(150) of type (mysql result)
	  ["result_array"]=>
	  array(0) {
	  }
	  ["result_object"]=>
	  array(0) {
	  }
	  ["current_row"]=>
	  int(0)
	  ["num_rows"]=>
	  int(1)
	  ["row_data"]=>
	  NULL
	}

	
### Documentation

+	#### get_entry($channel_id, $entry_id, $select = array('*'))
	
	Gets channel entries with easy filtering and using polymorphic parameters.
		
	Common Usage
	
		$this->EE->channel_data->get_entry(1);
			
	
+	#### get_entries($channel_id, $select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status'), $where = array(), $order_by = 'channel_titles.channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	
	Gets the channel entries using the standard polymorphic parameters. If you don't specify any additional parameters, the default will be used.
	
	Common Usage
		
		$this->EE->channel_data->get_entries($channel_id = 1);

### Disclaimer

This is library still very beta. If you have any complaints, dislikes, improvements, suggestions, or anything else, please feel free to fork the code or contact me directly. I want this to be a rock solid library, but it first needs to be scrutinized by the community.

### What's to Come?

Much more to come in the form of documentation. The source code is thoroughly documented, but I will port it along with more examples in to Markdown. Also, the current code base is still not very error tolerant. I will continue with bug fixes and really try to improve stability and error tolerance in the coming days and weeks.