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


$(document).on("dblclick", "#content", function(){

    var current = $(this).text();
    $("#content").html('<textarea class="form-control" id="newcont" rows="5">'+current+'</textarea>');
    $("#newcont").focus();
    
    $("#newcont").focus(function() {
        console.log('in');
    }).blur(function() {
         var newcont = $("#newcont").val();
         $("#content").text(newcont);
    });

})

$('[data-toggle="tooltip"]').tooltip(); 

//not working yet
function scrollTo(perc){
	window.scrollTo(0,document.querySelector(".grid-bottom-tabs").scrollHeight);
}