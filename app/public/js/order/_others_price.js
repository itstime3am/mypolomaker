if (typeof _OP_Load == 'undefined') {
	_OP_Load = true;

	var _op_current_row = false;
	var _blnOpChanged = false;
	$(function() {
		$('table#tbl_op_list tr').on('change', '#txt-op_price', function () {
				doClearVldrErrorElement(this)
				blnValidateElem_TypeDouble(this);
				return false;
			});
		
		$('table#tbl_op_list tr').on('click', '#btnOpSubmit', function() {
			var _tbl = $(this).parents('table#tbl_op_list')[0];
			var _op_edit_panel = $(this).parents('tr#op_edit_panel')[0];

			var _isUpdate = false;
			
			if (! blnValidateElem_TypeRequired($('#txt-op_detail', _op_edit_panel))) {
				return false;
			}
			
			var _elem = $('#txt-op_price', _op_edit_panel).get(0);
			doClearVldrErrorElement(_elem)
			if (! blnValidateElem_TypeRequired(_elem)) {
				return false;
			}
			if (! blnValidateElem_TypeDouble(_elem)) {
				return false;
			}
			
			if (_op_current_row) _isUpdate = (_op_current_row.css('display') == 'none');

			if (_isUpdate) {
				_arr = _op_current_row.children();
				if (_arr.length >= 2) {
					$(_arr[0]).html($('#txt-op_detail', _op_edit_panel).val());
					$(_arr[1]).html($('#txt-op_price', _op_edit_panel).val());
				}
			} else {
				_opInsertDetailRow(_tbl, {
					"detail": $('#txt-op_detail', _op_edit_panel).val()
					, "price": $('#txt-op_price', _op_edit_panel).val()
				});
			}
			_blnOpChanged = true;
			$('#btnOpCancel', _op_edit_panel).trigger('click');
			return false;
		});

		$('table#tbl_op_list').on('click', '#btnOpCancel', function() {
			var _tbl = $(this).parents('table#tbl_op_list')[0];
			var _op_edit_panel = $(this).parents('tr#op_edit_panel')[0];

			if (_op_current_row) _op_current_row.css('display', '');
			opClearEditPanel();
			$(_op_edit_panel).detach().appendTo($('tfoot', _tbl));
			return false;
		});

		$('table#tbl_op_list').on('click', '.ctrl-edit', function() {
			var _tbl = $(this).parents('table#tbl_op_list')[0];
			var _op_edit_panel = $('tr#op_edit_panel', _tbl)[0];
			
			if (_op_current_row) $('#btnOpCancel', _op_edit_panel).trigger('click');

			_op_current_row = $($(this).parents('tr').get(0)).css('display', 'none');
			
			opPopulateEditPanel(_op_current_row);
			return false;
		});

		$('table#tbl_op_list').on('click', '.ctrl-delete', function() {
			if (confirm(MSG_CONFIRM_DELETE_ROW.replace(/\(*\s+v_XX_1\s+\)*/, ''))) {
				$(this).parents('tr').get(0).remove();
				_blnOpChanged = true;
			}
			return false;			
		});
	});

	//++ Others price panel function
	function _opInsertDetailRow(tbl, objNew) {
		var _str = '<tr>';
		_str += '<td colspan="2">' + objNew.detail + '</td>';
		_str += '<td class="eventView-hide" colspan="2">' + objNew.price + '</td>';
		_str += '<td class="control-button eventView-hide"><img src="public/images/edit.png" class="op-edit-ctrl ctrl-edit" title="Edit" /><img src="public/images/b_delete.png" class="op-edit-ctrl ctrl-delete" title="Delete" /></td></tr>';
		
		$('tbody', tbl).append(_str);
	}
	
	function opPopulateEditPanel(op_current_row) {
		var _tbl = $(op_current_row).parents('table#tbl_op_list')[0];
		var _op_edit_panel = $('tr#op_edit_panel', _tbl)[0];
		_op_current_row = op_current_row;
		
		var _arrItems = $(_op_current_row).children();
		$('#txt-op_detail', _op_edit_panel).val($(_arrItems[0]).html());
		$('#txt-op_price', _op_edit_panel).val($(_arrItems[1]).html());

		$('#btnOpSubmit', _op_edit_panel).prop('title', 'Edit');
		$('#btnOpSubmit', _op_edit_panel).prop('act', 'update');
		$('#btnOpCancel', _op_edit_panel).prop('title', 'Cancel');
		$('#btnOpCancel', _op_edit_panel).prop('act', 'cancel');
		
		$(_op_edit_panel).detach().insertAfter(_op_current_row).show();
	}
	
	function opClearEditPanel() {
		var _tbl = $('table#tbl_op_list').filter(__fnc_filterNotNestedHiddenClass);
		var _divPrnt = $($(_tbl).parents('div#ord_others_price_container'));
		var _op_edit_panel = $('tr#op_edit_panel', _tbl)[0];

		$('#txt-op_detail', _op_edit_panel).val('');
		$('#txt-op_price', _op_edit_panel).val('');

		$('#btnOpSubmit', _op_edit_panel).prop('title', 'Insert');
		$('#btnOpSubmit', _op_edit_panel).prop('act', 'insert');
		$('#btnOpCancel', _op_edit_panel).prop('title', 'Reset');
		$('#btnOpCancel', _op_edit_panel).prop('act', 'reset');
		
		_op_current_row = false;
	}
	
	function isOpEditingRow() {
		var _tbl = $('table#tbl_op_list').filter(__fnc_filterNotNestedHiddenClass);
		//var _divPrnt = $($(_tbl).parents('div#ord_others_price_container'));
		var _op_edit_panel = $('tr#op_edit_panel', _tbl);
		
		if (($('#txt-op_detail', _op_edit_panel).val() != '') || ($('#txt-op_price', _op_edit_panel).val() != '')) {
			return true;
		} else {
			return false;
		}
	}
	
	function __getOtherPriceCtrl_CommittedChanged() {
		var _tbl = $('table#tbl_op_list').filter(__fnc_filterNotNestedHiddenClass);

		var _others_price = [];
		$("#tbl_op_list tbody tr").each(function () {
			if ($(this).css('display') != 'none') { //case open edit panel but not commit row before submit form
				_arr = $(this).children();
				if (_arr.length >= 2) {
					_others_price.push({
						"seq": (_others_price.length + 1)
						, "detail": $(_arr[0]).html()
						, "price": _cleanNumericValue($(_arr[1]).html())
					});
				}
			}
		});

		if (isOpEditingRow()) {
			var _op_edit_panel = $('tr#op_edit_panel', _tbl);
			_others_price.push({
				"seq": (_others_price.length + 1)
				, "detail": $('#txt-op_detail', _op_edit_panel).val()
				, 'price': _cleanNumericValue($('#txt-op_price', _op_edit_panel).val())
			});
		}
		
		return _others_price;
	}
}
//-- Others price panel function
