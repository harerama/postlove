<?php
/**
*
* Post Love extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Lucifer <http://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace anavaro\postlove\migrations;

/**
* Primary migration
*/

class release_1_1_3 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\anavaro\postlove\migrations\release_1_1_0',
		);
	}
	
	//lets create the needed table
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'topics'	=> array(
					'total_posts_likes'	=> array('INT:11', 0),
				),
			),
		);
	}
	
	public function update_data()
	{
		$sql = 'update ' . $this->table_prefix . 'topics topics set total_posts_likes = ifnull (( select count(distinct likes.user_id) from ' . $this->table_prefix . 'posts_likes likes, ' . $this->table_prefix . 'posts posts where posts.post_id = likes.post_id and posts.topic_id = topics.topic_id and posts.post_visibility = 1), 0)';
		$result = $this->db->sql_query($sql);
		$this->db->sql_freeresult($result);
	}
	
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'topics'	=> array(
					'total_posts_likes',
				),				
			),
		);
	}
}
