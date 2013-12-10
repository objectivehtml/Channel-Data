<?php namespace ChannelData\Base;

use ChannelData\Base\BaseModel;
use ChannelData\Model\Channel;
use ChannelData\Component\ChannelQueryBuilder;
use ChannelData\Component\ChannelEntriesApi;

// namespace ChannelData;

class ChannelModel extends BaseModel {

	protected $apiResponse = NULL;

	protected $channel = FALSE;

	protected $prefix = '';

	protected $required = array(
		'entry_id',
		'url_title',
		'site_id',
		'channel_id',
		'author_id',
		'entry_date',
		'expiration_date',
		'status'
	);

	protected $fillable = array('title');

	protected $table = 'channel_data';

	protected $idField = 'entry_id';

	protected $errors = array();

	protected $fields = FALSE;

	public function __construct($data = array())
	{
		$this->uidField   = $this->prefix.'uid';
		$this->required[] = $this->prefix.'uid';

		$this->fillable = array_merge($this->fillable, $this->fields());

		parent::__construct($data);
	}

	public function __set($property, $value)
	{
		if(property_exists($this, $this->prefix.$property))
		{
			$property = $this->prefix.$property;
		}

		$this->$property = $value;

		if($property == 'title')
		{
			$this->url_title = url_title(strtolower($this->title));
		}
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function channel()
	{
		$channel = Channel::findByName($this->channel);

		if(!$channel)
		{
			show_error('The '.$this->channel.' id set in the config is incorrect');
		}

		return $channel;
	}

	public function delete()
	{
		if($this->exists)
		{
			ee()->load->library('api');
			ee()->api->instantiate('channel_entries');
			ee()->api_channel_entries->delete_entry(array($this->entry_id));

			$this->deleted = TRUE;
			$this->exists  = FALSE;
		}

		return $this;
	}

	protected function _createRecord($data = array())
	{		
		if(count($data))
		{
			$this->fill($data);
		}

		$this->apiResponse = ChannelEntriesApi::submit_entry($this->channel()->channel_id, $this->data());
		$this->setAttribute($this->idField, $this->apiResponse);

		if(!is_int($this->apiResponse))
		{
			$this->errors = $this->apiResponse;
		}

		return $this;
	}

	protected function _updateRecord($data)
	{
		if(count($data))
		{
			$this->fill($data);
		}
		
		$this->apiResponse = ChannelEntriesApi::update_entry($this->channel_id, $this->entry_id, $this->data());
		
		if(!is_int($this->apiResponse))
		{
			$this->errors = $this->apiResponse;
		}

		return $this;
	}

	public function data()
	{
		$channel = Channel::findByName($this->channel);

		$data = parent::data();

		foreach($channel->fields()->items() as $row)
		{
			if(isset($data[$row->field_name]) && $value = $data[$row->field_name])
			{
				unset($data[$row->field_name]);

				$data['field_id_'.$row->field_id] = $value;
				$data['field_ft_'.$row->field_id] = $row->field_fmt;
			}
		}

		return $data;
	}

	public function columns()
	{
		return array_merge(
			ee()->db->list_fields('channel_titles'),
			$this->fields()
		);
	}

	public function fields()
	{
		if($this->fields)
		{
			return $this->fields;
		}

		foreach($this->channel()->fields()->items() as $field)
		{
			$this->fields[$field->field_id] = $field->field_name;
		}

		return $this->fields;
	}


	public static function findByUrlTitle($urlTitle)
	{
		return self::init(Course::where(array('url_title' => $urlTitle)));
	}

	public static function channelId()
	{
		$class = new static;

		return Channel::findByName($class->channel)->id();
	}

	public static function table()
	{
		return 'channel_titles';
	}

	public static function query()
	{
		$class = new static;

		$query = new ChannelQueryBuilder();
		$query->model = get_class($class);

		$query->from('channel_titles');
		$query->leftJoin('channel_data', 'channel_titles.entry_id', '=', 'channel_data.entry_id');
		$query->select(array(
			'entry_id',
			'site_id',
			'channel_id',
			'author_id',
			'title',
			'url_title',
			'status',
			'entry_date',
			'expiration_date',
		), NULL, 'channel_titles');

		foreach($class->fields() as $field_id => $field_name)
		{
			$query->select('field_id_'.$field_id, $field_name, 'channel_data');
		}

		return $query;
	}
}