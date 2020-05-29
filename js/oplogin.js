$(document).ready(function(){


	// Select/Deselect checkboxes
	var checkbox = $('table tbody input[type="checkbox"]');
	$("#selectAll").click(function(){
		if(this.checked){
			checkbox.each(function(){
				this.checked = true;                        
			});
		} else{
			checkbox.each(function(){
				this.checked = false;                        
			});
		} 
	});
	checkbox.click(function(){
		if(!this.checked){
			$("#selectAll").prop("checked", false);
		}
	});

	 $(".close").click(function(){
        $("#myAlert").alert('close');
    });

	window.setTimeout(function() {
    	$(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    		});
		}, 3000);
});

$(document).on("click", ".edit", function () {
     var myBookId = $(this).data('id');
     $(".modal-body #user_edit_mobile").val( myBookId );
});

$(document).on("click", ".delete", function () {
     var myBookId = $(this).data('id');
     $(".modal-body #delete_mobile").val( myBookId );
});

$(document).on("click", ".add", function () {
     var myBookId = $(this).data('id');
     $(".modal-body #mobile_add_bill").val( myBookId );
});
