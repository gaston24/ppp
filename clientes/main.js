function ppp(a) {
	
	$.ajax({
		url: 'pppUpdate.php',
		method: 'POST',
		data: {
			codClient: a
		},
		success: function(data) {
			// console.log(data);
		}
	});
	
}

function pedidos(a) {
	
	$.ajax({
		url: 'pedidosUpdate.php',
		method: 'POST',
		data: {
			codClient: a
		},
		success: function(data) {
			// console.log(data);
		}
	});
	
}
