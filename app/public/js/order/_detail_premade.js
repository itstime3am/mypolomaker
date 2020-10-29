if (typeof _DP_Load == 'undefined') {
	_DP_Load = true;

	var _current_row1 = false;
	var _current_row2 = false;
	var _blnDetailChanged = false;
	$(function() {
		$('#edit_panel1 .edit-ctrl[data="pattern_rowid"]').combobox({
			changed: function(value, event, ui) {
				var _prntTr = $(event.target).parents('tr')[0];
				var _sel = $(ui.item.option);
				if (_sel.length > 0) {
					$('.edit-ctrl[data="color"]', _prntTr).val($(_sel.get(0)).attr('ref_col'));
				}
				return false;
			}
		});

		$('table#tbl_detail_size').on('change', 'input.user-input', function () {
			var _prntTr = $(this).parents('tr#edit_panel2')[0];
			doClearVldrErrorElement(this);
			if ($(this).is('.input-integer')) {
				if (! blnValidateElem_TypeInt(this)) return false;
			} else if ($(this).is('.input-double')) {
				if (! blnValidateElem_TypeDouble(this)) return false;
			}
			var _total = 0;
			$('input:not(.cls-price-each)', _prntTr).each(function() {
				if (this.value != '' && (! isNaN(this.value))) _total += parseInt(this.value);
			});
			$('div.cls-sub-total', _prntTr).html(_total);
			
			var _price_each = getValue($('input.cls-price-each', _prntTr), 0);
			if ((_total > 0) && (_price_each > 0)) $('.cls-row-sum-amount', _prntTr).html(formatNumber(_total * _price_each, 2));
			return false;
		});
		
		$('.control-button').on('click', '.bttn-update', function () {
			var _prntDiv = $(this).parents('#div_premade_detail_panel')[0];
			if (! _prntDiv) return false;

			var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
			var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
			var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
			var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];
			
			var _isPremadeCustomSQ = ($(_tblSize).attr("premade_custom_size") == "true");
			
			var _elmSubTotal = $('.cls-sub-total', _prntTr2);
			var _elmPriceEach = $('.cls-price-each', _prntTr2);
			doClearVldrErrorElement(_elmSubTotal);
			doClearVldrErrorElement(_elmPriceEach);
			if (blnValidateContainer(false, _prntDiv)) {
				if (_cleanNumericValue(_elmSubTotal.html()) <= 0) {
					var _strErrMsg = MSG_VLDR_INVALID_DATATYPE.replace(/v_XX_1/g, '( กรุณาระบุจำนวน )');
					doSetVldrError(_elmSubTotal, 'detailQty', 'invalidOrderAmount', _strErrMsg);
					alert(_strErrMsg);
					return false;
				}
				if (_cleanNumericValue(getValue(_elmPriceEach, 0)) <= 0) {
					var _strErrMsg = MSG_VLDR_INVALID_DATATYPE.replace(/v_XX_1/g, '( กรุณาระบุราคา )');
					doSetVldrError(_elmPriceEach, 'detailPrice', 'invalidPrice', _strErrMsg);
					alert(_strErrMsg);
					return false;
				}
				var _arr;
				var _selected = $('.edit-ctrl[data="pattern_rowid"] option:selected', _prntTr1);
				var _pId = -1;
				var _pText = '';
				var _color = '';
				if (_selected.length > 0) {
					_pId = _selected.get(0).value;
					_pText = _selected.get(0).text;
				}
				_color = $('.edit-ctrl[data="color"]', _prntTr1).val();
				if (_current_row1 !== false) {
					_arr = _current_row1.children();
					if (_arr.length > 2) {
						$(_arr[1]).html(_pText);
						$(_arr[1]).attr('pattern_rowid', _pId);
						$(_arr[2]).html(_color);
					}
				} else {
					var _tr = '<tr><td class="control-button eventView-hide"><div class="control-button"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"> <img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td><td pattern_rowid="' + _pId + '">' + _pText + '</td><td>' + _color + '</td></tr>';
					//$(_prntTr1).before(_tr);
					$('tbody', _tblHead).append(_tr);
				}

				if (_current_row2 !== false) {
					_arr = _current_row2.children();
					for (var _i=0;_i<_arr.length;_i++) {
						var _dst = $(_arr[_i]);
						var _src;
						if (_isPremadeCustomSQ) {
							var _cIndex = _dst.attr('custom_index') || -1;
							_src = $('input.user-input[custom_index="' + _cIndex + '"]:not(.cls-price-each)', _prntTr2);
						} else {
							_src = $('input.user-input:not(.cls-price-each)', _prntTr2).filter(function () { return ($(this).attr('order_size_rowid') == _dst.attr('order_size_rowid')); });
						}
						if (_src.length > 0) {
							_dst.html(_src.val());
							if (_src.is('.cls-is-expired')) _dst.addClass('cls-is-expired');
							if (_src.is('.hidden')) _dst.addClass('hidden');							
						}
					}
					//_arr[_arr.length - 2].innerHTML = $('div.cls-sub-total', _prntTr2).html();
					$('.cls-sub-total', _current_row2).html($('.cls-sub-total', _prntTr2).html());
					$('.cls-price-each', _current_row2).html($('.cls-price-each', _prntTr2).val());
					$('.cls-row-sum-amount', _current_row2).html($('.cls-row-sum-amount', _prntTr2).html());
				} else {
					_tr = '<tr>';
					$('input.user-input:not(.cls-price-each)', _prntTr2).each(function () {
						var _class = '';
						if ($(this).is('.cls-is-expired')) _class += 'cls-is-expired ';
						if ($(this).is('.hidden')) _class += 'hidden ';
						_class = _class.trim();
						if (_isPremadeCustomSQ) {
							var _custom_index = $(this).attr("custom_index");
							_tr += '<td custom_index="' + _custom_index + '"';
						} else {
							_tr += '<td order_size_rowid="' + $(this).attr('order_size_rowid') + '"';
						}
						if (_class.trim() != '') _tr += ' class="' + _class + '"';
						_tr += '>' + this.value + '</td>';
					});
					_tr += '<td class="cls-td-sum"><div class="cls-sub-total">' + $('div.cls-sub-total', _prntTr2).html() + '</div><div class="eventView-hide"> * </div><div class="cls-price-each eventView-hide">' + $('input.cls-price-each', _prntTr2).val() + '</div><div class="eventView-hide"> = </div><div class="cls-row-sum-amount eventView-hide">' + $('div.cls-row-sum-amount', _prntTr2).html() + '</div></td><td class="control-button eventView-hide" style="width:50px;"><div class="control-button"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"> <img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td></tr>';
					//$(_prntTr2).before(_tr);
					$('tbody', _tblSize).append(_tr);
				}
				_blnDetailChanged = true;
				_fncRecalTotal();
				$('.control-button .bttn-reset', _prntTr1).trigger('click');
			}
			return false;
		});

		$('table tbody tr').on('click', '.bttn-delete', function() {
			var _prntDiv = $(this).parents('#div_premade_detail_panel')[0];
			if (! _prntDiv) return false;
			
			if (confirm(MSG_CONFIRM_DELETE_ROW.replace("v_XX_1", ''))) {
				var _row = $($(this).parents('tr').get(0));
				var _index = _row.index();
				$($('#tbl_detail_list tbody tr', _prntDiv).get(_index)).remove();
				$($('#tbl_detail_size tbody tr', _prntDiv).get(_index)).remove();
				_blnDetailChanged = true;

				doClearDetailsEditPanel();
				_fncRecalTotal();
			}
			return false;
		});

		$('table tbody tr').on('click', '.control-button .bttn-reset', function () {
			var _prntDiv = $(this).parents('#div_premade_detail_panel')[0];
			if (! _prntDiv) return false;
		
			var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
			var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
			var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
			var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];

			if ((_current_row1 !== false) && (_current_row2 !== false)) {
				_current_row1.css('display', '');
				_current_row2.css('display', '');

				$(_prntTr1).detach().appendTo($('tfoot', _tblHead));
				$(_prntTr2).detach().appendTo($('tfoot', _tblSize));
			}
			doClearDetailsEditPanel();
			return false;
		});

		$('table tr').on('click', '.bttn-edit', function () {
			var _prntDiv = $(this).parents('#div_premade_detail_panel')[0];
			if (! _prntDiv) return false;
		
			var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
			var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
			var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
			var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];
			
			if (_current_row1) $('.control-button .bttn-reset', _prntTr1).trigger('click');

			var _row = $($(this).parents('tr').get(0));
			var _index = _row.index();
			var _arrTr1 = $(_tblHead).find('tbody tr');
			var _arrTr2 = $(_tblSize).find('tbody tr');
			if (_arrTr1.length > _index) {
				_current_row1 = $(_arrTr1.get(_index));
				_current_row1.css('display', 'none');
			}
			if (_arrTr2.length > _index) {
				_current_row2 = $(_arrTr2.get(_index));
				_current_row2.css('display', 'none');
			}
			doPopulateEditPanel();
			return false;
		});
	});

	function doPopulateEditPanel() {
		if ((_current_row1 == false) || (_current_row2 == false)) return false;

		var _prntDiv = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass)[0];
		if (! _prntDiv) return false;
		
		var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
		var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
		var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
		var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];
		
		var _arrItems = $(_current_row1).children();
		$('.edit-ctrl[data="pattern_rowid"]', _prntTr1).combobox('setValue', $(_arrItems[1]).attr('pattern_rowid'));
		$('.edit-ctrl[data="color"]', _prntTr1).val($(_arrItems[2]).html());

		_arrItems = $(_current_row2).children();
		var _i = 0;
		if (_arrItems.length > 0) {
			$('input.user-input:not(.cls-price-each)', _prntTr2).each(function() {
				if ((_arrItems.length > _i) && (_arrItems[_i].innerHTML)) this.value = _cleanNumericValue(_arrItems[_i].innerHTML);
				_i++;
			});
		}
		$('div.cls-sub-total', _prntTr2).html($('.cls-sub-total', _current_row2).html());
		$('input.cls-price-each', _prntTr2).val($('.cls-price-each', _current_row2).html());
		$('div.cls-row-sum-amount', _prntTr2).html($('.cls-row-sum-amount', _current_row2).html());
		
		$('.control-button .bttn-update', _prntTr1).prop('title', 'Edit').prop('act', 'update');
		$('.control-button .bttn-reset', _prntTr1).prop('title', 'Cancel').prop('act', 'cancel');
		$('.control-button .bttn-update', _prntTr2).prop('title', 'Edit').prop('act', 'update');
		$('.control-button .bttn-reset', _prntTr2).prop('title', 'Cancel').prop('act', 'cancel');
		
		$(_prntTr1).detach().insertAfter(_current_row1);
		$(_prntTr2).detach().insertAfter(_current_row2);
	}

	function doClearDetailsEditPanel() {
		var _prntDiv = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass)[0];
		if (! _prntDiv) return false;
		
		var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
		var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
		var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
		var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];
		
		doClearVldrError(_prntTr1);
		doClearVldrError(_prntTr2);
		
		var _obj = $('.edit-ctrl[data="pattern_rowid"]', _prntTr1);
		if ((_obj.length > 0) && (_obj.data("ui-combobox"))) _obj.combobox("clearValue");
		$('.edit-ctrl[data="color"]', _prntTr1).val('');
				
		$('input.user-input', _prntTr2).each(function() {
			this.value = '';
		});
		$('.cls-sub-total', _prntTr2).html('0');
		$('.cls-price-each', _prntTr2).val('');
		$('.cls-row-sum-amount', _prntTr2).html('0');

		$('.control-button .bttn-update', _prntTr1).prop('title', 'Insert').prop('act', 'insert');
		$('.control-button .bttn-reset', _prntTr1).prop('title', 'Reset').prop('act', 'reset');
		$('.control-button .bttn-update', _prntTr2).prop('title', 'Insert').prop('act', 'insert');
		$('.control-button .bttn-reset', _prntTr2).prop('title', 'Reset').prop('act', 'reset');
		
		_current_row1 = false;
		_current_row2 = false;
	}

	function isDetailsEditingRow() {
		var _prntDiv = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass)[0];
		if (! _prntDiv) return false;
		
		var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
		var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
		var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
		var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];
		
		if (($('.edit-ctrl[data="pattern_rowid"]', _prntTr1).val() > 0) || ($('.edit-ctrl[data="color"]', _prntTr1).val() != '')) {
			return true;
		} else {
			var _blnHasVal = false;
			$('input.user-input', _prntTr2).each(function() {
				if ((this.value != '') && (! isNaN(this.value))) {
					_blnHasVal = true;
					return false;
				}
			});
			return _blnHasVal;
		}
	}

	function _fncRecalTotal() {
		var _prntDiv = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass)[0];
		if (! _prntDiv) return false;

		var _grand_total = 0;
		var _grand_amount = 0;
		$('table#tbl_detail_size tr:not(.tr-edit-panel)', _prntDiv).each(function() {
			var intQty = _cleanNumericValue($('.cls-sub-total', this).html() || '0');
			if (_isInt(intQty)) _grand_total += parseInt(intQty);
			var dblAmount = _cleanNumericValue($('.cls-row-sum-amount', this).html() || '0');
			if (! isNaN(dblAmount)) _grand_amount += dblAmount;
		});
		var divCntr = $(_prntDiv).parents('div.frm-edit-row-group')[0];
		$('.total-value', divCntr).html(_grand_total);
		$('.total-price', divCntr).html(formatNumber(_grand_amount, 2));		
	}

	function _premadeOrderFetchDetail(data) {
		var _prntDiv = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass)[0];
		if (! _prntDiv) return false;
		
		var _divGrpCntr = $(_prntDiv).parents('div.frm-edit-row-group')[0];
		var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
		var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
		var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
		var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];
		
		var _isPremadeCustomSQ = ($(_tblSize).attr("premade_custom_size") == "true");
		
		//++ set detail
		$('tbody tr:not(".tr-edit-panel")', _tblHead).remove();
		$('tbody tr:not(".tr-edit-panel")', _tblSize).remove();
		$('.total-value', _divGrpCntr).html(' -- ');
		$('.total-price', _divGrpCntr).html(' -- ');

		_str = '';
		var _total_qty = 0;
		var _total_price = 0;
		if (typeof data == 'string') data = JSON.parse(data);
		if ($.isArray(data)) {
			var _trSizeText = $('thead tr.cls-header-row-size-text', _tblSize);
			var _trSizeChest = $('thead tr.cls-header-row-size-chest', _tblSize);
			for (_r in data) {
				if (('pattern_rowid' in data[_r]) && ('color' in data[_r]) && ('order_size' in data[_r])) { // 
					var _row = data[_r];
					var _elmSel = $('.edit-ctrl[data="pattern_rowid"]', _prntTr1);
					var _ptrnCode = $('option', _elmSel).filter(function() { return (this.value == _row["pattern_rowid"]); }).text();
					var _tr = '<tr><td class="control-button eventView-hide"><div class="control-button"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"> <img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td><td pattern_rowid="' + _row["pattern_rowid"] + '">' + _ptrnCode + '</td><td>' + _row["color"] + '</td></tr>';
					
					$('tbody', _tblHead).append(_tr);

					var _arrSize = _row['order_size'];
					var _avgPrice = ('price' in _row) ? parseFloat(_row['price']) : 0;
					var _sumPrice = 0;
					if ($.isArray(_arrSize)) {
						var _qty = 0;
						_tr = '<tr>';
							
						$('input.user-input:not(.cls-price-each)', _prntTr2).each(function () {
							var _class = $(this).attr('class') || '';
							_class = _class.replace('user-input input-integer', '');
							if (_isPremadeCustomSQ) {
								var _custom_index = $(this).attr('custom_index') || -1;
								for (var _i=_arrSize.length-1;_i>=0;_i--) {
									var _ea = _arrSize[_i];
									var _seq = ('seq' in _ea) ? _ea['seq'] : -1;
									var _text = ('size_text' in _ea) ? _ea['size_text'] : '';
									var _chest = ('size_chest' in _ea) ? _ea['size_chest'] : -1;
									var _val = ('qty' in _ea) ? _ea['qty'] : -1;
									
									if ((_seq > 0) && (_custom_index == _seq) && (_val > 0)) {
										var _elm_size_text = $('input.sq-size-text[custom_index="' + _custom_index + '"]', _trSizeText);
										var _elm_size_chest = $('input.sq-size-chest[custom_index="' + _custom_index + '"]', _trSizeChest);
										if ((_elm_size_text.length > 0) && (getValue(_elm_size_text, '') == '') && (_text.trim().length > 0)) _elm_size_text.val(_text)
										if ((_elm_size_chest.length > 0) && (getValue(_elm_size_chest, -1) <= 0) && (_chest > 0)) _elm_size_chest.val(_chest);

										_tr += '<td class="' + _class + '" custom_index="' + _custom_index + '">' + _val + '</td>';
										_qty += _val;
										
										_arrSize.remove(_i);
										return true;
									}
								}
								_tr += '<td class="' + _class + '" custom_index="' + _custom_index + '"></td>';
							} else {
								var _order_size_rowid = $(this).attr('order_size_rowid') || 0;
								var _cat_id = $(this).attr('cat_id') || 0;
								var _sub_cat_id = $(this).attr('sub_cat_id') || 0;
								
								for (var _i=_arrSize.length-1;_i>=0;_i--) {
									var _ea = _arrSize[_i];
									if (('order_size_rowid' in _ea) && (_ea['order_size_rowid'] == _order_size_rowid)) {
										var _val = parseInt(_ea['qty']);
										_tr += '<td order_size_rowid="' + _order_size_rowid + '" cat_id="' + _cat_id + '" sub_cat_id="' + _sub_cat_id + '" class="' + _class + '">' + _val + '</td>';
										_qty += _val;
										
										_arrSize.remove(_i);
										return true;
									}
								}
								_tr += '<td order_size_rowid="' + _order_size_rowid + '" cat_id="' + _cat_id + '" sub_cat_id="' + _sub_cat_id + '" class="' + _class + '"></td>';
							}
						});
						var _sumPrice = (_qty * _avgPrice);
						_tr += '<td class="cls-td-sum"><div class="cls-sub-total">' + _qty + '</div><div class="eventView-hide"> * </div><div class="cls-price-each eventView-hide">' + formatNumber(_avgPrice) + '</div><div class="eventView-hide"> = </div><div class="cls-row-sum-amount eventView-hide">' + formatNumber(_sumPrice) + '</div></td><td class="control-button eventView-hide" style="width:50px;"><div class="control-button eventView-hide"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"><img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td></tr>';

						$('tbody', _tblSize).append(_tr);
						
						_total_price += _sumPrice;
						_total_qty += _qty;
					}
				}
			}
			_blnDetailChanged = false;
			$('.total-value', _divGrpCntr).html(_total_qty);
			/*
			if ((_total_price <= 0) && (_cleanNumericValue(cb_args[0]['total_price']) > 0) && (_total_qty > 0)) {
				_total_price = _cleanNumericValue(cb_args[0]['total_price']);
				var _avg_prc = (_total_price / _total_qty);
				$('#tbl_detail_size tbody tr').each(function() {
					var _elmQty = $('div.cls-sub-total', this);
					var _elmPrice = $('div.cls-price-each', this);
					var _elmSum = $('div.cls-row-sum-amount', this);
					var _qty = getValue(_elmQty, 0);
					_elmPrice.html(formatNumber(_avg_prc));
					_elmSum.html(formatNumber(_avg_prc * _qty));
				});
			}
			*/
			$('.total-price', _divGrpCntr).html(formatNumber(_total_price));
		}
	}
	
	function __getPremadeDetailCtrl_CommittedChanged() {
		var _prntDiv = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass)[0];
		if (! _prntDiv) return false;
		
		var _tblHead = $(_prntDiv).find('table#tbl_detail_list');
		var _tblSize = $(_prntDiv).find('table#tbl_detail_size');
		var _prntTr1 = $(_tblHead).find('tr#edit_panel1')[0];
		var _prntTr2 = $(_tblSize).find('tr#edit_panel2')[0];

		var _isPremadeCustomSQ = ($(_tblSize).attr('premade_custom_size') == 'true');
		
		if (isDetailsEditingRow()) {
/*
			if (! blnValidateContainer(false, _prntDiv, 'table tr.tr-edit-panel ')) return false;
			if (_cleanNumericValue($('.cls-sub-total', _prntTr2).html()) <= 0) {
				var _strErrMsg = MSG_VLDR_INVALID_DATATYPE.replace(/v_XX_1/g, '( กรุณาเลือกจำนวน )');
				$('.ul-vldr-error-msg').append('<li id="li_invalidOrderAmount">' + _strErrMsg + '</li>');
				alert(_strErrMsg);
				return false;
			}
*/
			alert('พบข้อมูลรายละเอียดที่แก้ไขหรือสร้างใหม่โดยยังไม่ได้ทำการยืนยัน, กรุณายืนยันหรือยกเลิกก่อนทำการบันทึก');
			return false;
		}

		var _objCustomSize = {};
		if (_isPremadeCustomSQ) {
			var _trSizeText = $('thead tr.cls-header-row-size-text', _tblSize);
			var _trSizeChest = $('thead tr.cls-header-row-size-chest', _tblSize);
			for (var _i=1;_i<11;_i++) {
				var _text = getValue($('input.sq-size-text[custom_index="' + _i + '"]', _trSizeText), '');
				var _chest = getValue($('input.sq-size-chest[custom_index="' + _i + '"]', _trSizeChest), -1);
				_objCustomSize[_i.toString()] = { "seq": parseInt(_i), "text": _text, "chest": _chest };
			}
		}
		var _arrDetail = false;
		var _det = $('tbody tr', _tblHead);
		var _detSize = $('tbody tr', _tblSize);
		if (_detSize.length > 0) {
			_arrDetail = [];
			var _detIndex = 0;
			_det.each(function () {
				var _this = $(this);
				if (_this.hasClass('tr-edit-panel')) {
					if (isDetailsEditingRow()) {
						var _order_size = [];
						var _avgPrice = getValue($('input.cls-price-each', _prntTr2), 0);
						$('input:not(.cls-price-each)', _prntTr2).each(function() {
							var _ea = $(this);
							if (_isPremadeCustomSQ) {
								var _custom_index = _ea.attr('custom_index') || -1;
								if (parseInt(_custom_index) > 0) {
									var _objSize = (_custom_index in _objCustomSize) ? _objCustomSize[_custom_index] : false;
									var _qty = getValue(_ea, -1);
									if ((_objSize) && (_qty > 0)) _order_size.push({ "seq": parseInt(_custom_index), "size_text": _objSize["text"], "size_chest": _objSize["chest"], "qty": _qty });
								}
							} else {
								if (_ea.attr('order_size_rowid') && (getValue(_ea, -1) > 0)) _order_size.push({ "order_size_rowid": _ea.attr('order_size_rowid'), "qty": getValue(_ea, -1) });
							}
						});
						_arrDetail.push({
							"pattern_rowid": $('.edit-ctrl[data="pattern_rowid"]', _prntTr1).val()
							, "color": $('.edit-ctrl[data="txt-color"]', _prntTr1).val()
							, "price": _avgPrice
							, "order_size": _order_size
						});
					}
				} else {
					if ((_detSize.length > _detIndex) && (_this.css('display') != 'none')) {
						var _order_size = [];
						var _trQty = $(_detSize.get(_detIndex));
						var _arr = _trQty.children();
						var _avgPrice = parseFloat(getValue($('.cls-price-each', _trQty), 0));
						for (var _i=0;_i<_arr.length;_i++) {
							_ea = $(_arr[_i]);
							if (_isPremadeCustomSQ) {
								var _seq = (_i + 1);
								var _objSize = (_seq.toString() in _objCustomSize) ? _objCustomSize[_seq.toString()] : false;
								var _qty = parseInt(_ea.html() || '-1');
								if ((_objSize) && (_qty > 0)) _order_size.push({ "seq": _seq, "size_text": _objSize["text"], "size_chest": _objSize["chest"], "qty": _qty });
							} else {
								if (_ea.attr('order_size_rowid') && _isInt(_ea.html())) _order_size.push({ "order_size_rowid": _ea.attr('order_size_rowid'), "qty": parseInt(_ea.html()) });
							}
						}
						_arr = _this.children();
						if (_arr.length > 2) {
							_arrDetail.push({
								"pattern_rowid": $(_arr[1]).attr('pattern_rowid')
								, "color": _arr[2].innerHTML
								, "price": _avgPrice
								, "order_size": _order_size
							});
						}
					}
				}
				_detIndex++;
			});
		}
		return _arrDetail;
	}
}