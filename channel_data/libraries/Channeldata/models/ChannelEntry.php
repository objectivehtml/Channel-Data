<?php namespace ChannelData\Model;

use ChannelData\Base\ChannelModel;
use ChannelData\Model\ChannelTitle;
use ChannelData\Model\ChannelData;

class ChannelEntry extends ChannelModel {

	public static function find($entry_id)
	{
		//$title = ChannelTitle::find($entry_id);
		$data  = ChannelData::find(26);

		var_dump($data);exit();

		if(!$title || !$data)
		{
			return NULL;
		}

		$return = array_merge($title->toArray(), $data->toArray());

		/*
		$channel = Channel::find($title->channel_id);
		$class   = ucfirst($channel->name());

		if(class_exists($class))
		{
			exit('true');
		}
		*/

		var_dump('false');exit();
	}

}