<?php namespace ChannelData\Model;

use ChannelData\Base\BaseModel;
use ChannelData\Model\Channel;

use ChannelData\Model\ChannelField;

class ChannelData extends BaseModel {

	protected $table = 'channel_data';

	protected $idField = 'entry_id';

	protected $fillable = array();

	/*
	public function __construct($data = array())
	{
		$this->fill($data);

		foreach(ChannelField::all()->items() as $item)
		{

		}
	}
	*/

	public static function findByChannel($channel)
	{
		$obj = new static;

		$fillable = array();

		$query = $obj->query();

		foreach(ChannelField::findByChannel($channel)->all() as $field)
		{
			$fillable[] = 'field_id_'.$field->id();
			$query->select('field_id_'.$field->id(), $field->name());
		}



		$obj->setAttribute('columns', $fillable);


		/*
		$return = $class::where('channel_data.channel_id', $channel)
						->join('channel_titles', 'channel_data.entry_id', '=', 'channel_titles.entry_id');
		*/


		var_dump($query->get()->result()->first()->toArray());exit();

		return $return->get();
	}

}