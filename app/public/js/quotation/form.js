var _QO_YEAR = (new Date()).getFullYear();
var _DLG_STATUS_REMARK = false;
$(function() {
//	$('.cls-div-form-edit-dialog[index="0"]').dialog('option', 'height', 520);
//	$('.cls-div-form-edit-dialog[index="1"]').dialog('option', 'height', 500);
	_DLG_STATUS_REMARK = $('#div_status_remark').dialog({
		height: 180
		, width: 780
		, show: {effect:"puff", duration: 1000}
		, hide: {effect:"fade", duration:1000}
		, modal: true
		, resizable: true
		, closeOnEscape: true
		, autoOpen: false
		, beforeClose: function(event, ui) {
			$(this).removeAttr('status_rowid').removeAttr('status_text');
		}
		, buttons: {
			'Commit': function() {
				var _rowid = $(this).attr('qo_rowid') || false;
				var _code = $(this).attr('qo_code') || false;
				var _curr_status = $(this).attr('curr_status_text') || false;
				var _status_rowid = $(this).attr('status_rowid') || false;
				var _status_text = $(this).attr('status_text') || false;
				var _remark = getValue($('#sel-status_remark'), '');
				_remark += ' ' + getValue($('#txa-status_remark'), '');
				_remark = _remark.trim();
				
				doClearVldrErrorElement($('#sel-status_remark'));
				doClearVldrErrorElement($('#txa-status_remark'));
				if (_remark == '') {
					doSetVldrError($('#sel-status_remark'), 'status_remark', 'required', 'กรุณาระบุเหตุผลในการเปลี่ยนเป็นสถานะ', 1);
					doSetVldrError($('#txa-status_remark'), 'status_remark', 'required', 'กรุณาระบุเหตุผลในการเปลี่ยนเป็นสถานะ', 1);
					_doDisplayToastMessage('กรุณาระบุเหตุผลในการเปลี่ยนเป็นสถานะ \"' + _status_text + '\"', 3, false);
				} else {
					var _str = 'id:' + _rowid;
					if (_code) _str = 'เลขที่ \"' + _code + '\"';
					if (confirm('กรุณายืนยันการเปลี่ยนสถานะของใบเสนอราคา ' + _str + ' เป็นสถานะ "' + _status_text + '"')) {
						__doChangeQuotationStatus(_rowid, _status_rowid, _remark, function() {
							clearValue($('#sel-status_remark'));
							clearValue($('#txa-status_remark'));
						});
						$(this).dialog('close');
					}
				}
				return false;
			}
			, 'Cancel': function() {
				$(this).dialog('close');
			}
		}
	});
	
	//++ Validation
	$('.input-integer').on('change paste keyup', function (ev) {
		doClearVldrErrorElement(this);
		blnValidateElem_TypeInt(this);
	});
	$('.input-double').on('change paste keyup', function (ev) {
		doClearVldrErrorElement(this);
		if (blnValidateElem_TypeDouble(this)) {
			var _index = ($(this).parents('[index]').length > 0) ? $($(this).parents('[index]')[0]).attr('index') : 0;
			if (_index == 1) {
				_doUpdateTotalValue(1);
			}
		}
	});
	//-- Validation
	//++ ChangeStatus
	$('body').on('change', '#tblSearchResult .cls-sel-change-status', function() {
		var _rowid = $(this).attr('qo_rowid') || -1;
		var _code = $(this).attr('qo_code') || false;
		var _curr_status_text = $(this).attr('curr_status_text') || false;
		var _selOpt = $('option:selected', this);
		var _selText = _selOpt.attr('name') || false;
		var _status_rowid = (_selOpt.length > 0) ? _selOpt.val() : -1;
		var _status_text = (_selOpt.length > 0) ? _selOpt.html() : '';

		var _str = 'id:' + _rowid;
		if (_code) _str = 'เลขที่ \"' + _code + '\"';
		if ((_rowid > 0) && (_status_rowid > 0)) {
			// 100:CMP, 180:CNL, 200:CLO
			if ((_status_rowid > 100) && (_status_rowid < 200)) {
				$('#div_status_remark').attr('qo_rowid', _rowid).attr('qo_code', _code)
					.attr('curr_status_text', _curr_status_text)
					.attr('status_rowid', _status_rowid).attr('status_text', _status_text);
				_DLG_STATUS_REMARK.dialog('option', 'title', 'ใบเสนอราคา' + _str + ' เปลี่ยนสถานะ จาก \"' + _curr_status_text + '\" เป็น \"' + _selText + '\"').dialog( "open" );
			} else {
				if (confirm('กรุณายืนยันการเปลี่ยนสถานะของใบเสนอราคา ' + _str + ' เป็นสถานะ "' + _status_text + '"')) __doChangeQuotationStatus(_rowid, _status_rowid);
			}
		}
		$(this).val($.data(this, 'current'));
		return false;
	});

	$('#frm_edit #txt-start_date').on("change", function() {
		var _val = $(this).val();
		var _dummydate = $.datepicker.parseDate('dd/mm/yy', _val);
		if (Object.prototype.toString.call(_dummydate) === '[object Date]') {
			if (_dummydate.getFullYear() == _QO_YEAR) return;
			_QO_YEAR = _dummydate.getFullYear();
			var _strParams = '{"start_date":"' + _val + '"}';
			$("#dialog-modal").dialog( "open" );
			doClearDisplayInfo();
			$.ajax({
				type: "POST", 
				url: "./quotation/json_get_qonumber",
				contentType: "application/json;charset=utf-8",
				dataType: "json",
				data: _strParams,
				success: function(data, textStatus, jqXHR) {
					if (data.success == false) {
						doDisplayInfo(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error), 'Get_QO_Number');
					} else {
						if (('qo_number' in data)) {
							$('#frm_edit #txt-qo_number').val(data['qo_number']);
						} else {
							doDisplayInfo(MSG_ALERT_QUERY_NO_DATA_FOUND, 'Get_QO_Number');
						}
					}
					$("#dialog-modal").dialog( "close" );					
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$("#dialog-modal").dialog( "close" );
					doDisplayInfo(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, textStatus + ' ( ' + errorThrown + ' )'), "ErrorMessage");
				},
				statusCode: {
					404: function() {
						$("#dialog-modal").dialog( "close" );
						doDisplayInfo("Page not found", "ErrorMessage");
					}
				}
			});
		}
		return false;
	});
	$('#txt-percent_discount').on('change', function () {
		_doUpdateTotalValue(1);
	});
	$('#sel-is_vat').on('change', function () {
		_doUpdateTotalValue(1);
	});

	$('#txt-deposit_percent').on('change', function () {
		_doUpdateDepositValues(getValue($(this), -1), -1);
	});
	$('#txt-deposit_amount').on('change', function () {
		_doUpdateDepositValues(-1, getValue($(this), -1));
	});

	$('.cls-frm-edit').on('click', '#btnDepositPaymentDialog', function() {
		var _rowid = $('.cls-frm-edit[index=0] #hdn-rowid').val() || -1;		
		var _status_rowid = $('.cls-frm-edit[index=0] #hdn-status_rowid').val() || 10;
		
		var _arr_deposit_log = getValue($('.cls-frm-edit[index=0] #hdn-arr_deposit_log'), []);
		if (typeof _arr_deposit_log == 'string') _arr_deposit_log = JSON.parse(_arr_deposit_log);

		var _arr_payment_log = getValue($('.cls-frm-edit[index=0] #hdn-arr_payment_log'), []);
		if (typeof _arr_payment_log == 'string') _arr_payment_log = JSON.parse(_arr_payment_log);

		var _grand_total = getValue($('.cls-frm-edit[index=0] #spn-grand_total'), 0);
		var _deposit_amount = getValue($('.cls-frm-edit[index=0] #txt-deposit_amount'), 0);
		
		_openPaymentListDialog({
			"status_rowid": _status_rowid
			,"grand_total": _grand_total
			,"deposit_amount": _deposit_amount
			,"deposit":
				//{"constant": {"quotation_rowid": _rowid}, "arr_payment_list": _arr_deposit_log, "is_editable": ((_status_rowid < 70) || (_status_rowid == 150) || (_status_rowid == 151)), "is_approveable": ((_status_rowid < 70) || (_status_rowid == 150) || (_status_rowid == 151)) }
				{"constant": {"quotation_rowid": _rowid}, "arr_payment_list": _arr_deposit_log, "is_editable": true, "is_approveable": true }
			, "after_deposit":
				{"constant": {"quotation_rowid": _rowid}, "arr_payment_list": _arr_payment_log, "is_enable": ((_status_rowid >= 70)), "is_editable": true, "is_approveable": true }
		});
		return false;
	});

	var _fncTemplate_doClearForm = _doClearForm;
	var _fncTemplate_doInsert = doInsert;
	var _fncTemplate_doView = doView;
	var _fncTemplate_doEdit = doEdit;
	var _fncTemplate_doSubmit = doSubmit;
	var _fncTemplate_doDelete = doDelete;
	var _fncTemplate_blnDataChanged = blnDataChanged;

	_doClearForm = function (_frm) {
		_fncTemplate_doClearForm.apply(this, arguments);
		var _index = $(_frm).attr('index') || 0;
		if (_index == 0) {
			$('#tabs').tabs({ active: 0 });
			$("#tabs").tabs( "disable" , 1 );
			_populateSublistDataTable(1, [], true);
			_QO_YEAR = '';

			setValue($('#txt-deposit_percent'), 50, false);
		} else {
			//++ premade details size
			$('#tbl_detail_list tbody tr:not("#edit_panel1")', _frm).remove();
			$('#tbl_detail_size tbody tr:not("#edit_panel2")', _frm).remove();
			$('.total-value').html(' -- ');
			$('.total-price').html(' -- ');
			doClearDetailsEditPanel();
			_blnDetailChanged = false;
			//-- premade details size

			//++ set size cat and hide another table
			$(".tbl_size_cat", _frm).css('display', 'none');
			$('.total-value').html(' -- ');
			$('.total-price').html(' -- ');
			//-- set size cat and hide another table
			
			//remove others price items
			$('#tbl_op_list tbody tr:not("#op_edit_panel")', _frm).remove();
			opClearEditPanel();
			_blnOpChanged = false;

			//remove screen items
			$('#tbl_sc_list tbody tr:not("#sc_edit_panel")', _frm).remove();
			scClearEditPanel();
			_blnScChanged = false;

			$('.user-input', $('#div_PO_props')).each(function() {
				clearValue($(this));
			});
			$('#txt-po_order_date').datepicker("option", "disabled", false);
			$('#div_PO_props').css("display", "none");
			$('#div_PO_remarks').css("display", "none");

			_blnDetailsChange = false;

			$('.cls-is-expired.hidden', _frm).removeClass('hidden');
			$('.cls-is-expired.has-value', _frm).removeClass('has-value');

			$('#divDetailTabs').tabs({ active: 0 });
			$('#tabMnuDetail').addClass('hidden');
			$('#tabMnuOthers').addClass('hidden');
			$('.cls-detail-panel').each(function() { $(this).addClass('hidden'); });
			$('.cls-others-panel').each(function() { $(this).addClass('hidden'); });

			$("div.display-upload", _frm).css('background-image', '');
		}
	};

	doInsert = function (divEditDlg) {
		_fncTemplate_doInsert.apply(this, arguments);
		var _prnt = $('.cls-frm-edit', divEditDlg);
		var _index = _prnt.attr('index') || 0;
		if (_index == 0) {
			$('#txt-start_date', _prnt).datepicker('setDate', new Date());
			$('#txt-start_date', _prnt).trigger('change');
			
			setValue($('#sel-day_limit', _prnt), 30);
			
			$('[from_qs]', _prnt).filter(function() {
				return (parseInt($(this).attr('from_qs')) > 0);
			}).each(function() {
				$(this).addClass('hidden').css('display', 'none');
			});
		} else {			
			//enable upload images
			$(".spn-image-select", _prnt).css('display', '');
			$(".spn-image-select input", _prnt).prop('disabled', false);
			
			//visible .eventView-hide
			$('.eventView-hide', _prnt).removeClass('hidden');

			//hide all frm reset, hide 3 first submit (for check all controls before insert)
			$('.cls-btn-form-reset', _prnt).addClass('hidden').css('display', 'none');
			$('.cls-btn-form-submit', _prnt).addClass('hidden').css('display', 'none');
			$('.cls-btn-form-submit', _prnt).last().removeClass('hidden').css('display', '');
			__fncManageExpired(false);
		}
		_fnc_onChangePaymentCondition();
	};

	doView = function(dataRowObj, divEditDlg) {
		_fncTemplate_doView.apply(this, arguments);
		var _index = $('.cls-frm-edit', divEditDlg).attr('index') || 0;
		if (_index == 0) {
			if (('rowid' in dataRowObj) && (dataRowObj['rowid'] > 0)) $("#tabs").tabs( "enable" , 1 );
			
			var _qo_status = parseInt(dataRowObj['status_rowid'] || 0);
			__fncCheckVisibleElementsByStatus(_qo_status, divEditDlg);
		} else {
			__fncPopulateSpecialControls(dataRowObj, divEditDlg);
			__fncManageExpired(false);
		}
		_fnc_onChangePaymentCondition();
		$('.eventView-hide').addClass('hidden');
		_blnDetailsChange = false;
	};

	doEdit = function(dataRowObj, trObj, divEditDlg) {
		_fncTemplate_doEdit.apply(this, arguments);		
		var _index = $('.cls-frm-edit', divEditDlg).attr('index') || 0;
		if (_index == 0) {
			if (('rowid' in dataRowObj) && (dataRowObj['rowid'] > 0)) $("#tabs").tabs( "enable" , 1 );

			var _qo_status = parseInt(dataRowObj['status_rowid'] || 0);
			__fncCheckVisibleElementsByStatus(_qo_status, divEditDlg);
		} else {
			__fncPopulateSpecialControls(dataRowObj, divEditDlg);
			__fncManageExpired(false);
		}
		_fnc_onChangePaymentCondition();
		_blnDetailsChange = false;
	};

	doSubmit = function (form) {
		if (blnDataChanged(form) == false) {
			alert(MSG_ALERT_COMMIT_NO_CHANGE);
			return false;
		}
		var _formIndex = $(form).attr('index');
		if (_currEditData == undefined) _currEditData = {};
		if (_formIndex == 0) { //main submit
			_doCommitUserControlsChanged(form);
			var _update = {};
			if (_currEditData !== undefined) _update = $.extend(true, {}, _currEditData);
			var _isInsert = (! (('rowid' in _update) && (_update['rowid'] > 0)));
			__fncSubmitQuotation(form, _formIndex, _update, function(data, textStatus, jqXHR) {
				if (data.success == false) {
					alert(MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, data.error));
					$("#dialog-modal").dialog( "close" );
				} else {
					_currEditTr = undefined;
					_currEditData = undefined;
					_doClearForm(form);

					var _rowid = -1;
					if (_isInsert) _rowid = ((('message' in data) && (_isInt(data.message))) ? parseInt(data.message) : -1);
					var opt_fncCallBack = function() {
						if (_rowid > 0) {
							var _arrTrs = _objDataTable.DataTable().rows().nodes();
							var _insData = false;
							var _insRowTr = false;
							if ((_arrTrs.length > 0)) {
								$.each(_arrTrs, function(idx, nTr) {
									var _nData = _objDataTable.DataTable().row(nTr).data() || false;
									if ((_nData) && ('rowid' in _nData) && (_nData['rowid'] == _rowid)) {
										_insData = _nData;
										_insRowTr = nTr;
										return false;
									}
								});
							}
							if (_insData && _insRowTr) {
								var _divEditDlg = $(".cls-div-form-edit-dialog[index=" + _formIndex + "]");
								doEdit(_insData, _insRowTr, _divEditDlg);
							}
						}
					};

					var _divDialog = $(form).parents(".cls-div-form-edit-dialog").get(0);
					if (typeof _divDialog != 'undefined') {
						if ($(_divDialog).attr("id").indexOf("Sublist") >= 0) {
							
						} else {
							if (typeof doSearch == 'function') doSearch(false, opt_fncCallBack);
						}
						if ($(_divDialog).dialog( "isOpen" )) $(_divDialog).dialog( "close" );
					}
					_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, ''), 3, false);
					$("#dialog-modal").dialog( "close" );
				}
			});
		} else {
			if (isScEditingRow()) {
				var _sc_edit_price = $('#sc_edit_panel #txt-sc_price').get(0);
				doClearVldrErrorElement(_sc_edit_price);
				if (! blnValidateElem_TypeDouble(_sc_edit_price)) {
					return false;
				}
			}
			if (isOpEditingRow()) {
				var _op_edit_price = $('#op_edit_panel #txt-op_price').get(0);
				doClearVldrErrorElement(_op_edit_price);
				if (! blnValidateElem_TypeDouble(_op_edit_price)) {
					return false;
				}
			}
			
			if (isDetailsEditingRow()) {
				alert('พบข้อมูลรายละเอียดที่แก้ไขหรือสร้างใหม่โดยยังไม่ได้ทำการยืนยัน, กรุณายืนยันหรือยกเลิกก่อนทำการบันทึก');
				return false;
			}

			_doUpdateDetailValues();
			_doUpdateTotalValue(1);
			__fncDPUpdateTotalDeposit();

			var _rowid = getValue($('#hdn-rowid', form), -1);
			var _arrToUpdate = _doCommitUserControlsChanged($('#divMain', form));
			// ++add master link (foreign key) if master details style
			if (typeof _masterLink != 'undefined') {
				for (_key in _masterLink) {
					_arrToUpdate[_key] = _masterLink[_key];
				}
			}
			// --add master link (foreign key) if master details style
			
			if ($('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass).length > 0) {
				_arrToUpdate['json_details'] = __getPremadeDetailCtrl_CommittedChanged();
			} else {
				_arrToUpdate['json_details'] = _doCommitUserControlsChanged($('div.cls-detail-panel:not(.hidden)', form));
			}
			_arrToUpdate['json_images'] = _doCommitUserControlsChanged($('#divImages', form));
			
			_arrToUpdate['json_others'] = {};
			//++ PO properties
			if (('po_order_date' in _arrToUpdate)) {
				delete _arrToUpdate["po_order_date"];
				_arrToUpdate['json_others']['po_order_date'] = $('#txt-po_order_date').datepicker("getDate").format("yyyymmdd");
			}
			if (('po_due_date' in _arrToUpdate)) {
				delete _arrToUpdate["po_due_date"];
				_arrToUpdate['json_others']['po_due_date'] = $('#txt-po_due_date').datepicker("getDate").format("yyyymmdd");
			}
			if (('po_deliver_date' in _arrToUpdate)) {
				delete _arrToUpdate["po_deliver_date"];
				_arrToUpdate['json_others']['po_deliver_date'] = $('#txt-po_deliver_date').datepicker("getDate").format("yyyymmdd");
			}
			if (('po_supplier_rowid' in _arrToUpdate)) {
				_arrToUpdate['json_others']['po_supplier_rowid'] = _arrToUpdate["po_supplier_rowid"];//getValue($('.user-input[data="po_supplier_rowid"]'));
				delete _arrToUpdate["po_supplier_rowid"];
			}
			if (('remark1' in _arrToUpdate)) {
				_arrToUpdate['json_others']['remark1'] = _arrToUpdate["remark1"];
				delete _arrToUpdate["remark1"];
			}
			if (('remark2' in _arrToUpdate)) {
				_arrToUpdate['json_others']['remark2'] = _arrToUpdate["remark2"];
				delete _arrToUpdate["remark2"];
			}

			//++ Size Quan 
			var _objReturn = __getSizeQuanCtrl_CommittedChanged();
			if ((_objReturn) && (typeof _objReturn == 'object')) {
				if (("size" in _objReturn)) {
					_arrToUpdate['json_details']["size_category"] = ("size_category" in _objReturn["size"]) ? _objReturn["size"]["size_category"] : -1;
					_arrToUpdate['json_others']["size"] = _objReturn["size"];
				}
				_arrToUpdate['json_others']["size_custom"] = ("size_custom" in _objReturn) ? _objReturn["size_custom"] : {};
			}
			//++ Screen
			_arrToUpdate['json_others']["screen"] = __getScreenCtrl_CommittedChanged();
			//++ Others price
			_arrToUpdate['json_others']["others_price"] = __getOtherPriceCtrl_CommittedChanged();
			if ($('#tabMnuDetail', form).hasClass('hidden') && ((_arrToUpdate['amount'] || 0) > 0)) {
				if ($.isArray(_arrToUpdate['json_others']["others_price"]) && (_arrToUpdate['json_others']["others_price"][0]["price"] <= 0)) {
					_arrToUpdate['json_others']["others_price"][0]["price"] = _arrToUpdate['amount'];
				}
			}
			//-- Others price
			
			__fncSubmitQuotation(form, _formIndex, _arrToUpdate, function(data, textStatus, jqXHR) {
				if (data.success == false) {
					_doUpdateTotalValue(_formIndex);
					alert(MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, data.error));
					$("#dialog-modal").dialog( "close" );
				} else {
					_currEditTr = undefined;
					_currEditData = undefined;
					_doClearForm(form);

					if (typeof populateSublist == 'function') populateSublist(true, null, function() {						
						var _divDialog = $(form).parents(".cls-div-form-edit-dialog").get(0);
						if ($(_divDialog).dialog( "isOpen" )) $(_divDialog).dialog( "close" );

						_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, ''), 3, false);
						$("#dialog-modal").dialog( "close" );
						_doUpdateTotalValue(_formIndex);
					});
				}
			});
		}
	};
	doDelete = function (dataRowObj, trObj, divEditDlg) {
		var _formIndex = $('form', divEditDlg).attr('index');
		if (_formIndex == 0) { //main form
			_fncTemplate_doDelete.apply(this, arguments);
		} else {
			var _dtIndex = $(divEditDlg).attr('index');
			if (('rowid' in dataRowObj) && (dataRowObj['rowid'] > 0)) {
				_fncTemplate_doDelete(dataRowObj, trObj, divEditDlg, function() {
					_doUpdateTotalValue(_dtIndex);
				});
			} else {
				_arrSublistDataTable[_dtIndex].fnDeleteRow(trObj);
				_doUpdateTotalValue(_dtIndex);
			}
		}
	};
	blnDataChanged = function() {
		if (( ! $("#divSublistFormEditDialog").is(":visible")) && (_blnDetailsChange)) {
			return true;
		} else {
			return _fncTemplate_blnDataChanged.apply(this, arguments);
		}
	};
});

function _fnc_onChangePaymentCondition(str, env, ui) {
	var _val = getValue($('#sel-payment_condition_rowid'), -1);
	var _elemDp = $('#txt-deposit_percent');
	if (! getValue(_elemDp, false)) {
		if (_val == 1) {
			setValue(_elemDp, 50, false);
			_elemDp.trigger('changed');
		} else {
			setValue(_elemDp, 0, false);
			_elemDp.trigger('changed');
		}
	}
	var _elmVal = $('#txt-days_credit');
	var _divVal = $(_elmVal.parents('div.table-value')[0]);
	if (_val == 2) { // credit
		_divVal.css('visibility', '');
		_divVal.prev().css('visibility', '');
	} else {
		setValue(_elmVal, 0);
		_divVal.css('visibility', 'hidden');
		_divVal.prev().css('visibility', 'hidden');
	}
	_blnDetailsChange = true;
}
function _doUpdateDepositValues(dblPercent, dblActual) {
	var _dblP = dblPercent || -1;
	var _dblA = dblActual || -1;
	var _dblAmount = _cleanNumericValue(getValue($('#divMain #spn-grand_total'), 0));	
	if (_dblAmount <= 0) return false;

	if ((_dblP < 0) && (_dblA < 0)) {
		var _status_rowid = getValue($('#hdn-status_rowid'), 10);
		if (_status_rowid < 60) {
			_dblA = -1;
			_dblP = 50;
		} else {
			_dblA = getValue($('#divMain #txt-deposit_amount'), -1);
			_dblP = getValue($('#divMain #txt-deposit_percent'), -1);
		}
	}
	if (_dblA > 0) {
		_dblP = ((_dblA * 100) / _dblAmount);
		setValue($('#divMain #txt-deposit_percent'), formatNumber(_dblP));
	} else if (_dblP > 0) {
		_dblA = ((_dblP / 100) * _dblAmount);
		setValue($('#divMain #txt-deposit_amount'), formatNumber(_dblA));
	}
	_blnDetailsChange = true;
}

function __fncCheckVisibleElementsByStatus(qo_status, divEditDlg) {
	$('[from_qs]', divEditDlg).each(function() {
		$(this).removeClass('hidden');
	});
	$('[to_qs]', divEditDlg).each(function() {
		$(this).removeClass('hidden');
	});
	
	$('[from_qs]', divEditDlg).each(function() {
		if (parseInt($(this).attr('from_qs') || 0) > qo_status) {
			$(this).addClass('hidden');
		}
	});
	$('[to_qs]', divEditDlg).each(function() {
		if (parseInt($(this).attr('to_qs') || 0) < qo_status) {
			$(this).addClass('hidden');
		}
	});
}

function __fncPopulateSpecialControls(dataRowObj, divEditDlg) {
	var _arr = dataRowObj['json_details'];
	if ((typeof _arr == 'string') && (_arr.trim().length > 0)) _arr = JSON.parse(_arr);
	
	if (! ($.isPlainObject(_arr) || $.isArray(_arr))) return false;
	
	if ($('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass).length > 0) {
		_premadeOrderFetchDetail(_arr);
	} else {
		var _div = $('div#ord_cap_quan_container').filter(__fnc_filterNotNestedHiddenClass);
		if (_div.length > 0) {
			var _qty = 0;
			var _price = 0;
			if ('order_qty' in _arr) _qty = parseInt(_arr['order_qty'] || 0);
			if ('order_price_each' in _arr) _price = parseFloat(_arr['order_price_each'] || 0);
			
			$('div.total-price', _div[0]).html(formatNumber(_qty * _price));
		}
	}
	_doSetValueFormUserInput(divEditDlg, _arr);
	var _size_cat = ('size_category' in _arr) ? _arr["size_category"] : -1;
	
	_arr = dataRowObj['json_images'];
	if (typeof _arr == 'string') _arr = JSON.parse(_arr);
	var _arrImgs = {};
	for (var _key in _arr) {
		var _val = _arr[_key] || false;
		if ((typeof _key == 'string') && (typeof _val == 'string') && (_val != 'unchange')) _arrImgs[_key] = {"url": '../app/uploads/' + _val, "name": _val};
	}
	_doSetValueFormUserInput($('#divImages', divEditDlg), _arrImgs);

	if (('json_others' in dataRowObj)) {
		if (typeof dataRowObj['json_others'] == 'string') dataRowObj['json_others'] = JSON.parse(dataRowObj['json_others']);
		
		//++ po properties
		var _objOthers = dataRowObj['json_others'];
		if (('po_order_date' in _objOthers) || ('po_due_date' in _objOthers) || ('po_deliver_date' in _objOthers) || ('po_supplier_rowid' in _objOthers)) {
			_doSetValueFormUserInput($('#div_PO_props'), dataRowObj['json_others']);
			$('#div_PO_props').css('display', '');
			$('#txt-po_order_date').datepicker("option", "disabled", true);
		} else {
			$('#div_PO_props').css('display', 'none');
		}
		if (('remark1' in _objOthers) && ((_objOthers["remark1"] + '').trim() != '')) setValue($('#txa-remark1', $('#div_PO_remarks')), _objOthers["remark1"]);
		if (('remark2' in _objOthers) && ((_objOthers["remark2"] + '').trim() != '')) setValue($('#txa-remark2', $('#div_PO_remarks')), _objOthers["remark2"]);
		
		//++ size quan
		if (_size_cat > 0) dataRowObj['json_others']['size_category'] = _size_cat;
		_sqFetchData(dataRowObj['json_others']);
		
		//++ Screen
		_tbl = $('table#tbl_sc_list', divEditDlg).filter(__fnc_filterNotNestedHiddenClass);
		if (_tbl.length > 0) _tbl = _tbl[0];
		$('tbody tr:not("#sc_edit_panel")', _tbl).remove();
		if ('screen' in dataRowObj['json_others']) {
			_arr = dataRowObj['json_others']["screen"];
			if (typeof _arr == 'string') _arr = JSON.parse(_arr);
			for (_i=0;_i<_arr.length;_i++) {
				var _row = _arr[_i];
				_scInsertDetailRow(_tbl, _row);
			}
		}
		//++ Others price
		_tbl = $('table#tbl_op_list', divEditDlg).filter(__fnc_filterNotNestedHiddenClass);
		if (_tbl.length > 0) _tbl = _tbl[0];
		$('tbody tr:not("#op_edit_panel")', _tbl).remove();
		if ('others_price' in dataRowObj['json_others']) {
			_arr = dataRowObj['json_others']["others_price"];
			if (typeof _arr == 'string') _arr = JSON.parse(_arr);
			for (_i=0;_i<_arr.length;_i++) {
				var _row = _arr[_i];
				_opInsertDetailRow(_tbl, _row);
			}
		}
	}
	_doUpdateDetailValues();
	_doUpdateTotalValue(1);
	__fncDPUpdateTotalDeposit();
	_blnDetailsChange = false;
}

function __fncSubmitQuotation(form, index, arrToUpdate, fncSuccessCallBack) {
	var _index = index || 0;
	var _fncSuccessCallback = fncSuccessCallBack || false;
	var _update = arrToUpdate || false;
	if (! _update ) return false;

	$("#dialog-modal").html("<p>" + MSG_DLG_HTML_COMMIT + "</p>");
	$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_COMMIT);
	$("#dialog-modal").dialog( "open" );
	
	_str = JSON.stringify(_update);
	$.ajax({
		type:"POST",
		url:"./" + $(form).attr("controller") + "/commit",
		contentType:"application/json;charset=utf-8",
		dataType:"json",
		data:_str,
		success: function(data, textStatus, jqXHR) {
			if (typeof _fncSuccessCallback == 'function') _fncSuccessCallback(data, textStatus, jqXHR);
		}
		, error: function(jqXHR, textStatus, errorThrown) {
			doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage", _index);
			if (typeof opt_fncCallBack == 'function') opt_fncCallBack.apply(this, arguments);
			$("#dialog-modal").dialog( "close" );
		}, statusCode: {
			404: function() {
				doDisplayInfo("Page not found", "ErrorMessage", _index);
				if (typeof opt_fncCallback == 'function') opt_fncCallback.apply(this, arguments);
				$("#dialog-modal").dialog( "close" );
			}
		}
	});
}

var _current_row;
var _blnDetailsChange = false;
function _doUpdateTotalValue(index) {
	if ((index >= 0)) { 
		var _totalNet = 0;
		var _totalDiscount = 0;
		var _totalVAT = 0;
		var _totalValue = 0;
		var _arr = _arrSublistDataTable[index].fnGetData() || [];
		for (_i=0;_i<_arr.length;_i++) {
			if (('amount' in _arr[_i]) && (_arr[_i]['amount'] != '') && (! isNaN(_arr[_i]['amount']))) {
				_totalNet += parseFloat(_arr[_i]['amount']);
			}
		}
		var _percent = $('#txt-percent_discount').val() || 0;
		if ((! isNaN(_percent)) && (_percent > 0) && (_percent < 100)) {
			_totalDiscount = (_totalNet * _percent / 100) * -1;
		} else {
			$('#txt-percent_discount').val('');
		}

		var _is_vat = getValue($('#sel-is_vat')) || 0;
		if (_is_vat == 1) {
			_totalVAT = Math.round(_totalNet * 7) / 100;
		} else if (_is_vat == 2) {
			_totalVAT = Math.round(_totalNet * 7) / 107;
			_totalNet = _totalNet - _totalVAT;
		} else {
			_totalVAT = 0;
		}
		_totalValue = _totalNet + _totalDiscount + _totalVAT;
		$('#divMain #spn-sum_net').html(formatNumber(_totalNet, 2));
		$('#divMain #spn-sum_discount').html(formatNumber(_totalDiscount, 2));
		$('#divMain #spn-sum_vat').html(formatNumber(_totalVAT, 2));
		$('#divMain #spn-grand_total').html(formatNumber(_totalValue, 2));
		_doUpdateDepositValues();
		//$('#divMain #spn-disp_deposit_payment').html(formatNumber(_totalValue, 2));
		//$('#divMain #spn-disp_left_amount').html(formatNumber(_totalValue, 2));
	}
}

function customCommand(command, aData, tr, divEditDlg) {
	var _cmd = (command + '').toLowerCase();
	if ((_cmd == 'pdf') && ('rowid' in aData)) {
		window.open("./quotation/get_pdf/" + aData['rowid']);
	} else if ((_cmd == 'payment_log') && ('rowid' in aData) && ('arr_payment_log' in aData)) {
		var _rowid = aData['rowid'];
		var _status_rowid = aData['status_rowid'] || 0;
		var _arr_deposit_log = aData['arr_deposit_log'];
		var _arr_payment_log = aData['arr_payment_log'];
		if ((typeof _arr_deposit_log == 'string') && (_arr_deposit_log.trim() != '')) _arr_deposit_log = JSON.parse(_arr_deposit_log);
		if ((typeof _arr_payment_log == 'string') && (_arr_payment_log.trim() != '')) _arr_payment_log = JSON.parse(_arr_payment_log);
		
		var _grand_total = ('grand_total' in aData) ? aData['grand_total'] : false;
		var _deposit_amount = ('deposit_amount' in aData) ? aData['deposit_amount'] : false;
		
		_openPaymentListDialog({
			"status_rowid": _status_rowid
			,"grand_total": _grand_total
			,"deposit_amount": _deposit_amount
			,"deposit":
				//{"constant": {"quotation_rowid": _rowid}, "arr_payment_list": _arr_deposit_log, "is_editable": ((_status_rowid < 70) || (_status_rowid == 150) || (_status_rowid == 151)), "is_approveable": ((_status_rowid < 70) || (_status_rowid == 150) || (_status_rowid == 151)) }
				{"constant": {"quotation_rowid": _rowid}, "arr_payment_list": _arr_deposit_log, "is_editable": true, "is_approveable": true }
			, "after_deposit":
				{"constant": {"quotation_rowid": _rowid}, "arr_payment_list": _arr_payment_log, "is_enable": ((_status_rowid >= 70)), "is_editable": true, "is_approveable": true }
		});
	} else if ((_cmd == 'produce') && ('rowid' in aData)) {
		window.open("./quotation_produce_order/index/" + aData['rowid']);
	} else if ((_cmd == 'deliver') && ('rowid' in aData)) {
		//__doInsertDeliveryOrder(aData['rowid']);
		window.open("./quotation_delivery_order/index/" + aData['rowid']);
	} else if ((_cmd == 'view_delivery') && ('link_delivery_rowid' in aData)) {
		var _link_dlv_rowid = (('link_delivery_rowid' in aData) && (aData['link_delivery_rowid'] > 0)) ? parseInt(aData['link_delivery_rowid']) : -1;
		window.open("./delivery/pass_command/1/" + _link_dlv_rowid);
	}
}

function __fncDPUpdateTotalDeposit() {
	var _obj = _objDP.__objGetTotalAmount();
	$('#divMain #spn-deposit_payment').html(' -- ');
	$('#divMain #spn-disp_left_amount').html(' -- ');
	if (_obj.total.amount > 0) {
		var _dblTotalAmount = _cleanNumericValue($('#divMain #spn-grand_total').html());
		var _dblLeftOver = _dblTotalAmount - _obj.total.amount;
		
		$('#divMain #spn-deposit_payment').html(formatNumber(_obj.total.amount, 2));
		$('#divMain #spn-disp_left_amount').html(formatNumber(_dblLeftOver, 2));
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
}

function __doChangeQuotationStatus(rowid, status_rowid, strStatusRemark, fncOnSuccess) {
	var _index = 0;
	var _rowid = rowid || false;
	var _status_rowid = status_rowid || false;
	var _status_remark = strStatusRemark || false;
	if (! (_rowid && _status_rowid)) {
		alert('Invalid parameters to change quotation status ( rowid = ' + rowid + ', status_rowid = ' + status_rowid + ' )');
		return false;
	}
	var _json = {"rowid": _rowid, "status_rowid": _status_rowid};
	if (_status_remark) _json["status_remark"] = _status_remark;
	_str = JSON.stringify(_json);
	$.ajax({
		type:"POST",
		url:"./quotation/change_status_by_id",
		contentType:"application/json;charset=utf-8",
		dataType:"json",
		data:_str,
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, 'quotation rowid#' + _rowid + ' status has changed to "' + status_rowid + '"'), 3, false);
				doSearch(false);
			} else {
				doDisplayInfo("UnknownError", "ErrorMessage", _index);
			}
			if (typeof fncOnSuccess == 'function') fncOnSuccess.apply(this);
			$("#dialog-modal").dialog( "close" );			
		}
		, error: function(jqXHR, textStatus, errorThrown) {
			doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage", _index);
			$("#dialog-modal").dialog( "close" );
		}, statusCode: {
			404: function() {
				doDisplayInfo("Page not found", "ErrorMessage", _index);
				$("#dialog-modal").dialog( "close" );
			}
		}
	});
}
/*
function __doInsertDeliveryOrder(rowid) {
	var _index = 0;
	var _rowid = rowid || false;
	var _json = {"rowid": _rowid};
	$.ajax({
		type:"POST",
		url:"./quotation/insert_delivery_order",
		contentType:"application/json;charset=utf-8",
		dataType:"json",
		data: JSON.stringify(_json),
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, 'quotation rowid#' + _rowid + ' successfully created delivery order'), 3, false);
				doSearch(false);
			} else {
				doDisplayInfo("UnknownError", "ErrorMessage", _index);
			}
			if (typeof fncOnSuccess == 'function') fncOnSuccess.apply(this);
			$("#dialog-modal").dialog( "close" );			
		}
		, error: function(jqXHR, textStatus, errorThrown) {
			doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage", _index);
			$("#dialog-modal").dialog( "close" );
		}, statusCode: {
			404: function() {
				doDisplayInfo("Page not found", "ErrorMessage", _index);
				$("#dialog-modal").dialog( "close" );
			}
		}
	});
}
*/
function fnc__DDT_Row_RenderPercentPayment(data, type, full) {
	var _elPanel = $('<div>');
	var _rdy = parseInt(full['is_deposit_ready'] || -1);
	
	var _spn = $('<span>');
	if (_rdy >= 2) {
		_spn.addClass("cls-percent-na").html("เครดิต");
	} else {
		var _total = parseFloat(full['grand_total']);
		var _appr = parseFloat(full['approved_payment'] || 0);
		var _prcnt = 0;
		if (_total > 0) {
			_prcnt = (_appr / _total) * 100;
		}
		if (_rdy == 1) {
			_spn.addClass("cls-percent-ready").html(formatNumber(_prcnt) + '%');
		} else {
			_spn.addClass("cls-percent-not-ready").html(formatNumber(_prcnt) + '%');
		}
	}
	_elPanel.append(_spn);
	return _elPanel.html();
}

function fnc__DDT_Row_RenderAvailStatus(data, type, full) {
	var _elPanel = $('<div>');
	var _qo_rowid = full['rowid'] || -1;
	var _qo_code = full['qo_number'] || false;
	var _currStatusName = full['disp_status'] || false;
	if (_qo_rowid < 1) return '';
	
	var _elSel = $('<select>').attr('qo_rowid', _qo_rowid)
		.addClass('cls-sel-change-status')
		.append($('<option>').html('--'))
		.appendTo(_elPanel);

	var _arrStt = [];
	if ('arr_avail_status' in full) {
		_arrStt = full['arr_avail_status'];
		if (typeof _arrStt == 'string') _arrStt = JSON.parse(_arrStt);
	}
	if (_qo_code) _elSel.attr("qo_code", _qo_code);
	if (_currStatusName) _elSel.attr("curr_status_text", _currStatusName);
	if (_arrStt.length > 0) {
		if ($.isArray(_ARR_QO_STATUS)) {
			$.each(_ARR_QO_STATUS, function(indx, obj) {
				if (('code' in obj) && ('rowid' in obj) && ('name' in obj)) {
					var _code = obj['code'] || false;
					if (! _code) return true;
					_code = _code.toLowerCase();
					if (_arrStt.indexOf(_code) >= 0) {
						_elSel.append($('<option>').attr("code", obj["code"]).attr("name", obj["name"]).val(obj["rowid"]).html(obj["code"] + ': ' + obj["name"]));
					}
				}
			});
		}
	}

	if (_elSel.children().length > 0) {
		return _elPanel.html();
	} else {
		return '';
	}
}

function fnc__DDT_Row_RenderNotify(data, type, full) {

	let _depositLog = full['arr_deposit_log'] ? JSON.parse(full['arr_deposit_log']) : undefined;
	let _paymentLog = full['arr_payment_log'] ? JSON.parse(full['arr_payment_log']) : undefined;
	let _sumDepositionNoApprove = 0;

	if(_depositLog && _depositLog.length > 0){
		let _depositNoApprove = _depositLog.filter((item)=> { return item.is_approve < 1})
		_sumDepositionNoApprove += _depositNoApprove.length
	}

	if(_paymentLog && _paymentLog.length > 0){
		let _paymentNoApprove = _paymentLog.filter((item)=> { return item.is_approve < 1})
		_sumDepositionNoApprove += _paymentNoApprove.length
	}
	
	if(_sumDepositionNoApprove > 0){
		return '<div class="notification" style="border-radius: 50%;height: 20px;margin: 0 30%;width: 20px;background-color:#FFCC00;"><strong style="color:#FFF;">'+_sumDepositionNoApprove+'</strong></div>';
	}else{
		return '<div class="notification"></div>';
	}
}

function fnc__DDT_Row_RenderAvailAction(data, type, full) {
	var _elPanel = $('<div>');
	var _div = $('<div>').attr('qo_rowid', full['rowid']).addClass("cls-quotation-row-control-panel").appendTo(_elPanel);
	var _arrAct = [], _arrStt = [], _quotation_status_rowid = -1;
	if ('arr_avail_action' in full) {
		_arrAct = full['arr_avail_action'];
		if (typeof _arrAct == 'string') _arrAct = JSON.parse(_arrAct);
	}
	if ('arr_avail_status' in full) {
		_arrStt = full['arr_avail_status'];
		if (typeof _arrStt == 'string') _arrStt = JSON.parse(_arrStt);
	}
	if ('status_rowid' in full) _quotation_status_rowid = (full['status_rowid'] || -1);
	
	if (_arrAct.indexOf('view') >= 0) {
		_div.append($('<img class="list-row-button" command="view" src="./public/images/b_view.png" alt="view" title="ดูข้อมูล">'));

		if (_quotation_status_rowid >= 60) {
			var _deposit_percent = full['deposit_percent'] || 0;
			var _deposit_amount = full['deposit_amount'] || 0;
			_div.append($('<img class="list-row-button" from_qs="40" command="payment_log" src="./public/images/icons/16/rental.png" alt="approve" title="ตรวจสอบข้อมูลการชำระเงิน">'));
		} else {
			_div.append($('<img class="list-row-button cls-disabled" src="./public/images/icons/16/rental.png">'));
		}

		if (_arrAct.indexOf('edit') >= 0) {
			_div.append($('<img class="list-row-button" command="edit" src="./public/images/b_edit.png" alt="edit" title="แก้ไขข้อมูล">'));
		} else {
			_div.append($('<img class="list-row-button cls-disabled" src="./public/images/icons/16/rental.png">'));
		}
		//if (_arrAct.indexOf('deposit') >= 0) {
		//	_div.append($('<img class="list-row-button" command="deposit_payment" src="./public/images/details_open.png" alt="deposit payment" title="รายการชำระเงินมัดจำ">'));
		//} else {
		//	_div.append($('<img class="list-row-button cls-disabled" src="./public/images/icons/16/rental.png">'));
		//}
		if (_arrAct.indexOf('produce') >= 0) {
			_div.append($('<img class="list-row-button" command="produce" src="./public/images/forms.png" alt="generate produce order" title="สร้างใบงานผลิต">'));
		} else {
			_div.append($('<img class="list-row-button cls-disabled" src="./public/images/forms.png">'));
		}

		if (_arrAct.indexOf('deliver') >= 0) {
			_div.append($('<img class="list-row-button" command="deliver" src="./public/images/package-32.png" alt="generate delivery order" title="สร้างใบงานส่งสินค้า">'));
			/*
			var _link_dlv_rowid = (('link_delivery_rowid' in full) && (full['link_delivery_rowid'] > 0)) ? parseInt(full['link_delivery_rowid']) : -1;
			if (_link_dlv_rowid >= 0) {
				_div.append($('<img class="list-row-button" command="view_delivery" src="./public/images/icons/16/file-complete.png" alt="link to delivery order" title="เรียกดูใบนำส่งสินค้า">'));
			} else {
				_div.append($('<img class="list-row-button" command="deliver" src="./public/images/package-32.png" alt="generate delivery order" title="สร้างใบงานส่งสินค้า">'));
			}
			*/
		} else {
			_div.append($('<img class="list-row-button cls-disabled" src="./public/images/package-32.png">'));
		}

		if (_arrAct.indexOf('delete') >= 0) {
			_div.append($('<img class="list-row-button" command="delete" src="./public/images/b_delete.png" alt="delete" title="ลบข้อมูล">'));
		} else {
			_div.append($('<img class="list-row-button cls-disabled" src="./public/images/b_delete.png">'));
		}
	}
	return _elPanel.html();
}

function fnc__exportSampleDtlOrder(qod_rowid) {
	var _qod_rowid = qod_rowid || -1;
	if (_qod_rowid > 0) {
		window.open("./quotation_detail/view_draft_pdf/" + _qod_rowid);
	}
}

function fnc__DDT_Row_RenderDraftDetailOrder(data, type, full) {
	var _str = (full['arr_details'] || '').trim();
	var _arr = ((typeof _str == 'string') && (_str.length > 0)) ? JSON.parse(_str) : [];
	if (_arr.length > 0) {
		var _elPanel = $('<div>');
		for (_k in _arr) {
			var _ea = _arr[_k];
			if ($.isPlainObject(_ea) && ('qod_rowid' in _ea)) {
				var _rowid = _ea['qod_rowid'];
				var _disp_text = _ea['disp_type'] + ' จำนวน ' + formatNumber(parseInt(_ea['qty']), 0) + ':: ยอดรวม ' + formatNumber(parseFloat(_ea['amount']));

				var _img = $('<img>')
					.addClass('list-row-button')
					.attr('src', 'public/images/icons/16/pdf_icon.png')
					.attr('command', 'fnc__exportSampleDtlOrder')
					.attr('params', _rowid)
					.attr('alt', 'ดูตัวอย่างใบงาน')
					.attr('title', _disp_text)
					.appendTo(_elPanel);
			}
		}
		return _elPanel.html();
	} else {
		return '- n/a -'
	}	
}

function fnc__DDT_Row_RenderStatus(data, type, full) {
	var _dispText = (full['disp_status'] || '').trim();
	var _status_rowid = full['status_rowid'] || -1;
	var _strRemark = (full['status_remark'] || '').trim();
	
	var _elPanel = $('<div>');
	var _div = $('<div>').html(_dispText)
		.addClass('cls-quotation-status')
		.addClass('cls-qs-rowid-' + _status_rowid)
		.appendTo(_elPanel)
	;
	if (_strRemark.length > 0) {
		_div.addClass('cls-qs-with-remark').attr('title', _strRemark).attr('remark', _strRemark);
	}
	return _elPanel.html();
}

function fnc__DDT_Row_RenderStatusRemark(data, type, full) {
	var _dispText = (full['disp_status'] || '').trim();
	var _status_rowid = full['status_rowid'] || -1;
	var _strRemark = _status_rowid == 170  ? (full['status_remark'] || '').trim() : '';

	var _elPanel = $('<div>');
	var _div = $('<div>').html(_strRemark)
		.addClass('cls-quotation-status-remark')
		.addClass('cls-qs-rowid-' + _status_rowid)
		.appendTo(_elPanel)
	;
	// if (_strRemark.length > 0) {
	// 	_div.addClass('cls-qs-with-remark').attr('title', _strRemark).attr('remark', _strRemark);
	// }
	return _elPanel.html();
}

function __fncManageExpired(blnIsCheckValue) {
	var _blnIsCheckValue = blnIsCheckValue || false;
	if (! _blnIsCheckValue) {
		$('.cls-is-expired').addClass('hidden');
	} else {
		$('input.cls-is-expired').each(function() {
			var _elem = $(this);
			if (_elem.length <= 0) return true;
			if (getValue(_elem, false)) _elem.addClass('has-value');
		});
		$('option.cls-is-expired').each(function() {
			var _opt = $(this);
			if (_opt.length <= 0) return true;

			_opt.attr('title', "- OBSOLETED -");
			if (_opt.html().indexOf("- obsoleted -") < 0) _opt.html(_opt.html() + ' - obsoleted -');
			var _cat_id = _opt.val();
			var _tbl = $('table.tbl_size_cat#cat_id_' + _cat_id);
			if (_tbl.length <= 0) return true;
			
			if ((! _opt.is(':selected')) && ($('input.cls-is-expired.has-value', _tbl).length <= 0)) {
				_tbl.addClass('hidden');
				_opt.addClass('hidden');				
			} else {
				_tbl.addClass('has-value');
			}			
		});
		// cut
		$('table.tbl_size_cat:not(.has-value)').each(function() {
			var _tbl = $(this);
			$('tr th.cls-col-size-txt.cls-is-expired', _tbl).each(function() {
				var _indx = $(this).index();
				var _tdPrc = $('tr td.cls-col-size-price.cls-is-expired:eq(' + _indx + ')', _tbl);
				var _tdQty = $('tr td.cls-col-size-qty.cls-is-expired:eq(' + _indx + ')', _tbl)
				if (
					(! $('input.sp-price', _tdPrc).hasClass('has-value')) 
					&& (! $('input.sq-qty', _tdQty).hasClass('has-value'))
				) {
					$(this).addClass('hidden');
					$('tr th.cls-col-size-chest.cls-is-expired:eq(' + _indx + ')', _tbl).addClass('hidden');
					_tdPrc.addClass('hidden');
					_tdQty.addClass('hidden');
				}
			});
		});
	}
}