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

add_action( 'admin_menu', 'todo_menu' );

add_shortcode( 'todo-table', 'todo_list_datatable');

function todo_menu() {
	add_options_page( 'TodoList Options', 'Todo List Settings', 'manage_options', 'todo-plugin-options', 'todo_list_options' );
}

function todo_list_datatable(){

	?>		
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
	<style type="text/css">
		.nav-pills{
			padding: 20px;
			margin-bottom: 50px;
		}
		.flex-grid{
			display: flex;
			align-items: center;
			justify-content: center;
			align-content: center;
		}
		#completed_todos tr td,.todo_completed {
			text-decoration: line-through!important;
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
	<div class="container todo_plugin_container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<ul class="nav nav-pills text-center flex-grid">
					<li class="active"><a data-toggle="pill" href="#home">All todos</a></li>
					<li><a data-toggle="pill" href="#menu1">Completed Todos</a></li>
					<li><a data-toggle="pill" href="#menu2">Pending Todos</a></li>
				</ul>
				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<table id="all_todos" class="table" style="width:100%">
					        <thead>
					            <tr>
					                <th>id</th>
					                <th>userId</th>
					                <th>title</th>
					                <th>status</th>
					            </tr>
					        </thead>
					        <tbody></tbody>
					    </table>
					</div>
					<div  id="menu1" class="tab-pane fade">
					    <table id="completed_todos" class="table" style="width:100%">
					        <thead>
					            <tr>
					                <th>id</th>
					                <th>userId</th>
					                <th>title</th>
					                <th>status</th>
					            </tr>
					        </thead>
					        <tbody></tbody>
					    </table>
					</div>
					<div id="menu2" class="tab-pane fade">
					    <table id="pending_todos" class="table" style="width:100%">
					        <thead>
					            <tr>
					                <th>id</th>
					                <th>userId</th>
					                <th>title</th>
					                <th>status</th>
					            </tr>
					        </thead>
					        <tbody></tbody>
					    </table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>	
	<script type="text/javascript">
		
			var all_todos = [];
			var pending_todos = [];
			var completed_todos = [];

			const call = async() => {
				
				const req = await fetch("<?php echo admin_url( 'admin-ajax.php' )."?action=get_all_todos";?>");
				const res = await req.json();
				
				all_todos = res;
				pending_todos = res.filter((x)=>x.completed === '0');
				completed_todos = res.filter((x)=>x.completed === '1');

				all_todos.forEach((todo) => {
					
					var html =  "";
						
						if(todo.completed=='0')
							html += "<tr style='width:100%;' id='"+todo.id+"'>";
						else
							html += "<tr class='todo_completed' style='width:100%;' id='"+todo.id+"'>";

						html += "<td>"+todo.id+"</td>";
						html += "<td>"+todo.userId+"</td>";
						html += "<td>"+todo.title+"</td>";
						if(todo.completed==='0')
							html += "<td><input type='checkbox' onclick='makeAsDone("+todo.id+")'></td>";
						else
							html += "<td><input type='checkbox' checked></td>";
						html += "</tr>";

					$("#all_todos tbody").append(html);

				});

				completed_todos.forEach((todo) => {
					
					var html =  "<tr style='width:100%;' id='"+todo.id+"'>";
						html += "<td>"+todo.id+"</td>";
						html += "<td>"+todo.userId+"</td>";
						html += "<td>"+todo.title+"</td>";
						html += "<td><input type='checkbox' checked></td>";
						html += "</tr>";

					$("#completed_todos tbody").append(html);
			
				});

				pending_todos.forEach((todo) => {
					
					var html =  "<tr style='width:100%;' id='"+todo.id+"'>";
						html += "<td>"+todo.id+"</td>";
						html += "<td>"+todo.userId+"</td>";
						html += "<td>"+todo.title+"</td>";
						html += "<td><input type='checkbox' onclick='makeAsDone("+todo.id+")'></td>";
						html += "</tr>";

					$("#pending_todos tbody").append(html);

				});
				
				$('#all_todos').DataTable({"aaSorting": [[ 0, "desc" ]]});
				$('#completed_todos').DataTable({"aaSorting": [[ 0, "desc" ]]});
				$('#pending_todos').DataTable({"aaSorting": [[ 0, "desc" ]]});
				
			}		


			function makeAsDone(id){
							$.ajax(	
							{	
								url: "<?php echo  admin_url( 'admin-ajax.php' ); ?>",
								type:"POST",
								dataType:"text",
								data:"action=check_todo_as_completed&id="+id, 
								success: function(result){
									var get_row = $("#all_todos tbody #"+id).html();
									$("#all_todos tbody #"+id).addClass('todo_completed');
									$("#pending_todos tbody #"+id).hide();
									$("#completed_todos tbody ").prepend("<tr class='todo_completed'>"+get_row+"</tr>");
									$("#completed_todos tbody #"+id).find("input").prop("checked",true);
									location.reload();
								},
								error:function(){
							
								
								},
								complete:function(){
									
								}
						});
						}

			$(document).ready(function(){
				call();
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

	$sql_check = "SELECT COUNT(*) as conta FROM ".$table_prefix."posts WHERE post_type='todo' AND post_title ='".$title."';";
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