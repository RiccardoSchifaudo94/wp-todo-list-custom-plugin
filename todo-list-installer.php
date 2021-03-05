<?php
	
	global $wpdb;
	$table_name = $wpdb->prefix . "todolist_settings";
	$todo_settings_version = '1.0.0';
	$charset_collate = $wpdb->get_charset_collate();

	if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

	    $sql = "CREATE TABLE $table_name (
	            ID mediumint(9) NOT NULL AUTO_INCREMENT,
	            `url_endpoint` text NOT NULL,
	            PRIMARY KEY  (ID)
	    ) $charset_collate;";

	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql);
	    add_option('todo_settings_version', $todo_settings_version);
	}

	$todo_table = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."posts");
	//Add column if not present.
	if(!isset($todo_table->completed)){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."posts ADD completed BOOLEAN DEFAULT FALSE");
	}

?>