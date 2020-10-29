	var _datDateFrom;
	var _datDateTo;
	var _datToday;
	var _blnDataChanged = false;
	var _objDataTable;
	//var _arrayData;
	var _arrayQueriedData;
	var _currentDataString; //use in re-query after change data

	$(function() {
		_datToday = new Date();
		_datToday = new Date(_datToday.getFullYear(), _datToday.getMonth(), _datToday.getDate());
	
		$(".clsComboBox").combobox();
		$("#txtDateFrom").datepicker({
			showOn: "both",
			buttonImage: "public/images/select_day.png",
			buttonText: "เลือกวันที่เริ่มต้น",
			buttonImageOnly: true,
			dateFormat: 'dd/mm/yy'//,
			/*monthNames: [''],
			monthNamesShort: [''],
			prevText: 'ก่อนหน้า',
			nextText: 'ถัดไป',*/
			/*onClose: function (selectedDate, inst) {
				doManageDateAction(this, selectedDate);
			}*/
		});
		
		$("#txtDateTo").datepicker({
			showOn: "both",
			buttonImage: "public/images/select_day.png",
			buttonText: "เลือกวันที่สิ้นสุด",
			buttonImageOnly: true,
			dateFormat: 'dd/mm/yy'/*,
			onClose: function (selectedDate, inst) {
				doManageDateAction(this, selectedDate);
			}*/
		});
		$('.clsDatePicker').change(
			function () {
				doManageDateAction(this, $(this).datepicker("getDate"));
			}
		);
		$("#txtDateTo").datepicker("setDate", _datToday);
		$(".clsDatePicker").datepicker("option", "maxDate", _datToday);
			
		$("#btnSearch").button().click(function() {
			doSearch(true);
		});
		$("#btnReset").button();

		$("#dialog-modal").html("<p>" + MSG_DLG_HTML_QUERY + "</p>");
		$("#dialog-modal").dialog({
			height:100,
			width:400,
			resizable:false,
			modal:true,
			closeOnEscape:false,
			title: MSG_DLG_TITLE_QUERY,
			autoOpen:false
		});
		
		/* ++ for test */
		$("#txtDateFrom").datepicker("setDate", '13/12/2010');//for test
		$("#txtDateTo").datepicker("setDate", '25/12/2010');//_datToday
		$("#selSale").val('PI-NIT08');
		doSearch(true);
		/* -- for test */

	});

	function doManageDateAction(obj, selectedDate) {
		var dummyFrom = $("#txtDateFrom").datepicker("getDate");
		var dummyTo = $("#txtDateTo").datepicker("getDate");
		var blnEditFrom = false;
		var blnEditTo = false;
		if ($(obj).attr('id') == 'txtDateFrom') {
			blnEditFrom = true;
		} else {
			blnEditTo = true;
		}
		if (blnEditFrom && (dummyFrom === null)) {
			$("#txtDateTo").datepicker("option", "minDate", "");
			$("#txtDateTo").datepicker("option", "maxDate", _datToday);
			return;
		}
		if (blnEditTo && (dummyTo === null)) {
			$("#txtDateFrom").datepicker("option", "minDate", "");
			$("#txtDateFrom").datepicker("option", "maxDate", _datToday);
			return;
		}
		
		if (dummyFrom !== null) {
			dummyFrom = dummyFrom.getTime();
		} else {
			dummyFrom = 0;
		}
		if (dummyTo !== null) {
			dummyTo = dummyTo.getTime();
		} else {
			dummyTo = 0;
		}
		var blnOutRange = (Math.ceil((dummyTo - dummyFrom) / _intMilliSecPerDay) > _intMaxQueryDayRange);
		//alert('from = ' + blnEditFrom + ', to = ' + blnEditTo + ', outrange = ' + blnOutRange);
		if (blnEditFrom) {
			if (blnOutRange && (dummyTo > 0)) {
				alert(MSG_ALERT_OVER_DATE_RANGE.replace(/v_XX_1/g, _intMaxQueryDayRange));
				/* //For adjust self if out range 
				selectedDate = new Date(dummyTo - (_intMaxQueryDayRange * _intMilliSecPerDay));
				$(obj).datepicker("setDate", selectedDate);
				*/
				/* //For remove self if out range
				$(obj).datepicker("setDate", "");
				selectedDate = "";
				*/
				//For adjust other if out range 
				dummyTo = new Date(selectedDate.getTime() + (_intMaxQueryDayRange * _intMilliSecPerDay));
				$("#txtDateTo").datepicker("setDate", dummyTo);
				selectedDate = dummyTo;
			}
			$("#txtDateTo").datepicker("option", "minDate", selectedDate);
			if (typeof selectedDate.getTime === 'function') $("#txtDateTo").datepicker("option", "maxDate", new Date(selectedDate.getTime() + (_intMaxQueryDayRange * _intMilliSecPerDay)));
		} else if (blnEditTo) {
			if (blnOutRange && (dummyFrom > 0)) {
				alert(MSG_ALERT_OVER_DATE_RANGE.replace(/v_XX_1/g, _intMaxQueryDayRange));
				/* //For adjust self if out range 
				dummyTo = dummyFrom + (_intMaxQueryDayRange * _intMilliSecPerDay);
				if (dummyTo > (_datToday.getTime() * _intMilliSecPerDay)) {
					selectedDate = _datToday;
				} else {
					selectedDate = new Date(dummyTo);
				}
				*/
				/* //For remove self if out range
				$(obj).datepicker("setDate", "");
				selectedDate = _datToday;
				*/
				//For adjust other if out range 
				dummyFrom = new Date(selectedDate.getTime() - (_intMaxQueryDayRange * _intMilliSecPerDay));
				$("#txtDateFrom").datepicker("setDate", dummyFrom);
				selectedDate = dummyFrom;
			}
			$("#txtDateFrom").datepicker("option", "maxDate", selectedDate);
			if (typeof selectedDate.getTime === 'function') $("#txtDateFrom").datepicker("option", "minDate", new Date(selectedDate.getTime() - (_intMaxQueryDayRange * _intMilliSecPerDay)));
		}
	}
	
	function doSearch(blnChangeSearchCriteria) {
		var _blnChangeSearchCriteria = typeof blnChangeSearchCriteria != undefined?blnChangeSearchCriteria:true;

		doResetEditedData();

		if (_blnChangeSearchCriteria) {

			var dummyFrom = $("#txtDateFrom").datepicker("getDate");
			var dummyTo = $("#txtDateTo").datepicker("getDate");
			if (dummyFrom !== null) {
				dummyFrom = dummyFrom.getTime();
			} else {
				dummyFrom = 0;
			}
			if (dummyTo !== null) {
				dummyTo = dummyTo.getTime();
			} else {
				dummyTo = 0;
			}
			var blnOutRange = (Math.ceil((dummyTo - dummyFrom) / _intMilliSecPerDay) > _intMaxQueryDayRange);
			if (blnOutRange || (dummyFrom == 0) && (dummyTo == 0)) { //Out ranged or both date have no values
				alert(MSG_ALERT_OVER_DATE_RANGE.replace(/v_XX_1/g, _intMaxQueryDayRange));
				return false;
			}

			var accid = $("#selCustomer").val();
			var saleid = $("#selSale").val();
			var invoice_no = $("#txtInvoiceNumber").val();
			var date_from = $("#txtDateFrom").val();
			var date_to = $("#txtDateTo").val();
			$("#dialog-modal").dialog( "open" );
			_currentDataString = "accid=" +  accid + "&saleid=" + saleid + "&invoice_no=" + invoice_no + 
				"&date_from=" + date_from + "&date_to=" + date_to;
		}
		//alert(_currentDataString);return;
		if (_objDataTable) _objDataTable.fnDestroy(true);//_objDataTable.fnClearTable();//
		_objDataTable = false;
		$.ajax({
			type: "POST",  
			url: "./home/query_invoice",
			data: _currentDataString, 
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if (data.success == false) {
					alert(MSG_ALERT_QUERY_FAILED.replace(/v_XX_1/g, data.error));
				} else {
					data = data.data;
					_arrayData = [];
					if (data.length > 0) {
						for (var i = 0; i < data.length; i++) {
							_arrayData[i] = {
								'client_temp_id':i,
								'invoice_number':data[i].invoice_number,
								'date_doc':data[i].date_doc,
								'accid':data[i].accid,
								'customer':data[i].customer,
								'saleid':data[i].saleid,
								'salename':data[i].salename,
								'item_id':data[i].item_id,
								'item_name':data[i].item_name,
								'item_price':parseFloat(data[i].item_price),
								'item_quan':parseFloat(data[i].item_quan),
								'item_unit':data[i].item_unit,
								'item_disc':parseFloat(data[i].item_disc),
								'item_amount':parseFloat(data[i].item_amount),
								'item_cost':parseFloat(data[i].cost),
								'cost_amount':parseFloat(data[i].cost_amount),
								'date_sort':data[i].date_sort,
								'comm_type':parseFloat(data[i].comm_type),
								'disp_comm_type':data[i].disp_comm_type,
								'add_value_1':parseFloat(data[i].add_value_1),
								'add_value_2':parseFloat(data[i].add_value_2),
								'comm_amount':parseFloat(data[i].comm_amount),
								'comm_rowid':parseInt(data[i].comm_rowid),
								'master_rowid':parseInt(data[i].master_rowid),
								'flag':0,
								'edit_trans_array_indx':-1,
								//'sessid_reserved':data[i].sessid_reserved,
								/*'comm_amount':flCalculateCommission({
									'comm_type': data[i].comm_type, 'add_value_1': data[i].add_value_1,
									'item_amount': data[i].item_amount, 'item_quan': data[i].item_quan, 
									'cost_amount': data[i].cost_amount
								}),*/
								'time_stamp': undefined,
								'disp_time_stamp':parseFloat(data[i].disp_time_stamp)
							};
						}
					}
					_arrayQueriedData = JSON.parse(JSON.stringify(_arrayData));
					doPopulateTable(_arrayData, _blnChangeSearchCriteria);
		
					doCheckUpdateData();
					
					/*$('#tblSearchResult tbody td img').click( 
						function () {
							doImgDetailClick(this);
						}
					);*/

				}
				$("#dialog-modal").dialog( "close" );
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$("#dialog-modal").dialog( "close" );
				doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage");
			},
			statusCode: {
				404: function() {
					$("#dialog-modal").dialog( "close" );
					doDisplayInfo("Page not found", "ErrorMessage");
				}
			}
		});  
		return false;
	}
	
	function doCheckUpdateData() {
		//console.info($('#tblSearchResult').dataTable().fnGetData());
		if (_arrEditedMaster.length > 0) {
			performDataChangedEvent(true);
			return;
		}
		
		var _arrDiff = arrGetUpdatedValue(_arrayQueriedData, $('#tblSearchResult').dataTable().fnGetData());
		if (_arrDiff.hasOwnProperty("error")) {
			alert(_arrDiff.error);
			return;
		}
		performDataChangedEvent(_arrDiff.length > 0);	
	}
	
	function doPopulateTable(arrData, blnChangeSearchCriteria) {
		var _blnChangeSearchCriteria = typeof blnChangeSearchCriteria != undefined?blnChangeSearchCriteria:true;
		if (_objDataTable) _objDataTable.fnDestroy(true);//_objDataTable.fnClearTable();//
		$('#divDisplayQueryResult').html('<table id="tblSearchResult"></table>');
		//$('#divDisplayQueryResult').html($('#divTemplateQueryResult').html());
		//$('#divDisplayQueryResult > table').attr('id', 'tblSearchResult');S
		_objDataTable = $('#tblSearchResult').dataTable(
			{
				//"bProcessing": true,
				//"sAjaxSource": arrData,
				"bJQueryUI": true,
				"sPaginationType": "full_numbers",
				"bDeferRender": true,
				"bAutoWidth": false,
				"aaData": arrData,
				"sScrollY": "85%",
				"sScrollX": "95%",
				"sScrollXInner": "100%",
				"aLengthMenu": [[20, 35, 50, -1], [20, 35, 50, "all"]],
				"iDisplayLength": 20,
				/*"aoColumnDefs": [
					{ "sClass": "right", "aTargets": [6, 7, 10, 11, 12, 14, 15] },
					{ "bSortable": false, "aTargets": [9] }
				],*/
				"bStateSave": true,
				"fnStateLoadParams": function (oSettings, oaData) {
					if (_blnChangeSearchCriteria) { //Destroy state saving if requery
						_blnChangeSearchCriteria = false;
						return false;
					}
				},
				"aoColumns": [
					{ "sTitle": '<div style="width:16px;"></div>', "mData": function() { return '<img class="tblButton" src="./public/images/details_open.png">';}, "bSortable": false},
					{ "sTitle": "เลขที่ invoice", "mData": "invoice_number" },
					{ "sTitle": "วันที่", "mData": "date_doc", "iDataSort": 13 },
					{ "sTitle": "ลูกค้า", "mData": "customer" },
					{ "sTitle": "เซลส์", "mData": "salename" },
					{ "sTitle": "รหัสสินค้า", "mData": "item_id" },
					//{ "sTitle": "ชื่อสินค้า", "mData": "item_name" },
					//{ "sTitle": "ราคา/หน่วย", "sClass": "right", "mData": function ( source, type, val ) { return _manageData(source, type, val, 'item_price', true) } },
					{ "sTitle": "จำนวน", "sClass": "right", "mData": function ( source, type, val ) { return _manageData(source, type, val, 'item_quan') } },
					{ "sTitle": "หน่วย", "sClass": "right", "mData": "item_unit" },
					//{ "sTitle": "ส่วนลด", "mData": "item_disc", "bVisible": false },
					{ "sTitle": "ยอดขาย", "sClass": "right", "mData": function ( source, type, val ) { return _manageData(source, type, val, 'item_amount', true) } },
					//{ "sTitle": "ต้นทุน/หน่วย",  "sClass": "right","mData": function ( source, type, val ) { return _manageData(source, type, val, 'cost', true) } },
					{ "sTitle": "รวมต้นทุน", "sClass": "right", "mData": function ( source, type, val ) { return _manageData(source, type, val, 'cost_amount', true) } },
					{ "sTitle": "สูตรคำนวณ", "mData": "disp_comm_type" },
					{ "sTitle": "ตัวแปร", "sClass": "right", "mData": function ( source, type, val ) { return _manageData(source, type, val, 'add_value_1', true) } },
					{ "sTitle": "Commission", "sClass": "right", "mData": function ( source, type, val ) { return _manageData(source, type, val, 'comm_amount', true) } }
				],
				"sDom": "<'row-fluid'<'span6'T><'span6'lf>r>t<'row-fluid'<'span6'i><'span6'p>><'clear'>T",
				"oTableTools": {
				"aButtons": [
						{
							"sExtends": "copy",
							"bShowAll": true,
							"mColumns": [1,2,3,4,5,6,7,8,9,10,11,12]
						}
						, {
							"sExtends": "print",
							"bShowAll": true,
							"mColumns": [1,2,3,4,5,6,7,8,9,10,11,12]
						}
						, {
							"sExtends": "text",
							"sButtonText": "Excel",
							"sButtonClass": "DTTT_button_xls",
							"fnClick": function ( nButton, oConfig, oFlash ) {
								doExportExcel();
							}
						}
						/*, {
							"sExtends": "collection",
							"sButtonText": "Export",
							"sButtonClass": "DTTT_collection",
							"aButtons": [
								{
									"sExtends": "xls",
									"bHeader": true,
									"sCharSet": "utf8",
									"mColumns": [1,2,3,4,5,6,7,8,9,10,11,12]
								}
								, {
									"sExtends": "pdf",
									"bHeader": true,
									"sCharSet": "utf8",
									"sPdfOrientation": "landscape",
									"mColumns": [1,2,3,4,5,6,7,8,9,10,11,12]
								}
							]
						}*/
						, {
							"sExtends": "div"
							, "sButtonText": ""
							, "sButtonClass": "DTTT_button_space"
							
						}
						, {
							"sExtends": "text",
							"sButtonText": "บันทึก",
							"sButtonClass": "DTTT_button_commit",
							"fnClick": function ( nButton, oConfig, oFlash ) {
								doCommit();
							}
						}
					],
					"sSwfPath": "public/js/jquery/dataTable/TableTools/2.1.5/swf/copy_csv_xls_pdf.swf"
				}
			}
		);
		_objDataTable.fnDraw();
	}

	function doExportExcel() {
		if (!_blnDataChanged) {
			var uri = 'data:application/vnd.ms-excel;base64,'
				, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
				, base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
				, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
			data = $('#tblSearchResult').dataTable().fnGetData();
			var ctx = {worksheet: name || 'export', table: strConvertDataToTableObject(data)}
			window.location.href = uri + base64(format(template, ctx));
		} else {
			alert(MSG_ALERT_EXPORT_DATA_CHANGED);
		}
	}
	
	function performDataChangedEvent(blnDataChanged) {
		_blnDataChanged = blnDataChanged;
		if (blnDataChanged) {
			/*
			$.each($("span:contains('Copy')").parent(), function () {
				$( this ).addClass('disabled');
			});
			$.each($("span:contains('Print')").parent(), function () {
				$( this ).addClass('disabled');
			});
			*/
			$.each($("span:contains('Excel')").parent(), function () {
				$( this ).addClass('disabled');
			});
			$.each($("span:contains('บันทึก')").parent(), function () {
				$( this ).removeClass('disabled');
			});
		} else {
			/*
			$.each($("span:contains('Copy')").parent(), function () {
				$( this ).removeClass('disabled');
			});
			$.each($("span:contains('Print')").parent(), function () {
				$( this ).removeClass('disabled');
			});
			*/
			$.each($("span:contains('Excel')").parent(), function () {
				$( this ).removeClass('disabled');
			});
			$.each($("span:contains('บันทึก')").parent(), function () {
				$( this ).addClass('disabled');
			});
		}
	}
	
	function doCommit() {
		var _arrDiff = arrGetUpdatedValue(_arrayQueriedData, $('#tblSearchResult').dataTable().fnGetData());
		if (_blnDataChanged) {
			//console.log(_arrEditedMaster);
			//console.log(_arrDiff);
			var _arrTrans = [];
			/*
					'remark': _arrDiff[i].remark,
					'create_by': _arrDiff[i].create_by,
					'update_date': _arrDiff[i].update_date,
					'update_by': _arrDiff[i].update_by,
					'sessid_reserved': _arrDiff[i].sessid_reserved,
					'reserved_time': _arrDiff[i].reserved_time,
			*/
			for (var i = 0;i < _arrDiff.length;i++) {
				_arrTrans.push({
					'invoice_number': _arrDiff[i].invoice_number,
					'invoice_date': strConvertInvoiceDate(_arrDiff[i].date_doc),
					'item_id': _arrDiff[i].item_id,
					'accid': _arrDiff[i].accid,
					'saleid': _arrDiff[i].saleid,
					'comm_type': _arrDiff[i].comm_type,
					'add_value_1': _arrDiff[i].add_value_1,
					'amount': _arrDiff[i].comm_amount,
					'create_date': _arrDiff[i].time_stamp,
					'time_stamp': _arrDiff[i].time_stamp
				});
			}
			
			$("#dialog-modal").html("<p>" + MSG_DLG_HTML_COMMIT + "</p>");
			$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_COMMIT);
			$("#dialog-modal").dialog( "open" );
			var _jsonObj = {'master': _arrEditedMaster,'transaction': _arrTrans};
			$.ajax({
				type:"POST",
				url:"./home/update_commission",
				dataType:"json",
				data: _jsonObj,
				success: function(data, textStatus, jqXHR) {
					if (data.success == false) {
						alert(MSG_ALERT_COMMIT_FAILED.replace(/v_XX_1/g, data.error));
					} else {
						alert(MSG_ALERT_COMMIT_SUCCESS.replace(/v_XX_1/g, data.message));
						doSearch(false);
					}
					$("#dialog-modal").dialog( "close" );
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$("#dialog-modal").dialog( "close" );
					doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage");
				},
				statusCode: {
					404: function() {
						$("#dialog-modal").dialog( "close" );
						doDisplayInfo("Page not found", "ErrorMessage");
					}
				}
			});
			$("#dialog-modal").html("<p>" + MSG_DLG_HTML_QUERY + "</p>");
			$("#dialog-modal").dialog('option', 'title', MSG_DLG_TITLE_QUERY);
		} else {
			alert(MSG_ALERT_COMMIT_NO_CHANGE);
		}
	}
	
	function doResetEditedData() {
		_currDataRow = false;
		_is_change_master = false;
		_arrEditedMaster = [];
		_arrEditedTransaction = [];
		//_formerPanel = false;
		_formerImg = false;
	}
	/* Formating function for row details 
	function fnFormatDetails ( oTable, nTr )
	{
		var aData = oTable.fnGetData( nTr );
	*/
	function fnFormatDetails (aData)
	{
		var sOut = $('#table_detailes_template').html();
		sOut = sOut.replace(/XX/g, aData['client_temp_id']);
		return sOut;
	}
	
	function _manageData(source, type, val, data_field, blnMoneySign) {
		if (blnMoneySign === undefined) blnMoneySign = false;
		if (type === 'set') {
			//alert(data_field + ': ' + source[data_field] + ", value: " + (blnMoneySign?"฿":'')+formatNumber(val));
			// Store the computed dislay and filter values for efficiency
			source[data_field + "_display"] = val==="" ? "" : (blnMoneySign?"฿":'')+formatNumber(val);
			source[data_field + "_filter"] = val==="" ? "" : (blnMoneySign?"฿":'')+formatNumber(val)+" "+val;
			//alert(data_field + ': ' + source[data_field] + ", value: " + (blnMoneySign?"฿":'')+formatNumber(val) + ":" + source[data_field + "_display"]);
		} else if (type === 'display') {
			return source[data_field + "_display"];
			//return source.price_display;
		} else if (type === 'filter') {
			return source[data_field + "_filter"];
			//return source.price_filter;
		}
		// 'sort', 'type' and undefined all just use the integer
		return source[data_field];
	}
	
	function doDisplayInfo(msg, title) {
		$("#div_info").html(msg);
		$("#div_info").animate({top: "0px"}, 1000 ).show(500).fadeIn(500).fadeOut(200).fadeIn(500).fadeOut(200).fadeIn(500).fadeOut(200);
	}
	