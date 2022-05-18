moment.locale('fr');

$body = $("body");
$(document).on({
    ajaxStart: function() { $('button[type="submit"]').html(` <i class="fa fa-spinner fa-spin mr-2"></i> Sauvegarder `); $body.addClass("loading");    },
     ajaxStop: function() { $('button[type="submit"]').html(` Sauvegarder `); $body.removeClass("loading"); }    
});

/*$("#selectall").click(function () {
    var checkAll = $("#selectall").prop('checked');
        if (checkAll) {
            $(".case").prop("checked", true);
        } else {
            $(".case").prop("checked", false);
        }
    });*/


$("#g_num_serie").click(function () {
    var checked = $("#g_num_serie").prop('checked');
        if (checked) {
            $("#num_serie_group").remove();
        } else {
        	setNumSerie();
        }
    });

function getAll() {
	$('#data tbody').remove();
	$.ajax({
	    url: "http://localhost/lbc/api/users/list.php",
	    type:'get',
	    headers : {
	    	'Authorization' : 'Bearer ' + localStorage.token
	    },
	    dataType: 'json',
	    success: function(response) {
			var tableau = `<tbody>`;
			for (var i = 0; i < response.length; i++) {
		      	tableau += `
		      		<tr class="tr-shadow">
		      			<td></td>
		      			<td>${response[i].id}</td> 
		      			<td>${response[i].username}</td> 
		      			<td><span class="block-email">${response[i].num_serie}</span></td> 
		      			<td>${moment(response[i].date_debut).format('LL')}</td> 
		      			<td>${moment(response[i].date_fin).format('LL')}</td> 
		      			<td> ${(response[i].status==1) ? '<span class="status--process"> Activé </span>' : '<span class="status--denied">  Désactivé </span>'} </td> 
		      			<td> 
		      				<div class="table-data-feature">  
		      					<button class="item edit" data-toggle="tooltip" data-placement="top" title="Edit" onclick="editItem(${response[i].id})"> <i class="zmdi zmdi-edit"></i> </button>
                                <button class="item delete"  data-toggle="tooltip" data-placement="top" title="Delete" onclick="removeItem(${response[i].id})"> <i class="zmdi zmdi-delete delete"></i></button>
		      				</div>

		      			</td>
		      		</tr> 
		      		<tr class="spacer"></tr>`;
			}
			tableau += `<tr class="noresults text-center"><td colspan="8"> Aucun résultat </td></tr></tbody>`;

			$('#data').append(tableau);

			$('input#search_item').quicksearch('table tbody tr:not(.spacer)', {
				'delay': 100,
				'loader': 'span.loading',
				'noResults': 'tr.noresults'
			});
			/*$(".case").click(function(){
			    if($(".case").length == $(".case:checked").length) {
			        $("#selectall").prop("checked", true);
			    } else {
			        $("#selectall").prop("checked", false);
			    }

			 });*/
		},

	    error: function(xhr) {
			if(xhr.responseJSON){
	    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
	    	} else {
	    		alert('Erreur de connexion au serveur');
	    	}

	    	localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
	    }
	});
}

function addModal(){

	var today = new Date().toISOString().split('T')[0];
	$('#g_num_serie').prop("disabled", false);
	$("#num_serie_group").remove();
	$('#username').val('');
	$('#num_serie').val('');
	$("#date_debut").val(today);
	$("#date_fin").val(today);
	$('#status').attr("checked",false);

	$('#staticModalLabel').text('Ajouter un utilisateur');
	$('#action').val('add');
	$('#addModal').modal('show');
}

function addItem(){
	var data = {
		username : $('#username').val(),
		num_serie : $('#num_serie').length ? $('#num_serie').val() : '',
		date_debut : $('#date_debut').val(),
		date_fin : $('#date_fin').val(),
		status : $('#status').is(":checked") ? 1 : 0
	}

	$.ajax({
	    url: "http://localhost/lbc/api/users/update.php?action=add",
	    type:'post',
	    data : data,
	    headers : {
	    	'Authorization' : 'Bearer ' + localStorage.token
	    },
	    dataType: 'json',
	    success: function(response) {
	    	getAll();
	    	$('#addModal').modal('hide');
		},
		complete : function(res) {
	    	console.log(res);
		},
	    error: function(xhr) {
	    	if(xhr.responseJSON){
	    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
	    	} else {
	    		alert('Erreur de connexion au serveur');
	    		localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
	    	}
	    }
	});
}

function removeItemMultiple(){
	var ids = $('.case:checkbox:checked').map(function() {
	    return this.value;
	}).get();

	if(ids.length<1) return;
	
	if(confirm(`Vous voulez vraiment supprimer l'utilisateur n° ${ids.join(',')} ?`))
	{
	$.ajax({
		    url: "http://localhost/lbc/api/users/update.php?action=delete_multiple",
		    type:'post',
		    data : { id : ids.join(',') },
		    headers : {
		    	'Authorization' : 'Bearer ' + localStorage.token
		    },
		    dataType: 'json',
		    success: function(response) {
		    	getAll();
			},

		    error: function(xhr) {
		    	if(xhr.responseJSON){
		    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
		    	} else {
		    		alert('Erreur de connexion au serveur');
		    		localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
		    	}
		    }
		});
	}
}

function removeItem(id){
	if(confirm('Vous voulez vraiment supprimer?'))
	{
	$.ajax({
		    url: "http://localhost/lbc/api/users/update.php?action=delete",
		    type:'post',
		    data : { id : id },
		    headers : {
		    	'Authorization' : 'Bearer ' + localStorage.token
		    },
		    dataType: 'json',
		    success: function(response) {
		    	getAll();
			},

		    error: function(xhr) {
		    	if(xhr.responseJSON){
		    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
		    	} else {
		    		alert('Erreur de connexion au serveur');
		    		localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
		    	}
		    }
		});
	}
}

function editItem(id){
	$.ajax({
	    url: "http://localhost/lbc/api/users/list.php?id="+id,
	    type:'get',
	    data : { id : id },
	    headers : {
	    	'Authorization' : 'Bearer ' + localStorage.token
	    },
	    dataType: 'json',
	    success: function(response) {
	    	$('#staticModalLabel').text('Modifier un utilisateur');
	    	$('#g_num_serie').prop("disabled", true);
	    	setNumSerie();
    		$('#username').val(response[0].username);
			$('#num_serie').val(response[0].num_serie);
			$('#date_debut').val(response[0].date_debut);
			$('#date_fin').val(response[0].date_fin);
			if(response[0].status=='1') 
				$('#status').attr("checked",true);
			else 
				$('#status').attr("checked",false);
			$('#action').val('update');
			$('#identity').val(response[0].id);
			$('#addModal').modal('show');

		},

	    error: function(xhr) {
	    	if(xhr.responseJSON){
	    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
	    	} else {
	    		alert('Erreur de connexion au serveur');
	    		localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
	    	}
	    }
	});
}

function updateItem(){
	var data = {
		id : $('#identity').val(),
		username : $('#username').val(),
		num_serie : $('#num_serie').val(),
		date_debut : $('#date_debut').val(),
		date_fin : $('#date_fin').val(),
		status : $('#status').is(":checked") ? 1 : 0
	}
	
	$.ajax({
	    url: "http://localhost/lbc/api/users/update.php?action=update",
	    type:'post',
	    data : data,
	    headers : {
	    	'Authorization' : 'Bearer ' + localStorage.token
	    },
	    dataType: 'json',
	    success: function(response) {
	    	getAll();
	    	$('#addModal').modal('hide');
		},

	    error: function(xhr) {
	    	if(xhr.responseJSON){
	    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
	    	} else {
	    		alert('Erreur de connexion au serveur');
	    		localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
	    	}
	    }
	});
}

function setNumSerie(){
		$("#num_serie_group").remove();
    	var out = `
		<div id="num_serie_group" class="form-group ">
           <label for="num_serie" class="form-control-label">Numéro de série</label>
           <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                    </div>
                    <input type="text" id="num_serie" name="num_serie" placeholder="Numéro de série" class="form-control form-control-sm" value="">
                </div>
                <!-- <small class="form-text text-muted text-danger">This is a help text</small> -->
            </div>
    `; 
    $("#add_user > div.modal-body").append(out);
}

function logout(){
	$.ajax({
	    url: "http://localhost/lbc/api/admin.php?action=logout",
	    type:'get',
	    success: function(response) {
	    	if (typeof response['success'] !== 'undefined') {
                localStorage.clear();
                localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
            }
		},

	    error: function(xhr) {
	    	if(xhr.responseJSON){
	    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
	    	} else {
	    		alert('Erreur de connexion au serveur');
	    		localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
	    	}
	    }
	});
}

$(document).ready(function(){

	//$('#clients').DataTable()

	getAll();

	$('#add_user').on('submit',(e)=>{
		e.preventDefault();
		if($('#action').val()=='add'){
			addItem();
		} else {
			updateItem();
		}
		
	})
})
