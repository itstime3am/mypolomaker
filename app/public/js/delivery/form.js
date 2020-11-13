$(function() {
	if (typeof _DETAIL_ROWS_LIMIT == "undefined") _DETAIL_ROWS_LIMIT = 15;
	//++ Validation
	$('.input-integer').on('change', function (ev) {
		doClearVldrErrorElement(this);
		if (blnValidateElem_TypeInt(this)) {
			var _elmDeliverQty = $('#edit_panel #txt-detail_qty');
			doClearVldrErrorElement(_elmDeliverQty);
			var _deliver_qty = getValue(_elmDeliverQty, 0);
			var _price = $('#edit_panel #txt-detail_price').val();
			var _percent_discount = getValue($('#edit_panel #txt-percent_discount'), 0);
			var _left_qty = $('#edit_panel').attr('left_qty') || -1;
			if ((_left_qty >= 0) && (_deliver_qty > _left_qty)) {
				doSetVldrError(_elmDeliverQty, 'deliver_qty', 'InvalidAssignQuantity', 'Assigned qty. greater than remaining qty ( ' + _left_qty + ' ).', 2);
				return false;
			} else {
				if ((_deliver_qty) && (_price) && (! isNaN(_deliver_qty)) && (! isNaN(_price)))  {
					var _amount = _deliver_qty * (_price * ((100 - _percent_discount) / 100));
					setValue($('#edit_panel #txt-detail_amount'), _amount);
				}
			}
		}
	});
	$('.input-double').on('change', function (ev) {
		doClearVldrErrorElement(this);
		if (blnValidateElem_TypeDouble(this)) {
			if ($(this).parents('#tbl_detail').length > 0) {
				var _deliver_qty = getValue($('#edit_panel #txt-detail_qty'), 0);
				var _price = $('#edit_panel #txt-detail_price').val();
				var _percent_discount = getValue($('#edit_panel #txt-percent_discount'), 0);
				if ((_deliver_qty) && (_price) && (! isNaN(_deliver_qty)) && (! isNaN(_price)))  {
					var _amount = _deliver_qty * (_price * ((100 - _percent_discount) / 100));
					setValue($('#edit_panel #txt-detail_amount'), _amount);
				}
			} else {
				_doRecalAmount();
			}
		}
	});
	//-- Validation
	
	$('#sel-is_vat').on('change', function () {
		_doRecalAmount();
	});
	$('#frm_edit #txt-report_create_date').datepicker({
		showOn: "both",
		buttonImage: "public/images/select_day.png",
		buttonImageOnly: true,
		dateFormat: 'dd/mm/yy'
	}).datepicker("setDate", new Date());
	$('#frm_edit #txt-deliver_date').datepicker({
		showOn: "both",
		buttonImage: "public/images/select_day.png",
		buttonImageOnly: true,
		dateFormat: 'dd/mm/yy'
	});
	$('#frm_edit #txt-deposit_date').datepicker({
		showOn: "both",
		buttonImage: "public/images/select_day.png",
		buttonImageOnly: true,
		dateFormat: 'dd/mm/yy'
	});
	$('#frm_edit #txt-payment_date').datepicker({
		showOn: "both",
		buttonImage: "public/images/select_day.png",
		buttonImageOnly: true,
		dateFormat: 'dd/mm/yy'
	});
	$('#frm_edit #acc-job_number').autocomplete({
		delay: 500
		, minLength: 3
		, source: function( request, response ) {
			var _input = this.element;
			$.ajax({
				dataType: "json", type: 'POST', data: {"search_text": request.term}, url: './delivery/json_search_acc_job_number'
				, success: function(data) {
					_input.removeClass('ui-autocomplete-loading');
					if ((data.success == true) && (data.data.length > 0)) {
						var _data = data.data;
						var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
						var _resp = $.map(_data, function(item) {
							_text = item.job_number || '';
							_type_id = item.type_id || -1;
							_order_rowid = item.order_rowid || -1;
							if ((_type_id > 0) && (_order_rowid > 0)) {
								if ($('#divDispSelectedJobNumber .cls-selected-jobnumber[type_id="' + _type_id + '"][order_rowid="' + _order_rowid + '"]').length > 0) return;
								
								if ( !request.term || matcher.test(_text)) {
									_title = item.disp_order_date + ': ' + item.customer;
									if ((item.company || '').trim() != '') _title += ' [ ' + item.company + ' ]';
									if ((item.category || '').trim() != '') _title += ' ' + item.category;
									if ((item.type || '').trim() != '') _title += ' ' + item.type;
									_title += ' - ' + item.items + ' รายการ';

									_disp = _text.replace(
										new RegExp(
											"(?![^&;]+;)(?!<[^<>]*)(" +
											$.ui.autocomplete.escapeRegex(request.term) +
											")(?![^<>]*>)(?![^&;]+;)", "gi"
										)
										, "<strong>$1</strong>"
									);
									return {
										label: '<div class="cls-list-job-number" title="' + _title + '">' + _disp + '</div>'
										,value: _text
										,job_number: item.job_number
										,category: item.category
										,type: item.type
										,type_id: _type_id
										,order_rowid: _order_rowid
										,customer: item.customer || ''
										,company: item.company || ''
										,dis_order_date: item.disp_order_date
										,items_list: item.items
									};
								}
							}
						});
						response(_resp); //_resp.slice(0, 300)
					}
				}
				, error: function(data) {
					_input.removeClass('ui-autocomplete-loading');  
				}
			});
		}
		, select: _evntJobNumerSelected
		, change: _evntJobNumerSelected
	});
	
	$('#divDispSelectedJobNumber').on('click', '.cls-remove-selected-jobnumber', function(ev, ui) {
		var _divJN = $(this).parent('.cls-selected-jobnumber');
		if (_divJN.length > 0) {
			var _div = $(_divJN[0]);
			var _deliver_rowid = getValue($('.cls-frm-edit #hdn-rowid'), -1);
			var _rowid = parseInt(_div.attr('rowid') || '-1');
			if ((_deliver_rowid > 0) && (_rowid > 0)) {
				_doUnlinkDeliverJobNumber(_rowid, _deliver_rowid, _div);
				return;
			} else {
				_div.remove();
			}
		}
	});
	$('#frm_edit #aac-customer_display').autocomplete({
		delay: 500
		, minLength: 3
		, source: function( request, response ) {
			var _input = this.element;
			$.ajax({
				dataType: "json", type: 'POST', data: {"display_name_company": request.term}, url: './customer/json_search_acc'
				, success: function(data) {
					_input.removeClass('ui-autocomplete-loading');
					if ((data.success == true) && (data.data.length > 0)) {
						var data = data.data;
						var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
						var _resp = $.map( data, function(item) {
								_text = item.display_name_company || ''; //item.display_name_company
								//_tel = item.tel || '';
								//if (_tel.trim() == '') _tel = item.mobile || '';
								if ( !request.term || matcher.test(_text) ) return {
										label: _text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" )
										,value: _text
										,rowid: item.rowid || ''
										,customer_name: item.display_name || ''
										,company: item.company || ''
										,tel: item.contact_no || '' //_tel
										,address: item.address || ''
										,addresses: item.arr_full_address || ''
									};
							});
						response(_resp.slice(0, 300));
					}
				}
				, error: function(data) {
					_input.removeClass('ui-autocomplete-loading');  
				}
			});
		}
		, select: _evntCustomerSelected
		, change: _evntCustomerSelected
	});
	/* -- mail issue 20160225 -- */

	$('#frm_edit #sel-customer_address').combobox({
		changed: evntCustomerAddressSelected
	});
	_setEnableElem($('#frm_edit #txt-report_create_date').get(0), false);
	_setEnableElem($('#frm_edit #sel-customer_address').get(0), false);

	_doInitialEditPanel();
	
	var _fncTemplate_doSetValueFormUserInput = _doSetValueFormUserInput;
	var _fncTemplate_doClearForm = _doClearForm;
	var _fncTemplate_doSubmit = doSubmit;
	var _fncTemplate_blnDataChanged = blnDataChanged;
	
	_doSetValueFormUserInput = function (_frm, dataRowObj) {
		_fncTemplate_doSetValueFormUserInput(_frm, dataRowObj);

		_doGetDetails(dataRowObj['rowid'], function () {
			/* ++ Set special array values */
			_doSetArrayValuedElement(_frm, dataRowObj, 'product_deliver');
			_doSetArrayValuedElement(_frm, dataRowObj, 'attachment');
			/* -- Set special array values */
			//evntCustomerSelected('', '', {item: {option: $('#frm_edit #sel-customer_rowid option:selected').get(0)}});
			var _customer = ('customer_name' in dataRowObj) ? dataRowObj['customer_name'] : '';
			var _company = ('company' in dataRowObj) ? dataRowObj['company'] : '';
			if (_company.trim().length > 0) _customer = _customer + ' [' + _company + ']';
			_evntCustomerSelected('query', {'item': {
				"rowid": dataRowObj['customer_rowid']
				, "value": _customer
				, "customer_name": dataRowObj['customer_name']
				, "company": dataRowObj['company']
				, "tel": dataRowObj['tel']
				, "address": dataRowObj['customer_address']
				, "addresses": dataRowObj['customer_full_addresses']
			}});
			return false;
		});
		return false;
	};
	_doClearForm = function (_frm) {
		_current_row = undefined;
		_currEditData = undefined;
		_blnDetailsChange = false;
		_doClearEditPanel();
		$('.added-controls', _frm).remove();
		$('#divDispSelectedJobNumber').empty();
		$('#tbl_detail tbody tr:not("#edit_panel")').remove();
		
		_fncTemplate_doClearForm.apply(this, arguments);
		
		_setEnableElem($('#frm_edit #txt-report_create_date').get(0), false);
		_setEnableElem($('#frm_edit #txt-company').get(0), false);
		_setEnableElem($('#frm_edit #txt-tel').get(0), false);
		_setEnableElem($('#frm_edit #sel-customer_address').get(0), false);
		$('#frm_edit #txt-report_create_date').datepicker("setDate", new Date());
		$('.eventView-hide').removeClass('hidden');
	};
	doSubmit = function() {
		_doRecalAmount();
		if (typeof _currEditData == 'undefined') _currEditData = {};
		var _delivery_rowid = getValue($("#hdn-rowid"), -1);
		if (_delivery_rowid < 0) {
			var _arrDetails = [];
			var _lastItem;
			var _detailIndex = 1;
			$('#tbl_detail tbody tr').each(function () {
				var _tr = $(this);
				var _rowid = _tr.attr('rowid') || -1;
				var _type_id = _tr.attr('type_id') || -1;
				var _order_rowid = _tr.attr('order_rowid') || -1;
				var _order_detail_rowid = _tr.attr('order_detail_rowid') || -1;
				if ($(this).attr('id') == 'edit_panel') {
					var _qty = getValue($('#txt-detail_qty', this), -1);
					var _title = getValue($('#txt-detail_title', this), '');
					var _price = getValue($('#txt-detail_price', this), -1);
					var _percent_discount = getValue($('#txt-percent_discount', this), -1);
					var _total_amount = getValue($('#txt-detail_amount', this), -1);
					if ((_qty > 0)) {
						_lastItem = {
							"title": _title
							, "qty": _qty
							, "price": _price
							, "total_amount": _total_amount
						};
						if (_type_id > 0) _lastItem['order_type_id'] = _type_id;
						if (_order_rowid > 0) _lastItem['order_rowid'] = _order_rowid;
						if (_order_detail_rowid > 0) _lastItem['order_detail_rowid'] = _order_detail_rowid;
					}
				} else {
					var _arrObj = _tr.children();
					if (_arrObj.length > 3) {
						var _newObj = {
							"seq": _detailIndex
							, "title": $(_arrObj.get(1)).html().trim()
							, "qty": parseFloat(_cleanNumericValue($(_arrObj.get(0)).html()))
							, "price": parseFloat(_cleanNumericValue($(_arrObj.get(2)).html()))
							, "total_amount": parseFloat(_cleanNumericValue($(_arrObj.get(4)).html()))
						}
						if ((_newObj['qty'] > 0)) {
							if (_type_id > 0) _newObj['order_type_id'] = _type_id;
							if (_order_rowid > 0) _newObj['order_rowid'] = _order_rowid;
							if (_order_detail_rowid > 0) _newObj['order_detail_rowid'] = _order_detail_rowid;
							_arrDetails.push(_newObj);					
							_detailIndex ++;
						}
					}
				}
			});
			if (_lastItem) {
				_lastItem["seq"] = _detailIndex;
				_arrDetails.push(_lastItem);
			}
			_currEditData["details"] = JSON.stringify(_arrDetails);
		}
/*		
		var _arrJN = [];
		var _JNIndex = 1;
		$('#divDispSelectedJobNumber .cls-selected-jobnumber').each(function() {
			var _div = $(this);
			var _rowid = parseInt(_div.attr('rowid') || '-1');
			if (_rowid < 0) { //excluding already inserted job_number (case edit)
				var _type_id = parseInt(_div.attr('type_id') || '-1');
				var _order_rowid = parseInt(_div.attr('order_rowid') || '-1');
				if ((_type_id > 0) && (_order_rowid > 0)) {
					_arrJN.push({
						"seq": _JNIndex
						, "order_type_id": _type_id
						, "order_rowid": _order_rowid
					});
					_JNIndex++;
				}
			}
		});
		_currEditData["link_job_numbers"] = JSON.stringify(_arrJN);
*/
		_fncTemplate_doSubmit.apply(this, arguments);
	};
	blnDataChanged = function() {
		if (_blnDetailsChange) {
			return true;
		} else {
			return _fncTemplate_blnDataChanged.apply(this, arguments);
		}
	};
});

var _current_row;
var _blnDetailsChange = false;
function _doInitialEditPanel() {
	$('#tbl_detail').on('evntRowChanged', function() {
		var _count = $('#tbl_detail tbody tr:not("#edit_panel")').length;
		if (_count >= _DETAIL_ROWS_LIMIT) {
			$('#txt-detail_title').val(MSG_ALERT_DELIVER_DETAIL_ROWS_LIMIT.replace('v_XX_1', _DETAIL_ROWS_LIMIT));
			$('#tbl_detail tbody tr#edit_panel td.control-button').css('display', 'none');
			$('#tbl_detail tbody tr#edit_panel td .edit-ctrl:not("#txt-detail_amount")').each(function () {
				_setEnableElem(this, false);
			});
		} else {
			$('#txt-detail_title').val('');
			$('#tbl_detail tbody tr#edit_panel td.control-button').css('display', '');
			$('#tbl_detail tbody tr#edit_panel td .edit-ctrl:not("#txt-detail_amount")').each(function () {
				_setEnableElem(this, true);
			});
		}
		_doRecalAmount();		
	});
	
	$('#edit_panel').on('click', '#btnPanelSubmit', function() {
		var _isUpdate = false;
		if ( ! (blnValidateElem_TypeRequired($('#edit_panel #txt-detail_qty')) && blnValidateElem_TypeRequired($('#edit_panel #txt-detail_title')) && blnValidateElem_TypeRequired($('#edit_panel #txt-detail_price')))) {
			return false;
		}
		doClearVldrError($('#edit_panel'));
		doClearDisplayError(2)
		var _elmDeliverQty = $('#edit_panel #txt-detail_qty');
		var _deliver_qty = getValue(_elmDeliverQty, 0);
		if (_deliver_qty <= 0) {
			doSetVldrError(_elmDeliverQty, 'deliver_qty', 'InvalidAssignQuantity', 'Quantity is required.', 2);
			return false;
		}
		var _left_qty = $('#edit_panel').attr('left_qty') || -1;
		if ((_left_qty >= 0) && (_deliver_qty > _left_qty)) {
			doSetVldrError(_elmDeliverQty, 'deliver_qty', 'InvalidAssignQuantity', 'Assigned qty. greater than remaining qty. ( ' + _left_qty + ' ).', 2);
			return false;
		}
		var _trEditPanel = $('#edit_panel');
		var _seq = (_current_row) ? _current_row.index() : $('#tbl_detail tbody tr:not(#edit_panel)').length + 1;
		var _title = getValue($('#edit_panel #txt-detail_title'), '');
		var _price = getValue($('#edit_panel #txt-detail_price'), 0);
		var _percent_discount = getValue($('#edit_panel #txt-percent_discount'), 0);
		var _total_amount = getValue($('#edit_panel #txt-detail_amount'), 0);
		var _rowid = _trEditPanel.attr('rowid') || -1;
		var _type_id = _trEditPanel.attr('type_id') || -1;
		var _order_rowid = _trEditPanel.attr('order_rowid') || -1;
		var _order_detail_rowid = _trEditPanel.attr('order_detail_rowid') || -1;
		
		_fncManageDataRow = function() {
			if (_current_row) _isUpdate = (_current_row.css('display') == 'none');
			if (_isUpdate) {
				var _arr = _current_row.children();
				if (_arr.length > 3) {
					$(_arr[0]).html(formatNumber(_deliver_qty, 0));
					$(_arr[1]).html(_title);
					$(_arr[2]).html(formatNumber(_price, 2));
					$(_arr[3]).html(formatNumber(_percent_discount, 0));
					$(_arr[4]).html(formatNumber(_total_amount, 2));
				}
				_blnDetailsChange = true;
				$('#btnPanelCancel').trigger('click');
			} else {
				_str = '<tr rowid="-1">';
				_str += '<td>' + formatNumber(_deliver_qty, 0) + '</td>';
				_str += '<td>' + _title + '</td>';
				_str += '<td>' + formatNumber(_price, 2) + '</td>';
				_str += '<td>' + formatNumber(_percent_discount, 0) + '%</td>';
				_str += '<td>' + formatNumber(_total_amount, 2) + '</td>';
				_str += '<td class="control-button"><img src="public/images/edit.png" class="ctrl-edit" title="Edit" /><img src="public/images/b_delete.png" class="ctrl-delete" title="Delete" /></td></tr>';
				
				var _count = $('#tbl_detail tbody tr:not("#edit_panel")').length;
				if (_count < _DETAIL_ROWS_LIMIT) {
					$('#tbl_detail tbody').append(_str);
					_blnDetailsChange = true;
					$('#btnPanelCancel').trigger('click');
				} else {
					alert(MSG_ALERT_DELIVER_DETAIL_ROWS_LIMIT.replace('v_XX_1', _DETAIL_ROWS_LIMIT));
				}
			}
		};
		var _delivery_rowid = getValue($('#hdn-rowid'), -1);
		if (_delivery_rowid > 0) {
			$("#dialog-modal").dialog( "open" );
			var _objUpdate = { "delivery_rowid": _delivery_rowid, "seq": _seq, "qty": _deliver_qty, "title": _title, "price": _price, "total_amount": _total_amount };
			if (_rowid > 0) _objUpdate['rowid'] = _rowid;
			if (_type_id > 0) _objUpdate['order_type_id'] = _type_id;
			if (_order_rowid > 0) _objUpdate['order_rowid'] = _order_rowid;
			if (_order_detail_rowid > 0) _objUpdate['order_detail_rowid'] = _order_detail_rowid;
			$.ajax({
				type: "POST", 
				url: "./delivery_detail/commit",
				contentType: "application/json;charset=utf-8",
				dataType: "json",
				data: JSON.stringify(_objUpdate),
				success: function(data, textStatus, jqXHR) {
					if (data.success == true) {
						_doDisplayToastMessage("Delivery detail updated.", 3, false);
						_fncManageDataRow();
					} else {
						doDisplayError("ErrorMessage", MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, data.error), true, 2);
					}
					$("#dialog-modal").dialog( "close" );					
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$("#dialog-modal").dialog( "close" );
					doDisplayError("ErrorMessage", MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, textStatus + ' ( ' + errorThrown + ' )'), true, 2);
				},
				statusCode: {
					404: function() {
						$("#dialog-modal").dialog( "close" );
						doDisplayError("ErrorMessage", "Page not found", false, 2);
					}
				}
			});
		} else {
			_fncManageDataRow();
		}
	});
	$('#tbl_detail tbody').on('click', '.ctrl-edit', function() {
		if (_current_row) $('#btnPanelCancel').trigger('click');
		_current_row = $($(this).parents('tr').get(0)).css('display', 'none');
		_doPopulateEditPanel(_current_row);
	});
	$('#edit_panel').on('click', '#btnPanelCancel', function() {
		if (_current_row) _current_row.css('display', '');
		$('#edit_panel').detach().appendTo($('#tbl_detail tbody'));
		_doClearEditPanel();
		$('#tbl_detail').triggerHandler('evntRowChanged');
	});
	$('#tbl_detail tbody').on('click', '.ctrl-delete', function() {
		if (confirm(MSG_CONFIRM_DELETE_ROW.replace("( v_XX_1 )", ''))) {
			var _tr = $($(this).parents('tr')[0]);
			var _rowid = _tr.attr('rowid') || -1;
			if (_rowid > 0) {
				$("#dialog-modal").dialog( "open" );
				$.ajax({
					type: "POST", 
					url: "./delivery_detail/delete/",
					contentType: "application/json;charset=utf-8",
					dataType: "json",
					data: JSON.stringify({"rowid": _rowid}),
					success: function(data, textStatus, jqXHR) {
						if (data.success == true) {
							$(this).parents('tr').get(0).remove();
							$('#tbl_detail').triggerHandler('evntRowChanged');
							_blnDetailsChange = true;
							_doDisplayToastMessage("Delivery detail deleted successfully.", 3, false);							
						} else {
							doDisplayError("ErrorMessage", MSG_ALERT_DELETE_FAILED.replace(/v_XX_1/g, data.error), true, 2);
						}
						$("#dialog-modal").dialog( "close" );
					},
					error: function(jqXHR, textStatus, errorThrown) {
						$("#dialog-modal").dialog( "close" );
						doDisplayError("ErrorMessage", MSG_ALERT_DELETE_FAILED.replace(/v_XX_1/g, textStatus + ' ( ' + errorThrown + ' )'), true, 2);
					},
					statusCode: {
						404: function() {
							$("#dialog-modal").dialog( "close" );
							doDisplayError("ErrorMessage", "Page not found", false, 2);
						}
					}
				});
			} else {
				$(this).parents('tr').get(0).remove();
				$('#tbl_detail').triggerHandler('evntRowChanged');
				_blnDetailsChange = true;
			}
		}
	});
}

function _doSetArrayValuedElement(container, dataSource, strName) {
	if (strName in dataSource) {
		var _str = dataSource[strName].trim();
		if ((_str.length > 1) && (_str.indexOf(',') >= 0)) {
			var _arr = _str.split(',');
			for (var _i=0;_i<_arr.length;_i++) {
				_strval = _arr[_i].trim();
				_elem = $('input[name*="' + strName + '"].user-input[value="' + _strval + '"]');
				if (_elem.length > 0) $(_elem.get(0)).prop('checked', true);
			}
		} else {
			_elem = $('input[name*="' + strName + '"].user-input[value="' + _str + '"]');
			if (_elem.length > 0) $(_elem.get(0)).prop('checked', true);
		}
	}
}

function _doRecalAmount() {
	var _total_amount = 0, _vat = 0, _deposit_amount = 0, _close_amount = 0, _deliver = 0, _left = 0;
	$('#tbl_detail tbody tr').each(function () {
		var _tr = $(this);
		var _amount = 0, _qty = 0;
		var _avg_deposit_payment = _tr.attr('avg_deposit_payment') || 0;
		var _avg_close_payment = _tr.attr('avg_close_payment') || 0;
		if (_tr.attr('id') == 'edit_panel') {
			_qty = getValue($('#txt-detail_qty', this), 0);
			_amount = getValue($('#txt-detail_amount', this), 0);
		} else {
			_qty = parseFloat(_cleanNumericValue($($(this).children().get(0)).html()));
			_amount = parseFloat(_cleanNumericValue($($(this).children().get(4)).html()));
		}
		if (_avg_deposit_payment > 0) _deposit_amount += (_avg_deposit_payment * _qty);
		if (_avg_close_payment > 0) _close_amount += (_avg_close_payment * _qty);
		_total_amount += _amount;
	});
	var _is_vat = getValue($('#sel-is_vat')) || 0;
	if (_is_vat == 1) {
		_vat = Math.round(_total_amount * 7) / 100;
		$('#frm_edit #txt-vat').val(formatNumber(_vat, 3));
	} else if (_is_vat == 2) {
		_vat = Math.round(_total_amount * 7) / 107;
		_total_amount = _total_amount - _vat;
		$('#frm_edit #txt-vat').val(formatNumber(_vat, 3));
	} else {
		_vat = 0;
		$('#frm_edit #txt-vat').val('');
	}
	$('#frm_edit #txt-total').val(formatNumber(_total_amount, 3));
	$('#frm_edit #txt-grand_total').val(formatNumber(_total_amount + _vat, 3));
	setValue($('#txt-deposit_amount'), _deposit_amount);
	setValue($('#txt-payment_amount'), _close_amount);
	_deliver = parseFloat(_cleanNumericValue($('#txt-deliver_amount').val()));
	if ((_deliver == '') || (isNaN(_deliver))) _deliver = 0;
	
	$('#frm_edit #txt-left_amount').val(formatNumber(_total_amount + _vat + _deliver - _deposit_amount - _close_amount, 3));
}

//++ details panel function
function _doPopulateEditPanel(current_row) {
	var _elmRow = $(current_row);
	var _trEditPanel = $('#edit_panel');
	if (_trEditPanel.length < 1) return false;

	var _rowid = _elmRow.attr('rowid') || -1;
	var _type_id = _elmRow.attr('type_id') || -1;
	var _order_rowid = _elmRow.attr('order_rowid') || -1;
	var _order_detail_rowid = _elmRow.attr('order_detail_rowid') || -1;
	var _left_qty = _elmRow.attr('left_qty') || 0;
	var _avg_deposit_payment = _elmRow.attr('avg_deposit_payment') || 0;
	var _avg_close_payment = _elmRow.attr('avg_close_payment') || 0;

	if (_rowid > 0) _trEditPanel.attr("rowid", _rowid);
	if (_type_id > 0) _trEditPanel.attr("type_id", _type_id);
	if (_order_rowid > 0) _trEditPanel.attr("order_rowid", _order_rowid);
	if (_order_detail_rowid > 0) _trEditPanel.attr("order_detail_rowid", _order_detail_rowid);
	if (_avg_deposit_payment > 0) _trEditPanel.attr("avg_deposit_payment", _avg_deposit_payment);
	if (_avg_close_payment > 0) _trEditPanel.attr("avg_close_payment", _avg_close_payment);
	var _elmDispLeftQty = $('#edit_panel #disp_left_qty');
	if (_left_qty > 0) {
		_trEditPanel.attr("left_qty", _left_qty);
		setValue(_elmDispLeftQty, ' / ' + formatNumber(_left_qty, 0));
		_elmDispLeftQty.show();
	} else {
		clearValue(_elmDispLeftQty);
		_elmDispLeftQty.hide();
	}
	var _arrItems = _elmRow.children();
	setValue($('#edit_panel #txt-detail_qty'), $(_arrItems[0]).html().replace(/,/g, ''));
	setValue($('#edit_panel #txt-detail_title'), $(_arrItems[1]).html());
	setValue($('#edit_panel #txt-detail_price'), $(_arrItems[2]).html().replace(/,/g, ''));
	setValue($('#edit_panel #txt-percent_discount'), $(_arrItems[3]).html().replace(/,/g, ''));
	setValue($('#edit_panel #txt-detail_amount'), $(_arrItems[4]).html().replace(/,/g, ''));

	$('#btnPanelSubmit').prop('title', 'Edit');
	$('#btnPanelSubmit').prop('act', 'update');
	$('#btnPanelCancel').prop('title', 'Cancel');
	$('#btnPanelCancel').prop('act', 'cancel');

	$('#tbl_detail tbody tr#edit_panel td.control-button').css('display', '');
	$('#tbl_detail tbody tr#edit_panel td .edit-ctrl:not("#txt-detail_amount")').each(function () {
		_setEnableElem(this, true);
	});
	_enableElem($('#edit_panel #txt-percent_discount'), false);
	if ((_type_id > 0) && (_order_rowid > 0)) {
		_enableElem($('#edit_panel #txt-detail_title'), false);
		_enableElem($('#edit_panel #txt-detail_price'), false);
	} else {
		_enableElem($('#edit_panel #txt-detail_title'), true);
		_enableElem($('#edit_panel #txt-detail_price'), true);
	}
	$('#edit_panel').detach().insertAfter(current_row).show();
}

function _doClearEditPanel() {
	doClearVldrError($('#edit_panel'));
	
	$('#edit_panel').removeAttr('rowid').removeAttr('type_id').removeAttr('order_rowid').removeAttr('order_detail_rowid').removeAttr('left_qty');
	clearValue($('#edit_panel #disp_left_qty'));
	$('#edit_panel #disp_left_qty').hide();
	
	$('#edit_panel #txt-detail_qty').val('');
	$('#edit_panel #txt-detail_title').val('');
	$('#edit_panel #txt-detail_price').val('');
	$('#edit_panel #txt-percent_discount').val('');
	$('#edit_panel #txt-detail_amount').val('');

	_enableElem($('#edit_panel #txt-detail_title'), true);
	_enableElem($('#edit_panel #txt-detail_price'), true);

	$('#btnPanelSubmit').prop('title', 'Insert');
	$('#btnPanelSubmit').prop('act', 'insert');
	$('#btnPanelCancel').prop('title', 'Reset');
	$('#btnPanelCancel').prop('act', 'reset');
	_current_row = false;
}
function isEditingRow() {
	if (($('#edit_panel #txt-detail_qty').val() != '') || ($('#edit_panel #txt-detail_title').val() != '') || ($('#edit_panel #txt-detail_price').val() != '')) {
		return true;
	} else {
		return false;
	}
}
//-- details panel function
var _blnCheckLoadingDetail = false;
function _doGetDetails(rowid, fncCallback) {
	var _rowid = rowid || -1;
	_blnCheckLoadingDetail = true;
	//if (!$("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_QUERY.replace(/v_XX_1/g, '')).dialog('option', 'html', "<p>" + MSG_DLG_HTML_QUERY + "</p>");
	$.ajax({
		type:"POST",
		url:"./delivery/get_detail_by_id",
		dataType:"json",
		data: 'rowid=' + _rowid,
		success: function(data, textStatus, jqXHR) {
			if (data.success == false) {
				alert(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error));
				$("#dialog-modal").dialog( "close" );
			} else {
				//run first since it has clearform function
				if (('linked_job_number' in data) && (data.linked_job_number.length > 0)) {
					for (var _i=0;_i<data.linked_job_number.length;_i++) {
						_row = data.linked_job_number[_i];
						_evntJobNumerSelected('query', {'item': {
							"rowid": _row['rowid']
							, "type_id": _row['order_type_id']
							, "order_rowid": _row['order_rowid']
							, "job_number": _row['job_number']
							, "category": _row['category']
							, "type": _row['type']
							, "customer": _row['customer']
							, "company": _row['company']
							, "disp_order_date": _row['disp_order_date']
						}});
					}
				}
				$('#tbl_detail tbody tr:not(#edit_panel)').remove();
				if (('details' in data) && (data.details.length > 0)) {
					for (var _i=0;_i<data.details.length;_i++) {
						_row = data.details[_i];
						_str = '<tr rowid="' + _row['rowid'] + '" type_id="' + _row['type_id'] + '" order_rowid="' + _row['order_rowid'] + '" order_detail_rowid="' + _row['order_detail_rowid'] + '" left_qty="' + _row['left_qty'] + '" avg_deposit_payment="' + _row['avg_deposit_payment'] + '" avg_close_payment="' + _row['avg_close_payment'] + '" >';
						_str += '<td>' + _row['qty'] + '</td>';
						_str += '<td>' + _row['title'] + '</td>';
						_str += '<td>' + formatNumber(parseFloat(_row['price']), 2) + '</td>';
						_str += '<td>' + parseFloat(_row['percent_discount']) + '%</td>';
						_str += '<td>' + formatNumber(parseFloat(_row['actual_amount']), 2) + '</td>';
						_str += '<td class="control-button"><img src="public/images/edit.png" class="ctrl-edit" title="Edit"><img src="public/images/b_delete.png" class="ctrl-delete" title="Delete"></td></tr>';
						
						$('#tbl_detail tbody').append(_str);
					}
					$('#tbl_detail tbody tr#edit_panel').hide().detach().appendTo($('#tbl_detail tbody'));
					$('#tbl_detail').triggerHandler('evntRowChanged');
				}
				_blnDetailsChange = false;
				$('#edit_panel').detach().appendTo($('#tbl_detail tbody'));
				_doRecalAmount();

				// ++ invis .eventView-hide
				if ($('#frm_edit #btnFormSubmit').css('display') == 'none') { //view
					$('th.eventView-hide').each(function () {
						var _jqTable = $(this).parents('table').get(0);
						var _indx = $(this).index();
						$('tbody tr', _jqTable).each(function () {
							$($('td', this).get(_indx)).addClass('eventView-hide');
						});
					});
					$('.eventView-hide').addClass('hidden');
				} else {
					$('.eventView-hide').removeClass('hidden');
				}
				// -- invis .eventView-hide
				if ($.isFunction(fncCallback)) fncCallback();
				
				if ($("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog( "close" );
				_blnCheckLoadingDetail = false;
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage");
			if ($("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog( "close" );
			_blnCheckLoadingDetail = false;
		},
		statusCode: {
			404: function() {
				doDisplayInfo("Page not found", "ErrorMessage");
				if ($("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog( "close" );
				_blnCheckLoadingDetail = false;
			}
		}
	});
	 //delay to run after editfor show modal ( in _list.js )
	setTimeout(
		function() {
			if (_blnCheckLoadingDetail) {
				$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_QUERY.replace(/v_XX_1/g, '')).dialog('option', 'html', "<p>" + MSG_DLG_HTML_QUERY + "</p>");
				$("#dialog-modal").dialog( "open" );
			}
		}
	, 300);
}

function _evntJobNumerSelected(ev, ui) {
	if (ui.item) {
		var _rowid = ui.item.rowid || -1;
		var _div = $('<div>').addClass('cls-selected-jobnumber')
			.attr('rowid', _rowid)
			.attr('type_id', ui.item.type_id)
			.attr('order_rowid', ui.item.order_rowid)
			.attr('job_number', ui.item.job_number)
			.attr('category', ui.item.category)
			.attr('type', ui.item.type)
			.attr('customer', ui.item.customer)
			.attr('company', ui.item.company)
			.attr('disp_order_date', ui.item.disp_order_date)
			.html(ui.item.job_number)
			.append('<div class="cls-remove-selected-jobnumber eventView-hide"></div>')
			.appendTo($('#divDispSelectedJobNumber'))
		;
		
		var _type_id = ui.item.type_id;
		var _order_rowid = ui.item.order_rowid;
		if ((typeof ev == 'object')) {
			if (!$("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_QUERY.replace(/v_XX_1/g, '')).dialog('option', 'html', "<p>" + MSG_DLG_HTML_QUERY + "</p>").dialog( "open" );
			$.ajax({
				type: "GET"
				, url: "./delivery/list_detail_items/" + _type_id + '/' + _order_rowid
				, dataType:"json"
				, success: function(data, textStatus, jqXHR) {
					if (data.success == false) {
						alert(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error));
						$("#dialog-modal").dialog( "close" );
					} else {
						if (('data' in data) && (data.data.length > 0)) {
							var _row = data.data[0];
							setValue($('#aac-customer_display'), _row['customer_name']);
							setValue($('#hdn-customer_name'), _row['customer_name']);
							setValue($('#hdn-customer_rowid'), _row['customer_rowid']);
							setValue($('#txt-company'), _row['company']);
							setValue($('#txt-tel'), _row['contact_no']);
							
							var _sel = $('#sel-customer_address').empty();
							_sel.append('<option value="' + _row['address'] + '" tel="' + _row['contact_no'] + '" selected="selected">' + _row['address'] + '</option>');
							setValue(_sel, _row['address']);
							
							setValue($('#sel-is_vat'), _row['is_vat']);
							setValue($('#txt-vat'), getValue($('#txt-vat'), 0) + _row['total_price_sum_vat']);
							setValue($('#txt-total'), getValue($('#txt-total'), 0) + _row['total_price_sum']);
							
							for (var _i=0;_i<data.data.length;_i++) {
								_row = data.data[_i];
								var _order_detail_rowid = _row['order_detail_rowid'] || '';
								var _left_qty = _row['qty'] || 0;
								_str = '<tr rowid="-1" type_id="' + _type_id + '" order_rowid="' + _order_rowid + '" order_detail_rowid="' + _order_detail_rowid + '" left_qty="' + _left_qty + '">';
								_str += '<td>' + _left_qty + '</td>';
								_str += '<td>' + _row['disp_title'] + '</td>';
								_str += '<td>' + formatNumber(parseFloat(_row['price']), 3) + '</td>';
								_str += '<td>' + formatNumber(parseFloat(_row['total_price_sum_net']), 3) + '</td>';
								_str += '<td class="control-button"><img src="public/images/edit.png" class="ctrl-edit" title="Edit" /><img src="public/images/b_delete.png" class="ctrl-delete" title="Delete" /></td></tr>';
								//$('#tbl_detail tbody').prepend(_str);
								$(_str).insertBefore($('#tbl_detail #edit_panel'));
							}
							$('#tbl_detail').triggerHandler('evntRowChanged');
						}
						$("#dialog-modal").dialog( "close" );
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage");
					$("#dialog-modal").dialog( "close" );
				},
				statusCode: {
					404: function() {
						doDisplayInfo("Page not found", "ErrorMessage");
						$("#dialog-modal").dialog( "close" );
					}
				}
			});
		}
	}
	if ((typeof ev == 'object') && ('target' in ev)) $(ev.target).val("");
	return false;
}

function _evntCustomerSelected(ev, ui) {
	_clearElemValue($('#frm_edit #hdn-customer_rowid'));
	_clearElemValue($('#frm_edit #txt-tel'));
	_clearElemValue($('#frm_edit #txt-company'));
	
	var _sel = $('#sel-customer_address');
	_sel.empty();
	_clearElemValue(_sel);
	_setEnableElem(_sel, false);
	if (ui.item) {
		_aac_text = ui.item.value.toString().trim();
		_rowid = ui.item.rowid.toString().trim();
		_customer_name = ui.item.customer_name.toString().trim();
		_company = ui.item.company.toString().trim();
		_tel = ui.item.tel.toString().trim();
		//_address = ui.item.address.toString().trim();
		_arr_full_addresses = JSON.parse(ui.item.addresses.toString().trim());
		
		$('#frm_edit #aac-customer_display').val(_aac_text);
		$('#frm_edit #hdn-customer_rowid').val(_rowid);
		$('#frm_edit #hdn-customer_name').val(_customer_name);
		$('#frm_edit #txt-company').val(_company);
		var _blnSelected = false;
		for (var _i=0;_i<_arr_full_addresses.length;_i++) {
			var _ea = _arr_full_addresses[_i];
			var _cus_addr_rowid = ('customer_address_rowid' in _ea) ? parseInt(_ea['customer_address_rowid']) : -1;
			if (_cus_addr_rowid > 0) {
				var _address = ('address' in _ea) ? _ea['address'] : '';
				var _addr_tel = ('tel' in _ea) ? _ea['tel'] : '';
				var _opt = $('<option>').val(_address).html(_address).appendTo(_sel);
				_opt.attr('tel', _addr_tel);
				if (! _blnSelected) {
					var _is_default = ('is_default' in _ea) ? parseInt(_ea['is_default']) : 0;
					if (_is_default > 0) {
						_setElemValue(_sel, _opt.val());
						evntCustomerAddressSelected('', '', {item:{option:{value:_opt.val()}}});
						_blnSelected = true;
					}
				}
			}
		}
		if (! _blnSelected) {
			var _opt = $('#sel-customer_address option:first');
			if (_opt) {
				_setElemValue(_sel, _opt.val());
				evntCustomerAddressSelected('', '', {item:{option:{value:_opt.val()}}});
			}
		}
		if ($('option', _sel).length > 1) _setEnableElem(_sel, true);
	}
	return false;
}

function evntCustomerAddressSelected(str, ev, ui) {	
	if ((ui.item) && (typeof ui.item != 'undefined')) {
		var _selectedOpt = $('#sel-customer_address option:selected');
		$('#frm_edit #txt-tel').val(_selectedOpt.attr('tel'));
	}
	return false;
}

function customCommand(command, aData, tr, divEditDlg) {
	if ('rowid' in aData) {
		var _rowid = aData['rowid'] || '-1';
		var _url = '';
		if ((command.toLowerCase() == 'pdf')) {
			window.open("./delivery/get_pdf/" + _rowid);
		} else if (command.toLowerCase() == 'approve') {
			if (confirm('กรุณากดยืนยัน เพื่อดำเนินการ Approve รายการนำส่งสินค้า')) {
				_url = './delivery/approve/' + _rowid;
			} else {
				return false;
			}
		} else if (command.toLowerCase() == 'revoke') {
			if (confirm('กรุณากดยืนยัน เพื่อดำเนินการยกเลิกสถานะ Approved')) {
				_url = './delivery/revoke/' + _rowid;
			} else {
				return false;
			}
		} else {
			return false;
		}
		$.ajax({
			type: "GET",
			url: _url,
			contentType: "application/json;charset=utf-8",
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if (('success' in data) && (data.success)) {
					if (command.toLowerCase() == 'approve') {
						_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, 'Approve ใบนำส่งสินค้าสำเร็จแล้ว !'), 3, false);
					} else {
						_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, 'Revoked ใบนำส่งสินค้าสำเร็จแล้ว !'), 3, false);				
					}
					doSearch(false);
				} else {
					_doDisplayToastMessage(MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, ' +'+ data['error'] +'!'), 3, false);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				doDisplayInfo(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, textStatus + ' ( ' + errorThrown + ' )'), "ErrorMessage");
			},
			statusCode: {
				404: function() {
					console.log('Error 404')
				}
			}
		});
	}
}

function _doCreateNew(customer_rowid, customer_name) {
	var _cus_rowid = customer_rowid || -1;
	var _cus_name = customer_name || '';
	if ((window.opener == null) && (_cus_rowid > 0) && (window.history.length > 1)) {
		//$('<a href="customer/index/' + _cus_rowid + '">Back</a>')
		$('<a onclick="window.history.back();">Back</a>')
			.button({icons:{primary: 'ui-icon-arrowthick-1-w'}})
			.addClass('cls-navigator')
			.insertBefore($('#frmSearch'));
	}
	var _divEditDlg = $("#divFormEditDialog");
	doInsert(_divEditDlg);
	var _frm = $(_divEditDlg).find(".cls-frm-edit").get(0);
	$('#aac-customer', _frm).val(_cus_name);
	$('#hdn-customer_rowid', _frm).val(_cus_rowid);
	/*
	$('#sel-customer_rowid', _frm).combobox('setValue', _cus_rowid);
	evntCustomerSelected('', '', {item: {option: $('#frm_edit #sel-customer_rowid option:selected').get(0)}});
	*/
}

function _doUnlinkDeliverJobNumber(rowid, deliver_rowid, elemUI) {
	if ((rowid > 0) && (deliver_rowid > 0)) {
		$.ajax({
			dataType: "json", type: 'GET', url: './delivery/unlink_job_number/' + rowid + '/' + deliver_rowid
			, success: function(data) {
				if ((data.success == true)) {
					if ((data.error) && (data.error.trim() != '')) {
						alert(MSG_ALERT_DELETE_SUCCESS.replace(/v_XX_1/g, data.error));
					}
					if ((elemUI) && (elemUI.length > 0)) elemUI.remove();
				} else {
					alert(MSG_ALERT_DELETE_FAILED.replace(/v_XX_1/g, data.error));
				}
			}
			, error: function(data) {
				console.log(data);
			}
		});
	} else {
		if ((elemUI) && (elemUI.length > 0)) elemUI.remove();		
	}
}

function _doExtCmdView(rowid, code, customer_rowid) {
	var _rowid = rowid || -1;
	var _code = code || '';
	var _cus_rowid = customer_rowid || -1;
	if ((window.opener == null) && (_cus_rowid > 0) && (window.history.length > 1)) {
		$('<a onclick="window.history.back();">Back</a>')
			.button({icons:{primary: 'ui-icon-arrowthick-1-w'}})
			.addClass('cls-navigator')
			.insertBefore($('#frmSearch'));
	}
	if (_code.trim() != '') {
		clearValue($('#txt-date_from.search-param'));
		clearValue($('#txt-date_to.search-param'));
		setValue($('#txt-deliver_job_number.search-param'), _code);
		_currentDataString = 'code=' + _code + '&';
	}
	if (_cus_rowid > 0) _currentDataString = 'customer_rowid=' + _cus_rowid;
	$('#divDisplayQueryResult').on('load_done', function() {
		var _divEditDlg = $("#divFormEditDialog");
		var _arrData = _objDataTable.fnGetData()
		var _data;
		if (_arrData.length > 0) {
			for (var _x in _arrData) {
				if (_arrData[_x].rowid == _rowid) {
					_data = _arrData[_x];
					break;
				}
			}
			if ((typeof _data != undefined) && (typeof doView == 'function')) doView(_data, _divEditDlg);
		}
		$('#divDisplayQueryResult').off('load_done', arguments.callee);
	});
	doSearch(false);
}

/* -- Old submit form procedure */
