if (typeof _SQ_Load == 'undefined') {
	_SQ_Load = true;

	$(function() {
		$('div#ord_size_quan_container').on('change', '#sel-size_category', function() {
			var _divPrnt = $(this).parents('div#ord_size_quan_container')[0];
			$('table.tbl_size_cat', _divPrnt).each(function() { $(this).css('display', 'none'); });
			
			_cat_rowid = getValue(this) || 0;
			if (_cat_rowid >= 0) {
				var _jq_tbl = $(_divPrnt).find("table#cat_id_" + _cat_rowid);
				if (_jq_tbl.length > 0) {
					$(_jq_tbl[0]).css('display', '');
					__fncRecalculate(_jq_tbl[0]);
				}
				//++ call sum quantity trigger
				/*$('tbody tr td input.user-input.sq-qty:first', _jq_tbl).each(
					function() {
						$(this).trigger('change');
					}
				);
				$('tbody tr td input.user-input.sp-price:first', _jq_tbl).each(
					function() {
						$(this).trigger('change');
					}
				);*/
				//-- call sum quantity trigger
			}
			return false;
		});
		$('table.tbl_size_cat').on('change', 'input.sq-qty', function () {
			var _tbl = $(this).parents('table.tbl_size_cat')[0];
			doClearVldrErrorElement(this);
			if (blnValidateElem_TypeInt(this)) {
				__fncRecalculate(_tbl);
			}
			return false;
		});
		$('table.tbl_size_cat').on('change', 'input.sp-price', function () {
			var _tbl = $(this).parents('table.tbl_size_cat')[0];
			doClearVldrErrorElement(this);
			if (blnValidateElem_TypeDouble(this)) {
				__fncRecalculate(_tbl);
			}
			return false;
		});		
		//_arrTables = $("#div_order_size_panel .tbl_size_cat");
		//if (_arrTables.length > 0) $(_arrTables.get(0)).css('display', '');
	});
	
	function __fncRecalculate(tbl) {		
		var _tbl = tbl || false;
		if ((! _tbl)) {
			var _elmSel = $('select#sel-size_category').filter(__fnc_filterNotNestedHiddenClass);
			//if (getValue(_elmSel, 0) <= 0) return false;

			var _selSQCatID = 'cat_id_' + getValue(_elmSel, 0);
			_tbl = $('table.tbl_size_cat').filter(function() { return ($(this).attr('id') == _selSQCatID); }).filter(__fnc_filterNotNestedHiddenClass)[0];
		}
		if (! _tbl) return false;
		
		var _divPrnt = $(_tbl).parents('div#ord_size_quan_container')[0];
		var _totalQuan = 0;
		var _totalPriceSum = 0;
		$('input.sp-price', _tbl).each(function() {
			var _tblPrnt = $(this).parents('table.tbl_size_cat')[0];
			var _quan_id = $(this).attr('id').replace('-sp', '-sq').replace('_price', '_qty');
			var _qty = $('#' + _quan_id, _tblPrnt).val().toString().replace(',', '').trim();
			var _val = $(this).val().toString().replace(',', '').trim();
			if ((_qty.length > 0) && (!isNaN(_qty)) && (_val.length > 0) && (!isNaN(_val))) {
				_totalQuan += parseInt(_qty);
				_totalPriceSum += parseInt(_qty) * parseFloat(_val);
			}
		});
		$('.total-value', _divPrnt).html(strGetDisplayNumber(_totalQuan, false, 0));
		$('.total-price', _divPrnt).html(strGetDisplayNumber(_totalPriceSum, true, 3));
	}
	
	function _sqFetchData(data) {
		var _validOpt = $('#sel-size_category option:not(.cls-is-expired)', $('.cls-others-panel').filter(__fnc_filterNotNestedHiddenClass));
//setValue($(_validOpt[0]).parent('select'), $(_validOpt[0]).val());
		if (_validOpt.length > 0) {
			setValue($(_validOpt[0]).parent('select'), $(_validOpt[0]).val());
		} else {
			clearValue($('#sel-size_category', $('.cls-others-panel').filter(__fnc_filterNotNestedHiddenClass)));
		}

		if (typeof data == 'string') data = JSON.parse(data);
		
		var _div = $('div#ord_size_quan_container').filter(__fnc_filterNotNestedHiddenClass);
		if (_div.length <= 0) return false;
		if ('size_category' in data) {
			setValue($('#sel-size_category', _div), data["size_category"]);
			$('#sel-size_category', _div).trigger('change');
		}
		if ('size_category' in data['size']) {
			setValue($('#sel-size_category', _div), data['size']['size_category']);
			$('#sel-size_category', _div).trigger('change');
		}
		

		var tbls = $('table.tbl_size_cat').filter(__fnc_filterNotNestedHiddenClass);
		if (tbls.length <= 0) return false;

		if ('size' in data) {
			_arr = data["size"];
			if (typeof _arr == 'string') _arr = JSON.parse(_arr);
			for (_k in _arr) {
				if (($.isPlainObject(_arr[_k])) && ('qty' in _arr[_k]) && (_arr[_k]['qty'] > 0)) {
					$('#txt-sp_' + _k, tbls).val(_arr[_k]['price']);
					$('#txt-sq_' + _k, tbls).val(_arr[_k]['qty']);
				}
			}			
		}
		if ('size_custom' in data) {
			_arr = data["size_custom"];
			if (typeof _arr == 'string') _arr = JSON.parse(_arr);
			for (_i in _arr) {
				for (_j in _arr[_i]) {
					for (_l in _arr[_i][_j]) {
						if (('qty' in _arr[_i][_j][_l]) && ((_arr[_i][_j][_l]['qty'] > 0))) {
							$('#txt-sq_' + _i + '_' + _j + '_text' + _l, tbls).val(_arr[_i][_j][_l]['text']);
							$('#txt-sq_' + _i + '_' + _j + '_chest' + _l, tbls).val(_arr[_i][_j][_l]['chest']);
							$('#txt-sq_' + _i + '_' + _j + '_qty' + _l, tbls).val(_arr[_i][_j][_l]['qty']);
							$('#txt-sp_' + _i + '_' + _j + '_price' + _l, tbls).val(_arr[_i][_j][_l]['price']);
						}
					}
				}
			}		
		}
		__fncRecalculate();	
	}

	function __getSizeQuanCtrl_CommittedChanged() {
		var _elmSel = $('select#sel-size_category').filter(__fnc_filterNotNestedHiddenClass);
		//if (getValue(_elmSel, 0) <= 0) return false;
		
		var _size_category = getValue(_elmSel, 0);
		var _tbl = false;
		if (_elmSel.length > 0) {
			var _selSQCatID = 'cat_id_' + _size_category;
			_tbl = $('table.tbl_size_cat').filter(function() { return ($(this).attr('id') == _selSQCatID); }).filter(__fnc_filterNotNestedHiddenClass)[0];
		}
		if (! _tbl) return false;
		
		var _divPrnt = $(_tbl).parents('div#ord_size_quan_container')[0];
		var _size_quan = { "size_category": _size_category };
		var _size_quan_custom = {"-1": {}};
		$(_tbl).find(".user-input").each(function () {
			_name = $(this).prop('id').substr(4);
			if ((_name.indexOf('sq_') == 0) || (_name.indexOf('sp_') == 0)) {
				_val = _getElemValue(this, '').toString().trim();
				if ((_val != '')) {
					_sq_check = _name.substr(3);
					if (isNaN(_sq_check) && (_sq_check.length > 5)) { //custom one format = {cat}_{sub}_{type}
						_arr = _sq_check.split("_");
						if (_arr.length < 3) return true;
						_cat_rowid = (_arr[0] || -1).toString();
						_sub_rowid = (_arr[1] || -1).toString();
						_str = _arr[2] || '';
						if (_str == '') return true;
						_cus_type = _str.substr(0, _str.length - 1);
						_cus_index = _str.substr(-1);
						if (!(_cat_rowid in _size_quan_custom)) _size_quan_custom[_cat_rowid] = {"-1": {}};
						if (!(_sub_rowid in _size_quan_custom[_cat_rowid])) _size_quan_custom[_cat_rowid][_sub_rowid] = {"-1": {}};
						if (!(_cus_index in _size_quan_custom[_cat_rowid][_sub_rowid])) _size_quan_custom[_cat_rowid][_sub_rowid][_cus_index] = {};
						if ((_cus_type == "qty") || (_cus_type == 'price')) {
							_size_quan_custom[_cat_rowid][_sub_rowid][_cus_index][_cus_type] = _cleanNumericValue(_val);
						} else {
							_size_quan_custom[_cat_rowid][_sub_rowid][_cus_index][_cus_type] = _val;						
						}
					} else {
						if (!(_sq_check in _size_quan)) _size_quan[_sq_check] = {"seq": Object.keys(_size_quan).length, "price": 0, "qty": 0};
						if (_name.indexOf('sq_') == 0) { //Quantity
							_size_quan[_sq_check]["qty"] = _cleanNumericValue(_val);
						} else if (_name.indexOf('sp_') == 0) { //Price
							_size_quan[_sq_check]["price"] = _cleanNumericValue(_val);
						}
					}
				}
				return true; //next
			}
		});
		return {
			"size": _size_quan
			, "size_custom": _size_quan_custom 
		};
	}
}
