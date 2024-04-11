jQuery(document).ready(function ($) {
	$("#postForm").submit(function (e) {
		console.log("clicked")
		e.preventDefault() // Prevent normal form submission
		var formData = new FormData(this)

		$.ajax({
			url: ajax_object.ajax_url,
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function () {
				$("form").hide()
				$(".loader").show()
			},
			success: function (response) {
				$(".alert").show()
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText)
			},
			complete: function () {
				$("form").show()
				$(".loader").hide()
			},
		})
	})
})
