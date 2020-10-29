$(function () {
	if ($('.cls-div-form-edit-dialog').hasClass('ui-dialog-content')) $('.cls-div-form-edit-dialog').dialog("destroy");
	$(".cls-div-form-edit-dialog").dialog({
		height:800,
		width:'98%',
		show: {effect:"puff",duration:1000},
		hide: {effect:"fade",duration:1000},
		resizable:true,
		modal:true,
		closeOnEscape:false,
		autoOpen:false,
		open: function () {
			var _frm = $(this).find(".cls-frm-edit").get(0);
			if (typeof _doCommitUserControlsChanged == 'function') _doCommitUserControlsChanged(_frm);
		}
	});

	var _fncTemplate_populateSublist = populateSublist;
	populateSublist = function (blnEditable, mapColumns, mapValues) {
		if (mapColumns && mapValues) _masterLink[mapColumns] = mapValues;
		$(".cls-div-sub-list").each(function() {
			var _controller = $(this).attr('controller');
			var _datastring = $(this).attr('main-search');
			var _index = $(this).attr('index');
			var _divEditDlg = $('.cls-div-form-edit-dialog[index=' + _index + ']').get(0);
			var _this = this;
			doClearDisplayInfo(_index);
			$.ajax({
				type: "POST", 
				url: "./" + _controller + "/json_sub_job_list_search",
				dataType: "json",
				data: _datastring,
				success: function(data, textStatus, jqXHR) {
					if (data.success == false) {
						doDisplayInfo(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error), 'Search');
					} else {
						data = data.data;
						var _arrayData = [];
						if (data.length > 0) {
							for (var i = 0; i < data.length; i++) {
								_arrayData[i] = {'client_temp_id':i};
								for (j=0;j<_Sublist_arrDtColumns[_index].length;j++) {
									_arrayData[i][_Sublist_arrDtColumns[_index][j][0]] = (data[i][_Sublist_arrDtColumns[_index][j][0]] == null)?'':data[i][_Sublist_arrDtColumns[_index][j][0]];
								}
							}
						}/*else {
							doDisplayInfo(MSG_ALERT_QUERY_NO_DATA_FOUND, 'Info', _index);
						}*/
						//_arrayQueriedData = JSON.parse(JSON.stringify(_arrayData));
						if (_arrSublistDataTable.length > 0) {
							if (_index in _arrSublistDataTable) if ($.isFunction(_arrSublistDataTable[_index].fnDestroy)) _arrSublistDataTable[_index].fnDestroy(true);
						}
						$( _this ).html('<table id="tblSubList' + _index + '" class="cls-tbl-list"></table>');
						var _arrColumns = _Sublist_aoColumns[_index].slice(0);
						var _arrButtons = [
							{
								"sExtends": "copy",
								"sButtonText": "คัดลอก",
								"bShowAll": true,
								"bHeader": true,
								"bFooter": false,
								"mColumns": "visible",
								"fnCellRender": function ( sValue, iColumn, nTr, iDataIndex ) {
									if (sValue.length > 4) if (sValue.substr(0, 4) == "<img") return '';
									return sValue;
								}
							}
							, {
								"sExtends": "print",
								"sButtonText": "พิมพ์",
								"bShowAll": true,
								"bHeader": true,
								"bFooter": false,
								"mColumns": "visible",
								"fnClick": function (nButton, oConfig, oFlash) {
									_blnLeft = false;
									if ($('#left_panel').css('display') !== 'none') {
										_blnLeft = true;
										doToggleLeftPanel();
									}
									_visibleButtonColumns(false);
									$(window).keyup(function() {
										_visibleButtonColumns(true);
										if (_blnLeft) doToggleLeftPanel();
									});
									this.fnPrint( true, oConfig );
								}
							}
							, {
								"sExtends": "text",
								"sButtonText": "Excel",
								"sButtonClass": "DTTT_button_xls",
								"bShowAll": true,
								"bHeader": true,
								"bFooter": false,
								"mColumns": "visible",
								"fnClick": function ( nButton, oConfig, oFlash ) {
									doExportExcel( nButton, oConfig, oFlash );
								}
							}
						];
/*
						if (blnEditable) {
							_arrButtons.push({
											"sExtends": "text"
											, "sButtonText": ""
											, "sButtonClass": "DTTT_button_space"
											
										});
							_arrButtons.push({
											"sExtends": "text",
											"sButtonText": "เพิ่ม",
											"sButtonClass": "DTTT_button_add_row",
											"fnClick": function ( nButton, oConfig, oFlash ) {
												doInsert(_divEditDlg);
											}
										});
						} else {
							_arrColumns.pop();
							_arrColumns.pop();
						}
*/
						_arrSublistDataTable[_index] = $('#tblSubList' + _index).dataTable(
							{
								"bJQueryUI": true,
								"sPaginationType": "full_numbers",
								"bDeferRender": true,
								"bAutoWidth": false,
								"aaData": _arrayData,
								"aaSorting":[],
								"sScrollY": "50%",
								"sScrollX": "95%",
								"sScrollXInner": "100%",
								"bScrollCollapse": true,
								"aLengthMenu": [[5, 15, 30, -1], [5, 15, 30, "all"]],
								"iDisplayLength": 15,
								"bStateSave": true,
								"aoColumns": _arrColumns,
								"sDom": "<'row-fluid'<'span6'T><'span6'lf>r>t<'row-fluid'<'span6'i><'span6'p>><'clear'><'span6'T>",
								"oTableTools": {
								"aButtons": 
									_arrButtons,
									"sSwfPath": "public/js/jquery/dataTable/TableTools/2.1.5/swf/copy_csv_xls_pdf.swf"
								}
							}
						);
						oSettings = _arrSublistDataTable[_index].fnSettings();
						oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
						_arrSublistDataTable[_index].fnDraw();
					}
					$(_this).trigger("subTableLoaded", [_index, _arrSublistDataTable[_index], _masterLink]);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					doDisplayInfo(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, textStatus + ' ( ' + errorThrown + ' )'), "ErrorMessage", _index);
				},
				statusCode: {
					404: function() {
						doDisplayInfo("Page not found", "ErrorMessage", _index);
					}
				}
			});
		});
	}
});

function fnc_cmdJobListRow(main, type, rowid, jobnumber, customer_rowid) {
	var _main = main || 0;
	var _type = type || 0;
	var _rowid = rowid || 0;
	var _jobnumber = jobnumber || '';
	var _customer_rowid = customer_rowid || 0;
	var _url = '';
	switch (_type) {
		case 1:
		case '1':
			_url = 'order_polo/pass_command/'
			break;
		case 2:
		case '2':
			_url = 'order_tshirt/pass_command/'
			break;
		case 3:
		case '3':
			_url = 'order_premade_polo/pass_command/'
			break;
		case 4:
		case '4':
			_url = 'order_premade_tshirt/pass_command/'
			break;
	}
	if (_url != '') {
		switch (_main) {
			case 'view':
				_url = _url + '1';
				break;
			case 'edit':
				_url = _url + '2';
				break;
			case 'clone':
				_url = _url + '3';
				break;
			default:
				_url = _url + _main;
				break;
		}
		if (_rowid > 0) _url = _url + '/' + _rowid;
		if (_jobnumber != '') _url = _url + '/' + _jobnumber;
		if (_customer_rowid > 0) _url = _url + '/' + _customer_rowid;
		if ((_main != '') && (_url != '')) {
			window.location.href = _url;
		}
	}
}