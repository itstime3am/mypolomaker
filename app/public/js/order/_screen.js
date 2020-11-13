if (typeof _SC_Load == 'undefined') {
	_SC_Load = true;
	
	var _ADD_SCREEN_ROW_LIMIT = 9;
	var _sc_current_row = false;
	var _blnScChanged = false;
	$(function() {

		_DLG_STATUS_REMARK = $('#div_status_remark').dialog({
			height: 180
			, width: 780
			, show: {effect:"puff", duration: 100}
			, hide: {effect:"fade", duration: 100}
			, modal: true
			, resizable: true
			, closeOnEscape: true
			, autoOpen: false
			, beforeClose: function(event, ui) {
				$(this).removeAttr('status_rowid').removeAttr('status_text');
				$('#div_status_remark').css('display','none');
				$(this).find('#txa-status_remark').attr('readonly',false);
				$(this).find('#txa-status_remark').val('');
			}
			, buttons: {
				'Commit': function() {
					var ele = $('#div_status_remark');
					var _rowid = ele.attr('rowid') || false;
					var _status_rowid = ele.attr('status_rowid') || false;
					var _status_text =ele.attr('status_text') || false;
					var _type = ele.attr('type') || false;
					var _remark = $(this).find('#txa-status_remark').val();
					var readonly = $(this).find('#txa-status_remark').attr('readonly') || false;

					doClearVldrErrorElement($('#sel-status_remark'));
					doClearVldrErrorElement($('#txa-status_remark'));
					if(!readonly){
						if (_remark == '') {
							doSetVldrError($('#sel-status_remark'), 'status_remark', 'required', 'กรุณาระบุเหตุผลในการเปลี่ยนเป็นสถานะ', 1);
							doSetVldrError($('#txa-status_remark'), 'status_remark', 'required', 'กรุณาระบุเหตุผลในการเปลี่ยนเป็นสถานะ', 1);
							_doDisplayToastMessage('กรุณาระบุเหตุผลในการเปลี่ยนเป็นสถานะ', 3, false);
						} else {
							if (confirm('กรุณายืนยันการเปลี่ยนสถานะป็น "' + _status_text + '"')) {
								__doChangeStatus(_rowid, _status_rowid, _status_text, _type, _remark)
								$(this).dialog('close');
							}
						}
					}else{
						$(this).dialog('close');
					}
					return false;
				}
				, 'Cancel': function() {
					$(this).dialog('close');
				}
			}
		});




		$('table#tbl_sc_list tr').on('change', '#txt-sc_price', function () {
				doClearVldrErrorElement(this)
				blnValidateElem_TypeDouble(this);
				return false;
			});
		
		$('table#tbl_sc_list tr').on('click', '#btnScSubmit', function() {
			var _sc_edit_panel = $(this).parents('#sc_edit_panel')[0];
			var _tbl = $(this).parents('#tbl_sc_list')[0];

			var _isUpdate = false;

			var _elemRowID = $('#sel-sc_order_screen_rowid', _sc_edit_panel).get(0);
			var _elemPrice = $('#txt-sc_price', _sc_edit_panel).get(0);
			var _elemPos = $('#sel-sc_position', _sc_edit_panel).get(0);

			doClearVldrErrorElement(_elemRowID);
			doClearVldrErrorElement(_elemPrice);
			doClearVldrErrorElement(_elemPos);
			
			if (! (blnValidateElem_TypeRequired(_elemRowID) && blnValidateElem_TypeRequired(_elemPos) && blnValidateElem_TypeRequired(_elemPrice) && blnValidateElem_TypeDouble(_elemPrice))) {
				return false;
			}
			var _sel = $('option:selected', _elemRowID);
			if (_sel.length <= 0) return false;
			
			if (_sc_current_row) _isUpdate = (_sc_current_row.css('display') == 'none');

			if (_isUpdate) {
				_arr = _sc_current_row.children();
				if (_arr.length > 4) {
					$(_arr[0]).html(_sel.text());
					$(_arr[0]).attr('order_screen_rowid', _sel.val());
					//$(_arr[1]).html($('#sc_edit_panel #txt-sc_position').val());
					$(_arr[1]).html($('option:selected', _elemPos).text());
					$(_arr[2]).html($('#txa-sc_detail', _sc_edit_panel).val());
					$(_arr[3]).html($('#txt-sc_size', _sc_edit_panel).val()/* + ' ' + $('#sel-sc_size_unit option:selected', _sc_edit_panel).text() */);
					$(_arr[4]).html($('#sel-sc_job_hist option:selected', _sc_edit_panel).text());
					$(_arr[5]).html($('#txt-sc_price', _sc_edit_panel).val());
				}
			} else {
				_scInsertDetailRow(_tbl, {
					"order_screen_rowid": _sel.val()
					, "order_screen": _sel.text()
					, "position": $('option:selected', _elemPos).text()
					, "detail": $('#txa-sc_detail', _sc_edit_panel).val()
					, "size": $('#txt-sc_size', _sc_edit_panel).val()
					, "job_hist": $('#sel-sc_job_hist option:selected', _sc_edit_panel).text()
					, "price": $('#txt-sc_price', _sc_edit_panel).val()
				});
			}
			_blnScChanged = true;
			$('#btnScCancel', _sc_edit_panel).trigger('click');
			return false;
		});

		$('table#tbl_sc_list tr').on('click', '#btnScCancel', function() {
			var _tbl = $(this).parents('table#tbl_sc_list')[0];
			//var _sc_edit_panel = $(this).parents('tr#sc_edit_panel')[0];
			
			if (_sc_current_row) _sc_current_row.css('display', '');
			scClearEditPanel();
			// check to prevent add more then limit row
			__fncCheckSCRowLimit();
			return false;
		});

		$('table#tbl_sc_list tbody').on('click', '.ctrl-edit', function() {
			var _tbl = $(this).parents('table#tbl_sc_list')[0];
			var _sc_edit_panel = $('tr#sc_edit_panel', _tbl)[0];
			
			if (_sc_current_row) $('#btnScCancel', _sc_edit_panel).trigger('click');

			_sc_current_row = $($(this).parents('tr').get(0)).css('display', 'none');
			
			scPopulateEditPanel(_sc_current_row);
			return false;
		});

		$('table#tbl_sc_list tbody').on('click', '.ctrl-delete', function() {
			var _tbl = $(this).parents('table#tbl_sc_list')[0];
			var _sc_edit_panel = $('tr#sc_edit_panel', _tbl)[0];

			if (confirm(MSG_CONFIRM_DELETE_ROW.replace(/\(*\s+v_XX_1\s+\)*/, ''))) {
				$(this).parents('tr').get(0).remove();
				_blnScChanged = true;
				__fncCheckSCRowLimit();
			}
			return false;
		});
		
		$('#sc_edit_panel #sel-sc_position').combobox({is_allow_add: true});

		$('body').on('click', '.view-status-remark', function () {
			var txt_remark = $(this).attr('txt_remark');
			if(txt_remark != undefined && txt_remark != ''){
				
				var ele = $('#div_status_remark.ui-dialog-content').children('#txa-status_remark');
				ele.val(txt_remark).attr('readonly',true);
				
			}
			_DLG_STATUS_REMARK.dialog('option', 'title', 'สาเหตุ ').dialog( "open" );
		});

		$('body').on('change', '.cls-sel-change-status', function () {
			var _rowid = $(this).attr('rowid') || -1;
			var _selOpt = $('option:selected', this);
			var _type = _selOpt.attr('type') || false;
			var _status_text = _selOpt.attr('name') || false;
			var _status_rowid = (_selOpt.length > 0) ? _selOpt.val() : -1;
			// var _status_text = (_selOpt.length > 0) ? _selOpt.val() : '';
			if ((_rowid >= 0) && (_status_rowid == 110)) {
				// 100:CMP, 180:CNL, 200:CLO
				$('#div_status_remark').attr('rowid', _rowid)
				.attr('status_rowid', _status_rowid)
				.attr('status_text', _status_text)
				.attr('type', _type)
				.show();

				_DLG_STATUS_REMARK.dialog('option', 'title', ' ระบุสาเหตุ ').dialog( "open" );
			}else{
				if (confirm('กรุณายืนยันการเปลี่ยนสถานะป็น "' + _status_text + '"')) {
					__doChangeStatus(_rowid, _status_rowid, _status_text, _type)
				};
			}
			$(this).val($.data(this, 'current'));
			return false;
		});
	
		
	});
	//++ Screen panel function
	function _scInsertDetailRow(tbl, objNew) {
		var _order_screen_txt = false;
		if (('order_screen' in objNew)) {
			_order_screen_txt = objNew.order_screen;
		} else {
			var _sc_edit_panel = $('tr#sc_edit_panel', tbl)[0];
			var _elmSel = $('#sel-sc_order_screen_rowid', _sc_edit_panel).get(0);
			var _opt = $('option', _elmSel).filter(function() { return ($(this).val() == objNew.order_screen_rowid); });
			if (_opt.length > 0) _order_screen_txt = _opt.text();
		}
		var _str = '<tr><td order_screen_rowid="' + objNew.order_screen_rowid + '" >' + _order_screen_txt + '</td>';
		//_str += '<td>' + $('#sc_edit_panel #txt-sc_position').val() + '</td>';
		_str += '<td>' + objNew.position + '</td>';
		_str += '<td>' + objNew.detail + '</td>';
		_str += '<td>' + objNew.size + '</td>';
		_str += '<td>' + objNew.job_hist + '</td>';
		_str += '<td class="eventView-hide">' + objNew.price + '</td>';
		_str += '<td class="control-button eventView-hide"><img src="public/images/edit.png" class="sc-edit-ctrl ctrl-edit" title="Edit" /><img src="public/images/b_delete.png" class="sc-edit-ctrl ctrl-delete" title="Delete" /></td></tr>';
		
		$('tbody', tbl).append(_str);
	}

	function _scInsertDetailRowOrder(tbl, objNew) {
		var _status = objNew.disp_status || '-';
		var _block_emp = objNew.block_emp || '-';
		var _approve_date = objNew.approve_date || '-';

		var _order_screen_txt = false;
		if (('order_screen' in objNew)) {
			_order_screen_txt = objNew.order_screen;
		} else {
			var _sc_edit_panel = $('tr#sc_edit_panel', tbl)[0];
			var _elmSel = $('#sel-sc_order_screen_rowid', _sc_edit_panel).get(0);
			var _opt = $('option', _elmSel).filter(function() { return ($(this).val() == objNew.order_screen_rowid); });
			if (_opt.length > 0) _order_screen_txt = _opt.text();
		}
		var _str = '<tr><td order_screen_rowid="' + objNew.disp_type + '" >' + objNew.disp_type + '</td>';
		//_str += '<td>' + $('#sc_edit_panel #txt-sc_position').val() + '</td>';
		_str += '<td>' + objNew.position + '</td>';
		_str += '<td>' + objNew.detail + '</td>';
		_str += '<td>' + objNew.size + '</td>';
		_str += '<td>' + objNew.job_hist + '</td>';
		var status_remark = '';
		if(objNew.status_remark){ 
			status_remark =  '<img src="public/images/doc_table_icon.png" class="view-status-remark" txt_remark="'+objNew.status_remark+'" />'
		}
		_str += '<td class="status_remark">' + status_remark  + '</td>';
		_str += '<td class="disp_status">' + _status + '</td>';
		_str += '<td class="status"> <select class="cls-sel-change-status" rowid="'+objNew.prod_rowid+'" status_rowid="'+objNew.prod_status+'">' + renderAvaiStatus(objNew.prod_rowid, objNew.arr_avail_status, objNew.screen_type, objNew.prod__status); +'</select></td>';
		_str += '<td class="approve_date">'  +_approve_date + '</td>';
		_str += '<td class="block_emp">'  +_block_emp + '</td>';
		_str += '<td class="img">' + renderBtnDownload(objNew.img, objNew.screen_type) + '</td>';
		_str += '<td class="eventView-hide">' + objNew.price + '</td>';
		_str += '<td class="control-button eventView-hide"><img src="public/images/edit.png" class="sc-edit-ctrl ctrl-edit" title="Edit" /><img src="public/images/b_delete.png" class="sc-edit-ctrl ctrl-delete" title="Delete" /></td></tr>';
		
		$('tbody', tbl).append(_str);
	}

	function __doChangeStatus(rowid, status_rowid, _status_text, _type, _remark) {
		var _index = 0;
		var _json ='';
		var _rowid = rowid || false;
		var _status_rowid = status_rowid || false;

		if (!(_rowid && _status_rowid)) {
			alert('Invalid parameters to change status ( rowid = ' + rowid + ', status_rowid = ' + status_rowid + ' )');
			return false;
		}
	
		if(_remark){
			_json = { "rowid": rowid, "status_rowid": status_rowid,  "type": _type, "status_remark": _remark};
		}else{
			_json = { "rowid": rowid, "status_rowid": status_rowid,  "type": _type };
		}
		_str = JSON.stringify(_json);
		$.ajax({
			type: "POST",
			url: "./order_polo/change_status_manu_by_id",
			contentType: "application/json;charset=utf-8",
			dataType: "json",
			data: _str,
			success: function (data, textStatus, jqXHR) {
				if (data.success) {
					$('select[rowid="'+ rowid+'"]').empty().append('<option>--</option>');
					$('select[rowid="'+ rowid+'"]').parent().siblings('td.disp_status').text(_status_text);
					_doDisplayToastMessage(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, 'rowid#' + _rowid + ' status has changed to "' + status_rowid + '"'), 3, false);
					doSearch(false);
				} else {
					doDisplayInfo("UnknownError", "ErrorMessage", _index);
				}
				if (typeof fncOnSuccess == 'function') fncOnSuccess.apply(this);
				$("#dialog-modal").dialog("close");
			}
			, error: function (jqXHR, textStatus, errorThrown) {
				doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage", _index);
				$("#dialog-modal").dialog("close");
			}, statusCode: {
				404: function () {
					doDisplayInfo("Page not found", "ErrorMessage", _index);
					$("#dialog-modal").dialog("close");
				}
			}
		});
	}
	
	function renderBtnDownload(_img, _screen_type){
		var _type = '';
		var _path = '';
		var _elSel = $('<div>')

		if (_screen_type == 1){
			_type ='weave';
		}else{
			_type = 'screen';
		}

		if(_img){
			_path = 'http://manu.mypolomaker.com/app/uploads/manu_'+ _type +'/'+_img;
				_elSel.append($('<a>').attr("target", "_blank").attr("href", _path).attr('download', _img).html('โหลดรูปภาพ').append($('<buntton>')));
			
		}
		return _elSel.html();

	}

	function renderAvaiStatus(_rowid, _avai_status, _screen_type, _prod_status){

		var _type = '';
		var _arr_status;

		if (_screen_type == 1){
			_type ='weave';
			_arr_status = _ARR_WEAVE_STATUS;
		}else{
			_type = 'screen';
			_arr_status  = _ARR_SCREEN_STATUS
		}

		$('.cls-sel-change-status')
		.attr('screen_type', _screen_type)

		var _elSel = $('<select>').append($('<option>').html('--'))

		var _arrStt = [];
		if (typeof _avai_status == 'string' && _avai_status != '') _arrStt = JSON.parse(_avai_status);

		if (_arrStt.length > 0) {
			if ($.isArray(_arr_status)) {
				$.each(_arr_status, function(indx, obj) {
					if (('code' in obj) && ('rowid' in obj) && ('name' in obj)) {
						var _code = obj['code'] || false;
						if (! _code) return true;
						_code = _code.toLowerCase();
						if (_arrStt.indexOf(_code) >= 0) {
							_elSel.append($('<option>').attr("code", obj["code"]).attr('type', _type).attr("name", obj["name"]).val(obj["rowid"]).html(obj["code"] + ': ' + obj["name"]));
						}
					}
				});
			}
		}

		return (_elSel.html());
		
	}
	
	function scPopulateEditPanel(sc_current_row) {
		var _tbl = $(sc_current_row).parents('table#tbl_sc_list')[0];
		var _sc_edit_panel = $('tr#sc_edit_panel', _tbl)[0];
		_sc_current_row = sc_current_row;

		_arrItems = $(_sc_current_row).children();
		$('#sel-sc_order_screen_rowid option', _sc_edit_panel).each(function() {
			if ($(this).text() == $(_arrItems[0]).html()) {
				$(this).prop('selected', true);
				return false;
			}
		});
		//$('#sc_edit_panel #txt-sc_position').val($(_arrItems[1]).html());
		var _pos = $(_arrItems[1]).html();
		var _elem = $('#sel-sc_position', _sc_edit_panel);
		if ($('option[value="' + _pos + '"]', _elem).length <= 0) {
			var newOption = $('<option></option>').val(_pos).html(_pos).attr('new', true).appendTo(_elem);
		}
		_setElemValue(_elem, _pos, false);

		$('#txa-sc_detail', _sc_edit_panel).val($(_arrItems[2]).html());
		/*var _arr = ($(_arrItems[3]).html() + '').split(' ');
		if ((_arr.length > 1) && (! isNaN(_arr[0]))) $('#txt-sc_size', _sc_edit_panel).val(parseFloat(_arr[0]));*/
		$('#txt-sc_size', _sc_edit_panel).val($(_arrItems[3]).html());
		$('#sel-sc_job_hist option', _sc_edit_panel).each(function() {
			if ($(this).text() == $(_arrItems[4]).html()) {
				$(this).prop('selected', true);
				return false;
			}
		});
		$('#txt-sc_price', _sc_edit_panel).val($(_arrItems[5]).html());

		$('#btnScSubmit', _sc_edit_panel).prop('title', 'Edit');
		$('#btnScSubmit', _sc_edit_panel).prop('act', 'update');
		$('#btnScCancel', _sc_edit_panel).prop('title', 'Cancel');
		$('#btnScCancel', _sc_edit_panel).prop('act', 'cancel');
		
		$(_sc_edit_panel).detach().insertAfter(_sc_current_row).show();
	}
	
	function scClearEditPanel() {
		var _tbl = $('table#tbl_sc_list').filter(__fnc_filterNotNestedHiddenClass);
		var _divPrnt = $($(_tbl).parents('div#ord_scrn_container'));
		var _sc_edit_panel = $('tr#sc_edit_panel', _tbl);
		
		$('#sel-sc_order_screen_rowid', _sc_edit_panel).prop('selectedIndex', -1);
		if ($('#sel-sc_position', _sc_edit_panel).data("ui-combobox")) {
			var _scPos = $('#sel-sc_position', _sc_edit_panel);
			_scPos.combobox('clearNewOption');
			_scPos.combobox('clearValue');
		} else {
			$('#sel-sc_position', _sc_edit_panel).prop('selectedIndex', -1);
		}
		$('#txa-sc_detail', _sc_edit_panel).val('');
		$('#txt-sc_size', _sc_edit_panel).val('');
		$('#sel-sc_job_hist', _sc_edit_panel).prop('selectedIndex', -1);
		$('#txt-sc_price', _sc_edit_panel).val('');

		$('#btnScSubmit', _sc_edit_panel).prop('title', 'Insert');
		$('#btnScSubmit', _sc_edit_panel).prop('act', 'insert');
		$('#btnScCancel', _sc_edit_panel).prop('title', 'Reset');
		$('#btnScCancel', _sc_edit_panel).prop('act', 'reset');
		
		_sc_current_row = false;
		$(_sc_edit_panel).detach().appendTo($('tfoot', _tbl));
	}

	function isScEditingRow() {
		var _tbl = $('table#tbl_sc_list').filter(__fnc_filterNotNestedHiddenClass);
		var _divPrnt = $($(_tbl).parents('div#ord_scrn_container'));
		var _sc_edit_panel = $('tr#sc_edit_panel', _tbl);

		if (((getValue($('#sel-sc_order_screen_rowid', _sc_edit_panel), 0) > 0) && (getValue($('#sel-sc_position', _sc_edit_panel), '').trim() != '') 
			&& (getValue($('#txt-sc_price', _sc_edit_panel), 0) > 0))) {
			return true;
		} else {
			return false;
		}
	}
	
	function __fncCheckSCRowLimit() {
		var _tbl = $('table#tbl_sc_list').filter(__fnc_filterNotNestedHiddenClass);
		var _divPrnt = $($(_tbl).parents('div#ord_scrn_container'));
		var _sc_edit_panel = $('tr#sc_edit_panel', _tbl);

		var _jqEditPnl = $(_sc_edit_panel);
		var _jqWarn = $('#tr_warn_max_row', _tbl);
		if ($('tbody tr', _tbl).length < _ADD_SCREEN_ROW_LIMIT) {
			_jqEditPnl.show();
			_jqWarn.hide();
		} else {
			_jqEditPnl.hide();
			_jqWarn.show();
		}
	}
	
	function __getScreenCtrl_CommittedChanged() {
		var _tbl = $('table#tbl_sc_list').filter(__fnc_filterNotNestedHiddenClass);
		var _divPrnt = $($(_tbl).parents('div#ord_scrn_container'));
		var _sc_edit_panel = $('tr#sc_edit_panel', _tbl);

		_screen = [];
		$(":not(thead) tr", _tbl).each(function () {
			if ($(this).attr('id') == 'sc_edit_panel') {
				if (isScEditingRow()) {
					_screen.push({
						"seq": (_screen.length + 1)
						, "order_screen_rowid": _cleanNumericValue(getValue($('#sel-sc_order_screen_rowid', _sc_edit_panel), 0))
						, "position": getValue($('#sel-sc_position', _sc_edit_panel), '')
						, "detail": getValue($('#txa-sc_detail', _sc_edit_panel), '')
						, "size": getValue($('#txt-sc_size', _sc_edit_panel), '')
						, 'job_hist': getValue($('#sel-sc_job_hist', _sc_edit_panel), '')
						, 'price': _cleanNumericValue(getValue($('#txt-sc_price', _sc_edit_panel), 0))
					});
				}
			} else if ($(this).css('display') != 'none') { //case open edit panel but not commit row before submit form
				_arr = $(this).children();
				if (_arr.length > 5) {
					/*var _arrS = ($(_arr[3]).html() || '').split(' ');
					var _dblSize = ((_arrS.length > 1) && (! isNaN(_arrS[0]))) ? parseFloat(_arrS[0]) : null;*/
					_screen.push({
						"seq": (_screen.length + 1)
						, "order_screen_rowid": _cleanNumericValue($(_arr[0]).attr('order_screen_rowid') || 0)
						, "position": ($(_arr[1]).html() || '')
						, "detail": ($(_arr[2]).html() || '')
						//, "size": (_dblSize || '')
						, "size": ($(_arr[3]).html() || '')
						, 'job_hist': ($(_arr[4]).html() || '')
						, 'price': _cleanNumericValue($(_arr[5]).html() || 0)
					});
				}
			}
		});
		return _screen;			
	}
}
//-- Screen panel function
