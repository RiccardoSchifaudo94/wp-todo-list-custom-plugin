$(document).ready(function(){
	$("#form_url_endpoint button").click(function(e){
		e.preventDefault();
		
		if(isURL($("#uri_json").val()))
			saveUrlEndpointAPI();
		else
			$("#error_url").show();	
	
	})
	$(".loader_bkg").hide();
	$(".wrap").removeClass("opacity-0");
	$("#success_load_api_rest").hide();
	$("#error_url").hide();
	
	$(".close").click(function(){
		$(".alert").hide();
	});
	
});

$(document).ajaxStop(function () {
	$(".loader_bkg").hide();
	$(".wrap").removeClass("opacity-0");
	$("#success_load_api_rest").show();
});
  

function saveUrlEndpointAPI(){
	
	const uri = document.querySelector("#uri_json");

	$(".loader_bkg").show();
	$(".wrap").addClass("opacity-0");
	
	$.ajax(	{	
			url: "admin-ajax.php",
			type:"POST",
			dataType:"text",
			data:"action=save_url_endpoint&uri_json="+encodeURIComponent($("#uri_json").val()), 
		   	success: function(result){
  			},
			complete:function(){
			},
			error:function(){
				$("#error_url").show();	
			}
  	});



	getTodosFromAPI = async () => {
							    
			try {
					const response = await fetch(uri.value);
					const results = await response.json();
					   		
					results.map((item)=>{
						var bool_todo = 0;
						
						if(item.completed==true)
							bool_todo = 1;

						insertTodoInDB(item.userId,item.id,item.title,'publish',bool_todo);

					});

			} catch (error) {
					$("#error_url").show();	
			}
  	};

  	getTodosFromAPI(); 
}

function insertTodoInDB(idUser,idTodos,title,status,statusTodo){

	$.ajax({	
			url: "admin-ajax.php",
			type:"POST",
			dataType:"text",
			data:"action=insert_todo_in_db&idUser="+idUser+"&id="+idTodos+"&title="+encodeURI(title)+"&status="+encodeURI(status)+"&status_todo="+encodeURI(statusTodo), 
		   	success: function(result){
				$("#success_load_api_rest").show();
  			},
			error:function(){
				$("#error_url").show();	
			},
			complete:function(){
			}
  	});
}

function isURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return pattern.test(str);
}
