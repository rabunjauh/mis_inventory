$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});

// let select_filter = document.getElementById('select_filter');
// let table_all_items = document.getElementById('table_all_items');
// const baseUrl = '<?= base_url("dashboard/view_dashboard/") ?>';
// select_filter.addEventListener('change', function(){
// console.log(baseUrl + select_filter.value);

// 	//create ajax object
// 	let xhr = new XMLHttpRequest();

// 	// check if ajax is ready
// 	xhr.onreadystatechange = function(){
// 		if(xhr.readyState == 4 && xhr.status == 200){
// 			table_all_items.innerHTML = xhr.responseText;
// 		}
// 	}

// 	// ajax execution
// 	xhr.open('GET', baseUrl + select_filter.value, true);
// 	xhr.send();
// })

// $(document).ready(function(){
// 	const baseUrl = '<?= base_url("dashboard/view_dashboard/") ?>';
// 	$('#select_filter').change(function(){
// 	console.log(select_filter.val());
// 		$.ajax({
// 			url: baseUrl,
// 			type: 'POST',
// 			data: {keyword: $('#select_filter').val()},
// 			dataType: 'json',
// 			beforeSend: function(e){
// 				if(e && e.overrideMimeType){
// 					e.overrideMimeType("application/json;charset=UTF-8");
// 				}
// 			},
// 			success: function(response){
				
// 			}  
// 		})
// 	})
// })
