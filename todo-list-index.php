<?php
/**
 * Plugin Name: Todo List Plugin
 * Plugin URI: https://www.the-shinobi-arts-of-eccentricity.com/
 * Description: Simple todo list plugin to manage your tasks.
 * Version: 1.0
 * Author: Riccardo Schifaudo
 * Author URI: https://www.the-shinobi-arts-of-eccentricity.com/
 */

wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
wp_enqueue_style('prefix_bootstrap');

wp_register_style('todolist-css', '/wp-content/plugins/todo-list-plugin/style.css');
wp_enqueue_style('todolist-css');

wp_register_style('datatable-css','//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css');
wp_enqueue_style('datatable-css');

wp_register_script('jquery-3','https://code.jquery.com/jquery-3.5.1.js', array('jquery'),'1.1', true);
wp_enqueue_script('jquery-3');

wp_register_script('datatable-js','https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js', array('jquery'),'1.1', true);
wp_enqueue_script('datatable-js');

wp_register_script('todo-app-js','/wp-content/plugins/todo-list-plugin/todo-app.js', array('jquery'),'1.1', true);
wp_enqueue_script('todo-app-js');

function installer(){
    include('todo-list-installer.php');
}
register_activation_hook(__file__, 'installer');

add_action( 'the_content', 'welcome_todo_list' );

function welcome_todo_list ( $content ) {
    return $content .= '<h3 class="text-center">Welcome in TODO List</h3>';
}

add_action( 'admin_menu', 'todo_menu' );
add_action( 'the_content', 'todo_list_datatable' );

//add_shortcode( 'todo_table', 'todo_list_datatable');

function todo_menu() {
	add_options_page( 'TodoList Options', 'Todo List Settings', 'manage_options', 'todo-plugin-options', 'todo_list_options' );
}

function todo_list_datatable(){

	?>
		<hr>
			<div class="row">
				   <div class="col-md-12 col-sm-12 col-xs-12">
				   <h2>Dynamic Pills</h2>
					<p>To make the tabs toggleable, add the data-toggle="pill" attribute to each link. Then add a .tab-pane class with a unique ID for every tab and wrap them inside a div element with class .tab-content.</p>
					<ul class="nav nav-pills">
						<li class="active"><a data-toggle="pill" href="#home">All todos</a></li>
						<li><a data-toggle="pill" href="#menu1">Completed Todos</a></li>
						<li><a data-toggle="pill" href="#menu2">Pending Todos</a></li>
					</ul>
					
					<div class="tab-content">
						<div id="home" class="tab-pane fade in active">
								<h3>ALL Todos</h3>
								<table class="table table-condesed" id="table_todos">
									<thead>
										<tr><th>ID_User</th><th>ID</th><th>Title</th><th>Status</th><th>Type</th><th>Completed</th></tr>
									</thead>
									<tbody>
										
										<?php
											global $wpdb,$table_prefix;

											$sql_check = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish'";
											$results = $wpdb->get_results($sql_check,ARRAY_A);	
											
											foreach($results as $value){
												?>
												<tr>
													<td><?php echo $value['ID'] ?></td>
													<td><?php echo $value['post_author'] ?></td>
													<td><?php echo $value['post_title'] ?></td>
													<td><?php echo $value['post_status'] ?></td>
													<td><?php echo $value['post_type'] ?></td>
													<td>
														<?php if($value['completed']==0||$value['completed']==false): ?>
														<input type='checkbox'/>
														<?php else: ?>
														<input type='checkbox' checked/>
														<?php endif; ?>
													</td>
												</tr>
												<?php
											}

										?>
									</tbody>
								</table>	
						</div>
						<div id="menu1" class="tab-pane fade">
						<h3>COMPLETED Todos</h3>
						<table class="table table-condesed" id="table_todos_completed">
							<thead>
								<tr><th>ID_User</th><th>ID</th><th>Title</th><th>Status</th><th>Type</th><th>Completed</th></tr>
							</thead>
							<tbody>
								
								<?php
									global $wpdb,$table_prefix;

									$sql_check = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish' AND completed = 'true'";
									$results = $wpdb->get_results($sql_check,ARRAY_A);	
									
									foreach($results as $value){
										?>
										<tr>
											<td><?php echo $value['ID'] ?></td>
											<td><?php echo $value['post_author'] ?></td>
											<td><?php echo $value['post_title'] ?></td>
											<td><?php echo $value['post_status'] ?></td>
											<td><?php echo $value['post_type'] ?></td>
											<td>
												<?php if($value['completed']==0||$value['completed']==false): ?>
												<input type='checkbox'/>
												<?php else: ?>
												<input type='checkbox' checked/>
												<?php endif; ?>
											</td>
										</tr>
										<?php
									}

								?>
							</tbody>
						</table>	
						</div>
						<div id="menu2" class="tab-pane fade">
							<h3>PENDING TODOS</h3>
							<table class="table table-condesed" id="table_todos_completed">
							<thead>
								<tr><th>ID_User</th><th>ID</th><th>Title</th><th>Status</th><th>Type</th><th>Completed</th></tr>
							</thead>
							<tbody>
								
								<?php
									global $wpdb,$table_prefix;

									$sql_check = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish' AND completed = 0;";
									$results = $wpdb->get_results($sql_check,ARRAY_A);	
									
									foreach($results as $value){
										?>
										<tr>
											<td><?php echo $value['ID'] ?></td>
											<td><?php echo $value['post_author'] ?></td>
											<td><?php echo $value['post_title'] ?></td>
											<td><?php echo $value['post_status'] ?></td>
											<td><?php echo $value['post_type'] ?></td>
											<td>
												<?php if($value['completed']==0||$value['completed']==false): ?>
												<input type='checkbox'/>
												<?php else: ?>
												<input type='checkbox' checked/>
												<?php endif; ?>
											</td>
										</tr>
										<?php
									}

								?>
							</tbody>
						</table>	
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<script
  			src="https://code.jquery.com/jquery-3.6.0.min.js"
 			integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
 			crossorigin="anonymous"></script>
			 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#table_todos").DataTable();
				$("#table_todos_completed").DataTable();
				$("#table_todos_pending").DataTable();
			});
		</script>
	<?php
}

function todo_list_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	global $wpdb,$table_prefix;
	
	$url_endpoint = $wpdb->get_var('SELECT url_endpoint FROM '.$table_prefix.'todolist_settings');
	
	?>	
		<div class="loader_bkg">
			<div class="loader"></div>
			<h3>Wait until the end of procedure, it could take a couple of minutes</h3>
			<p>In case you are to going to leave this section during the process, the procedure can be incompleted!</p>
		</div>
		<div class="wrap opacity-0">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-success alert-dismissible" id="success_load_api_rest">
						<a href="#success_load_api_rest" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Success!</strong> API REST insert todos with success into DB and Publish! <hr>
						<p>Go to <strong>Todo List > All Todos</strong> to see the loaded items!</p>
					</div>
					<div class="alert alert-danger alert-dismissible" id="error_url">
						<a href="#error_url" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Error!</strong>Something went wrong! Try to insert a valid URL
					</div>
				</div>
			</div>
			<div class="row jumbotron">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h4>TODO LIST Options</h4>
						<label>Insert API Endopoit of TodoList</label>
						<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>db_controller.php" id="form_url_endpoint">
							<div class="flex-container">
									<input type="url" id="uri_json" class="form-control" value="<?php echo $url_endpoint; ?>"/>
									<button class="btn btn-primary" type="submit">Fetch & Publish</button>
							</div>
						</form>
					</div>
				
			</div>
	<?php
}

/* ————————————     CREATE CUSTOM POST FIELD TO MANAGE TODOS ————————————- */


add_action('init', 'create_cpt_todo_list');


function create_cpt_todo_list() {


    $labels = array(
			'name'               => __('Todo List' , 'todo-list-plugin'),
			'singular_name'      => __('Todo' , 'todo-list-plugin'),
			'add_new'            => __('Add Todo', 'todo-list-plugin'),
			'add_new_item'       => __('Add New Todo' , 'todo-list-plugin'),
			'edit_item'          => __('Edit Todo', 'todo-list-plugin'),
			'new_item'           => __('New Todo', 'todo-list-plugin'),
			'all_items'          => __('All Todos', 'todo-list-plugin'),
			'search_items'       => __('Search Todo' , 'todo-list-plugin'),
			'not_found'          => __('Todo Not found', 'todo-list-plugin'),
			'not_found_in_trash' => __('Todo Not found in the trash', 'todo-list-plugin'),
	);

    $args = array(
					'labels'    		 => $labels,
					'public'             => true,
					'rewrite'            => array('slug' => 'todo-list'),
					'has_archive'        => true,
					'hierarchical'       => true,
					'menu_position'      => 22,
					'menu_icon' 		 => 'dashicons-yes',
					'supports'           => array(
					'title',
					'excerpt',
					'page-attributes', 
					'author',
					'custom-field'
	)


    );


   	register_post_type('todo', $args);

}

add_action('wp_ajax_save_url_endpoint', 'save_url_endpoint');
add_action('wp_ajax_nopriv_save_url_endpoint', 'save_url_endpoint');

function save_url_endpoint(){

   	global $wpdb,$table_prefix;

   	if(isset($_REQUEST['uri_json'])&&$_REQUEST['uri_json']!==null){
   		if(filter_var($_REQUEST['uri_json'], FILTER_VALIDATE_URL)){
   			
   			$wpdb->query("DELETE FROM ".$table_prefix."todolist_settings");
	   		
	   		$qry = $wpdb->insert( 
			    $table_prefix.'todolist_settings', 
			    array( 
			        'url_endpoint' => $_REQUEST['uri_json'] 
			    )
			);
	   	}
   	}
 
   exit();

}

add_action('wp_ajax_insert_todo_in_db', 'insert_todo_in_db');
add_action('wp_ajax_nopriv_insert_todo_in_db', 'insert_todo_in_db');

function insert_todo_in_db(){
	

	global $wpdb,$table_prefix;

	$id = isset($_REQUEST['id']) ? $_REQUEST['id']:null;
	$idUser = isset($_REQUEST['idUser']) ? $_REQUEST['idUser']:null;
	$title =  isset($_REQUEST['title']) ? $_REQUEST['title']:null;
	$status = isset($_REQUEST['status']) ? $_REQUEST['status']:null;
	$status_todo = isset($_REQUEST['status_todo']) ? $_REQUEST['status_todo']:null;
	
	$conta = 0;	

	$sql_check = $sql_check = "SELECT COUNT(*) as conta FROM ".$table_prefix."posts WHERE post_type='todo' AND post_title ='".$title."';";
	$row = $wpdb->get_results($sql_check,ARRAY_A);
	$conta = $row[0]['conta'];

	if($conta==0){
		$query = "INSERT INTO ".$table_prefix."posts (ID, post_author, post_title, post_status, post_type, completed) VALUES ('".$id."', '".$idUser."', '".$title."','".$status."','todo','".$status_todo."')";
		$check = $wpdb->query($query);
	}

	exit();
}
add_action('wp_ajax_get_all_todos', 'get_all_todos');
add_action('wp_ajax_nopriv_get_all_todos', 'get_all_todos');

function get_all_todos(){
	
	global $wpdb,$table_prefix;

	$sql_check = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish'";
	$results = $wpdb->get_results($sql_check,ARRAY_A);
	$todos = array();
	
	foreach($results as $value){
		array_push($todos,array(
			'id'=> $value['ID'],
			'userId'=>$value['post_author'],
			'title' => $value['post_title'],
			'status'=>$value['post_status'],
			'type'=>$value['post_type'],
			'completed'=>$value['completed']
		));
	}
	
	header('Content-Type: application/json');
	echo json_encode($todos);
	//echo $todos;
	exit();
}