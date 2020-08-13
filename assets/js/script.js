$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});


// const baseUrl = 'http://localhost/mis_inventory/dashboard/view_dashboard';

// $(document).ready(function(){
	
// 	$('#select_filter').change(function(){
// 		$.ajax({
// 			url: baseUrl,
// 			type: 'POST',
// 			data: {keyword: $(this).val()},
// 			dataType: 'json',
// 			beforeSend: function(e){
// 				if(e && e.overrideMimeType){
// 					e.overrideMimeType("application/json;charset=UTF-8");
// 				}
// 			},
// 			success: function(response){
// 				console.log(response);
// 			},
// 			error: function(xhr, ajaxOptions, thrownError){
// 				console.log(thrownError);
// 			}  
// 		})
// 	})
// })
