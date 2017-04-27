<?php

/*
Plugin Name: World of Warcraft Recruitment
Plugin URI: http://hereticgaming.com
Description: World of Warcraft Recruitment - HereticGaming.com
Author: Canalla
Version: 1.0
Author URI: http://hereticgaming.com
*/

/*  Copyright 2016 Canalla (email: info at hereticgaming.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class HereticRecruitment extends WP_Widget {

    private $urlImages;

    private $class = array(
        'death_knight' => array('blood', 'frost', 'unholy'),
        'demon_hunter' => array('havoc', 'vengeance'),
        'druid' => array('balance', 'guardian', 'feral', 'restoration'),
        'hunter' => array('beast_mastery', 'marksmanship', 'survival'),
        'mage' => array('arcane', 'fire', 'frost'),
    	'monk' => array('brewmaster', 'mistweaver', 'windwalker'),
        'paladin' => array('holy', 'protection', 'retribution'),
        'priest' => array('discipline', 'holy', 'shadow'),
        'rogue' => array('assassination', 'combat', 'subtlety'),
        'shaman' => array('elemental', 'enhancement', 'restoration'),
        'warlock' => array('affliction', 'demonology', 'destruction'),
        'warrior' => array('arms', 'fury', 'protection')
    );

	private $priorityValues = array(
        0 => 'Closed',
        1 => 'Low',
        2 => 'Medium',
        3 => 'High'
    );

	public function __construct() {
		$this->urlImages = WP_PLUGIN_URL.'/heretic-recruitment/images';
		parent::__construct('HereticRecruitment', 'World of Warcraft Recruitment', array('description' => 'HereticGaming.com - World of Warcraft Recruitment Widget'));
	}

    public function form($instance) {
		$title = ($instance) ? esc_attr($instance['title']) : 'Recruitment';
        echo 'Title:<br />';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$title.'" />';
        echo '<br /><br />';
		echo '<ul style="list-style-type: none">';
		foreach ($this->class as $class => $skills) {
            echo '<li><img  width="26px" height="26px" src="'.$this->urlImages.'/class/'.$class.'.png" style="vertical-align: middle" /> <b>'.ucwords(str_replace('_', ' ', $class)).'</b>';
            echo '<ul style="margin-top: 10px; list-style-type: none">';
            foreach ($skills as $skill) {
				$key = $class.'-'.$skill;
                echo '<li style="padding-left: 30px"><img  width="26px" height="26px"  src="'.$this->urlImages.'/skills/'.$class.'_'.$skill.'.png" title="'.$this->formatSkill($skill).'" style="vertical-align: middle" />';
                echo '<select name="'.$this->get_field_name($key).'" id="'.$this->get_field_id($key).'" style="margin-left: 10px; width: 150px">';
                foreach ($this->priorityValues as $searchKey => $searchVal) {
                    echo '<option value="'.$searchKey.'" '.((array_key_exists($key, $instance) && $instance[$key] == $searchKey) ? 'selected="selected"' : '').'>'.$searchVal.'</option>';
                }
                echo '</select>';
                echo '</li>';
            }
            echo '</ul></li>';
        }
        echo '</ul>';
    }

	public function update($newInstance, $oldInstance) {
		$instance = $newInstance;
		$instance['title'] = strip_tags($newInstance['title']);
		return $instance;
	}

    public function widget($args, $instance) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
        echo '<div style="padding: 5px;"><table width="100%" border="0">';
        foreach ($this->class as $class => $skills) {
			echo '<tr height="28"><td><img  width="26px" height="26px" src="'.$this->urlImages.'/class/'.$class.'.png" / style="vertical-align:middle;"> <span class="'.$class.'">'.ucwords(str_replace('_', ' ', $class)).'</span></td>';
			echo '<td width="120" style="text-align: right">';
			foreach ($skills as $skill) {
				$key = $class.'-'.$skill;
				if (array_key_exists($key, $instance)) {
					if ($instance[$key] == 3) {
						echo '<img  width="26px" height="26px" src="'.$this->urlImages.'/skills/'.$class.'_'.$skill.'.png" title="'.$this->formatSkill($skill).': '.$this->priorityValues[$instance[$key]].'"  style="vertical-align:middle;"/> ';
					}
 					else if ($instance[$key] == 2) {
						echo '<img  width="26px" height="26px" src="'.$this->urlImages.'/skills/'.$class.'_'.$skill.'.png" title="'.$this->formatSkill($skill).': '.$this->priorityValues[$instance[$key]].'"  style="vertical-align:middle; filter:alpha(opacity=80); -moz-opacity: 0.80; opacity: 0.80;"/> ';
						}
					else if ($instance[$key] == 1) {
						echo '<img  width="26px" height="26px" src="'.$this->urlImages.'/skills/'.$class.'_'.$skill.'.png" title="'.$this->formatSkill($skill).': '.$this->priorityValues[$instance[$key]].'"  style="vertical-align:middle; filter:alpha(opacity=60); -moz-opacity: 0.60; opacity: 0.60;"/> ';
						}
					else {
						echo '<img  width="26px" height="26px" src="'.$this->urlImages.'/skills/'.$class.'_'.$skill.'.png" title="'.$this->formatSkill($skill).': '.$this->priorityValues[$instance[$key]].'"  style="vertical-align:middle; filter:alpha(opacity=15); -moz-opacity: 0.15; opacity: 0.15;"/> ';
					}
				}
			}
			echo '</td></tr>';
        }
        echo '</table></div></li>';
		echo $args['after_widget'];
    }

    private function formatSkill($skill) {
        return ucwords(str_replace('_', ' ', $skill));
    }
}

add_action('widgets_init', create_function('', 'return register_widget("HereticRecruitment");'));

?>
