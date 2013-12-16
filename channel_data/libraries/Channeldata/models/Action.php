<?php namespace ChannelData\Model;

use ChannelData\Base\BaseModel;
use ChannelData\Component\QueryBuilder;

class Action extends BaseModel {

	protected $table = 'actions';

	protected $idField = 'action_id';
	
	protected $fillable = array(
		'class',
		'method',
		'csrf_exempt'
	);

	protected $guarded = array('action_id');

	public static function findByClass($class)
	{
		return self::where('class', '=', $class)->get();
	}

	public static function findByMethod($method)
	{
		return self::where('method', '=', $method)->get();
	}
}