Channel Data
============

### Version 0.3.0 - 20111102

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

+	get_entry($channel_id, $entry_id, $select = array('*'))
	
	Gets channel entries with easy filtering and using polymorphic parameters.
		
	Common Usage
	
		$entry = $this->EE->channel_data->get_entry(1)->row();
	
	My Response
		
		object(stdClass)#46 (21) {
		  ["entry_id"]=>
		  string(1) "3"
		  ["channel_id"]=>
		  string(1) "1"
		  ["title"]=>
		  string(4) "test"
		  ["url_title"]=>
		  string(4) "test"
		  ["entry_date"]=>
		  string(10) "1317063358"
		  ["expiration_date"]=>
		  string(1) "0"
		  ["status"]=>
		  string(4) "open"
		  ["gis_latitude"]=>
		  string(10) "38.8026097"
		  ["gis_longitude"]=>
		  string(19) "-116.41938900000002"
		  ["gis_address"]=>
		  string(0) ""
		  ["gis_state"]=>
		  string(0) ""
		  ["gis_zipcode"]=>
		  string(0) ""
		  ["gis_city"]=>
		  string(0) ""
		  ["gis_full_address"]=>
		  string(11) "Nevada, USA"
		  ["gis_country"]=>
		  string(0) ""
		  ["gis_zoom"]=>
		  string(1) "5"
		  ["gis_geocoder_reponse"]=>
		  string(625) "[{"address_components":[{"long_name":"Nevada","short_name":"NV","types":["administrative_area_level_1","political"]},{"long_name":"United States","short_name":"US","types":["country","political"]}],"formatted_address":"Nevada, USA","geometry":{"bounds":{"ma":{"b":35.001857,"d":42.002207},"Y":{"d":-120.00647300000003,"b":-114.039648}},"location":{"Ja":38.8026097,"Ka":-116.41938900000002},"location_type":"APPROXIMATE","viewport":{"ma":{"b":36.5420003,"d":40.9937136},"Y":{"d":-120.5172895,"b":-112.32148849999999}}},"types":["administrative_area_level_1","political"],"latitude":38.8026097,"longitude":-116.41938900000002}]"
		  ["gis_gmap"]=>
		  string(0) ""
		  ["gis_start_wapoint"]=>
		  string(0) ""
		  ["gis_end_waypoint"]=>
		  string(0) ""
		  ["gis_region_data"]=>
		  string(0) ""
		}

			
	
+	get_entries($channel_id, $select = array('channel_data.entry_id', 'channel_data.channel_id', 'channel_titles.title', 'channel_titles.url_title', 'channel_titles.entry_date', 'channel_titles.expiration_date', 'status'), $where = array(), $order_by = 'channel_titles.channel_id', $sort = 'DESC', $limit = FALSE, $offset = 0)
	
	Gets the channel entries using the standard polymorphic parameters. If you don't specify any additional parameters, the default will be used.
	
	Common Usage
		
		$entries = $this->EE->channel_data->get_entries($channel_id = 1)->result();

	My Response
		
		array(4) {
		  [0]=>
		  object(stdClass)#60 (21) {
		    ["entry_id"]=>
		    string(1) "3"
		    ["channel_id"]=>
		    string(1) "1"
		    ["title"]=>
		    string(4) "test"
		    ["url_title"]=>
		    string(4) "test"
		    ["entry_date"]=>
		    string(10) "1317063358"
		    ["expiration_date"]=>
		    string(1) "0"
		    ["status"]=>
		    string(4) "open"
		    ["gis_latitude"]=>
		    string(10) "38.8026097"
		    ["gis_longitude"]=>
		    string(19) "-116.41938900000002"
		    ["gis_address"]=>
		    string(0) ""
		    ["gis_state"]=>
		    string(0) ""
		    ["gis_zipcode"]=>
		    string(0) ""
		    ["gis_city"]=>
		    string(0) ""
		    ["gis_full_address"]=>
		    string(11) "Nevada, USA"
		    ["gis_country"]=>
		    string(0) ""
		    ["gis_zoom"]=>
		    string(1) "5"
		    ["gis_geocoder_reponse"]=>
		    string(625) "[{"address_components":[{"long_name":"Nevada","short_name":"NV","types":["administrative_area_level_1","political"]},{"long_name":"United States","short_name":"US","types":["country","political"]}],"formatted_address":"Nevada, USA","geometry":{"bounds":{"ma":{"b":35.001857,"d":42.002207},"Y":{"d":-120.00647300000003,"b":-114.039648}},"location":{"Ja":38.8026097,"Ka":-116.41938900000002},"location_type":"APPROXIMATE","viewport":{"ma":{"b":36.5420003,"d":40.9937136},"Y":{"d":-120.5172895,"b":-112.32148849999999}}},"types":["administrative_area_level_1","political"],"latitude":38.8026097,"longitude":-116.41938900000002}]"
		    ["gis_gmap"]=>
		    string(0) ""
		    ["gis_start_wapoint"]=>
		    string(0) ""
		    ["gis_end_waypoint"]=>
		    string(0) ""
		    ["gis_region_data"]=>
		    string(0) ""
		  }
		  [1]=>
		  object(stdClass)#59 (21) {
		    ["entry_id"]=>
		    string(1) "4"
		    ["channel_id"]=>
		    string(1) "1"
		    ["title"]=>
		    string(12) "Sample Entry"
		    ["url_title"]=>
		    string(12) "sample-entry"
		    ["entry_date"]=>
		    string(10) "1317078686"
		    ["expiration_date"]=>
		    string(1) "0"
		    ["status"]=>
		    string(4) "open"
		    ["gis_latitude"]=>
		    string(10) "41.4925374"
		    ["gis_longitude"]=>
		    string(18) "-99.90181310000003"
		    ["gis_address"]=>
		    string(0) ""
		    ["gis_state"]=>
		    string(0) ""
		    ["gis_zipcode"]=>
		    string(0) ""
		    ["gis_city"]=>
		    string(0) ""
		    ["gis_full_address"]=>
		    string(13) "Nebraska, USA"
		    ["gis_country"]=>
		    string(0) ""
		    ["gis_zoom"]=>
		    string(0) ""
		    ["gis_geocoder_reponse"]=>
		    string(624) "[{"address_components":[{"long_name":"Nebraska","short_name":"NE","types":["administrative_area_level_1","political"]},{"long_name":"United States","short_name":"US","types":["country","political"]}],"formatted_address":"Nebraska, USA","geometry":{"bounds":{"aa":{"b":39.999932,"d":43.00170689999999},"Y":{"d":-104.053514,"b":-95.30829}},"location":{"Ja":41.4925374,"Ka":-99.90181310000003},"location_type":"APPROXIMATE","viewport":{"aa":{"b":39.3177305,"d":43.5967087},"Y":{"d":-103.9997136,"b":-95.80391259999999}}},"types":["administrative_area_level_1","political"],"latitude":41.4925374,"longitude":-99.90181310000003}]"
		    ["gis_gmap"]=>
		    string(0) ""
		    ["gis_start_wapoint"]=>
		    string(0) ""
		    ["gis_end_waypoint"]=>
		    string(0) ""
		    ["gis_region_data"]=>
		    string(0) ""
		  }
		  [2]=>
		  object(stdClass)#58 (21) {
		    ["entry_id"]=>
		    string(1) "5"
		    ["channel_id"]=>
		    string(1) "1"
		    ["title"]=>
		    string(13) "All 50 States"
		    ["url_title"]=>
		    string(13) "all-50-states"
		    ["entry_date"]=>
		    string(10) "1317078791"
		    ["expiration_date"]=>
		    string(1) "0"
		    ["status"]=>
		    string(4) "open"
		    ["gis_latitude"]=>
		    string(10) "40.0583238"
		    ["gis_longitude"]=>
		    string(11) "-74.4056612"
		    ["gis_address"]=>
		    string(0) ""
		    ["gis_state"]=>
		    string(0) ""
		    ["gis_zipcode"]=>
		    string(0) ""
		    ["gis_city"]=>
		    string(0) ""
		    ["gis_full_address"]=>
		    string(15) "New Jersey, USA"
		    ["gis_country"]=>
		    string(0) ""
		    ["gis_zoom"]=>
		    string(1) "3"
		    ["gis_geocoder_reponse"]=>
		    string(628) "[{"address_components":[{"long_name":"New Jersey","short_name":"NJ","types":["administrative_area_level_1","political"]},{"long_name":"United States","short_name":"US","types":["country","political"]}],"formatted_address":"New Jersey, USA","geometry":{"bounds":{"aa":{"b":38.788657,"d":41.357423},"Y":{"d":-75.56358599999999,"b":-73.88506000000001}},"location":{"Ja":40.0583238,"Ka":-74.4056612},"location_type":"APPROXIMATE","viewport":{"aa":{"b":38.9564291,"d":41.1426841},"Y":{"d":-76.45461139999998,"b":-72.35671100000002}}},"types":["administrative_area_level_1","political"],"latitude":40.0583238,"longitude":-74.4056612}]"
		    ["gis_gmap"]=>
		    string(0) ""
		    ["gis_start_wapoint"]=>
		    string(0) ""
		    ["gis_end_waypoint"]=>
		    string(0) ""
		    ["gis_region_data"]=>
		    string(0) ""
		  }
		  [3]=>
		  object(stdClass)#57 (21) {
		    ["entry_id"]=>
		    string(1) "6"
		    ["channel_id"]=>
		    string(1) "1"
		    ["title"]=>
		    string(29) "A route from Indiana to Texas"
		    ["url_title"]=>
		    string(29) "a-route-from-indiana-to-texas"
		    ["entry_date"]=>
		    string(10) "1317094558"
		    ["expiration_date"]=>
		    string(1) "0"
		    ["status"]=>
		    string(4) "open"
		    ["gis_latitude"]=>
		    string(10) "31.9685988"
		    ["gis_longitude"]=>
		    string(18) "-99.90181310000003"
		    ["gis_address"]=>
		    string(0) ""
		    ["gis_state"]=>
		    string(0) ""
		    ["gis_zipcode"]=>
		    string(0) ""
		    ["gis_city"]=>
		    string(0) ""
		    ["gis_full_address"]=>
		    string(6) "mexico"
		    ["gis_country"]=>
		    string(0) ""
		    ["gis_zoom"]=>
		    string(1) "5"
		    ["gis_geocoder_reponse"]=>
		    string(619) "[{"address_components":[{"long_name":"Texas","short_name":"TX","types":["administrative_area_level_1","political"]},{"long_name":"United States","short_name":"US","types":["country","political"]}],"formatted_address":"Texas, USA","geometry":{"bounds":{"ma":{"b":25.8371639,"d":36.500704},"Y":{"d":-106.645646,"b":-93.508039}},"location":{"Ja":31.9685988,"Ka":-99.90181310000003},"location_type":"APPROXIMATE","viewport":{"ma":{"b":26.9980919,"d":36.6839567},"Y":{"d":-108.09761409999999,"b":-91.70601210000001}}},"types":["administrative_area_level_1","political"],"latitude":31.9685988,"longitude":-99.90181310000003}]"
		    ["gis_gmap"]=>
		    string(0) ""
		    ["gis_start_wapoint"]=>
		    string(0) ""
		    ["gis_end_waypoint"]=>
		    string(0) ""
		    ["gis_region_data"]=>
		    string(0) ""
		  }
		}


### Disclaimer

This is library still very beta. If you have any complaints, dislikes, improvements, suggestions, or anything else, please feel free to fork the code or contact me directly. I want this to be a rock solid library, but it first needs to be scrutinized by the community.

### What's to Come?

Much more to come in the form of documentation. The source code is thoroughly documented, but I will port it along with more examples in to Markdown. Also, the current code base is still not very error tolerant. I will continue with bug fixes and really try to improve stability and error tolerance in the coming days and weeks.