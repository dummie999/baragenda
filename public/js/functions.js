function showEnlistment(obj) {
	$('.enlistform').hide()
	$('.enlistbutton').show()
	try {
		autocomplete(document.getElementById("ac_"+$(obj).attr("data-date")), users);
	}
	catch {
		1
	}
	$('#E_'+$(obj).attr("data-date")).toggle();
	$(obj).toggle();
		//console.log('E_'+id)
}

