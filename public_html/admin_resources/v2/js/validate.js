jQuery(document).ready(function($) {
	$('.form-group input, .form-group textarea').focusout(function(event) {
		if ($(this).hasClass('field-required')) {
			if (!$(this).val()) {
				$(this).closest('.form-group').find('.field-required-sup').css('color', 'red');
				$(this).css('border-color', '#886bb5');
			} else {
				$(this).closest('.form-group').find('.field-required-sup').css('color', '#886bb5');
				$(this).css('border-color', '#E5E5E5');
			}
		}
	});

	$('.form-group select').on('change', function(event) {
		if ($(this).hasClass('field-required')) {
			if (!$(this).val() || $(this).val() == 0) {
				console.log('no');
				$(this).closest('.form-group').find('.select2-selection').css('border', '1px solid #886bb5');
				$(this).closest('.form-group').find('.field-required-sup').css('color', 'red');
			} else {
				console.log('yes');
				$(this).closest('.form-group').find('.select2-selection').css('border', '1px solid #E5E5E5');
				$(this).closest('.form-group').find('.field-required-sup').css('color', '#886bb5');
			}
		}
	});

	$(document).on('focusout', '.form-group.multi-fields-field input', function() {
		let form_group = $(this).closest('.form-group.multi-fields-field');
		let field_inputs = $(form_group).find('input.field-key');
		if (field_inputs.length) {
			field_inputs.each(function(key, field) {
				if (!$(field).val()) {
					$(field).css('border', '1px solid #886bb5');
					$(field).siblings('.field-value').css('border', '1px solid #886bb5');
				} else {
					$(field).css('border', '1px solid #E5E5E5');
					$(field).siblings('.field-value').css('border', '1px solid #E5E5E5');
				}
			});
		}
	});

	// $(document).on('focusout', '.editor-textarea', function(event) {
	// 	console.log($(this).val());
	// });

	$(document).on('focusout', '.form-group.multi-input-field input', function() {
		let form_group = $(this).closest('.form-group.multi-input-field');
		let field_inputs = $(form_group).find('input');
		if (field_inputs.length) {
			field_inputs.each(function(key, field) {
				if (!$(field).val()) {
					$(field).css('border', '1px solid #886bb5');
				} else {
					$(field).css('border', '1px solid #E5E5E5');
				}
			});
		}
	});
	
});

function validate(obj){


	$('.fieldset-form-alert-box').css("display", "none");
	let errors = 0;
	var errors_data = [];
	var required_fields_data = $(obj).find('.field-required');
	
	required_fields_data.each(function(index, el) {
		if ($(el).hasClass('textfield-field')) {
			if (!$(el).val()) {
				errors_data.push($(el).data('error-text'));
				$(el).css('border-color', '#886bb5');
				$(el).closest('.form-group').find('.field-required-sup').css('color', 'red');
				errors++;
			}
		} else if($(el).hasClass('editor-textarea')) {
			tinymce.triggerSave();
			if (!$(el).val() || $(el).val() == '') {
				errors_data.push($(el).closest('.form-group').find('textarea').data('error-text'));
				$(el).closest('.form-group').find('.mce-tinymce.mce-container.mce-panel').css('border', '1px solid #886bb5');
				$(el).closest('.form-group').find('.field-required-sup').css('color', 'red');
				errors++;
			} else {
				$(el).closest('.form-group').find('.mce-tinymce.mce-container.mce-panel').css('border', '1px solid #E5E5E5');
			}
		} else if($(el).hasClass('field-select')) {
			if ((!$(el).val()) || $(el).val() == 0) {
				errors_data.push($(el).data('error-text'));
				$(el).closest('.form-group').find('.select2-selection').css('border', '1px solid #886bb5');
				$(el).closest('.form-group').find('.field-required-sup').css('color', 'red');
				errors++;
			}
		} else if($(el).hasClass('multi-input-field')) {
			let no_inputs = 0;
			let field_inputs = $(el).find('input');
			if (!field_inputs.length) {
				no_inputs++;
			}  else {
				field_inputs.each(function(key, field) {
					if (!$(field).val()) {
						$(field).css('border', '1px solid #886bb5');
						no_inputs++;
					}
				});
			}
			if (no_inputs) {
				errors_data.push($(el).data('error-text'));
				$(this).find('.field-required-sup').css('color', 'red');
				errors++;
			}
		} else if($(el).hasClass('multi-fields-field')) {
			let no_inputs = 0;
			let field_inputs = $(el).find('input.field-key');

			if (!field_inputs.length) {
				no_inputs++;
			}  else {
				field_inputs.each(function(key, field) {
					if (!$(field).val()) {
						$(field).css('border', '1px solid #886bb5');
						$(field).siblings('.field-value').css('border', '1px solid #886bb5');
						no_inputs++;
					}
				});
			}
			if (no_inputs) {
				$(this).find('.field-required-sup').css('color', 'red');
				errors_data.push($(el).data('error-text'));
				errors++;
			}
		} else if($(el).hasClass('fileInput')) {
			if (!$(el).val()) {
				if (!$(el).closest('.form-group').find('.photo-preview').length) {
					errors_data.push($(el).data('error-text'));
					$(el).closest('.form-group').find('.field-required-sup').css('color', 'red');
					errors++;
				}
			}
		}

	});

	if (errors) {
		$('.errors-data-container').text(errors_data.join(', '));
		$("html, body").animate({ scrollTop: 0 }, "slow");
		$('.fieldset-form-alert-box').css("display", "block");
		return false;
	}

}