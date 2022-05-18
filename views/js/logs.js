moment.locale('fr');

$body = $("body");
$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});

/*$("#selectall").click(function () {
    var checkAll = $("#selectall").prop('checked');
        if (checkAll) {
            $(".case").prop("checked", true);
        } else {
            $(".case").prop("checked", false);
        }
    });*/



function getAll() {
	/*$('#data').DataTable({
		"language" : {
			"url" : "vendor/datatables/dataTable-french.json"
		},
		"ajax": {
		    "url": "http://localhost/lbc/api/logs/list.php",
		    "beforeSend": function (request) {
		        request.setRequestHeader("Authorization", "Bearer " + localStorage.token);
		    },
		    "dataSrc": function ( json ) {
                //Make your callback here.
                //alert("Done!");
                return json.data;
            }  
	  	}
	});*/

	$('#data tbody').remove();
	$.ajax({
	    url: "http://localhost/lbc/api/logs/list.php",
	    type:'get',
     	"beforeSend": function (request) {
	        request.setRequestHeader("Authorization", "Bearer " + localStorage.token);
	    },
	    dataType: 'json',
	    success: function(response) {
			var tableau = `<tbody>`;
			for (var i = 0; i < response.length; i++) {
		      	tableau += `
		      		<tr class="tr-shadow">
		      			<td></td>
		      			<td>${response[i].id_user}</td> 
		      			<td>${response[i].username}</td> 
		      			<td> <span class="block-email">${response[i].ip_address}</span> </td> 
		      			<td> <span class="block-email">${response[i].id_visitor}</span> </td> 
		      			<td>${moment(response[i].date_login).fromNow()}</td>
		      			<td> 
		      				<div class="table-data-feature">
                                <button class="item delete"  data-toggle="tooltip" data-placement="top" title="Delete" onclick="removeItem(${response[i].id})"> <i class="zmdi zmdi-delete delete"></i></button>
		      				</div>

		      			</td>
		      		</tr> 
		      		<tr class="spacer"></tr>`;
			}

			
			tableau += `<tr class="noresults text-center"><td colspan="7"> Aucun résultat </td></tr></tbody>`;

			$('#data').append(tableau);

			$('input#search_item').quicksearch('table tbody tr:not(.spacer)', {
				'delay': 100,
				'loader': 'span.loading',
				'noResults': 'tr.noresults'
			});
			// $(".case").click(function(){
			//     if($(".case").length == $(".case:checked").length) {
			//         $("#selectall").prop("checked", true);
			//     } else {
			//         $("#selectall").prop("checked", false);
			//     }
			//  });

		},

	    error: function(xhr) {
			if(xhr.responseJSON){
	    		alert('Erreur code ' + xhr.responseJSON.status + ' : ' + xhr.responseJSON.message);
	    	} else {
	    		alert('Erreur de connexion au serveur')
	    	}
	    	localStorage.clear(); window.open('http://localhost/lbc/views/','_self');
	    }
	});
}

function removeItem(id){
	if(confirm('Vous voulez vraiment supprimer?'))
	{
	$.ajax({
		    url: "http://localhost/lbc/api/logs/update.php?action=delete",
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

function logout(){
	$.ajax({
	    url: "http://localhost/lbc/api/admin.php?action=logout",
	    type:'get',
	    success: function(response) {
	    	if (typeof response['success'] !== 'undefined') {
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

function clearItem(){
	if(confirm('Vous voulez vraiment tous supprimer?'))
	{
	$.ajax({
		    url: "http://localhost/lbc/api/logs/update.php?action=clear",
		    type:'post',
		    dataType: 'json',
		    headers : {
		    	'Authorization' : 'Bearer ' + localStorage.token
		    },
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

$(document).ready(function(){

	//$('#clients').DataTable()

	getAll();
})
