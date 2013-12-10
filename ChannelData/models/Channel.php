<?php namespace ChannelData\Model;

use ChannelData\Base\BaseModel;

class Channel extends BaseModel {

	protected $table = 'channels';

	protected $idField = 'channel_id';
	
	protected $fillable = array(
		'site_id',
		'channel_name',
		'channel_title',
		'channel_url',
		'channel_description',
		'channel_lang',
		'total_entries',
		'total_comments',
		'last_entry_date',
		'last_comment_date',
		'cat_group',
		'status_group',
		'deft_status',
		'field_group',
		'deft_comments',
		'channel_require_membership',
		'channel_max_chars',
		'channel_html_formatting',
		'channel_allow_img_urls',
		'channel_auto_link_urls',
		'channel_notify',
		'channel_notify_emails',
		'comment_url',
		'comment_system_enabled',
		'comment_require_membership',
		'comment_use_captcha',
		'comment_moderate',
		'comment_max_chars',
		'comment_timelock',
		'comment_require_email',
		'comment_text_formatting',
		'comment_html_formatting',
		'comment_allow_img_urls',
		'comment_auto_link_urls',
		'comment_notify',
		'comment_notify_authors',
		'comment_notify_emails',
		'comment_expiration',
		'search_results_url',
		'show_button_cluster',
		'rss_url',
		'enable_versioning',
		'max_revisions',
		'default_entry_title',
		'url_title_prefix',
		'live_look_template'
	);

	protected $guarded = array('channel_id');

	public function fields()
	{
		return ChannelField::findByGroup($this->field_group);
	}

	public static function findByName($name)
	{
		return self::query()->where('channel_name', $name)->get()->first();
	}
}