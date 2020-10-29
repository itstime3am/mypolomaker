	var _currDataRow=false;
	var _arrEditedMaster=[];
	var _arrEditedTransaction=[];
	//var _formerPanel = false;
	var _formerImg = false;
	$(function() {
		$('#divPenelHandler').click(function() {
			doToggleLeftPanel();
		});
		
		$("#btnSubmitCommission").button().live('click', 
			function() {
				doSubmitCommission();
			}
		);
		
		$("#btnResetCommission").button().live('click', 
			function() {
				blnResetCurrentPanel();
			}
		);
		
		$('.clsTblDetailsCommission td [rel="user_input"]').live('change', 
			function() {
				if (blnValidateValue($(this), true)) if  (blnValidateForm(false)) objUpdateCommissionPanel();
			}
		);
		
		$('#tblSearchResult tbody td img').live('click', 
			function () {
				doImgDetailClick(this);
			}
		);

		/*
		$('#tblSearchResult tbody tr').live('click', 
			function () {
				var data = _objDataTable.fnGetData( this );
				alert(data);
			}
		);
		*/

		/*
		$('#tblSearchResult tbody td img').live('click', 
			function () {
				var nTr = $(this).parents('tr')[0];
				if (_objDataTable.fnIsOpen(nTr)) {
					this.src = "./public/images/details_open.png";
					_objDataTable.fnClose(nTr);
					_formerPanel = false;
					_formerthis = false;
				} else {
					this.src = "./public/images/details_close.png";
					var aData = _objDataTable.fnGetData(nTr);
					if (aData !== undefined) {
						//if (_currDataRow !== false) {
						//	//Disabled edit of former panel
						//	if (!blnResetCurrentPanel()) return;
						//	$('#tdComm_Type_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
						//	$('#tdAdd_Value_1_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
						//	$('#tdAdd_Value_2_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
						//	$('#tdResult_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
						//	$('#tdComm_Type_' + _currDataRow['client_temp_id']).html(_currDataRow['disp_comm_type']);
						//	$('#tdAdd_Value_1_' + _currDataRow['client_temp_id']).html(_currDataRow['add_value_1']);
						//	$('#tdAdd_Value_2_' + _currDataRow['client_temp_id']).html(_currDataRow['add_value_2']);
						//	$('#tdResult_' + _currDataRow['client_temp_id']).html("฿" + formatNumber(_currDataRow['comm_amount']));
						//	$('#tdButton_' + _currDataRow['client_temp_id']).html('');
						//	//Disabled edit of former panel
						//}
						if (_formerPanel !== false) {
							_formerImg.src = "./public/images/details_open.png";
							_objDataTable.fnClose(_formerPanel);
						}
						_formerImg = this;
						_formerPanel = nTr;
						_objDataTable.fnOpen(nTr, fnFormatDetails(aData), 'details');
						_currDataRow = aData;
						$('table #tblDetails_' + _currDataRow['client_temp_id'] + ' td[rel="details_value"]').each(
							function() {
								if (aData[$(this).attr('id')] !== undefined) { 
									var dispVal;
									switch ($(this).attr('type')) {
										case 'currency':
											dispVal = "฿" + formatNumber(aData[$(this).attr('id')]);
											break;
										default:
											dispVal = aData[$(this).attr('id')];
											break;
									}
									$(this).html(dispVal);
									$(this).attr('title', dispVal);
								}
							}
						);
						$('#selComm_Type_' + _currDataRow['client_temp_id'] + ' option[value=' + aData['comm_type'] + ']').attr("selected", true);
						$('#txtAdd_Value_1_' + _currDataRow['client_temp_id'] + '').val(formatNumber(aData['add_value_1']));
						$('#txtAdd_Value_2_' + _currDataRow['client_temp_id'] + '').val(formatNumber(aData['add_value_2']));
						$('#txtResult_' + _currDataRow['client_temp_id'] + '').val("฿" + formatNumber(aData['comm_amount']));
						
						//$('#hdnCommRowid_panel').val(parseInt(aData['comm_rowid']));
						//$('#hdnMasterRowid_panel').val(parseInt(aData['master_rowid']));
						//$('#hdnAccid_panel').val(aData['accid']);
						//$('#hdnSaleid_panel').val(aData['saleid']);
						//$('#hdnItemid_panel').val(aData['item_id']);

						$('#hdnComm_Type_' + _currDataRow['client_temp_id']).val(parseInt(aData['comm_type']));
						$('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['add_value_1']));
						$('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['add_value_2']));
						$('#txtResult_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['comm_amount']));

					}
				}
			}
		);
		*/
	});
	/*
	function doImgDetailClick_Old(img) {
		var nTr = $(img).parents('tr')[0];
		if (_objDataTable.fnIsOpen(nTr)) {
			// img row is already open - close it
			img.src = "./public/images/details_open.png";
			_objDataTable.fnClose(nTr);
			if (_formerImg == img) {
				_currDataRow = false;
			}
		} else {
			// Open img row
			var aData = _objDataTable.fnGetData(nTr);
			if (aData !== undefined) {
				if (_currDataRow !== false) {
					//Disabled edit of former panel
					if (!blnResetCurrentPanel()) return;
					$('#tdComm_Type_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
					$('#tdAdd_Value_1_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
					//$('#tdAdd_Value_2_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
					$('#tdResult_' + _currDataRow['client_temp_id']).attr('class', 'RedTextBoxRight');
					$('#tdComm_Type_' + _currDataRow['client_temp_id']).html($('#hdnDispComm_Type_' + _currDataRow['client_temp_id']).val());
					$('#tdAdd_Value_1_' + _currDataRow['client_temp_id']).html($('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).attr('title'));
					//$('#tdAdd_Value_2_' + _currDataRow['client_temp_id']).html($('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).attr('title'));
					$('#tdResult_' + _currDataRow['client_temp_id']).html("฿" + formatNumber($('#txtResult_' + _currDataRow['client_temp_id']).attr('title')));
					$('#tdButton_' + _currDataRow['client_temp_id']).html('');
					//Disabled edit of former panel
				}
				img.src = "./public/images/details_close.png";
				_formerImg = img;
				_objDataTable.fnOpen(nTr, fnFormatDetails(aData), 'details');
				_currDataRow = aData;
				$('table #tblDetails_' + _currDataRow['client_temp_id'] + ' td[rel="details_value"]').each(
					function() {
						if (aData[$(img).attr('id')] !== undefined) { 
							var dispVal;
							switch ($(img).attr('type')) {
								case 'currency':
									dispVal = "฿" + formatNumber(aData[$(img).attr('id')]);
									break;
								default:
									dispVal = aData[$(img).attr('id')];
									break;
							}
							$(img).html(dispVal);
							$(img).attr('title', dispVal);
						}
					}
				);
				$('#selComm_Type_' + _currDataRow['client_temp_id'] + ' option[value=' + aData['comm_type'] + ']').attr("selected", true);
				$('#txtAdd_Value_1_' + _currDataRow['client_temp_id'] + '').val(formatNumber(aData['add_value_1']));
				//$('#txtAdd_Value_2_' + _currDataRow['client_temp_id'] + '').val(formatNumber(aData['add_value_2']));
				$('#txtResult_' + _currDataRow['client_temp_id'] + '').val("฿" + formatNumber(aData['comm_amount']));
				
				//$('#hdnCommRowid_panel').val(parseInt(aData['comm_rowid']));
				//$('#hdnMasterRowid_panel').val(parseInt(aData['master_rowid']));
				//$('#hdnAccid_panel').val(aData['accid']);
				//$('#hdnSaleid_panel').val(aData['saleid']);
				//$('#hdnItemid_panel').val(aData['item_id']);

				$('#hdnComm_Type_' + _currDataRow['client_temp_id']).val(parseInt(aData['comm_type']));
				$('#hdnDispComm_Type_' + _currDataRow['client_temp_id']).val(aData['disp_comm_type']);
				$('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['add_value_1']));
				//$('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['add_value_2']));
				$('#txtResult_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['comm_amount']));

			}
		}
	}
	*/
	
	function doImgDetailClick(img) {
		var nTr = $(img).parents('tr')[0];
		if (_objDataTable.fnIsOpen(nTr)) {
			/* img row is already open - close it */
			img.src = "./public/images/details_open.png";
			_objDataTable.fnClose(nTr);
			//alert(_formerTr == nTr);
			if (_formerTr == nTr) {
				_currDataRow = false;
			}
		} else {
			/* Open img row */
			var aData = _objDataTable.fnGetData(nTr);
			if (aData !== undefined) {
				blnResetCurrentPanel(_currDataRow, 
					function(indx) {
						if (indx !== undefined) {
							$('#tdComm_Type_' + indx).html($('#hdnDispComm_Type_' + indx).val());
							$('#tdAdd_Value_1_' + indx).html($('#txtAdd_Value_1_' + indx).attr('title'));
							//$('#tdAdd_Value_2_' + indx).html($('#txtAdd_Value_2_' + indx).attr('title'));
							$('#tdResult_' + indx).html("฿" + formatNumber($('#hdnDispComm_Amount_' + indx).val()));
							$('#tdComm_Type_' + indx).attr('class', 'RedTextBoxRight');
							$('#tdAdd_Value_1_' + indx).attr('class', 'RedTextBoxRight');
							//$('#tdAdd_Value_2_' + indx).attr('class', 'RedTextBoxRight');
							$('#tdResult_' + indx).attr('class', 'RedTextBoxRight');
							$('#tdButton_' + indx).html('');
						}
						img.src = "./public/images/details_close.png";
						_formerTr = nTr;
						_objDataTable.fnOpen(nTr, fnFormatDetails(aData), 'details');
						_currDataRow = aData;
						$('table #tblDetails_' + _currDataRow['client_temp_id'] + ' td[rel="details_value"]').each(
							function() {
								if (aData[$(this).attr('id')] !== undefined) { 
									var dispVal;
									switch ($(this).attr('type')) {
										case 'currency':
											dispVal = "฿" + formatNumber(aData[$(this).attr('id')]);
											break;
										default:
											dispVal = aData[$(this).attr('id')];
											break;
									}
									$(this).html(dispVal);
									$(this).attr('title', dispVal);
								}
							}
						);
						$('#selComm_Type_' + _currDataRow['client_temp_id'] + ' option[value=' + aData['comm_type'] + ']').attr("selected", true);
						$('#txtAdd_Value_1_' + _currDataRow['client_temp_id'] + '').val(parseFloat(aData['add_value_1']));
						//$('#txtAdd_Value_2_' + _currDataRow['client_temp_id'] + '').val(parseFloat(aData['add_value_2']));
						$('#txtResult_' + _currDataRow['client_temp_id'] + '').val('฿' + formatNumber(parseFloat(aData['comm_amount'])));

						$('#hdnComm_Type_' + _currDataRow['client_temp_id']).val(parseInt(aData['comm_type']));
						$('#hdnDispComm_Type_' + _currDataRow['client_temp_id']).val(aData['disp_comm_type']);
						$('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['add_value_1']));
						//$('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['add_value_2']));
						$('#txtResult_' + _currDataRow['client_temp_id']).attr("title", parseFloat(aData['comm_amount']));
						$('#hdnComm_Amount_' + _currDataRow['client_temp_id']).val(parseInt(aData['comm_amount']));
					}
				);
				/*doLiveCheckUnsaveData(
					{
						'img': img,
						'objDataRow': _currDataRow,
						'fnNextCallback': 
					}
				);
				*/
			}
		}
	}
	
	function blnValidateValue(jqObj, blnAction) {
		_blnAction = blnAction && true;
		var _blnValid = true;
		var val = jqObj.val();
		//console.info(jqObj.attr('id') + ' value=' + jqObj.val());

		if (isNaN(val)) {
			if (_blnAction) alert(MSG_ALERT_INVALID_NUMBER_TYPE_INPUT);
			_blnValid = false;
		} else if ((val <= 0) || (val == '')) {
			if (_blnAction) alert(MSG_ALERT_INVALID_NO_VALUE_INPUT);
			_blnValid = false;
		}
		if (_blnAction && (!_blnValid)) {
			jqObj.val('');
			jqObj.focus();		
		}
		return _blnValid;
	}
	
	function blnValidateForm(blnAlert) {
		_blnAlert = blnAlert && true;
		var _arrInput = $('.clsTblDetailsCommission td [rel="user_input"]');
		for (var i=0;i<_arrInput.length;i++) {
			_jqObj = $(_arrInput[i]);
			_id = _jqObj.attr('id');
			if (_id !== undefined) {
				if (_id.indexOf('XX') < 0) {
					if (!blnValidateValue(_jqObj, _blnAlert)) {
						return false;
					}
				}
			}
		}
		return true;
	}
	
	function objUpdateCommissionPanel() {
		if (_currDataRow == false) return false;
		
		var comm_type = parseInt($('#selComm_Type_' + _currDataRow['client_temp_id']).val());
		var add_value_1 = parseFloat($('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).val());
		//var add_value_2 = parseFloat($('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).val());
		var comm_amount = parseFloat($('#hdnComm_Amount_' + _currDataRow['client_temp_id']).val());
		var old_comm_type = parseInt($('#hdnComm_Type_' + _currDataRow['client_temp_id']).val());
		var old_add_value_1 = parseFloat($('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).attr('title'));
		//var old_add_value_2 = parseFloat($('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).attr('title').trim());

		flResult = flCalculateCommission(
			{
				'comm_type': comm_type, 'add_value_1': add_value_1, /*'add_value_2': add_value_2,*/
				'item_amount': _currDataRow['item_amount'], 'item_quan': _currDataRow['item_quan'], 
				'cost_amount': _currDataRow['cost_amount']
			}
		);
		if (flResult != comm_amount) {
			$('#txtResult_' + _currDataRow['client_temp_id']).val("฿" + formatNumber(flResult));
			$('#txtResult_' + _currDataRow['client_temp_id']).attr('title', flResult);
		}
		_objToReturn = {'former': {'comm_type': old_comm_type, 'add_value_1': old_add_value_1, 'comm_amount': comm_amount}, 'current': {'comm_type': comm_type, 'add_value_1': add_value_1, 'comm_amount': flResult}};
		//, 'add_value_1': old_add_value_1  //, 'add_value_1': add_value_1
		if (blnIsDataRowChanged(_objToReturn.former, _objToReturn.current)) {
			return _objToReturn;
		} else {
			return false;
		}
	}
	
	function flCalculateCommission(arrObj) {
		if (arrObj !== false) {
			var intComm_Type = arrObj['comm_type'];
			var flAdd_Value_1 = arrObj['add_value_1'];
			//var flAdd_Value_2 = arrObj['add_value_2'];
			var flItem_Amount = arrObj['item_amount'];
			var flResult=0;
			switch (intComm_Type) {
				case 1: //เปอร์เซ็นต์ยอดขาย
					flResult = flItem_Amount * (flAdd_Value_1 / 100);
					break;
				case 2: //ต้นทุนต่อหน่วย
					var flItem_Quan = arrObj['item_quan'];
					flResult = flItem_Amount - (flAdd_Value_1 * flItem_Quan);
					break;
				case 3: //เปอร์เซ็นต์กำไร
					var flCost_Amount = arrObj['cost_amount'];
					flResult = (flItem_Amount - flCost_Amount) * (flAdd_Value_1 / 100)
					break;
				default:
					break;
			}
			return flResult;
		}
		return -1;
	}
	
	function doSubmitCommission() {
		if (blnValidateForm(true)) {
			_objDiff = objUpdateCommissionPanel();
			if (_objDiff !== false) {
				if (!_blnDataChanged) performDataChangedEvent(true);
				_curr = _objDiff.current;

				var _isUpdateMaster = false;
				if ((_objDiff.former.comm_type != _objDiff.current.comm_type) || (_objDiff.former.add_value_1 != _objDiff.current.add_value_1)) {
					_isUpdateMaster = true; //if master fields changed, default is insert into master table (both insert and update, if none existed always insert)
					if (_currDataRow['master_rowid'] > 0) { //Former master existed in database
						if (!confirm(MSG_CONFIRM_UPDATE_MASTER_TABLE)) { //User donot want to change master (use old master settings)
							_isUpdateMaster = false;
						}
					}
				}
				if (_isUpdateMaster) { //'rowid': _currDataRow['master_rowid'], , 'add_value_2': _curr.add_value_2
					_arrEditedMaster = arrUpdateMasterDataEditList(_arrEditedMaster, {
						'item_id': _currDataRow['item_id'], 'accid': _currDataRow['accid'], 'saleid': _currDataRow['saleid'], 'comm_type': _curr.comm_type, 'add_value_1': _curr.add_value_1, 'flag': 0, 'remark': 'User changed', 'create_date': new Date(), 'time_stamp': new Date()
					});
				}
				if (confirm(MSG_CONFIRM_UPDATE_COMM_FORMULA)) { //Change all data that matched by criteria
					var _arrData = _objDataTable.fnGetData();
					for (var i = 0; i < _arrData.length; i++) {
						var _rowDataSource = _arrData[i];
						if (((_rowDataSource['saleid'] == _currDataRow['saleid']) && (_rowDataSource['accid'] == _currDataRow['accid']) && (_rowDataSource['item_id'] == _currDataRow['item_id']))) {
							doUpdateDataRow(_rowDataSource, _curr);
						}
					}
				} else {
					/*
					_currDataRow['comm_type'] = _curr.comm_type;
					_currDataRow['disp_comm_type'] = arrDispCommType[_curr.comm_type];
					_currDataRow['add_value_1'] = _curr.add_value_1;
					//_currDataRow['add_value_2'] = _curr.add_value_2;
					_currDataRow['comm_amount'] = _curr.comm_amount;
					if (_currDataRow['edit_trans_array_indx'] == -1) { //unedit yet
						_currDataRow['edit_trans_array_indx'] = _arrEditedTransaction.length;
						_arrEditedTransaction[_arrEditedTransaction.length] = _currDataRow['client_temp_id'];
					}
					*/
					doUpdateDataRow(_currDataRow, _curr);
				}
			}
			if (_objDataTable.fnIsOpen(_objDataTable.fnGetNodes(_currDataRow['client_temp_id']))) {
				_objDataTable.fnClose(_objDataTable.fnGetNodes(_currDataRow['client_temp_id']));
				img = $('img', _objDataTable.fnGetNodes(_currDataRow['client_temp_id']))[0];
				if (img) img.src = "./public/images/details_open.png";
			}
			//_objDataTable.fnUpdate(_currDataRow, _currDataRow['client_temp_id']);
			_currDataRow = false;
			
			doClearDetailsPanel();
		}
	}
	
	function doUpdateDataRow(objDataRow, objDataToUpdate) {
		objDataRow['comm_type'] = objDataToUpdate['comm_type'];
		objDataRow['disp_comm_type'] = arrDispCommType[objDataToUpdate['comm_type']];
		objDataRow['add_value_1'] = objDataToUpdate['add_value_1'];
		//objDataRow['add_value_2'] = objDataToUpdate['add_value_2'];
		_val = flCalculateCommission(objDataRow);
		objDataRow['comm_amount'] = _val;
		if ('comm_amount_display' in objDataRow) {
			objDataRow['comm_amount_display'] = "฿"+formatNumber(_val);
			objDataRow['comm_amount_filter'] = "฿"+formatNumber(_val)+" "+_val;
		}
		objDataRow['flag'] = 1;
		objDataRow['time_stamp'] = new Date();
		if (objDataRow['edit_trans_array_indx'] == -1) { //unedit yet
			objDataRow['edit_trans_array_indx'] = _arrEditedTransaction.length;
			_arrEditedTransaction[_arrEditedTransaction.length] = objDataRow['client_temp_id'];
		}
		if (_objDataTable.fnIsOpen(_objDataTable.fnGetNodes(objDataRow['client_temp_id']))) {
			_objDataTable.fnClose(_objDataTable.fnGetNodes(objDataRow['client_temp_id']));
			img = $('img', _objDataTable.fnGetNodes(objDataRow['client_temp_id']))[0];
			if (img) img.src = "./public/images/details_open.png";
		}
		var _intDataRow = parseInt(objDataRow['client_temp_id']);
		_objDataTable.fnUpdate(arrDispCommType[objDataToUpdate['comm_type']], _intDataRow, 10);
		_objDataTable.fnUpdate(objDataToUpdate.add_value_1, _intDataRow, 11);
		_objDataTable.fnUpdate(_val, _intDataRow, 12);
	}
	
	function doClearDetailsPanel() {
		/*++ Clear panel */
		$('#selComm_Type_' + _currDataRow['client_temp_id'] + ' option[value="-1"]').attr("selected", true);
		$('#txtAdd_Value_1_' + _currDataRow['client_temp_id'] + '').val('');
		//$('#txtAdd_Value_2_' + _currDataRow['client_temp_id'] + '').val('');
		$('#txtResult_' + _currDataRow['client_temp_id'] + '').val('฿0.00');

		$('#hdnComm_Type_' + _currDataRow['client_temp_id']).val('-1');
		$('#hdnDispComm_Type_' + _currDataRow['client_temp_id']).val('');
		$('#txtAdd_Value_1_' + _currDataRow['client_temp_id']).attr("title", '');
		//$('#txtAdd_Value_2_' + _currDataRow['client_temp_id']).attr("title", '');
		$('#txtResult_' + _currDataRow['client_temp_id']).attr("title", '');
		$('#hdnComm_Amount_' + _currDataRow['client_temp_id']).val('');
		/*-- Clear panel */	
	}
	
	function blnResetCurrentPanel(objCurrDataRow, fnCallback) {
		if (objCurrDataRow === undefined) {
			objCurrDataRow = _currDataRow;
		}
		if (objCurrDataRow !== false) {
			var intDummyIndex = objCurrDataRow['client_temp_id'];
			var comm_type = parseInt($('#selComm_Type_' + intDummyIndex).val());
			var add_value_1 = parseFloat($('#txtAdd_Value_1_' + intDummyIndex).val());
			//var add_value_2 = parseFloat($('#txtAdd_Value_2_' + intDummyIndex).val());
			var old_comm_type = parseInt($('#hdnComm_Type_' + intDummyIndex).val());
			var old_add_value_1 = parseFloat($('#txtAdd_Value_1_' + intDummyIndex).attr('title'));
			//var old_add_value_2 = parseFloat($('#txtAdd_Value_2_' + intDummyIndex).attr('title'));
			var old_comm_amount = parseInt($('#hdnComm_Amount_' + intDummyIndex).val());
			
			//alert(old_comm_type + ' : ' + comm_type + ', ' + old_add_value_1 + ' : ' + add_value_1 + ', ' + old_add_value_2 + ' : ' + add_value_2);
			if ((old_comm_type != comm_type) || (old_add_value_1 != add_value_1)/* || (old_add_value_2 != add_value_2)*/) {
				if (confirm(MSG_CONFIRM_CANCEL_EDITED_PANEL)) {
					$('#selComm_Type_' + intDummyIndex + ' option[value=' + old_comm_type + ']').attr("selected", true);
					$('#txtAdd_Value_1_' + intDummyIndex).val(old_add_value_1);
					//$('#txtAdd_Value_2_' + intDummyIndex).val(old_add_value_2);
					$('#txtResult_' + intDummyIndex).val('฿' + formatNumber(old_comm_amount));
					
					objCurrDataRow['comm_type'] = old_comm_type;
					objCurrDataRow['add_value_1'] = old_add_value_1;
					//objCurrDataRow['add_value_2'] = old_add_value_2;
					objCurrDataRow['comm_amount'] = old_comm_amount;
					/*if ('comm_amount_display' in objCurrDataRow) {
						objCurrDataRow['comm_amount_display'] = "฿"+formatNumber(old_comm_amount);
						objCurrDataRow['comm_amount_filter'] = "฿"+formatNumber(old_comm_amount)+" "+old_comm_amount;
					}*/
					objCurrDataRow['flag'] = 0;
					
					if (fnCallback !== undefined) {
						fnCallback(intDummyIndex);
					}
				} else {
					return false;
				}
			} else {
				if (fnCallback !== undefined) {
					fnCallback(intDummyIndex);
				}
			}
		} else {
			if (fnCallback !== undefined) {
				fnCallback();
			}
		}
		return true;
	}
	
	function arrUpdateMasterDataEditList(arrList, objToUpdate) {
		if (arrList.length > 0) {
			for (i in arrList) {
				var _each = arrList[i];
				if ((_each.item_id == objToUpdate.item_id) && (_each.saleid == objToUpdate.saleid) && (_each.accid == objToUpdate.accid)) {
					arrList[i].comm_type = objToUpdate.comm_type;
					arrList[i].add_value_1 = objToUpdate.add_value_1;
					return arrList;
				}
			}
		}
		arrList.push(JSON.parse(JSON.stringify(objToUpdate)));
		return arrList;
	}
	
	_leftPanelHide = 0;
	_leftPanelShow = 290;
	function doToggleLeftPanel() {
		if ($('#left_panel').css('display') !== 'none') {
			$('#left_panel').css('display', 'none');
			$('#work_panel').css('margin-left', (_leftPanelHide+10) + 'px');
			$('#divPenelHandler').css('left', _leftPanelHide + 'px');
		} else {
			$('#left_panel').css('display', 'block');
			$('#work_panel').css('margin-left', (_leftPanelShow+10) + 'px');
			$('#divPenelHandler').css('left', _leftPanelShow + 'px');
		}
		_objDataTable.fnDraw();
	}