/* liteUploader v1.3.1 | https://github.com/burt202/lite-uploader | Aaron Burtnyk (http://www.burtdev.net) */

$.fn.liteUploader = function (userOptions)
{
	var defaults = { script: null, allowedFileTypes: null, maxSizeInBytes: null, typeMessage: null, sizeMessage: null, before: function(){}, each: function(file, errors){}, success: function(response){}, fail: function(jqXHR){} },
		options = $.extend(defaults, userOptions);

	this.change(function ()
	{
		var i, formData = new FormData(), file, obj = $(this), errors = false, errorsArray = [];

		if (this.files.length === 0) { return; }

		options.before();

		for (i = 0; i < this.files.length; i += 1)
		{
			file = this.files[i];

			errorsArray = validateFile(file, options.allowedFileTypes, options.maxSizeInBytes, options.typeMessage, options.sizeMessage);
			if (errorsArray.length > 0) { errors = true; }

			formData.append(obj.attr('name') + '[]', file);

			options.each(file, errorsArray);
		}

		if (errors) { return; }
		if ($(this).attr('id')) { formData.append('liteUploader_id', $(this).attr('id')); }

		$.ajax(
		{
			url: options.script,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false
		})
		.always(function ()
		{
			obj.replaceWith(obj.val('').clone(true));
		})
		.done(function(response)
		{
			options.success(response);
		})
		.fail(function(jqXHR)
		{
			options.fail(jqXHR);
		});
	});

	function validateFile (file, allowedFileTypes, maxSizeInBytes, typeMessage, sizeMessage)
	{
		var errorsArray = [], message;

		if (allowedFileTypes && jQuery.inArray(file.type, allowedFileTypes.split(',')) === -1)
		{
			message = typeMessage || 'Incorrect file type (only ' + allowedFileTypes + ' allowed)';
			errorsArray.push({'type': 'type', 'message': message});
		}

		if (maxSizeInBytes && file.size > maxSizeInBytes)
		{
			message = sizeMessage || 'File size too big (max ' + maxSizeInBytes + ' bytes)';
			errorsArray.push({'type': 'size', 'message': message});
		}

		return errorsArray;
	}
};