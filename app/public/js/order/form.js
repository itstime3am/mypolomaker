var _blnFetching = false;
$(function() {
	if (typeof __doInitialOrderControls == 'function') __doInitialOrderControls.apply(this);

	var _fncTemplateDoClearForm = _doClearForm;
	var _fncTemplateDoInsert = doInsert;
	var _fncTemplateDoView = doView;
	var _fncTemplateDoEdit = doEdit;
	var _fncTemplateDoSubmit = doSubmit;
	var _fncTemplateBlnDataChanged = blnDataChanged;
	
	_doClearForm = function (form) {
		_fncTemplateDoClearForm.apply(this, [form]);
		$("div.display-upload", form).css('background-image', '');

		//++ set size cat and hide another table
		$(".tbl_size_cat", form).css('display', 'none');
		$('.total-value').html('');
		$('.total-price').html('');
		//-- set size cat and hide another table
		
		//remove others price items
		$('#tbl_op_list tbody tr:not("#op_edit_panel")', form).remove();
		
		//remove screen items
		$('#tbl_sc_list tbody tr:not("#sc_edit_panel")', form).remove();
		scClearEditPanel();
		$('.cls-is-expired.hidden', form).removeClass('hidden');
		$('.cls-is-expired.has-value', form).removeClass('has-value');
		
		_blnScChanged = false;

		$('#tabs').tabs( "option", "active", 0 );
	};

	doInsert = function (divEditDlg) {
		_fncTemplateDoInsert.apply(this, [divEditDlg]);
		
		$('.cls-frm-edit #txt-order_date').datepicker("setDate", new Date());

		//++ set size cat and hide another table (case no value)
		if (($('#frm_edit #sel-size_category').length > 0) && ((getValue($('#frm_edit #sel-size_category')) || "") == "")) $('#frm_edit #sel-size_category option:first').attr('selected', 'selected');	
		$('#frm_edit #sel-size_category').trigger('change');
		//-- set size cat and hide another table (case no value)
		
		//enable others price controls 
		$('#tbl_op_list tbody tr td img').css('visibility', '');
		$('#tbl_op_list #op_edit_panel').css('display', '');

		//enable screen control 
		$('#tbl_sc_list tbody tr td img').css('visibility', '');
		$('#tbl_sc_list #sc_edit_panel').css('display', '');
		
		//enable upload images
		$(".spn-image-select").css('display', '');
		$(".spn-image-select input").prop('disabled', false);
		
		//visible .eventView-hide
		$('.eventView-hide').removeClass('hidden');

		//hide all frm reset, hide 3 first submit (for check all controls before insert)
		$('.cls-btn-form-reset').addClass('hidden').css('display', 'none');
		$('.cls-btn-form-submit').addClass('hidden').css('display', 'none');
		$('.cls-btn-form-submit').last().removeClass('hidden').css('display', '');
		
		__fncManageExpired(false);
	};
	doView = function (dataRowObj, divEditDlg) {
		_doRequestOrderDetails(_fncTemplateDoView, false, arguments);
		return false;
	};
	doEdit = function (dataRowObj, trObj, divEditDlg) {
		_doRequestOrderDetails(_fncTemplateDoEdit, true, arguments);
		//show all form control buttons
		$('.cls-btn-form-reset').removeClass('hidden').css('display', '');
		$('.cls-btn-form-submit').removeClass('hidden').css('display', '');
		return false;
	};
	doSubmit = function (form) {
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
		
		$("#dialog-modal").html("<p>" + MSG_DLG_HTML_COMMIT + "</p>");
		$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_COMMIT);
		$("#dialog-modal").dialog( "open" );
		
		var _toUpdate = {};
		if (_currEditData !== undefined) _toUpdate = $.extend({}, _currEditData);
		
		var _size_quan = {};
		var _size_quan_custom = {};
		var _selSQCatID = false;
		if ($('#sel-size_category').length > 0) _selSQCatID = 'cat_id_' + getValue($('#sel-size_category'), 0);
		$(form).find(".user-input").each(function () {
			var _tag = this.tagName.toLowerCase();
			var _type = this.type;
			if (_tag == 'input' && (_type == 'radio')) {
				_name = $(this).prop('name');
				_val =_getElemValue(this);
				if (_val) _toUpdate[_name] = _val;
				return true; //next
			}
			_name = $(this).prop('id').substr(4);
			if ((_selSQCatID) && (_name.indexOf('sq_') == 0) || (_name.indexOf('sp_') == 0)) {
				_val = _getElemValue(this, '').toString().trim();
				if (($(this).parents('table.tbl_size_cat').attr('id') == _selSQCatID) && (_val != '')) {
					_sq_check = _name.substr(3);
					if (isNaN(_sq_check) && (_sq_check.length > 5)) { //custom one format = {cat}_{sub}_{type}
						_arr = _sq_check.split("_");
						if (_arr.length < 3) return true;
						_cat_rowid = _arr[0] || -1;
						_sub_rowid = _arr[1] || -1;
						_str = _arr[2] || '';
						if (_str == '') return true;
						_cus_type = _str.substr(0, _str.length - 1);
						_cus_index = _str.substr(-1);
						if (!(_cat_rowid in _size_quan_custom)) _size_quan_custom[_cat_rowid] = {};
						if (!(_sub_rowid in _size_quan_custom[_cat_rowid])) _size_quan_custom[_cat_rowid][_sub_rowid] = {};
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
			// fall through last steps
			if (_getElemValue(this, '').toString().trim() != '') _toUpdate[_name] = _getElemValue(this);
		});
		_toUpdate["size"] = _size_quan;
		_toUpdate["size_custom"] = _size_quan_custom;

		//++ Screen
		_screen = [];
		$(form).find("#tbl_sc_list :not(thead) tr").each(function () {
			if ($(this).attr('id') == 'sc_edit_panel') {
				if (isScEditingRow()) {
					_screen.push({"seq": (_screen.length + 1), "order_screen_rowid": _cleanNumericValue(getValue($('#sc_edit_panel #sel-sc_order_screen_rowid'), 0)), "position": getValue($('#sc_edit_panel #sel-sc_position'), ''), "detail": getValue($('#txa-sc_detail'), ''), "size": getValue($('#txt-sc_size'), ''), 'job_hist': getValue($('#sel-sc_job_hist'), ''), 'price': _cleanNumericValue(getValue($('#txt-sc_price'), 0))});
				}
			} else {
				if ($(this).css('display') != 'none') { //case open edit panel but not commit row before submit form
					_arr = $(this).children();
					if (_arr.length > 5) {
						_screen.push({"seq": (_screen.length + 1), "order_screen_rowid": _cleanNumericValue($(_arr[0]).attr('order_screen_rowid') || 0), "position": ($(_arr[1]).html() || ''), "detail": ($(_arr[2]).html() || ''), "size": ($(_arr[3]).html() || ''), 'job_hist': ($(_arr[4]).html() || ''), 'price': _cleanNumericValue($(_arr[5]).html() || 0)});
					}
				}
			}
		});
		_toUpdate["screen"] = _screen;
		//-- Screen

		//++ Others price
		var _others_price = [];
		$(form).find("#tbl_op_list tbody tr").each(function () {
			if ($(this).attr('id') == 'op_edit_panel') {
				if (isOpEditingRow()) {
					_others_price.push({"seq": (_others_price.length + 1), "detail": $('#txt-op_detail').val(), 'price': _cleanNumericValue($('#txt-op_price').val())});
				}
			} else {
				if ($(this).css('display') != 'none') { //case open edit panel but not commit row before submit form
					_arr = $(this).children();
					if (_arr.length >= 2) {
						_others_price.push({"seq": (_others_price.length + 1), "detail": $(_arr[0]).html(), "price": _cleanNumericValue($(_arr[1]).html())});
					}
				}
			}
		});
		_toUpdate["others_price"] = _others_price;
		//-- Others price
		
		_str = JSON.stringify(_toUpdate);
		$.ajax({
			type:"POST",
			url:"./" + CONTROLLER_NAME + "/commit",
			contentType: "application/json;charset=utf-8",
			dataType:"json",
			data: _str,
			success: function(data, textStatus, jqXHR) {
				if (data.success == false) {
					alert(MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, data.error));
					$("#dialog-modal").dialog( "close" );
				} else {
					doSearch(false);
					_currEditTr = undefined;
					_currEditData = undefined;
					_doClearForm(form);
					var _divDialog = $(form).parents(".cls-div-form-edit-dialog").get(0);
					if ($(_divDialog).dialog( "isOpen" )) $(_divDialog).dialog( "close" );
					alert(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, ''));
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
	};

	blnDataChanged = function (form) {
		if (_blnOpChanged || isOpEditingRow()) return true;
		if (_blnScChanged || isScEditingRow()) return true;
		return _fncTemplateBlnDataChanged.apply(this, arguments);
	};
});

function _doRequestOrderDetails(fnc_callback, is_edit, cb_args) {
	if (!$("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_QUERY.replace(/v_XX_1/g, '')).dialog('option', 'html', "<p>" + MSG_DLG_HTML_QUERY + "</p>").dialog( "open" );
	_dataRowObj = cb_args[0];
	var _customer = _dataRowObj['customer'] || '';
	_doClearForm($('#frm_edit'));
	$.ajax({
		type:"POST",
		url:"./" + CONTROLLER_NAME + "/get_order_by_id",
		dataType:"json",
		data: 'rowid=' + _dataRowObj['rowid'],
		success: function(data, textStatus, jqXHR) {
			if (data.success == false) {
				alert(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error));
				$("#dialog-modal").dialog( "close" );
			} else {
				_blnFetching = true; //prevent on select event, looping
				cb_args[0] = data.data;
				cb_args[0]['customer'] = _customer;
				//cb_args[0]['old_file_image1'] = cb_args[0]['file_image1'];
				//cb_args[0]['old_file_image2'] = cb_args[0]['file_image2'];
				if ($.isFunction(fnc_callback)) fnc_callback.apply(this, cb_args);
				_blnFetching = false; //prevent on select event, looping

				//++ Set upload images
				$("#frm_edit div.display-upload").css('background-image', '');
				for (var _i=1;_i<10;_i++) {
					var _strKey = 'file_image' + _i;
					if (_strKey in cb_args[0]) {
						if ((cb_args[0][_strKey] != null) && (cb_args[0][_strKey] != '')) {
							cb_args[0][('old_file_image' + _i)] = cb_args[0][_strKey]; // set old value for delete
							$($("#hdn-" + _strKey).parents('div').get(0)).css('background-image', "url(uploads/" + cb_args[0][_strKey] + ")");
						}
					}
				}
				//-- Set upload images

				//++ Screens list
				
				$('#tbl_sc_list tbody tr:not("#sc_edit_panel")').remove();
				_str = '';
				if ('screen' in cb_args[0]) {
					for (_r in cb_args[0]['screen']) {
						if ('order_screen' in cb_args[0]['screen'][_r]) {
							_row = cb_args[0]['screen'][_r];
							_scInsertDetailRow($('#tbl_sc_list'),_row);
							//_str += '<tr><td order_screen_rowid="' + _row['order_screen_rowid'] + '" >' + _row['order_screen'] + "</td><td>" + _row['position'] + "</td><td>" + _row['detail'] + "</td><td>" + _row['size'] + "</td><td>" + _row['job_hist'] + "</td><td>" + _row['price'] + "</td><td class='control-button'><img src='public/images/edit.png' class='sc-edit-ctrl ctrl-edit' title='Edit' /><img src='public/images/b_delete.png' class='sc-edit-ctrl ctrl-delete' title='Delete' /></td></tr>";
						}
					}
					//$('#sc_edit_panel').before(_str);
					//$('#tbl_sc_list tbody').append(_str);
					_blnScChanged = false;
				}
				
				if ('weave_order' in cb_args[0]) {
					for (_r in cb_args[0]['weave_order']) {
						if ('disp_type' in cb_args[0]['weave_order'][_r]) {
							_row = cb_args[0]['weave_order'][_r];
							_scInsertDetailRowOrder($('#tbl_sc_list'),_row);
						}
					}
					_blnScChanged = false;
				}

				if ('screen_order' in cb_args[0]) {
					for (_r in cb_args[0]['screen_order']) {
						if ('disp_type' in cb_args[0]['screen_order'][_r]) {
							_row = cb_args[0]['screen_order'][_r];
							_scInsertDetailRowOrder($('#tbl_sc_list'),_row);
						
						}
					}
					_blnScChanged = false;
				}

				//-- Screens list

				//++ set size cat and hide another table
				setValue($('#frm_edit #sel-size_category'), cb_args[0]['size_category']);
				$('#frm_edit #sel-size_category').trigger('change');
				//-- set size cat and hide another table
				
				//++ Others price list
				$('#tbl_op_list tbody tr:not("#op_edit_panel")').remove();
				_str = '';
				if (('others_price' in cb_args[0]) && ($.isArray(cb_args[0]['others_price'])) && (cb_args[0]['others_price'].length > 0)) {
					for (_r in cb_args[0]['others_price']) {
						_row = cb_args[0]['others_price'][_r];
						if ('detail' in _row) {
							_opInsertDetailRow($('#tbl_op_list'), _row);
							//_str += '<tr><td colspan="2" class="price_detail">' + _row['detail'] + '</td><td colspan="2" class="price">' + _row['price'] + "</td><td class='control-button'><img src='public/images/edit.png' class='op-edit-ctrl ctrl-edit' title='Edit' /><img src='public/images/b_delete.png' class='op-edit-ctrl ctrl-delete' title='Delete' /></td></tr>";
						}
					}
					//$('#op_edit_panel').before(_str);
					_blnOpChanged = false;
				}
				//-- Others price list

				// ++ invis .eventView-hide
				if (! is_edit) { //view
					$('th.eventView-hide').each(function () {
						var _jqTable = $(this).parents('table').get(0);
						var _indx = $(this).index();
						$('tbody tr', _jqTable).each(function () {
							$($('td', this).get(_indx)).addClass('eventView-hide');
						});
					});
					$('.eventView-hide').addClass('hidden');
				} else {
					$('#frm_edit #sel-size_category option.cls-is-expired').each(function() {
						var _opt = $(this);
						if (_opt.is(':selected')) {
							_opt.show();
						} else {
							_opt.hide();
						}
					});
					//++ set size cat and hide another table (case no value)
					if ((getValue($('#frm_edit #sel-size_category')) || "") == "") {
						$('#frm_edit #sel-size_category option:not(.cls-is-expired):first').attr('selected', 'selected');
					}
					$('#frm_edit #sel-size_category').trigger('change');

					$('.eventView-hide').removeClass('hidden');
				}
				__fncManageExpired(true);

				// -- invis .eventView-hide
				$('#frm_edit').trigger('fetchDone'); //raise event load done to do next step
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
//	if (!$("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog( 'close' );
}

function _doCloneJobNumber(dest_rowid) {
	if (!$("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_QUERY.replace(/v_XX_1/g, '')).dialog('option', 'html', "<p>" + MSG_DLG_HTML_QUERY + "</p>").dialog( "open" );
	// prevent clear / change values
	$('#txt-job_number').addClass('data-constant');
	$('#sel-ref_number').addClass('data-constant');
	$('#txt-order_date').addClass('data-constant');
	$('#txt-due_date').addClass('data-constant');
	$('#txt-deliver_date').addClass('data-constant');
	_doClearForm($('#frm_edit'));
	$.ajax({
		type:"POST",
		url:"./" + CONTROLLER_NAME + "/get_order_by_id",
		dataType:"json",
		data: 'rowid=' + dest_rowid,
		success: function(data, textStatus, jqXHR) {
			if (data.success == false) {
				alert(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error));
				$("#dialog-modal").dialog( "close" );
			} else {
				data.data['ref_number'] = data.data['job_number'];
				data.data['customer'] = data.data['customer_name'];
				//++ remove data dont want to clone
				for (var _id in data.data) {
					if ((typeof _id == 'string') && ((_id.substring(0, 3) == 'sp_') || (_id.substring(0, 3) == 'sq_'))) delete data.data[_id];
				}
				delete data.data['rowid'];
				delete data.data['job_number'];
				delete data.data['ref_number'];
				delete data.data['order_date'];
				delete data.data['due_date'];
				delete data.data['deliver_date'];
				/* ++ mail issue 20160225 ++ */
				if ('order_price_each' in data.data) delete data.data['order_price_each'];
				if ('order_qty' in data.data) delete data.data['order_qty'];
				if ('total_price' in data.data) delete data.data['total_price'];
				if ('others_price' in data.data) delete data.data['others_price'];
				if ('remark1' in data.data) delete data.data['remark1'];
				if ('remark2' in data.data) delete data.data['remark2'];
				/* -- mail issue 20160225 -- */
				//-- remove data dont want to clone
				_blnFetching = true; //prevent on select event, looping
				_doSetValueFormUserInput($('#frm_edit'), data.data);
				_blnFetching = false; //prevent on select event, looping

				//++ Screens list
				$('#tbl_sc_list tbody tr:not("#sc_edit_panel")').remove();
				_str = '';
				if ('screen' in data.data) {
					for (_r in data.data['screen']) {
						if ('order_screen' in data.data['screen'][_r]) {
							_row = data.data['screen'][_r];
							_scInsertDetailRow($('#tbl_sc_list'), _row);
							//_str += '<tr><td order_screen_rowid="' + _row['order_screen_rowid'] + '" >' + _row['order_screen'] + "</td><td>" + _row['position'] + "</td><td>" + _row['detail'] + "</td><td>" + _row['size'] + "</td><td>" + _row['job_hist'] + "</td><td>" + _row['price'] + "</td><td class='control-button'><img src='public/images/edit.png' class='sc-edit-ctrl ctrl-edit' title='Edit' /><img src='public/images/b_delete.png' class='sc-edit-ctrl ctrl-delete' title='Delete' /></td></tr>";
						}
					}
					//$('#tbl_sc_list tbody').append(_str);
					_blnScChanged = true;
				}
				//-- Screens list

				//++ set size cat and hide another table
				$('#frm_edit #sel-size_category').val(data.data['size_category']);
				$('#frm_edit #sel-size_category').trigger('change');
				//-- set size cat and hide another table
				
				/* ++ mail issue 20160225 ++ */
				//++ Others price list
				/*
				$('#tbl_op_list tbody tr:not("#op_edit_panel")').remove();
				_str = '';
				if (('others_price' in data.data) && ($.isArray(data.data['others_price'])) && (data.data['others_price'].length > 0)) {
					for (_r in data.data['others_price']) {
						_row = data.data['others_price'][_r];
						if ('detail' in _row) {
							_scInsertDetailRow($('#tbl_op_list'), _row);
							//_str += '<tr><td colspan="2" class="price_detail">' + _row['detail'] + '</td><td colspan="2" class="price">' + _row['price'] + "</td><td class='control-button'><img src='public/images/edit.png' class='op-edit-ctrl ctrl-edit' title='Edit' /><img src='public/images/b_delete.png' class='op-edit-ctrl ctrl-delete' title='Delete' /></td></tr>";
						}
					}
					//$('#op_edit_panel').before(_str);
					_blnOpChanged = true;
				}
				//-- Others price list
				*/
				/* -- mail issue 20160225 -- */				
				$("#dialog-modal").dialog( "close" );
			}
			$('#txt-job_number').removeClass('data-constant');
			$('#sel-ref_number').removeClass('data-constant');
			$('#txt-order_date').removeClass('data-constant');
			$('#txt-due_date').removeClass('data-constant');
			$('#txt-deliver_date').removeClass('data-constant');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('#txt-job_number').removeClass('data-constant');
			$('#sel-ref_number').removeClass('data-constant');
			$('#txt-order_date').removeClass('data-constant');
			$('#txt-due_date').removeClass('data-constant');
			$('#txt-deliver_date').removeClass('data-constant');

			doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage");
			$("#dialog-modal").dialog( "close" );
		},
		statusCode: {
			404: function() {
				$('#txt-job_number').removeClass('data-constant');
				$('#sel-ref_number').removeClass('data-constant');
				$('#txt-order_date').removeClass('data-constant');
				$('#txt-due_date').removeClass('data-constant');
				$('#txt-deliver_date').removeClass('data-constant');

				doDisplayInfo("Page not found", "ErrorMessage");
				$("#dialog-modal").dialog( "close" );
			}
		}
	});
}

//++ Pattern details panel function
function doSetPatternDetail(objPttrn) {
	var _container = $("#tbl_pattern_details").get(0);
	_doClearForm(_container);
	if ((objPttrn) && (_container)) {
		_doSetValueFormUserInput(_container, objPttrn)
		_doSetEnableFormUserInput(_container, false);
	}
}
//-- Pattern details panel function

function __fncManageExpired(blnIsEdit) {
	var _blnIsCheckValue = blnIsEdit || false;
	if (! _blnIsCheckValue) {
		$('.cls-is-expired').addClass('hidden');
	} else {
		$('input.cls-is-expired').each(function() {
			var _elem = $(this);
			if (_elem.length <= 0) return true;
			if (getValue(_elem, false)) _elem.addClass('has-value');
			/*switch (_elem.nodeName.toLowerCase()) {
				case 'input':
					if (! getValue(_elem, false)) _elem.addClass('hidden');
					break;
			}*/
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