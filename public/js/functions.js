function showEnlistment(obj) {
	$('.enlistform').hide()
	$('.enlistbutton').show()
	autocomplete(document.getElementById("ac_"+$(obj).attr("data-date")), users);
		$('#E_'+$(obj).attr("data-date")).toggle();
		$(obj).toggle();
		//console.log('E_'+id)
}

