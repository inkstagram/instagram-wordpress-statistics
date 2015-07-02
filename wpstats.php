<?php
/*
	Plugin Name: Instagram Statistics
	Plugin URI: http://wordpress.ord/extend/plugins/instagram-statistics
	Description: Comprehensive Instagram statistics widget with tonnes of options
	Version: 1.0.6
	Author: jbenders
	Author URI: http://ink361.com/
*/

if (!defined('INSTAGRAM_STATISTICS_PLUGIN_URL')) {
	define('INSTAGRAM_STATISTICS_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
}

function wp_instagram_stats_admin_register_head() {
	$siteurl = get_option('siteurl');	
}

add_action('admin_head', 'wp_instagram_stats_admin_register_head');
add_action('widgets_init', 'load_wp_instagram_stats');
add_action('admin_notices', 'wpinstagram_stats_show_instructions');

function wpinstagram_stats_show_instructions() {
	global $wpdb;
	
	$results = $wpdb->get_results("SELECT * FROM igstats_widget");
	
	if (sizeof($results) == 0) {	
		$url = plugins_url('wpstats-admin.css', __FILE__); 
		wp_enqueue_style('wpstats-admin.css', $url);
		wp_enqueue_script("jquery");
		wp_enqueue_script("lightbox", plugin_dir_url(__FILE__)."js/lightbox.js", Array('jquery'), null);
		
		require(plugin_dir_path(__FILE__) . 'templates/setupInstructions.php');		
	} else {
		$settings = $wpdb->get_results("SELECT * FROM igstats_global_settings WHERE name='firstRun' and value <= DATE_SUB(now(), INTERVAL 7 DAY)");
		
		if (sizeof($settings) == 0) {
			#has it been set yet?
			$check = $wpdb->get_results("SELECT * FROM igstats_global_settings WHERE name='firstRun'");
			if (sizeof($check) == 0) {				
				#create it
				$wpdb->get_results("INSERT INTO igstats_global_settings (name, value) VALUES ('firstRun', NOW())");
			}
		} else {
			#have we been disabled?
			$disabled = $wpdb->get_results("SELECT * FROM igstats_global_settings WHERE name='disabledMessage'");
			
			if (sizeof($disabled) == 0) {	
				#have we received a request to remove the message?								
				if (isset($_POST['instagram_stats_disable_message']) || isset($_GET['instagram_stats_disable_message'])) {
					$wpdb->get_results("INSERT INTO igstats_global_settings (name, value) VALUES ('disabledMessage', '1')");
				} else {						
					#need to show header
					$url = plugins_url('wpstats-admin.css', __FILE__); 
					wp_enqueue_style('wpstats-admin.css', $url);
					wp_enqueue_script("jquery");
					wp_enqueue_script("lightbox", plugin_dir_url(__FILE__)."js/lightbox.js", Array('jquery'), null);
					
					require(plugin_dir_path(__FILE__) . 'templates/reviewInstructions.php');					
				}				
			}	
		}		
	}
}

function load_wp_instagram_stats() {
	register_widget('WPInstagramStatsWidget');
}
	
class WPInstagramStatsWidget extends WP_Widget {
	function WPInstagramStatsWidget($args=array()) {
		$widget_ops = array('description' => __('Show off your Instagram statistics!', 'wpstats'));
		$control_ops = array('id_base' => 'wpstats-widget');
		
		$this->wpstats_path = plugin_dir_url(__FILE__);
		$this->WP_Widget('wpstats-widget', __('Instagram Statistics Widget', 'wpstats'), $widget_ops, $control_ops);
		
		if (is_admin()) {
			$this->handleTables();
		}
	}
	
	function widget($args, $instance) {
		extract($args);
		
		if ($instance['db_id']) {
			global $wpdb;
			
			$details = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->_tablePrefix() . "widget WHERE uid=%s", $instance['db_id']));
			
			if (sizeof($details) > 0) {
				$details = $details[0];								
				$stats = NULL;				
				$details = $this->_confirmDefaults($details);
				
				#get our stats if we have any
				if ($details->user_id !== '' && $details->user_id !== NULL) {
					$stats = $wpdb->get_results($wpdb->prepare("SELECT uid, last_generated, statistics FROM igstats_stats WHERE uid=%s", $details->user_id));	
					
					if (sizeof($stats) > 0) {
						$stats = $stats[0];						
					} else {
						$stats = NULL;
					}							
				}		
				
				if ($stats === NULL) {
					if ($details->verbose === 1) {						
						require(plugin_dir_path(__FILE__) . 'templates/errorFrontend.php');		
					}
				} else {
					wp_enqueue_style('wpstats', $this->wpstats_path . 'wpstats.css', Array(), null);
					wp_enqueue_script('odometer', $this->wpstats_path . 'js/odometer.js', Array(), null);
					wp_enqueue_script('amcharts', $this->wpstats_path . 'js/amcharts.js', Array(), null);
					wp_enqueue_script('amcharts-serial', $this->wpstats_path . 'js/serial.js', Array('amcharts'), null);
					wp_enqueue_script('amcharts-pie', $this->wpstats_path . 'js/pie.js', Array('amcharts'), null);					
					wp_enqueue_script('wpstats', $this->wpstats_path . 'js/wpstats.js', Array('odometer', 'amcharts', 'amcharts-serial', 'amcharts-pie'), null);									
										
					$tmp = stripslashes($stats->statistics);																																								
					$stats = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $tmp));
					
					require(plugin_dir_path(__FILE__) . "templates/widgetHeader.php");
					
					if ($details->sharing) {
						require(plugin_dir_path(__FILE__) . "templates/social.php");
					}
					
					if ($details->shows === "counters") {
						require(plugin_dir_path(__FILE__) . 'templates/counters.php');
					} else if ($details->shows === "interaction") {
						require(plugin_dir_path(__FILE__) . 'templates/interaction.php');
					} else if ($details->shows === "posthistory") {
						require(plugin_dir_path(__FILE__) . 'templates/posthistory.php');
					} else if ($details->shows === "yearlydistribution") {
						require(plugin_dir_path(__FILE__) . 'templates/yearlydistribution.php');
					} else if ($details->shows === "yearlydots") {
						require(plugin_dir_path(__FILE__) . 'templates/yearlydots.php');
					} else if ($details->shows === "geolocation") {
						require(plugin_dir_path(__FILE__) . 'templates/geolocation.php');
					} else if ($details->shows === "tagged") {
						require(plugin_dir_path(__FILE__) . 'templates/tagged.php');
					} else if ($details->shows === "likesreceived") {
						require(plugin_dir_path(__FILE__) . 'templates/likesreceived.php');
					} else if ($details->shows === "commentsreceived") {
						require(plugin_dir_path(__FILE__) . 'templates/commentsreceived.php');
					} else if ($details->shows === "filterusage") {
						require(plugin_dir_path(__FILE__) . 'templates/filterusage.php');
					} else if ($details->shows === "filterinteraction") {
						require(plugin_dir_path(__FILE__) . 'templates/filterinteraction.php');
					}
					
					require(plugin_dir_path(__FILE__) . 'templates/widgetFooter.php');
				}
			}
		}		
	}
	
	function form($instance) {
		$url = plugins_url('wpstats-admin.css', __FILE__);
		wp_enqueue_style('wpstats-admin.css', $url);		
		wp_enqueue_script('jquery');
		wp_enqueue_script('lightbox', plugin_dir_url(__FILE__) . 'js/lightbox.js', Array('jquery'), null);
		
		$details = NULL;				
		
		if ($instance['db_id']) {
			global $wpdb;
			
			$details = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->_tablePrefix() . "widget WHERE uid=%s", $instance['db_id']));
			
			if (sizeof($details) > 0) {
				$details = $details[0];
				
				$details = $this->_confirmDefaults($details);				
				$stats = NULL;
				
				#get our stats if we have any
				if ($details->user_id !== '' && $details->user_id !== NULL) {
					$stats = $wpdb->get_results($wpdb->prepare("SELECT uid, last_generated FROM igstats_stats WHERE uid=%s", $details->user_id));	
					
					if (sizeof($stats) > 0) {
						$stats = $stats[0];						
					} else {
						$stats = NULL;
					}							
				}
				
				if ($details->error_detected > 0) {
					require(plugin_dir_path(__FILE__) . 'templates/errorBackend.php');
				}	
			}		
		}
		
		require(plugin_dir_path(__FILE__) . 'templates/setupButton.php');
		
		return;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $new_instance;
		global $wpdb;
		
		if (!$old_instance['db_id']) {						
			$wpdb->get_results($wpdb->prepare("INSERT INTO " . $this->_tablePrefix() . "widget (localid, setup, last_modified) VALUES (%s, 0, NOW())", $this->id));
			
			$result = $wpdb->get_results("SELECT last_insert_id() as uid");
						
			$instance['db_id'] = $result[0]->uid;			
		} else {
			$instance['db_id'] = $old_instance['db_id'];			
		}				
		
		if ($_POST['instance_token']) {
			$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget set error_detected=0, setup=1, token=%s, last_modified=NOW() WHERE uid=%s", sanitize_text_field($_POST['instance_token']), $instance['db_id']));
		}
	
		#our widget settings			
		if ($_POST['title']) {
			$settings = array(
				'title'		=> sanitize_text_field(stripslashes($_POST['title'])),
				'username'	=> sanitize_text_field(stripslashes($_POST['username'])),
				'user_id'	=> sanitize_text_field($_POST['user']),
				'shows'		=> sanitize_text_field($_POST['shows']),
				'primary'	=> sanitize_text_field(stripslashes($_POST['primary'])),
				'secondary'	=> sanitize_text_field(stripslashes($_POST['secondary'])),
				'text'		=> sanitize_text_field(stripslashes($_POST['text'])),
				'background'=> sanitize_text_field(stripslashes($_POST['background'])),
				'responsive'=> sanitize_text_field($_POST['responsive']),
				'sharing' 	=> sanitize_text_field($_POST['sharing']),
				'verbose' 	=> sanitize_text_field($_POST['verbose']),
				'powered'	=> sanitize_text_field($_POST['powered']),
			);	
			
			$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "widget SET title=%s, 
																							  username=%s, 
																							  user_id=%s, 
																							  shows=%s, 
																							  primary_color=%s, 
																							  secondary_color=%s,
																							  text_color=%s,
																							  background_color=%s,
																							  responsive=%s,
																							  sharing=%s,
																							  verbose=%s,
																							  powered=%s,
																							  last_modified=NOW() WHERE uid=%s", $settings['title'], $settings['username'],
																							  $settings['user_id'], $settings['shows'], $settings['primary'], $settings['secondary'],
																							  $settings['text'], $settings['background'], $settings['responsive'], $settings['sharing'],
																							  $settings['verbose'], $settings['powered'], $instance['db_id']));	
		}
		
		if ($_POST['statistics'] && $_POST['statistics'] !== '') {
			#get our data for our user ID and then update our statistics object
			$details = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->_tablePrefix() . "widget WHERE uid=%s", $instance['db_id']));
			
			if (sizeof($details) > 0) {
				$details = $details[0];				
				$stats = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $this->_tablePrefix() . "stats WHERE uid=%s", $details->user_id));
				
				if (sizeof($stats) == 0) {
					#insert them
					$wpdb->get_results($wpdb->prepare("INSERT INTO " . $this->_tablePrefix() . "stats (uid, last_generated, statistics) VALUES (%s, NOW(), %s)", $details->user_id, sanitize_text_field($_POST['statistics'])));										
				} else {
					#update them	
					$wpdb->get_results($wpdb->prepare("UPDATE " . $this->_tablePrefix() . "stats SET statistics=%s, last_generated=NOW() where uid=%s", sanitize_text_field($_POST['statistics']), $details->user_id));					
				}
			}						
		}
		
		return $instance;
	}
	
	function _tablePrefix($args=array()) {
		extract($args);
	
		return 'igstats_';
	}	

	function _tableDescription($args=array()) {
		extract($args);
		
		return array(
			$this->_tablePrefix() . 'stats' => array(
				'uid'	=> array(
					'type'	=> 'varchar(255)',
					'null'	=> false,
					'pk'	=> true, 	
				),
				'last_generated' => array(
					'type' => 'datetime',
					'null'	=> true,	
				),
				'statistics'	=> array(
					'type'	=> 'mediumtext',
					'null'	=> true,
				),
			),
			$this->_tablePrefix() . 'widget' => array(
				'uid' 	=> array(
					'type' 	=> 'int(11)',
					'null' 	=> false,
					'pk' 	=> true,
					'auto'	=> true,
				),
				'localid' => array(
					'type' 	=> 'varchar(255)',
					'null' 	=> false,
				),
				'token'	=> array(
					'type' 	=> 'varchar(255)',
					'null'	=> true,
				),
				'setup' => array(
					'type'	=> 'int(1)',
					'null'	=> false,
				),
				'title' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,	
				),
				'error_detected' => array(
					'type'	=> 'int(1)',
					'null'	=> true,
				),
				'last_modified' => array(
					'type' 	=> 'datetime',
					'null'	=> true,
				),
				'username' => array(
					'type'	=> 'varchar(255)',	
					'null' => true,
				),
				'user_id' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,
				),
				'shows'	=> array(
					'type'	=> 'varchar(255)',
					'null'	=> true,
				),
				'primary_color' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,	
				),
				'secondary_color' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,	
				),
				'text_color'	=> array(
					'type'	=> 'varchar(255)',
					'null'	=> true,
				),
				'background_color' => array(
					'type'	=> 'varchar(255)',
					'null'	=> true,	
				),	
				'responsive'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,	
				),
				'powered'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,	
				),
				'sharing'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,					
				),
				'verbose'	=> array(
					'type'	=> 'varchar(1)',
					'null'	=> true,
				),							
			),
			$this->_tablePrefix() . 'global_settings' => array(
				'uid'	=> array(
					'type' 	=> 'int(11)',
					'null' 	=> false,
					'pk' 	=> true,
					'auto'	=> true,
				),
				'name'	=> array(
					'type'	=> 'varchar(255)',
					'null'	=> true,
				),
				'value'	=> array(
					'type'	=> 'text',
					'null'	=> true,	
				),
			),
		);
	}
	
	function _describeTable($name) {
		global $wpdb;
		
		$ret = array();		
		$result = $wpdb->get_results("DESC $name");
		
		if (sizeof($result) == 0) {
			return NULL;
		} else {
			foreach ($result as $column) {
				$fields = array();

				#type
				$fields['type'] = strtolower($column->Type);
								
				#null				
				if (strtolower($column->Null) === 'no') {
					$fields['null'] = false;
				} else {
					$fields['null'] = true;
				}
				#pk
				if (strtolower($column->Key) === 'pri') {
					$fields['pk'] = true;
				} else {
					$fields['pk'] = false;
				}
				#auto
				if (strtolower($columns->Extra) === 'auto_increment') {
					$fields['auto'] = true;
				} else {
					$fields['auto'] = false;
				}

				$ret[$column->Field] = $fields;
			}
		}				
		
		return $ret;
	}
	
	function handleTables($args=array()) {
		global $wpdb;
		
		extract($args);
		
		$tables = $this->_tableDescription();
		
		foreach ($tables as $name => $description) {
			$currentTable = $this->_describeTable($name);		
			
			if (is_null($currentTable)) {
				#make the table!
				$query = "CREATE TABLE $name (";
				
				foreach ($description as $columnName => $columnDetails) {
					$query .= " $columnName ";
					if ($columnDetails['type']) {
						$query .= $columnDetails['type'] . ' ';
					} else {
						$query .= ' varchar(255) ';
					}
					
					if ($columnDetails['null']) {
						$query .= ' NULL ';
					} else {
						$query .= ' NOT NULL ';
					}
					
					if ($columnDetails['auto']) {
						$query .= ' auto_increment ';
					}
					
					if ($columnDetails['pk']) {
						$query .= ' primary key ';
					}
					
					$query .= ', ';
				}
				
				$query = substr($query, 0, -2);
				$query .= ")";
				$result = $wpdb->get_results($query);
			} else {
				#compare the columns to see if we need to add one
				foreach ($description as $columnName => $columnDetails) {
					$found = false;
					foreach ($currentTable as $currentName => $currentDetails) {
						if ($currentName === $columnName) {
							$found = true;
						}
					}
					
					if ($found === false) {
						$query = "ALTER TABLE $name ADD COLUMN ";
						
						$query .= " $columnName ";
						if ($columnDetails['type']) {
							$query .= $columnDetails['type'] . ' ';
						} else {
							$query .= ' varchar(255) ';
						}
					
						if ($columnDetails['null']) {
							$query .= ' NULL ';
						} else {
							$query .= ' NOT NULL ';
						}
					
						if ($columnDetails['auto']) {
							$query .= ' auto_increment ';
						}
						
						if ($columnDetails['pk']) {
							$query .= ' primary key ';
						}
						
						$result = $wpdb->get_results($query);
					} else {																		
						if ($description[$columnName]['type'] !== $columnDetails['type']) {
							$query = "ALTER TABLE $name CHANGE COLUMN ";
							
							$query .= " $columnName $columnName ";
						
							if ($columnDetails['type']) {
								$query .= $columnDetails['type'] . ' ';
							} else {
								$query .= ' varchar(255) ';
							}
						
							if ($columnDetails['null']) {
								$query .= ' NULL ';
							} else {
								$query .= ' NOT NULL ';
							}
						
							if ($columnDetails['auto']) {
								$query .= ' auto_increment ';
							}
							
							if ($columnDetails['pk']) {
								$query .= ' primary key ';
							}
							
							$result = $wpdb->get_results($query);	
						}
					}
				}
			}
		}
	}
	
	function _confirmDefaults($settings) {		
		if ($settings->title === NULL || $settings->title === '') {
			$settings->title = 'My Instagram Statistics';
		}
		
		if ($settings->username === NULL) {
			$settings->username = '';
		}
						
		if ($settings->user_id === NULL) {
			$settings->user_id = '';		
		}
		
		if ($settings->shows === NULL || $settings->shows === '') {
			$settings->shows = 'counters';
		}
		
		if ($settings->primary_color === NULL || $settings->primary_color === '') {
			$settings->primary_color = '#34A3D6';			
		}
		
		if ($settings->secondary_color === NULL || $settings->secondary_color === '') {
			$settings->secondary_color = '#FEF100';
		}
		
		if ($settings->text_color === NULL || $settings->text_color === '') {
			$settings->text_color = '#666666';
		}
		
		if ($settings->background_color === NULL || $settings->background_color === '') {
			$settings->background_color = '#FFFFFF';
		}
		
		if ($settings->responsive === NULL || $settings->responsive === '') {
			$settings->response = '1';
		}
		
		if ($settings->powered === NULL || $settings->powered === '') {
			$settings->powered = '0';
		}
		
		if ($settings->sharing === NULL || $settings->sharing === '') {
			$settings->sharing = '1';
		}
		
		if ($settings->verbose === NULL || $settings->verbose === '') {
			$settings->verbose = '1';			
		}
		
		return $settings;
	}
}
	
?>