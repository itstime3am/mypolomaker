$(function() {
	doPopulateTable = function (arrData, blnChangeSearchCriteria) {
		var _blnChangeSearchCriteria = typeof blnChangeSearchCriteria != undefined?blnChangeSearchCriteria:true;
		_OBJ_CHANGED_DATA = {};
		//if (_objDataTable) _objDataTable.fnDestroy(true);
		$('#divDisplayQueryResult').html(_TMPL_TBL_SEARCH);			
		_objDataTable = $('#tblSearchResult').dataTable({
			"jQueryUI": true
			,"deferRender": true
			,"data": arrData
			,"columns": _aoColumns
			,"autoWidth": false
			,"processing": true
			,"info": true
			,"searching": true
			,"searchDelay": 500
			,"ordering": true
			,"order": []
			,"sScrollY": "85%"
			,"sScrollX": "100%"
			,"sScrollXInner": "500%"
			,"language": {"url": "public/js/jquery/dataTable/dataTables.thai.lang"}
			,"dom": '<"ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-tl ui-corner-tr"Bf>t<"ui-toolbar ui-widget-header ui-helper-clearfix"B><"ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-bl ui-corner-br"ip>'
			,"scrollCollapse": true
			,"paging": true
			,"pagingType": "full_numbers"
			,"lengthMenu": [[15, 25, 35, 50, -1], [15, 25, 35, 50, "all"]]
			,"pageLength": -1
			,"stateSave": true
			,"buttons": (_tableToolButtons != undefined) ? _tableToolButtons : []
			,"stateLoadParams": function (oSettings, oaData) {
				if (_blnChangeSearchCriteria) { //Destroy state saving if requery
					_blnChangeSearchCriteria = false;
					return false;
				}
			}
			,"fnInitComplete": function(oSettings, json) {
				oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();	
				_objDataTableFixedColumns = new $.fn.dataTable.FixedColumns( this, { "iLeftColumns": 5 });
				setTimeout(_doResize, 1000);
			}
			,"fnDrawCallback": function () { //fnInfoCallback
				_doSetEditableColumns();
				setTimeout(_doSetDataTablePagePlugins, 1000);
			}
			,"fixedHeader": true
			//,"colReorder": true
		});
		//_objDataTable.fnDraw();
		_doUpdateSummaryValue(arrData);
		// check if search and have row(s) hide search panel to extending work spaces
		doToggleLeftPanel((arrData.length == 0));
	};
});

if (_ALLOW_EDIT === false) {
	_doSetEditableColumns = function() {
		//do nothing;
	};
} else {
	_doSetEditableColumns = function() {
		$('#tblSearchResult tbody tr td.deliver_status_id').editable(
			function (value, settings) {
				var _val;
				if (_doPrepareChangedData('deliver_status_id', value, this)) {
					_val = (settings.data[value]);
				} else {
					_val = this.revert;
				}
				//++ Set value and class to fixed column 
				var _otr = $($(this).parents('tr')[0]);
				var _trIndex = _otr.index();
				var _tdIndex = $(this).index();
				if ((_trIndex > -1) && (_tdIndex > -1) && ($('div.DTFC_LeftBodyLiner table tbody tr').length > _trIndex)) {
					var _fcTr = $($('div.DTFC_LeftBodyLiner table tbody tr').get(_trIndex));
					var _fcCol = $($('td', _fcTr).get(_tdIndex));
				}
				if (_fcCol) {
					_fcCol.html(_val);
					_fcCol.removeClass('data-edit-changed');
					if ($(this).hasClass('data-edit-changed')) _fcCol.addClass('data-edit-changed');
				}
				//-- Set value and class to fixed column 
				return _val;
			}, {
					type: 'select'
					, data: _MASTER_DT_EDITABLE['status']
					, onblur: 'submit'
					, tooltip: 'Click to edit...'
					, callback: function() {
						$('div.DTFC_LeftBodyWrapper').css('display', '');
					}
				}
		);
		$('#tblSearchResult tbody tr td.deliver_by').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('deliver_by', val, this)) return val;
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.collected_cash').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('collected_cash', val, this)) return formatNumber(parseFloat(val.toString().replace(',', '')), 2);
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, data: function (val, settings) { return val.toString().replace(/,/gi, ''); }
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.collected_method').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('collected_method', val, this)) return (settings.data[val]);
				return this.revert;
			}, {
				type: 'select'
				, data: _MASTER_DT_EDITABLE['collected_method']
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.actual_deliver_datetime').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('actual_deliver_datetime', val, this)) return val;
				return this.revert;
			}, {
				type: 'datetimepicker'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.deliver_terminal').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('deliver_terminal', val, this)) return val;
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.terminal_recordno').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('terminal_recordno', val, this)) return val;
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.terminal_contactno').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('terminal_contactno', val, this)) return val;
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.total_packs').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('total_packs', val, this)) return parseInt(val.toString().replace(',', ''));
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.total_items').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('total_items', val, this)) return parseInt(val.toString().replace(',', ''));
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.delivering_fee').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('delivering_fee', val, this)) return formatNumber(parseFloat(val.toString().replace(',', '')), 2);
				return this.revert;
			}, {
				type: 'text'
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
		$('#tblSearchResult tbody tr td.remark').editable(
			function (val, settings) { 
				if (_doPrepareChangedData('remark', val, this)) return val;
				return this.revert;
			}, {
				type: 'textarea'
				, row: 1
				, onblur: 'submit'
				, tooltip: 'Click to edit...'
			}
		);
	};
}

function _doPrepareChangedData(data_field, val, objSource) {
	var _tr = $(objSource).parents('tr')[0];
	var _datatable = $(objSource).parents('.cls-tbl-list')[0];
	if ((_tr) && (_datatable)) {
		var _aData = $(_datatable).dataTable().fnGetData(_tr);
		var _org_value = (data_field in _aData) ? _aData[data_field] : null;			
		if (('rowid' in _aData) && (data_field) && (val)) {
			var _value = val;
			var _rowid = _aData['rowid'].toString();
			var _str_index = _rowid.toString();
			
			//Clear this property in case edit and clear val
			if ((_str_index in _OBJ_CHANGED_DATA) && (data_field in _OBJ_CHANGED_DATA[_str_index])) delete _OBJ_CHANGED_DATA[_str_index][data_field];
			var _blnIsUnchanged = false;
			switch (data_field) {
				case 'deliver_status_id':
				case 'total_packs':
				case 'total_items':
				case 'collected_method':
				case 'remark':
					if ((_org_value !== null) && (_org_value == val)) _blnIsUnchanged = true;
					break;
				case 'collected_cash':
				case 'delivering_fee':
					$('.ul-vldr-error-msg #li_' + data_field + '__typeDouble').remove();
					$(objSource).removeClass('input-invalid').removeProp('invalid-msg');
					_value = val.toString().replace(/\,/g, '');
					if (isNaN(_value)) {
						var _strErrMsg = MSG_VLDR_INVALID_DATATYPE.replace(/v_XX_1/g, '( ' + data_field + ': double )') + ' ';
						$(objSource).addClass('input-invalid').prop('invalid-msg', _strErrMsg);
						$('.ul-vldr-error-msg').append('<li id="li_' + data_field + '__typeDouble">' + _strErrMsg + '</li>');
						return false;
					}
					if (parseFloat(_aData[data_field].toString().replace(/\,/g, '')).toFixed(2) == parseFloat(_value).toFixed(2)) _blnIsUnchanged = true;
					break;
				default:
					var _elemInput = $(objSource).find('input');
					if (_elemInput.length < 1) break;
					if (_elemInput.is('.hasDatepicker')) {
						var _dat = _elemInput.datepicker("getDate") || false;
						var _strDate = '';
						if (data_field.substr(-9) == '_datetime') {
							strDate = _dat.format('dd/mm/yyyy HH:MM');
						} else {
							strDate = _dat.format('dd/mm/yyyy');
						}
						if ((strDate) && (_org_value) && (strDate == _org_value)) {
							//if ($(objSource).hasClass('data-edit-changed')) _blnIsUnchanged = true;
							_blnIsUnchanged = true;
						}
					} else {
						if ((_value) && (_org_value) && (_value == _org_value)) _blnIsUnchanged = true;
					}
					break;
			}
			if (_blnIsUnchanged) {
				if ($(objSource).hasClass('data-edit-changed')) {
					$(objSource).removeClass('data-edit-changed');
					_fnCheckDataChanged(); //run to check and reset state if no others data changed
				}
				return false;					
			}
			
			if (!(_str_index in _OBJ_CHANGED_DATA)) _OBJ_CHANGED_DATA[_str_index] = {
					"rowid": _rowid
				};
			_OBJ_CHANGED_DATA[_str_index][data_field] = _value;

			$(objSource).addClass('data-edit-changed');
			$('.DTTT_button_commit_page').removeClass('DTTT_button_disabled');
			return true;
		}
	}
	return false;
}

function _doCommitPage() {
//console.log('COMMIT');
	var _str = JSON.stringify(_OBJ_CHANGED_DATA);
//console.log(_str);
	if (( ! _OBJ_CHANGED_DATA) || (_str == '{}')) {
		alert(MSG_ALERT_COMMIT_NO_CHANGE);
		return false;
	}

	$("#dialog-modal").html("<p>" + MSG_DLG_HTML_COMMIT + "</p>");
	$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_COMMIT);
	$("#dialog-modal").dialog( "open" );
	$.ajax({
		type:"POST",
		url:"./approved_delivery/commit",
		contentType: "application/json;charset=utf-8",
		dataType:"json",
		data: _str,
		success: function(data, textStatus, jqXHR) {
			if (data.success == false) {
				alert(MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, data.error));
				$("#dialog-modal").dialog( "close" );
			} else {
				doSearch(false, false, function() {
					alert(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, ''));
					$("#dialog-modal").dialog( "close" );
				});
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
function customCommand(command, aData, tr, divEditDlg) {
	if ('delivery_rowid' in aData) {
		var _delivery_rowid = aData['delivery_rowid'] || '-1';
		if ((_delivery_rowid > 0) && (command.toLowerCase() == 'pdf')) {
			window.open("./delivery/get_pdf/" + _delivery_rowid);
		}
	}
}
