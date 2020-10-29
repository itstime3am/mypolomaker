$(function() {
	$('.cls-frm-edit #txt-option_male_fix_length').on('change', function() {
		var _val = getValue(this, '').trim();
		var _elem = $('.cls-frm-edit #chk-option_is_mfl');
		if (_val != '') {
			setValue(_elem, 1);
		} else {
			setValue(_elem, 0);
		}
	});
	$('.cls-frm-edit #txt-option_female_fix_length').on('change', function() {
		var _val = getValue(this, '').trim();
		var _elem = $('.cls-frm-edit #chk-option_is_ffl');
		if (_val != '') {
			setValue(_elem, 1);
		} else {
			setValue(_elem, 0);
		}
	});
});