 $(document).on( 'click', 'a.btn-modal', function(e){
    	e.preventDefault();

    	$.ajax({
			url: $(this).data("href"),
			dataType: "html",
			success: function(result){
				$('.container').html(result).modal('show');
			}
		});
    });