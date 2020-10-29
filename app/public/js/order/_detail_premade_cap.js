/*	var _current_row1 = false;
	var _current_row2 = false;
	var _blnDetailChanged = false;
	var _TMPL_TD_CONTROLLER = '<td class="control-button"><div class="control-button"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"> <img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td>';
	$(function() {
		$('#edit_panel1 .edit-ctrl[data="pattern_rowid"]').combobox({
			select: function (value) {
				_selected = $('#edit_panel1 .edit-ctrl[data="pattern_rowid"] option:selected');
				if (_selected.length > 0) {
					$('#edit_panel1 .edit-ctrl[data="color"]').val($(_selected.get(0)).attr('ref_col'));
				}
				return false;
			}
		});

		$('#edit_panel2').on('change', 'input', function () {
			doClearVldrErrorElement(this);
			if ($(this).is('.input-integer')) {
				if (! blnValidateElem_TypeInt(this)) return false;
			} else if ($(this).is('.input-double')) {
				if (! blnValidateElem_TypeDouble(this)) return false;					
			}
			var _total = 0;
			$('#edit_panel2 input:not(.cls-price-each)').each(function() {
				if (this.value != '' && (! isNaN(this.value))) _total += parseInt(this.value);
			});
			$('#edit_panel2 div.cls-sub-total').html(_total);
			var _price_each = getValue($('#edit_panel2 input.cls-price-each'), 0);
			if ((_total > 0) && (_price_each > 0)) $('#edit_panel2 .cls-row-sum-amount').html(formatNumber(_total * _price_each, 2));
		});
		
		$('.control-button').on('click', '.bttn-update', function () {
			$('#li_invalidOrderAmount', $('.ul-vldr-error-msg')).remove();
			if (blnValidateContainer(false, $('#div_premade_detail_panel'), 'table tr.tr-edit-panel ')) {
				if ($('#edit_panel2 .cls-sub-total').html() <= 0) {
					var _strErrMsg = MSG_VLDR_INVALID_DATATYPE.replace(/v_XX_1/g, '( กรุณาระบุจำนวน )');
					$('.ul-vldr-error-msg').append('<li id="li_invalidOrderAmount">' + _strErrMsg + '</li>');
					alert(_strErrMsg);
					return false;
				}
				var _arr;
				var _selected = $('#edit_panel1 .edit-ctrl[data="pattern_rowid"] option:selected'), _pId = -1, _pText = '', _color = '';
				if (_selected.length > 0) {
					_pId = _selected.get(0).value;
					_pText = _selected.get(0).text;
				}
				_color = $('#edit_panel1 .edit-ctrl[data="color"]').val();
				if (_current_row1 !== false) {
					_arr = _current_row1.children();
					if (_arr.length > 2) {
						$(_arr[1]).html(_pText);
						$(_arr[1]).attr('pattern_rowid', _pId);
						$(_arr[2]).html(_color);
					}
				} else {
					var _tr = '<tr>' + _TMPL_TD_CONTROLLER + '<td pattern_rowid="' + _pId + '">' + _pText + '</td><td>' + _color + '</td></tr>';
					$('#edit_panel1').before(_tr);
				}
				if (_current_row2 !== false) {
					_arr = _current_row2.children();
					var _i = 0;
					$('#edit_panel2 input:not(.cls-price-each)').each(function () {
						if ($(_arr[_i]).attr('order_size_rowid') == $(this).attr('order_size_rowid')) _arr[_i].innerHTML = this.value;
						_i++;
					});
					//_arr[_arr.length - 2].innerHTML = $('#edit_panel2 div.cls-sub-total').html();
					$('.cls-sub-total', _current_row2).html($('#edit_panel2 .cls-sub-total').html());
					$('.cls-price-each', _current_row2).html($('#edit_panel2 .cls-price-each').val());
					$('.cls-row-sum-amount', _current_row2).html($('#edit_panel2 .cls-row-sum-amount').html());
				} else {
					_tr = '<tr>';
					$('#edit_panel2 input:not(.cls-price-each)').each(function () {
						_tr += '<td order_size_rowid="' + $(this).attr('order_size_rowid') + '">' + this.value + '</td>';
					});
					_tr += '<td><div class="cls-sub-total">' + $('#edit_panel2 div.cls-sub-total').html() + '</div> * <div class="cls-price-each">' + $('#edit_panel2 input.cls-price-each').val() + '</div> = <div class="cls-row-sum-amount">' + $('#edit_panel2 div.cls-row-sum-amount').html() + '</div></td>' + _TMPL_TD_CONTROLLER + '</tr>';
					$('#edit_panel2').before(_tr);
				}
				_blnDetailChanged = true;

				var _grand_total = 0;
				$('#tbl_detail_size tr:not(.tr-edit-panel) .cls-sub-total').each(function() {
					if (_isInt($(this).html())) _grand_total += parseInt($(this).html());
				});
				$('#size-grand-total').html(_grand_total);
				
				var _grand_amount = 0;
				$('#tbl_detail_size tr:not(.tr-edit-panel) .cls-row-sum-amount').each(function() {
					var _val = _cleanNumericValue($(this).html() || 0);
					if (! isNaN(_val)) _grand_amount += _val;
				});
				$('#spn-total_price').html(formatNumber(_grand_amount, 2));

				$('#edit_panel1 .control-button .bttn-reset').trigger('click');
				return false;
			}
		});

		$('table tr').on('click', '.bttn-delete', function() {
			if (confirm(MSG_CONFIRM_DELETE_ROW.replace("v_XX_1", ''))) {
				var _row = $($(this).parents('tr').get(0));
				var _index = _row.index();
				$($('#tbl_detail_list tbody tr').get(_index)).remove();
				$($('#tbl_detail_size tbody tr').get(_index)).remove();
				_blnDetailChanged = true;
				
				doClearDetailsEditPanel();
			}
		});

		$('.control-button .bttn-reset').on('click', function () {
			if ((_current_row1 !== false) && (_current_row2 !== false)) {
				_current_row1.css('display', '');
				_current_row2.css('display', '');
			}
			$('#edit_panel1').detach().appendTo($('#tbl_detail_list tbody'));
			$('#edit_panel2').detach().appendTo($('#tbl_detail_size tbody'));
			doClearDetailsEditPanel();
		});
		
		$('table tr').on('click', '.bttn-edit', function () {
			if (_current_row1) $('#edit_panel1 .control-button .bttn-reset').trigger('click');

			var _row = $($(this).parents('tr').get(0));
			var _index = _row.index();
			var _body = $('#tbl_detail_list tbody tr');
			if (_body.length > _index) _current_row1 = $(_body.get(_index));
			_body = $('#tbl_detail_size tbody tr');
			if (_body.length > _index) _current_row2 = $(_body.get(_index));

			_current_row1.css('display', 'none');
			_current_row2.css('display', 'none');

			doPopulateEditPanel();
		});

		$('#txt-total_price').on('change', function () {
			doClearVldrErrorElement(this)
			blnValidateElem_TypeDouble(this);
		});
	});

	function doPopulateEditPanel() {
		if ((_current_row1 == false) || (_current_row2 == false)) return false;
		var _arrItems = $(_current_row1).children();
		$('#edit_panel1 .edit-ctrl[data="pattern_rowid"]').combobox('setValue', $(_arrItems[1]).attr('pattern_rowid'));
		$('#edit_panel1 .edit-ctrl[data="color"]').val($(_arrItems[2]).html());

		_arrItems = $(_current_row2).children();
		var _i = 0;
		if (_arrItems.length > 0) {
			$('#edit_panel2 input').each(function() {
				if (_arrItems.length > _i) this.value = _arrItems[_i].innerHTML;
				_i++;
			});
		}
		//$('#edit_panel2 div.cls-sub-total').html(_arrItems[_arrItems.length - 2].innerHTML);
		$('#edit_panel2 div.cls-sub-total').html($('.cls-sub-total', _current_row2).html());
		$('#edit_panel2 input.cls-price-each').val($('.cls-price-each', _current_row2).html());
		$('#edit_panel2 div.cls-row-sum-amount').html($('.cls-row-sum-amount', _current_row2).html());
		
		$('.control-button .bttn-update').prop('title', 'Edit');
		$('.control-button .bttn-update').prop('act', 'update');
		$('.control-button .bttn-reset').prop('title', 'Cancel');
		$('.control-button .bttn-reset').prop('act', 'cancel');
		
		$('#edit_panel1').detach().insertAfter(_current_row1);
		$('#edit_panel2').detach().insertAfter(_current_row2);
	}
	
	function doClearDetailsEditPanel() {
		doClearVldrError();
		
		var _obj = $('#edit_panel1 .edit-ctrl[data="pattern_rowid"]');
		if ((_obj.length > 0) && (_obj.data("ui-combobox"))) _obj.combobox("clearValue");
		$('#edit_panel1 .edit-ctrl[data="color"]').val('');
				
		$('#edit_panel2 input').each(
			function() {
				this.value = '';
			}
		);
		$('#edit_panel2 div.cls-sub-total').html('0');

		$('.control-button .bttn-update').prop('title', 'Insert');
		$('.control-button .bttn-update').prop('act', 'insert');
		$('.control-button .bttn-reset').prop('title', 'Reset');
		$('.control-button .bttn-reset').prop('act', 'reset');
		
		var _grand_total = 0;
		$('#tbl_detail_size td.cls-sub-total').each(function() {
			if (_isInt($(this).html())) _grand_total += parseInt($(this).html());
		});
		$('#size-grand-total').html(_grand_total);

		_current_row1 = false;
		_current_row2 = false;
	}
	
	function isDetailsEditingRow() {
		if (($('#edit_panel1 .edit-ctrl[data="pattern_rowid"]').val() > 0) || ($('#edit_panel1 .edit-ctrl[data="color"]').val() != '')) {
			return true;
		} else {
			var _blnHasVal = false;
			$('#edit_panel2 input').each(function() {
				if (this.value != '' && (! isNaN(this.value))) {
					_blnHasVal = true;
					return false;
				}
			});
			return _blnHasVal;
		}
	}
*/