var form = $('#add-category').formValid({
	fields: {
		"name": {
			"required": true, 
			"tests": [
				{
					"type": "null", 
					"message": "Please Enter Category Name"
				}
			]
		},
		"category_image": {
			"required": true,
			"tests": [
				{
					"message": "Not Uploaded Image"
				}
			]
		},
		"category_color": {
			"required": true,
			"tests": [
				{
					"message": "Not selected Color"
				}
			]
		}
	}
});

form.keypress(300);

$('button[type="submit"]').click(function() {
	form.test();
	if (form.errors() == 0) {
		return true;
	}
	return false;
});