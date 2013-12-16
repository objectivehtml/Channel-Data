<?php namespace ChannelData\Model;

use ChannelData\Base\BaseModel;

class ChannelTitle extends BaseModel {

	protected $table = 'channel_titles';

	protected $idField = 'entry_id';

	protected $fillable = array(
		'site_id',
		'channel_id',
		'author_id',
		'forum_topic_id',
		'ip_address',
		'title',
		'url_title',
		'status',
		'versioning_enabled',
		'view_count_one',
		'view_count_two',
		'view_count_three',
		'view_count_four',
		'allow_comments',
		'sticky',
		'entry_date',
		'year',
		'month',
		'day',
		'expiration_date',
		'comment_expiration_date',
		'edit_date',
		'recent_comment_date',
		'comment_total'
	);
}