var _blnFetching = false;
$(function() {
	if (typeof __doInitialOrderControls == 'function') __doInitialOrderControls.apply(this);

	var _fncTemplateDoClearForm = _doClearForm;
	var _fncTemplateDoInsert = doInsert;
	var _fncTemplateDoView = doView;
	var _fncTemplateDoEdit = doEdit;
	var _fncTemplateDoSubmit = doSubmit;
	var _fncTemplateBlnDataChanged = blnDataChanged;
	
	_doClearForm = function(form) {
		_fncTemplateDoClearForm.apply(this, [form]);
		$("div.display-upload", form).css('background-image', '');

		//++ details size
		$('#tbl_detail_list > *:not("thead") tr:not("#edit_panel1")', form).remove();
		$('#tbl_detail_size > *:not("thead") tr:not("#edit_panel2")', form).remove();
		var divCntr = $('#div_premade_detail_panel').parents('div.frm-edit-row-group')[0];
		$('.total-value', divCntr).html(' -- ');
		$('.total-price', divCntr).html(' -- ');
		//$('#size-grand-total').html('0');
		//$('#spn-total_price').html('0.00');
		
		// doClearDetailsEditPanel();
		_blnDetailChanged = false;
		//-- details size

		//remove others price items
		$('#tbl_op_list > *:not("thead") tr:not("#op_edit_panel")', form).remove();

		//-- remove screen items
		$('#tbl_sc_list > *:not("thead") tr:not("#sc_edit_panel")', form).remove();
		scClearEditPanel();
		$('th.th-sub-cat-title', form).each(function() {
			var _strCatFilter = '';
			var _sub_cat_id = $(this).attr('sub_cat_id') || -1;
			var _cat_id = $(this).attr('cat_id') || -1;
			if (_cat_id > 0) _strCatFilter += '[cat_id=' + _cat_id + ']';
			if (_sub_cat_id > 0) _strCatFilter += '[sub_cat_id=' + _sub_cat_id + ']';
			var _colspan = $('td.td-qty input' + _strCatFilter + ':not(.cls-is-expired)').length;
			//$(this).attr('colspan', $(this).attr('org_colspan'))
			$(this).attr('colspan', _colspan);
		});
		$('th.th-cat-title', form).each(function() {
			var _strCatFilter = '';
			var _cat_id = $(this).attr('cat_id') || -1;
			if (_cat_id > 0) _strCatFilter += '[cat_id=' + _cat_id + ']';
			var _colspan = $('td.td-qty input' + _strCatFilter + ':not(.cls-is-expired)').length;
			//$(this).attr('colspan', $(this).attr('org_colspan'))
			$(this).attr('colspan', _colspan);
		});
		$('.cls-is-expired.hidden', form).removeClass('hidden');
		$('.cls-is-expired.has-value', form).removeClass('has-value');

		_blnScChanged = false;

		$('#tabs').tabs( "option", "active", 0 );
	};
	doInsert = function (divEditDlg) {
		_fncTemplateDoInsert.apply(this, [divEditDlg]);
		
		$('.cls-frm-edit #txt-order_date').datepicker("setDate", new Date());

		//enable details control 
		$('tr.tr-edit-panel').css('display', '');
		$('td div.control-button').css('display', '');
		
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
		//console.log('function replaced');
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
		//console.log('function replaced');
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

		//validate detail edit panel
		if (isDetailsEditingRow()) {
/*
			if (! blnValidateContainer(false, $('#div_premade_detail_panel'), 'table tr.tr-edit-panel ')) return false;
			if ($('#edit_panel2 .cls-sub-total').html() <= 0) {
				var _strErrMsg = MSG_VLDR_INVALID_DATATYPE.replace(/v_XX_1/g, '( กรุณาเลือกจำนวน )');
				$('.ul-vldr-error-msg').append('<li id="li_invalidOrderAmount">' + _strErrMsg + '</li>');
				alert(_strErrMsg);
				return false;
			}
*/
			alert('พบข้อมูลรายละเอียดที่แก้ไขหรือสร้างใหม่โดยยังไม่ได้ทำก่ารยืนยัน, กรุณายืนยันหรือยกเลิกก่อนทำการบันทึก');
			return false;
		}

		$("#dialog-modal").html("<p>" + MSG_DLG_HTML_COMMIT + "</p>");
		$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_COMMIT);
		$("#dialog-modal").dialog( "open" );
		var _elems = {};
		if (_currEditData !== undefined) _elems = $.extend({}, _currEditData);
		$(".user-input", form).each(
			function () {
				_tag = this.tagName.toLowerCase();
				_type = this.type;
				if (_tag == 'input' && (_type == 'radio')) {
					_name = $(this).prop('name');
					_val =_getElemValue(this);
					if (_val) _elems[_name] = _val;
				} else {
					_name = $(this).prop('id').substr(4);
					if (_name.indexOf('sq_') == 0) {
						if ($(this).is(':visible')) {
							_elems[_name] = _getElemValue(this);
						} else {
							_elems[_name] = "";
						}
					} else {
						_elems[_name] = _getElemValue(this);
					}
				}
			}
		);

		_detail = [];
		var _det = $('#tbl_detail_list tbody tr');
		var _detSize = $('#tbl_detail_size tbody tr');
		if (_detSize.length > 0) {
			var _detIndex = 0;
			var _order_size = [];
			_det.each(function () {
				var _this = $(this);
				var _arr;
				var _ea;
				if (_this.hasClass('tr-edit-panel')) {
					if (isDetailsEditingRow()) {
						_order_size = [];
						$('#edit_panel2 input:not(.cls-price-each)').each(function() {
							_ea = $(this);
							if (_ea.attr('order_size_rowid') && _isInt(_ea.val())) _order_size.push({"order_size_rowid": _ea.attr('order_size_rowid'), "qty": _ea.val()});
						});
						var _avgPrice = getValue($('#edit_panel2 input.cls-price-each'), 0);
						_detail.push({
							"pattern_rowid": $('#edit_panel1 .edit-ctrl[data="pattern_rowid"]').val()
							, "color": $('#edit_panel1 .edit-ctrl[data="txt-color"]').val()
							, "price": _avgPrice
							, "order_size": _order_size
						});
					}
				} else {
					if ((_detSize.length > _detIndex) && (_this.css('display') != 'none')) {
						_order_size = [];
						_arr = $(_detSize.get(_detIndex)).children();
						_avgPrice = 0;
						for (var _i=0;_i<_arr.length;_i++) {
							_ea = $(_arr[_i]);
							if (_ea.attr('order_size_rowid') && _isInt(_ea.html())) {
								_order_size.push({
									"order_size_rowid": _ea.attr('order_size_rowid')
									, "qty": _ea.html()
								});
							} else {
								_em = $('.cls-price-each', _ea);
								if (_em.length > 0) _avgPrice = parseFloat(getValue(_em, 0));
							}
						}

						_arr = _this.children();
						if (_arr.length > 2) {
							_detail.push({
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
		_elems['detail'] = _detail;

		//++ screen
		_screen = [];
		$(form).find("#tbl_sc_list tbody tr").each(function () {
			if ($(this).attr('id') == 'sc_edit_panel') {
				if (isScEditingRow()) {
					_screen.push({"order_screen_rowid": $('#sel-sc_order_screen_rowid').val(), "position": getValue($('#sel-sc_position'), ''), "detail": $('#txa-sc_detail').val(), "size": $('#txt-sc_size').val(), 'job_hist': $('#sel-sc_job_hist').val(), 'price': _cleanNumericValue($('#txt-sc_price').val())});
				}
			} else {
				if ($(this).css('display') != 'none') { //case open edit panel but not commit row before submit form
					_arr = $(this).children();
					if (_arr.length > 4) {
						_screen.push({"order_screen_rowid": $(_arr[0]).attr('order_screen_rowid'), "position": $(_arr[1]).html(), "detail": $(_arr[2]).html(), "size": $(_arr[3]).html(), 'job_hist': $(_arr[4]).html(), 'price': _cleanNumericValue($(_arr[5]).html())});
					}
				}
			}
		});
		_elems["screen"] = _screen;
		//-- screen

		//++ Others price
		var _others_price = [];
		$(form).find("#tbl_op_list tbody tr").each(function () {
			if ($(this).attr('id') == 'op_edit_panel') {
				if (isOpEditingRow()) {
					_others_price.push({"detail": $('#txt-op_detail').val(), 'price': _cleanNumericValue($('#txt-op_price').val())});
				}
			} else {
				if ($(this).css('display') != 'none') { //case open edit panel but not commit row before submit form
					_arr = $(this).children();
					if (_arr.length >= 2) {
						_others_price.push({"detail": $(_arr[0]).html(), "price": _cleanNumericValue($(_arr[1]).html())});
					}
				}
			}
		});
		_elems["others_price"] = _others_price;
		//-- Others price

		_str = '';
		_str = JSON.stringify(_elems);
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
		if (_blnDetailChanged || isDetailsEditingRow()) return true;
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
				for (var _i=1;_i<10;_i++) {
					cb_args[0]['old_file_image' + _i] = cb_args[0]['file_image' + _i];
				}
				if ($.isFunction(fnc_callback)) fnc_callback.apply(this, cb_args);
				_blnFetching = false; //prevent on select event, looping

				//++ Screens list
				$('#tbl_sc_list > *:not("thead") tr:not("#sc_edit_panel")').remove();
				_str = '';
				if ('screen' in cb_args[0]) {
					for (_r in cb_args[0]['screen']) {
						if ('order_screen' in cb_args[0]['screen'][_r]) {
							_row = cb_args[0]['screen'][_r];
							_scInsertDetailRow($('#tbl_sc_list'),_row);
						}
					}
					$('#sc_edit_panel').before(_str);
					_blnScChanged = false;
				}
				if ('weave_order' in cb_args[0]) {
					var _prov_seq = '';
					for (_r in cb_args[0]['weave_order']) {
						if ('disp_type' in cb_args[0]['weave_order'][_r]) {
							_row = cb_args[0]['weave_order'][_r];
							if(_prov_seq != _row['seq']){
								_scInsertDetailRowOrder($('#tbl_sc_list'),_row);
								_prov_seq = _row['seq'];
							}

						}
					}
					_blnScChanged = false;
				}

				if ('screen_order' in cb_args[0]) {
					var _prov_seq = '';
					for (_r in cb_args[0]['screen_order']) {
						if ('disp_type' in cb_args[0]['screen_order'][_r]) {
							_row = cb_args[0]['screen_order'][_r];
							if(_prov_seq != _row['seq']){
								_scInsertDetailRowOrder($('#tbl_sc_list'),_row);
								_prov_seq = _row['seq'];
							}
						}
					}
					_blnScChanged = false;
				}

				//-- Screens list
				
				//++ Set upload images
				$("#frm_edit div.display-upload").css('background-image', '');
				if ('file_image1' in cb_args[0]) {
					if ((cb_args[0]['file_image1'] != null) && (cb_args[0]['file_image1'] != '')) $($("#hdn-file_image1").parents('div').get(0)).css('background-image', "url(uploads/" + cb_args[0]['file_image1'] + ")");
				}
				if ('file_image2' in cb_args[0]) {
					if ((cb_args[0]['file_image2'] != null) && (cb_args[0]['file_image2'] != '')) $($("#hdn-file_image2").parents('div').get(0)).css('background-image', "url(uploads/" + cb_args[0]['file_image2'] + ")");
				}
				$(".spn-image-select input").prop('disabled', (! is_edit));
				(is_edit && $(".spn-image-select").css('display', '')) || $(".spn-image-select").css('display', 'none');
				//-- Set upload images

				//++ set detail
				$('#tbl_detail_size > *:not("thead") tr:not(".tr-edit-panel")').remove();
				$('.total-value').html(' -- ');
				$('.total-price').html(' -- ');
				_str = '';
				var _grand_total = 0;
				var _total_amount = 0;
				if ('detail' in cb_args[0]) {
					var _det = cb_args[0]['detail']
					for (_r in _det) {
						if (('pattern_rowid' in _det[_r]) && ('code' in _det[_r]) && ('color' in _det[_r]) && ('size' in _det[_r])) {
							_row = _det[_r];
							var _tr = '<tr><td class="control-button eventView-hide"><div class="control-button"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"> <img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td><td pattern_rowid="' + _row["pattern_rowid"] + '">' + _row["code"] + '</td><td>' + _row["color"] + '</td></tr>';
							$('#edit_panel1').before(_tr);

							var _size = _row['size'];
							var _avgPrice = 0;
							var _sumPrice = 0;
							if ($.isArray(_size)) {
								_tr = '<tr>';
								_total = 0;
								$('#edit_panel2 input:not(.cls-price-each)').each(
									function () {
										var _order_size_rowid = $(this).attr('order_size_rowid') || 0;
										var _cat_id = $(this).attr('cat_id') || 0;
										var _sub_cat_id = $(this).attr('sub_cat_id') || 0;
										var _class = $(this).attr('class') || '';
										_class = _class.replace('user-input input-integer', '');
										for (var _i=_size.length-1;_i>=0;_i--) {
											var _val = 0;
											var _rowPrice = 0; 
											if (_size[_i]['order_size_rowid'] == _order_size_rowid) {
												_val = parseInt(_size[_i]['qty']);
												_avgPrice = parseFloat(_size[_i]['price'] || 0);
												_tr += '<td order_size_rowid="' + _order_size_rowid + '" cat_id="' + _cat_id + '" sub_cat_id="' + _sub_cat_id + '" class="' + _class + '">' + _val + '</td>';
												_size.remove(_i);
												
												_total += _val;
												_sumPrice += (_val * _avgPrice);
												return true;
											}
										}
										_tr += '<td order_size_rowid="' + _order_size_rowid + '" cat_id="' + _cat_id + '" sub_cat_id="' + _sub_cat_id + '" class="' + _class + '"></td>';
									}
								);
								//_avgPrice = (_total > 0) ? (_sumPrice / _total) : 0;
								_total_amount += _sumPrice;
								_grand_total += _total;
								_tr += '<td><div class="cls-sub-total">' + _total + '</div> * <div class="cls-price-each">' + formatNumber(_avgPrice) + '</div> = <div class="cls-row-sum-amount">' + formatNumber(_total * _avgPrice) + '</div></td><td class="control-button" style="width:50px;"><div class="control-button eventView-hide"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"><img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td></tr>';

								$('#edit_panel2').before(_tr);
							}
						}
					}
					_blnDetailChanged = false;
					$('.total-value').html(_grand_total);
					if ((_total_amount <= 0) && (_cleanNumericValue(cb_args[0]['total_price']) > 0) && (_grand_total > 0)) {
						_total_amount = _cleanNumericValue(cb_args[0]['total_price']);
						var _avg_prc = (_total_amount / _grand_total);
						$('#tbl_detail_size tbody tr').each(function() {
							var _elmQty = $('div.cls-sub-total', this);
							var _elmPrice = $('div.cls-price-each', this);
							var _elmSum = $('div.cls-row-sum-amount', this);
							var _qty = getValue(_elmQty, 0);
							_elmPrice.html(formatNumber(_avg_prc));
							_elmSum.html(formatNumber(_avg_prc * _qty));
						});
					}
					$('.total-price').html(formatNumber(_total_amount));
				}
				//++ Others price list
				$('#tbl_op_list > *:not("thead") tr:not("#op_edit_panel")').remove();
				_str = '';
				if (('others_price' in cb_args[0]) && ($.isArray(cb_args[0]['others_price'])) && (cb_args[0]['others_price'].length > 0)) {
					for (_r in cb_args[0]['others_price']) {
						_row = cb_args[0]['others_price'][_r];
						if ('detail' in _row) {
							_str += '<tr><td colspan="2" class="price_detail">' + _row['detail'] + '</td><td colspan="2" class="price">' + _row['price'] + "</td><td class='control-button'><img src='public/images/edit.png' class='op-edit-ctrl ctrl-edit eventView-hide' title='Edit' /><img src='public/images/b_delete.png' class='op-edit-ctrl ctrl-delete eventView-hide' title='Delete' /></td></tr>";
						}
					}
					$('#op_edit_panel').before(_str);
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
	if (!$("#dialog-modal").dialog( "isOpen" )) $("#dialog-modal").dialog( 'close' );
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
				if ('others_price' in data.data) delete data.data['others_price'];
				if ('total_price' in data.data) delete data.data['total_price'];
				if ('remark1' in data.data) delete data.data['remark1'];
				if ('remark2' in data.data) delete data.data['remark2'];
				/* -- mail issue 20160225 -- */
				//-- remove data dont want to clone
				_blnFetching = true; //prevent on select event, looping
				_doSetValueFormUserInput($('#frm_edit'), data.data);
				_blnFetching = false; //prevent on select event, looping

				//++ Screens list
				$('#tbl_sc_list > *:not("thead") tr:not("#sc_edit_panel")').remove();
				_str = '';
				if ('screen' in data.data) {
					for (_r in data.data['screen']) {
						if ('order_screen' in data.data['screen'][_r]) {
							_row = data.data['screen'][_r];
							_str += '<tr><td order_screen_rowid="' + _row['order_screen_rowid'] + '" >' + _row['order_screen'] + "</td><td>" + _row['position'] + "</td><td>" + _row['detail'] + "</td><td>" + _row['size'] + "</td><td>" + _row['job_hist'] + "</td><td>" + _row['price'] + "</td><td class='control-button'><img src='public/images/edit.png' class='sc-edit-ctrl ctrl-edit' title='Edit' /><img src='public/images/b_delete.png' class='sc-edit-ctrl ctrl-delete' title='Delete' /></td></tr>";
						}
					}
					$('#sc_edit_panel').before(_str);
					_blnScChanged = true;
				}
				//-- Screens list

				//++ set detail
				$('#tbl_detail_size > *:not("thead") tr:not(".tr-edit-panel")').remove();
				$('#size-grand-total').html('0');
				$('#spn-total_price').html('0.00');
				_str = '';
				if ('detail' in data.data) {
					var _det = data.data['detail']
					for (_r in _det) {
						if (('pattern_rowid' in _det[_r]) && ('code' in _det[_r]) && ('color' in _det[_r]) && ('size' in _det[_r])) {
							_row = _det[_r];
							var _tr = '<tr><td class="control-button"><div class="control-button eventView-hide"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"> <img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td><td pattern_rowid="' + _row["pattern_rowid"] + '">' + _row["code"] + '</td><td>' + _row["color"] + '</td></tr>';
							$('#edit_panel1').before(_tr);
							var _size = _row['size'];
							if ($.isArray(_size)) {
								_tr = '<tr>';
								$('#edit_panel2 input:not(.cls-price-each)').each(
									function () {
										var _order_size_rowid = $(this).attr('order_size_rowid') || 0;
										var _cat_id = $(this).attr('cat_id') || 0;
										var _sub_cat_id = $(this).attr('sub_cat_id') || 0;
										var _class = $(this).attr('class') || '';
										_class = _class.replace('user-input input-integer', '');
										for (var _i=_size.length-1;_i>=0;_i--) {
											if (_size[_i]['order_size_rowid'] == _order_size_rowid) {
												_tr += '<td order_size_rowid="' + _order_size_rowid + '" cat_id="' + _cat_id + '" sub_cat_id="' + _sub_cat_id + '" class="' + _class + '"></td>';
												_size.remove(_i);
												return true;
											}
										}
										_tr += '<td order_size_rowid="' + _order_size_rowid + '" cat_id="' + _cat_id + '" sub_cat_id="' + _sub_cat_id + '" class="' + _class + '"></td>';
									}
								);
								_tr += '<td><div class="cls-sub-total">' + $('#edit_panel2 div.cls-sub-total').html() + '</div> * <div class="cls-price-each">' + $('#edit_panel2 input.cls-price-each').val() + '</div> = <div class="cls-row-sum-amount">' + $('#edit_panel2 div.cls-row-sum-amount').html() + '</div><td class="control-button" style="width:50px;"><div class="control-button eventView-hide"><img src="public/images/edit.png" class="edit-ctrl bttn-edit" act="edit" title="Edit"><img src="public/images/b_delete.png" class="edit-ctrl bttn-delete" act="delete" title="Delete"></div></td></tr>';

								$('#edit_panel2').before(_tr);
							}
						}
					}
					_blnDetailChanged = true;
				}
				$('#tbl_op_list > *:not("thead") tr:not("#op_edit_panel")').remove();
				__fncManageExpired(true);
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

function __fncManageExpired(blnIsEdit) {
	var _blnIsCheckValue = blnIsEdit || false;
	if (! _blnIsCheckValue) {
		$('.cls-is-expired').addClass('hidden');
	} else {	
		$('table#tbl_detail_size tr th.th-chest.cls-is-expired').each(function() {
			var _elem = $(this);
			if (_elem.length <= 0) return true;
			
			var _order_size_rowid = _elem.attr('order_size_rowid') || 0;
			if ($('table#tbl_detail_size tr:not(.tr-edit-panel) td[order_size_rowid='+_order_size_rowid+']:not(:empty)').length > 0) {
				_elem.addClass('has-value');
			} else {
				_elem.addClass('hidden');
				$('table#tbl_detail_size tr td[order_size_rowid='+_order_size_rowid+']').each(function() {
					$(this).addClass('hidden');
				});
				$('table#tbl_detail_size tr.tr-edit-panel td input[order_size_rowid='+_order_size_rowid+']').each(function() {
					$(this).addClass('hidden');
					$($(this).parents('td.td-qty')[0]).addClass('hidden');
				});
			}
		});
		
		$('table#tbl_detail_size tr th.th-cat-title').each(function() {
			var _elem = $(this);
			if (_elem.length <= 0) return true;

			var _cat_rowid = _elem.attr('cat_id') || 0;
			var _orgSpan = $('table#tbl_detail_size tr th.th-chest[cat_id='+_cat_rowid+']').length;
			var _curSpan = $('table#tbl_detail_size tr th.th-chest[cat_id='+_cat_rowid+']:not(.cls-is-expired)').length + $('table#tbl_detail_size tr th.th-chest[cat_id='+_cat_rowid+'].has-value').length;
			
			if (_curSpan > 0) {
				_elem.attr('colspan', _curSpan).attr('org_colspan', _orgSpan);
			} else {
				_elem.addClass('hidden').attr('colspan', 0);
			}
		});
		
		$('table#tbl_detail_size tr th.th-sub-cat-title').each(function() {
			var _elem = $(this);
			if (_elem.length <= 0) return true;

			var _cat_rowid = _elem.attr('cat_id') || 0;
			var _sub_cat_rowid = _elem.attr('sub_cat_id') || 0;
			var _orgSpan = $('table#tbl_detail_size tr th.th-chest[cat_id='+_cat_rowid+'][sub_cat_id='+_sub_cat_rowid+']').length;
			var _curSpan = $('table#tbl_detail_size tr th.th-chest[cat_id='+_cat_rowid+'][sub_cat_id='+_sub_cat_rowid+']:not(.cls-is-expired)').length + $('table#tbl_detail_size tr th.th-chest[cat_id='+_cat_rowid+'][sub_cat_id='+_sub_cat_rowid+'].has-value').length;
			
			if (_curSpan > 0) {
				_elem.attr('colspan', _curSpan).attr('org_colspan', _orgSpan);
			} else {
				_elem.addClass('hidden').attr('colspan', 0);
			}
		});
	}
}
