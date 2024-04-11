jQuery(document).ready(function ($) {
	$("#postForm").submit(function (e) {
		e.preventDefault() // Prevent normal form submission
		var formData = new FormData(this)

		$.ajax({
			url: ajax_object.ajax_url,
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				$("#message").html(response) // Show response message
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText)
			},
		})
	})
})
