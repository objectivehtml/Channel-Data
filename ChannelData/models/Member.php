<?php namespace ChannelData\Model;

use ChannelData\Base\BaseModel;

class Member extends BaseModel {

	protected $table = 'members';

	protected $idField = 'member_id';
	
	protected $fillable = array(
		'username',
		'screen_name',
		'email',
		'url',
		'location',
		'occupation',
		'interests'
	);

	protected $guarded = array('member_id', 'group_id');

	protected $hidden = array('member_id', 'group_id');

	public function __construct($data = array())
	{
		if(empty($data))
		{
			return;
		}

		$self = $this;

		MemberField::all()->each(function($i, $field) use($self, $data) {
			$self->setAttribute($field->name(), $data->{$field->name()});
		});

		parent::__construct($data);

		if(isset($data->member_id))
		{
			$this->member_id = $data->member_id;
		}

		if(isset($data->group_id))
		{
			$this->group_id = $data->group_id;
		}
	}

	public static function query()
	{
		$class = self::instantiate();

		$query = new QueryBuilder();
		$query->model = get_class($class);

		$query->select('*');

		MemberField::all()->each(function($i, $field) use ($query) {
			$query->select('m_field_id_'.$field->id(), $field->m_field_name);
		});

		$query->from($class::table());
		$query->join('member_data', 'members.member_id', '=', 'member_data.member_id');

		return $query;
	}
}