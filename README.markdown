Channel Data
============

### Version 0.8.8 - 20121021

#### By Justin Kimbrell / Objective HTML

Overview
--------

Channel Data is designed to give you access to a concise and memorable syntax. The current API's to retrieve channel data seem cumbersome and often require many lines of code just to get your entry data along with the custom fields. Native API's also have parameters that aren't consistent, resulting in code that is repeatedly looked up for what should be basic tasks. Simply put, Channel Data just works and speeds add-on development up tremendously. 

Channel Data also provided a simple and effective interface for developers to interact with other add-ons using a standards based approach. By using the Channel Data API to create an API for your add-on, you can easily allow other developers to programmatically interact with your add-ons in ways that weren't possible before.

Table of Contents
-----------------

  1. [Basic Usage]( #basicusage "Go to Basic Usage")
  2. [Polymorphic Parameters]( #polymorphicparameters "Go to 'Polymorphic Parameters' section")
  3. [Channel Data Library]( #channeldatalibrary "Go to the 'Channel Data Library' section")
  4. [What's to come?](#whatstocome "Go to the 'What's to come' section")
  5. [Contributors](#contributors "Go to the 'Contributors' section")
  6. [Disclaimer](#disclaimer "Go to the Disclaimer section")

Basic Usage
-----------

Channel Data is a modified CodeIgniter driver and has two parts to the architecture, the library, and the add-on API framework. The library potion attempts provides all the methods one would need to easily retrieve data from your channels using a familiar polymorphic syntax. Native libraries are inconstant in nomenclature, are undocumented, and usually don't even do what you need without loading 4 or 5 models and writing 20 or 30 lines of code. It gets tedious and time consuming after a while, and the code looks like a mess. The Channel Library provides a clean and flexible API that is capable of performing complex queries, and is very easy to remember. The nomenclature is logical and makes send. For example, get_channel_entries($channel_id, …) actually gets the channel entries AND joins the custom fields in one nice easy to remember method. 

The add-on API framework allows you load third-party add-on API's. It can also help you create an API for your add-ons that other developers can use. The add-on API framework extends the Channel Data Library, so you inherently get access to all the library functions within you API. Since Channel Data is a modified CodeIgniter driver, to get access to third-party API's you must load them as such. API's are files inside the parent add-on directory with the naming scheme: __api.your_module_name.php__. Once the driver is loaded, it becomes a child to the Channel Data object.

#### Example A - Using the Channel Data Library

	$this->EE->load->driver('channel_data');

	$channels 	= $this->EE->channel_data->get_channels();
	$entries    = $this->EE->channel_data->get_entries();
	$fields     = $this->EE->channel_data->get_fields();

#### Example B - Loading Channel Data drivers
	
	$this->EE->channel_data->api->load('gmap');

	$location = $this->EE->channel_data->gmap->geocode('MT Elbert');

#### Example C - Sample Gmap API
	
	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		/* Example API file, in this case the Gmap API */

		class Gmap_api extends Base_API {

			public function geocode($query, $limit = FALSE, $offset = 0)
			{
				$this->EE->load->library('Google_maps');
		
				return $this->EE->google_maps->geocode($query, $limit, $offset);
			}

		}

		// End of File
		// ./system/expressionengine/third_party/gmap/api.gmap.php
	?>

_By extending the Base_API class, you inherent the Channel Data object. Now, find cool way to extend add-ons in a easier and much more powerful way._

[Back to Top](#channeldata "Go to the top of the page")

Polymorphic Parameters
----------------------

Almost all of the methods have polymorphic parameters. Helper methods are used to quickly retrieve a single entry _do not_ use polymorphic parameters. However, helper methods than quickly grab data in relation to a channel_id generally do accept polymorphic parameters.

Unless the method only returns a single row, the select parameter is always used for polymorphic values. In these polymorphic parameters, you can either fields to select, or you can define an active record array. It's really just a preference, there are reasons to use both, but really it just makes for a really versatile and functional API and more memorable. Complex queries are generally a lot easier to comprehend when using polymorphic parameters.

#### Example A
	
	$channel_id = 1;
	$select 	= array('*');
	$where 		= array('channel_data.entry_id >' => 5); //Note, on shared columns you must specify a table name
	$order_by 	= 'title';
	$sort		= 'ASC';
	$limit		= 1;
	$offset 	= 5;
	
	$entries = $this->EE->channel_data->get_channel_entries($channel_id, $select, $where, $order_by, $sort, $limit, $offset);
		
_This example uses the standard parameters are select, where, order_by, sort, limit, and offset. When present, all these parameters are always in this order. Unless a method is designed to pull a single entry, it will almost always contain these parameters._

#### Example B

	$entries = $this->EE->channel_data->get_channel_entries($channel_id, array(
		'select'	=> array('*'),
		'where'		=> array('channel_data.entry_id >' => 5),
		'order_by'	=> 'title',
		'sort'		=> 'ASC',
		'limit'		=> 1,
		'offset'	=> 5
	));
	
	$entries = $this->EE->channel_data->get_channel_entries($channel_id, array(
		'select'	=> array('*'),
		'where'		=> array(
			'channel_data.entry_id >' 	 	 => 5,
			'channel_data.channel_id !=' 	 => 4,
			'or channel_data.entry_id'   	 => 1
			'{2} or channel_data.entry_id'   => 2
			'{3} or channel_data.entry_id'   => 3,
			'channel_data.entry_id' => array(
				'1',
				'OR 2',
				'OR 3'
			)
		),
		'order_by'	=> 'title',
		'sort'		=> 'ASC',
		'limit'		=> 1,
		'offset'	=> 5
	));

_Or... You can use the alternative syntax. This array is referred to an active record array. Notice you can use OR and AND prefixes and logical operators as the suffix. If not logical operator exists, then '=' is assumed._

#### Examples C
	
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

_The returned object would look something like this, also known as an active record object…_

[Back to Top](#channeldata "Go to the top of the page")

Channel Data Library	
--------------------

I am currently working to fully documenting the source code and properly generate a dynamic documentation form the comments within the code. Until then, refer to __Channel_data_lib.php__ source code file to reference methods. I realize this is pretty terrible, but this stuff comes in phases, and this is a free library built in conjunction with the projects I build for paying clients… I will get the new docs created as soon as I learn the overly complicated systems that exist to create real documentation without re-typing everything 3 times.

[Back to Top](#channeldata "Go to the top of the page")


What's to come?
---------------

As the project start to mature, the main goal is get to people to start adopting this library and using to it to create rich third-party API's for their add-ons. API's are awesome and allow us all to make better products. If we can all make an effort to create add-ons with API's I bet we start to see even more cool things built.

[Back to Top](#channeldata "Go to the top of the page")


Contributors
------------

Using feedback from others is a critical part of success. Become a contributor by simply helping me improve the library by giving me some feedback on how to improve it, or even forking the library and changing it yourself.

 -  [@wesrice](#https://www.twitter.com/wesrice "Go to @wesrice's Twitter page") - Thanks for the inspiration and ideas to include an add-on API

[Back to Top](#channeldata "Go to the top of the page")


Disclaimer
----------

This is library still very beta. If you have any complaints, dislikes, improvements, suggestions, or anything else, please feel free to fork the code or contact me directly. I want this to be a rock solid library, but it first needs to be scrutinized by the community. Contact me on Twitter @objectivehtml.

License
-------
Channel Data is licensed using the BSD 2-Clause License. In a nutshell, do whatever you want with it so long as you leave the copyright information and don't take credit for my work. The idea is for everyone to benefit from the library. For a full copy of the license, refer to license.txt in the download package.

[Back to Top](#channeldata "Go to the top of the page")
