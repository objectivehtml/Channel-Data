# Channel Data

##### Version 1.0-rc2 (2013-12-16)

Created By [Objective HTML](https://objectivehtml.com)

---

## Table of Contents

1. [Overview](#overview)
2. [System Requirements](#system-requirements)
3. [Getting Started](#getting-started)
4. [QueryBuilder](#querybuilder)
4. [QueryResponse](#queryresponse)
5. [QueryString](#querystring)
5. [Collections](#collections)
6. [Models](#models)
6. [Channel Models](#channel-models)

---

## Overview

Channel Data was created to provide a universal and abstract set of models for you to use within your [ExpressionEngine](http://ellislab.com/expressionengine) applications and addons. Channel Data is now based on the <a href="http://four.laravel.com/docs/eloquent">Eloquent ORM</a>, so the syntax should be familiar to those that have used it.

Why didn't we just port Eloquent to ExpressionEngine? Others have done so, and it's definitely possible. However, after a lot of consideration, it was decided to rewrite the code from scratch specifically for ExpressionEngine. This will make it easier to integrate Channel Data into existing applications and maintain the codebase as ExpressionEngine evolves.

---

## System Requirements

PHP 5.3+

ExpressionEngine 2.4+ 

*(Earlier versions of ExpressionEngine may work, but are untested)*
 
---

## Getting Started

To get started using Channel Data, you need to copy `channel_data` into your `system/expressionengine/third_party` directory. Channel Data is packages as its own *module* so you an reuse the same libary with all of your add-ons. This is also convenient when you upgrade ExpressionEngine since you don't have to worry about override files stored in the system directories.

Channel Data includes some core modules for you to use. Core models belong to the `ChannelData\Model` namespace, and can be reused within your application. You can create your own models using the following structure:


##### Instantiate Channel Data

The key thing to note when instantiating Channel Data is that you need to do 3 things.

	1. Load the Channel Data package into your add-on

	2. Load the Channel Data driver from the package

	3. Tell Channel Data where the auto loader should look for your files.


	ee()->load->add_package_path(PATH_THIRD . 'your_addon_name');

	// Load the driver and directories at once

	ee()->load->driver('ChannelData', array(
		'directories' => array(
			PATH_THIRD . 'your_addon_name/models'
		)
	));

	// Or use the autoload() method to pass a directory path

	ee()->channeldata->autoload(PATH_THIRD . 'your_addon_name/models');

	// You can also pass an array of directories

	ee()->channeldata->autoload(array(
		PATH_THIRD . 'your_addon_name/models',
		PATH_THIRD . 'your_addon_name/models/channels'
	));


*Note, Channel Data is a CodeIgniter driver, not a library. You must load it using the `driver` method.*

----

## QueryBuilder

QueryBuilder is a standalone class that provides a clean syntax for building SQL statements based on the Eloquent ORM. QueryBuilder can be used as a standalone class, or within the [Models](#models) themselves.

### Instantiate

	$builder = new QueryBuilder();

### Response

After you are finished building your query, you need to instantiate a `QueryResponse` object by running the `get()` method.

	$response = $builder->get();

Or you can skip the response and just grab the results. If a model has been defined, a `Collection` will be returned. Otherwise, an array of standard objects will be returned.

	$response = $builder->result();

### Select

	// Select all
	$builder->select('*');

	// Select columns from a specific table
	$builder->select('members.member_id');

	// Select multiple columns with an array
	$builder->select(array('username', 'email', 'screen_name'));

	// Select columns with an alias
	$builder->select('members.email', 'email_address');

	// Select columns with a closure
	$builder->select(function($table) {
		$table->select('email');
		$table->select('username');
	});

	// Select data using a raw query string
	$builder->select(QueryString::raw('`username` as \'user\''));

### From
	
	// Specify a table name
	$builder->from('members');

	// Specify data using a raw query string
	$builder->select(QueryString::raw('`exp_members` as \'member\''));

### Where

	// A basic conditional with operator
	$builder->where('username', '=', 'Justin');

	// A basic conditional with out operator
	$builder->where('username', 'Justin');

	// Add an "OR" conditional
	$builder->orWhere('username', 'Justin');

	// Add an "and" conditional (this is an alias to where())
	$builder->andWhere('username', 'Justin');

	// Perform a "WHERE IN" conditional against an array
	$builder->whereIn('member_is', array(1, 2, 3));
	$builder->andWhereIn('member_is', array(1, 2, 3));
	$builder->orWhereIn('member_is', array(1, 2, 3));

	// Perform nested conditionals with a closure
	$builder->where(function($table) {
		$table->where('screen_name', 'Justin Kimbrell');
		$table->orWhere('screen_name', 'Objective HTML');
	});

### Having

	// A basic conditional with operator
	$builder->having('username', '=', 'Justin');

	// A basic conditional with out operator
	$builder->having('username', 'Justin');

	// Add an "OR" conditional
	$builder->orHaving('username', 'Justin');

	// Add an "and" conditional (this is an alias to where())
	$builder->andHaving('username', 'Justin');

	// Perform a "WHERE IN" conditional against an array
	$builder->havingIn('member_is', array(1, 2, 3));
	$builder->andHavingIn('member_is', array(1, 2, 3));
	$builder->orHavingIn('member_is', array(1, 2, 3));

	// Perform nested conditionals with a closure
	$builder->having(function($table) {
		$table->having('screen_name', 'Justin Kimbrell');
		$table->orHaving('screen_name', 'Objective HTML');
	});

### Join

	// Basic
	$builder->join('table2', 'table1.id', '=', 'table2.id');

	// Left
	$builder->leftJoin('table2', 'table1.id', '=', 'table2.id');

	// Right
	$builder->rightJoin('table2', 'table1.id', '=', 'table2.id');

	// Outer
	$builder->outerJoin('table2', 'table1.id', '=', 'table2.id');

	// Inner
	$builder->innerJoin('table2', 'table1.id', '=', 'table2.id');

	// Raw String
	$builder->join(QueryString::raw('table2 ON table1.id = table2.id'));

### Order By
	
	// Basic
	$builder->orderBy('id', 'asc')

	// Raw String
	$builder->orderBy(QueryString::raw('id ASC'));

### Sort

	$builder->sort('ASC');

### Limit

	// Limit with offset at 0
	$builder->limit(10);

	// Limit with offset at 10
	$builder->limit(10, 10);

### Offset

	$builder->offset(5);

----

## QueryResponse

`QueryResponse` is a class that is instantiated when the `get` method is ran on the `QueryResponse` object.

### count()

Returns the total number of items in the response
	
	$response = $builder->get();
	$response->count();

### each()

This method loops through the entire collection and passes each item to a closure.

	$response = $builder->get();
	
	$response->each(function($i, $row) {
		$row->some_field = 'Some new value';
	});

### first()

This method returns the first row in the result array
	
	$response = $builder->get();
	
	$row = $response->first();

### last()

This method returns the last row in the result array

	$response = $builder->get();
	
	$row = $response->last();

### index()

This method returns a row in the result by index, or if no index is exists, NULL will be returned.
	
	$response = $builder->get();
	
	$row = $response->index(2);

### next()

This method returns the next row in the result set.
	
	$response = $builder->get();
	
	$row = $response->next();

### prev()

This method returns the previous row in the result set.
	
	$response = $builder->get();
	
	$row = $response->next();

### result()

This method returns the result array
	
	$response = $builder->get();
	
	$row = $response->result();

### row()

This method returns the first row in the result array
	
	$response = $builder->get();
	
	$row = $response->row();


----

## QueryString

`QueryString` is a class that can be passed into any parameter that will output a raw string. `QueryString` also has a number of static helper methods to manipulate strings for common uses within SQL.

### Instantiate

	$rawString = new QueryString('This is a raw string');

	var_dump((string) $rawString);

### QueryString::protect($str, $table = FALSE)

This method will attempt to protect strings from SQL syntax and formatting errors. It will encapsulate table and field names with backticks.

	// Returns `field`
	QueryString::protect('field');

	// Returns `exp_table`.`field`
	QueryString::protect('field', 'table');

	// Returns `exp_table`.`field`
	QueryString::protect('table.field');

### QueryString::raw($str)

This method will instantiate a QueryString object.

	// Returns QueryString object
	QueryString::raw('This is a test');


### QueryString::table($str)

This method will convert a string into a properly prefixed table name.

	// Returns 'exp_table_name'
	QueryString::table('table_name');


### QueryString::strip($str)

This method will strip unnecessary formatting from strings. It's important to note, this will also strip numeric prefixes that can be used in indexes within arrays (to make them unique).

	// Returns 'table_name'
	QueryString::strip('`table_name`');

	// Returns 'table_name'
	QueryString::strip('{1} `table_name`');

	// Returns 'table_name'
	QueryString::strip('{2} `table_name`');


### QueryString::clean($str)

This method will trim and clean excess conditional keywords from strings

	// Returns 'field1 OR field2'
	QueryString::clean(' AND field1 OR field2 ');

---

## Collections

When a model processes QueryBuilder objects, it will take those results and instantiate the respected objects and pass an array of items to the `Collection` class.

### each($closure)

This method loops through the entire collection and passes each item to a closure.

	$members = Member::all();

	$members->each(function($i, Member $member) {
		$member->some_field = 'Some new value';
		$member->save();
	});

### items()

This method returns an array of instantiated model objects.

	$members = Member::all();

	foreach($members->$items() as $i => $member)
	{
		$member->some_field = 'Some new value';
		$member->save();
	}

### first()

This method returns the first model in the collection.
	
	$members = Member::all();

	$member = $members->first()

### last()

This method returns the last model in the collection.
	
	$members = Member::all();
	
	$member = $members->last()

### get($index = FALSE, $default = NULL)

This method returns an item in the collection by index, or if no index is exists, all items will be returned.
	
	$member = Member::all()->get(2);
	$member = Member::all()->get(12, 'some default value');

### count()

Returns the total number of items in the collection
	
	$count = Member::all()->count();

----

## Models

Models are really the key component behind Channel Data. Each model is assigned to a database table and comes complete with a full CRUD controller. Models can be instantiated and be accessed statically. All models should extend the `BaseModel` class, and are located in the `models` directory within `ChannelData`, within the `models` directory inside your add-on, or in the global `models` directory.

	<?php

	use ChannelData\Base\BaseModel;

	class ChannelFields extends BaseModel {

		protected $table = 'channel_fields';

		protected $idField = 'field_id';
		
		protected $fillable = array(
			'site_id',
			'group_id',
			'field_name',
			'field_label',
			'field_instructions',
			'field_type',
			'field_list_items',
			'field_pre_populate',
			'field_pre_channel_id',
			'field_pre_field_id',
			'field_ta_rows',
			'field_maxl',
			'field_required',
			'field_text_direction',
			'field_search',
			'field_is_hidden',
			'field_fmt',
			'field_show_fmt',
			'field_order',
			'field_content_type',
			'field_settings'
		);

		protected $guarded = array('field_id');

		public function toArray()
		{
			$return = parent::toArray();
			$return['field_settings'] = unserialize(base64_decode($return['field_settings']));

			return $return;
		}
	}


##### Properties

#### $exists

A public property that is `TRUE` is the model exists in the database. Is `FALSE` is the model has not been saved.

### $table

This property assigns a datatable to the model

### $idField

This property defines the `id()` field name for the table

### $uidField

This property defined the `uid()` name for the table

### $deleted

This property is set to `TRUE` if the model has been deleted in the database

### $prefix

This property allows you to define a column prefix. So if all the columns in the table have *member_*, set that as the prefix.

### $fillable

This property allows you to define columns that are fillable by user data. This is a security setting. This property expects an array of column names.

### $guarded

This property allows you to define columns that are guarded from data entry. Any data being guarded will not get passed to the `save()` or `update()` methods. This property expects an array of column names.

### $hidden

This property allows you to hide specific columns from the `toArray()` and `toJson()` methods. This property expects an array of column names.

##### Static Methods

All query builder methods are supported.

	Member::where('username', 'LIKE', '%justin%')->where(...);

### MyModel::all()

Return all the results in the model
	
	$members = Member::all();

### MyModel::find($id)

Find a model by ID

	$members = Member::find(1);

### MyModel::findByUid($id)

Find a model by UID. The `uidField` inmust be defined in your model class to use this method.

	$members = Member::findByUid('ak20f-2dajd-2adawwad-zjadafj3');

### MyModel::query()

Instantiate a `QueryBuilder` object.

	$query = Member::query()->where(...);

### MyModel::table()

Return the table that is being searched

	$table = Member::table();
	
##### Instance Methods

### id()

Returns the id of the model

	$memberId = $member->id();

### uid()

Returns the uid of the model. This field required the `uidField` property to be set in the model.

	$memberId = $member->id();

### getAttributes()

Returns all the attributes for the model.

	$attributes = $member->getAttributes();

### getAttribute($name)

Returns a single the attributes for the model.

	$attribute = $member->getAttribute('username');

### setAttributes($data)

Sets an array of attributes. Ignores the security filters.

	$member->setAttributes(array(
		'username' => 'Justin Kimbre',
		'company'  => 'Objective HTML'
	));

### setAttribute($prop, $value)

Sets an single attribute. Ignores the security filters.

	$member->setAttributes('company', 'Objective HTML');

### fill($data = array())

Fill the model with an array data. Respects the security filters.

	$member->fill(array)

### save($data = array())

Save the model data. If an array is passed, the model data is passed to the `fill` method.

	$member->save();

### update($data = array())

Update the model data. If an array is passed, the model data is passed to the `fill` method.

	$member->update();

### delete()

Delete the model.

	$member->delete();

### toArray()

Output the model as an associative array

	$member->toArray();

### toJson()

Output the model as a JSON string

	$member->toJson();

----

## Channel Models

Channel Models extend the `BaseModel` class and are specifically built to work with Channel Entries.

	<?php

	use ChannelData\Base\ChannelModel;

	class School extends ChannelModel {

		protected $channel = 'schools';

		protected $prefix  = 'school_';
	}


##### Properties

#### $apiResponse

If the API has saved or updated an entry, the response is saved here.

#### $channel

The name of the channel within ExpressionEngine.

#### $errors

A protected property that stores an array of errors if the entry failed to save or update.

#### $fields

An indexed array of channel fields.

	$fields = array(  
        // field_id => field_name
		
		5 => 'your_field_name'
	);


##### Instance Methods

#### getErrors()

Get the array of errors in the `$errors` property (which is protected).


##### Static Methods

#### MyModel::findByUrlTitle()

Get the entry by the `url_title`.


#### MyModel::channelId()

Get the `channel_id` of the model.