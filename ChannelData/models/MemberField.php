<?php namespace ChannelData\Model;

use ChannelData\Base\BaseModel;

class MemberField extends BaseModel {

	protected $table = 'member_fields';

	protected $idField = 'm_field_id';

	protected $fillable = array(
		'm_field_id',
		'm_field_name',
		'm_field_label',
		'm_field_description',
		'm_field_type',
		'm_field_list_items',
		'm_field_ta_rows',
		'm_field_maxl',
		'm_field_width',
		'm_field_search',
		'm_field_requried',
		'm_field_public',
		'm_field_reg',
		'm_field_cp_reg',
		'm_field_fmt',
		'm_field_order'
	);

	protected $prefix = 'm_field_';
}