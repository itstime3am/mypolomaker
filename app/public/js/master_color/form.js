$(function() {
	$('.cls-div-form-edit-dialog[index="0"]').dialog('option', 'height', 580);

	var _fncTemplate_doClearForm = _doClearForm;
	var _fncTemplate_doInsert = doInsert;
	//var _fncTemplate_doSubmit = doSubmit;
	//var _fncTemplate__doSetValueFormUserInput = _doSetValueFormUserInput;
	//var _fncTemplate_blnDataChanged = blnDataChanged;

	_doClearForm = function (_frm) {
		_fncTemplate_doClearForm.apply(this, arguments);
		var _index = $(_frm).attr('index') || 0;
		if (_index == 1) {
			
		}
	};
	doInsert = function (divEditDlg) {
		_fncTemplate_doInsert.apply(this, arguments);
		var _index = $('#frm_edit', divEditDlg).attr('index') || 0;
	};
/*	doSubmit = function (form, opt_fncCallback) {
		_index = $(form).attr('index') || 0;
		if (blnDataChanged(form) == false) {
			alert(MSG_ALERT_COMMIT_NO_CHANGE);
			return false;
		}
		if (_currEditData == undefined) _currEditData = {};
		var _divDialog = $(form).parents(".cls-div-form-edit-dialog").get(0);
		var _dtIndex = $(_divDialog).attr('index');
		$(form).find(".user-input").each(function () {
			var _name = getData(this);
			var _val = getValue(this);
			_currEditData[_name] = _val;
		});
		
		for (var _prd of ['polo', 'tshirt', 'jacket']) {
			_dummyStr = '';
			for (var _ea of ['main', 'line', 'sub', 'hem']) {
				var _key = _prd + '_' + _ea;
				if ((_key in _currEditData) && (_currEditData[_key])) {
					_dummyStr += _ea + ',';
					delete _currEditData[_key];
				}
			}
			if (_dummyStr.length > 0) _currEditData[_prd + '_cols'] = ',' + _dummyStr;
		}
		_dummyStr = '';
		for (var _ea of ['front', 'back', 'brim', 'button', 'swr', 'afh']) {
			var _key = 'cap_' + _ea;
			if ((_key in _currEditData) && (_currEditData[_key])) {
				_dummyStr += _ea + ',';
				delete _currEditData[_key];
			}			
		}
		if (_dummyStr.length > 0) _currEditData['cap_cols'] = ',' + _dummyStr;
		
		
	};
	__genDispControlSet = function(strType, retObj) {
		var _dataRowObj = retObj || {};
		var _type = (strType || ' ').trim().toLowerCase();
		if ((typeof _type != 'string') || (_type == '')) return _dataRowObj;

		if (_type + '_cols' in _dataRowObj) {
			var _str = _dataRowObj[_type + '_cols'] || '';
			var _arr = _str.split(',');
			if ($.isArray(_arr) && (_arr.length > 0)) {
				for (var _i=0;_i<_arr.length;_i++) {
					var _ea = _arr[_i] || ' ';
					_ea = _ea.trim().toLowerCase();
					if ((typeof _ea != 'string') || (_ea == '')) continue;
					if (_ea != '') _dataRowObj[_type + '_' + _ea] = true;
				}
			}
		}
		return _dataRowObj;
	};
	
	_doSetValueFormUserInput = function(_frm, dataRowObj) {
		_dataRowObj = dataRowObj || {};
		_dataRowObj = __genDispControlSet('polo', _dataRowObj);
		_dataRowObj = __genDispControlSet('tshirt', _dataRowObj);
		_dataRowObj = __genDispControlSet('cap', _dataRowObj);
		_dataRowObj = __genDispControlSet('jacket', _dataRowObj);

		_fncTemplate__doSetValueFormUserInput.apply(this, [_frm, _dataRowObj]);
	};

	blnDataChanged = function() {
		return _fncTemplate_blnDataChanged.apply(this, arguments);
	};
*/
});