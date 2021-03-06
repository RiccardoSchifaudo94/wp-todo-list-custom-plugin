<?php
/**
 * Plugin Name: Todo List Plugin
 * Plugin URI: https://www.the-shinobi-arts-of-eccentricity.com/
 * Description: Simple todo list plugin to manage your tasks.
 * Version: 1.0
 * Author: Riccardo Schifaudo
 * Author URI: https://www.the-shinobi-arts-of-eccentricity.com/
 */

wp_register_script('jquery-3','https://code.jquery.com/jquery-3.5.1.js', array('jquery'),'1.1', true);
wp_enqueue_script('jquery-3');

wp_register_script('datatable-js','https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js', array('jquery'),'1.1', true);
wp_enqueue_script('datatable-js');

wp_register_script('todo-app-js','/wp-content/plugins/todo-list-plugin/todo-app.js', array('jquery'),'1.1', true);
wp_enqueue_script('todo-app-js');

wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
wp_enqueue_style('prefix_bootstrap');

wp_register_style('todolist-css', '/wp-content/plugins/todo-list-plugin/style.css');
wp_enqueue_style('todolist-css');

wp_register_style('datatable-css','//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css');
wp_enqueue_style('datatable-css');



function installer(){
    include('todo-list-installer.php');
}
register_activation_hook(__file__, 'installer');

//add_action( 'the_content', 'todo_list_datatable' );

function welcome_todo_list ( $content ) {
     $content .= '<h3 class="text-center">Welcome in TODO List</h3>';
	 return $content;
}

add_action( 'admin_menu', 'todo_menu' );

add_shortcode( 'todo-table', 'todo_list_datatable');

function todo_menu() {
	add_options_page( 'TodoList Options', 'Todo List Settings', 'manage_options', 'todo-plugin-options', 'todo_list_options' );
}

function todo_list_datatable(){

	?>		
	<div class="todo_plugin_container">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></link>
			<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap.min.css"></link>
			<style>
				#table_todos_completed tr td,.todo_completed {
					text-decoration: line-through!important;
				}
				#table_todos strong, #table_todos_completed strong, #table_todos_pending strong{
					font-size:14px;
				}
				#table_todos p, #table_todos_completed p, #table_todos_pending p{
					font-size:10px;
				}
				.todo_plugin_container{
					text-align: center;
					margin: 0 auto;
					display: block;
					background-color: #f1f1f1;
					box-shadow: 3px 4px 15px #b5b5b5;
					padding: 20px;
					border-radius: 10px;
					border-bottom: 5px solid #6f6e6e;
				}
			</style>
			<div class="row">
				   <div class="col-md-12 col-sm-12 col-xs-12">
				   <h1>TODO List</h1>
				   <h2>What are you still doing for today?</h2>
					<p>Check what you have done or you have to do...</p>
					<ul class="nav nav-pills">
						<li class="active"><a data-toggle="pill" href="#home">All todos</a></li>
						<li><a data-toggle="pill" href="#menu1">Completed Todos</a></li>
						<li><a data-toggle="pill" href="#menu2">Pending Todos</a></li>
					</ul>
					
					<div class="tab-content">
						<div id="home" class="tab-pane fade in active">
								<h3>ALL Todos</h3>
								<div class="table-responsive">
									<table class="table table-condesed" id="table_todos">
										<thead>
											<tr><th>ID</th><th>ID_User</th><th>Title</th><th>Completed</th></tr>
										</thead>
										<tbody>
											
											<?php
												global $wpdb,$table_prefix;

												$sql_all_todos = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish'";
												$results_all_todos = $wpdb->get_results($sql_all_todos,ARRAY_A);	
												
												foreach($results_all_todos as $value){
													?>
													<tr class="<?php if($value['completed'] === "1") { echo "todo_completed"; }?> trow_all_<?php echo $value['ID']; ?>">
														<td><?php echo $value['ID'] ?></td>
														<td><?php echo $value['post_author'] ?></td>
														<td><strong><?php echo $value['post_title'] ?></strong><p><?php echo $value['post_excerpt'] ?></p></td>
														<td>
															<?php if($value['completed']==0||$value['completed']==false): ?>
															<input type='checkbox' onclick="markAsDone(<?php echo $value['ID']; ?>);"/>
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
						<div id="menu1" class="tab-pane fade">
						<h3>COMPLETED Todos</h3>
						<div class="table-responsive">
								<table class="table table-condesed" id="table_todos_completed">
									<thead>
										<tr><th>ID</th><th>ID_User</th><th>Title</th><th>Completed</th></tr>
									</thead>
									<tbody>
										
										<?php
											global $wpdb,$table_prefix;

											$sql_completed_todos = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish' AND completed = '1'";
											$results_completed_todos = $wpdb->get_results($sql_completed_todos,ARRAY_A);	
											
											foreach($results_completed_todos as $value){
												?>
												<tr>
													<td><?php echo $value['ID'] ?></td>
													<td><?php echo $value['post_author'] ?></td>
													<td><strong><?php echo $value['post_title'] ?></strong><p><?php echo $value['post_excerpt'] ?></p></td>
													<td>
														<input type='checkbox' checked/>
													</td>
												</tr>
												<?php
											}

										?>
									</tbody>
								</table>
							</div>	
						</div>
						<div id="menu2" class="tab-pane fade">
							<h3>PENDING Todos</h3>
							<div class="table-responsive">
								<table class="table table-condesed" id="table_todos_pending">
									<thead>
										<tr><th>ID</th><th>ID_User</th><th>Title</th><th>Completed</th></tr>
									</thead>
									<tbody>
										
										<?php
											global $wpdb,$table_prefix;

											$sql_check = "SELECT * FROM ".$table_prefix."posts WHERE post_type='todo' AND post_status='publish' AND (completed = 0 OR completed = 'false');";
											$results = $wpdb->get_results($sql_check,ARRAY_A);	
											
											foreach($results as $value){
												?>
												<tr class="trow_pending_<?php echo $value['ID']; ?>">
													<td><?php echo $value['ID'] ?></td>
													<td><?php echo $value['post_author'] ?></td>
													<td><strong><?php echo $value['post_title'] ?></strong><p><?php echo $value['post_excerpt'] ?></p></td>
													<td>
														<input type='checkbox' onclick="markAsDone(<?php echo $value['ID']; ?>);"/>
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
		</div>
		<script
  			src="https://code.jquery.com/jquery-3.6.0.min.js"
 			integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
 			crossorigin="anonymous"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
			<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>	
			<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap.min.js"></script>
			<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>						
		<script>
			$(document).ready(function(){
				$("#table_todos").DataTable({
    				responsive: true
				} );
				$("#table_todos_completed").DataTable({
    				responsive: true
				} );
				$("#table_todos_pending").DataTable({
    				responsive: true
				} );
			});
			function markAsDone(id){
				$.ajax(	
				{	
					url: "<?php echo  admin_url( 'admin-ajax.php' ); ?>",
					type:"POST",
					dataType:"text",
					data:"action=check_todo_as_completed&id="+id, 
					success: function(result){
						var get_row = $(".trow_pending_"+id).html();
						console.log(get_row);
						$(".trow_all_"+id).hide();
						$("#table_todos_completed tbody").prepend("<tr class='todo_completed'>"+get_row+"</tr>").find("input").first().prop("checked",true);
						$("#table_todos tbody").prepend("<tr class='todo_completed'>"+get_row+"</tr>").find("input").first().prop("checked",true);
						$(".trow_pending_"+id).hide();

					},
					error:function(){
				
					
					},
					complete:function(){
						
					}
			});
			}
		</script>
		</div>
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
		echo $query;
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
	exit();
}

add_action('wp_ajax_check_todo_as_completed', 'check_todo_as_completed');
add_action('wp_ajax_nopriv_check_todo_as_completed', 'check_todo_as_completed');

function check_todo_as_completed(){
	
	global $wpdb,$table_prefix;
	
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$sql_complete_todo = "UPDATE ".$table_prefix."posts SET completed = '1' WHERE ID = '".$id."';";
	$wpdb->query($sql_complete_todo);
	return true;
	exit();
}