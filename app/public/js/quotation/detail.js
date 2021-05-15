$(function() {
	_evntCheckBeforeChangeTab = function(currTab) {
		if ($('#hdn-customer_rowid', currTab).length > 0) doClearVldrErrorElement($('#hdn-customer_rowid', currTab));
		if (! blnValidateContainer(false, currTab, '.user-input')) {
			$('.cls-frm-edit .ui-tabs-panel:visible .input-invalid').each(function() {
				var _elem = $(this);
				if (_elem.length > 0) {
					var _msg = _elem.attr('invalid-msg') || 'Invalid';
					if (_elem.attr('id') == 'hdn-customer_rowid') {
						doClearVldrErrorElement(_elem);
						doSetVldrError($('#aac-customer'), 'customer_rowid', _msg, 'ข้อมูลลูกค้า: ' + _msg);
					}
				}
			});
			return false;
		} else {
			return true;
		}
	};
	
	var _chldCount = $("#tabs ul").find("a").length;
	var _currIndex = 0;
	// ++ Inferno:: ** prevent error neverending reload loop
	$("div.cls-tab-container ul").find("a").each(function() {
		var href = $( this ).attr( "href" );
		if ( href.indexOf( "#" ) == 0 ) {
			var newHref = window.location.protocol + '//' + window.location.hostname + window.location.pathname + href;
			$(this).attr("href", newHref);
			/*
			var _divEa = $(href);
			if (_divEa.length > 0) {
				var _divDialog = $($(this).parents(".cls-div-form-edit-dialog").get(0));
				if (_divDialog.length <= 0) return false;
				
				var _bSet = _divEa.find('[class^="cls-btn-form-"]:not(.hidden)');
				var _buttons = [];
				
				if (_currIndex > 0) {
					if (_bSet.length > 0) {
						$(_bSet[0]).before($('<input type="button" class="btn-prev ui-icon-circle-triangle-w ui-button ui-widget ui-state-default ui-corner-all" value="ก่อนหน้า" tab_index="' + _currIndex + '"/>').on('click', function() {
							if (_evntCheckBeforeChangeTab(this)) {
								var _curr = parseInt($(this).attr('tab_index') || 0);
								$("#tabs").tabs("option", "active", (_curr - 1));
							}
							return false;
						}));
						$(_bSet[0]).before($('<span> << </span>'));
					}
				}
				if (_currIndex < (_chldCount - 1)) {
					if (_bSet.length > 0) {
						$(_bSet[(_bSet.length - 1)]).after($('<input type="button" class="btn-next ui-icon-circle-triangle-e ui-button ui-widget ui-state-default ui-corner-all" value="ถัดไป" tab_index="' + _currIndex + '"/>').on('click', function() {
							if (_evntCheckBeforeChangeTab(this)) {
								var _curr = parseInt($(this).attr('tab_index') || 0);
								$("#tabs").tabs("option", "active", (_curr + 1));
							}
							return false;
						}));
						$(_bSet[(_bSet.length - 1)]).after($('<span> >> </span>'));
					}
					_divEa.find('[class^="cls-btn-form-reset"]:not(.hidden)').addClass('hidden').css('display', 'none');
				}
				if (_buttons.length > 0)  {
					_divDialog.dialog('option', 'buttons', _buttons);
				}	
				_currIndex += 1;
			}
			*/
		}
	});
	// -- Inferno:: ** prevent error neverending reload loop
    $("#divDetailTabs").tabs({
		beforeActivate: function( event, ui ) {
			if (! _evntCheckBeforeChangeTab(ui.oldPanel)) {
				event.preventDefault();
				event.stopPropagation();
				return false;
			}
		}
		, activate: function(event, ui) {
			$($.fn.dataTable.fnTables(true)).DataTable().columns.adjust(); //.responsive.recalc()
			if (ui.newPanel.attr("id") == 'divMain') {
				_doUpdateDetailValues();
				if (typeof _doUpdateTotalValue == 'function') _doUpdateTotalValue(1);
				if (typeof __fncDPUpdateTotalDeposit == 'function') __fncDPUpdateTotalDeposit();
			}
		}
	})
	.show();

	$('body').on('click', '.submit-query-cqt', function(){
		// let aa = $('#div_order_size_panel table#cat_id_5').find('tbody tr td.cls-col-size-qty');
		var _jobNumber = $('.input-query-cqt input').val();
		if(!_jobNumber){alert('กรุณากรอกหมายเลขงาน / CQT');return;};

		if(_jobNumber.indexOf('CQT') >= 0) {
			$.ajax({
				type:"GET",
				url:`https://www.pmkcustomdesign.com/api/psom/sale_input?job_number=${_jobNumber}`,
				success: function(data, textStatus, jqXHR) {
					if(!data.error){
						let result = data.data;
						let _arrSize = result['size'];
	
						Object.keys(result).map( (key, index) => { 
							let _val = result[key];
							if(!_val || _val == '' || key == 'size' || key.indexOf('detail_remark') >= 0) return;
	
							if(key.indexOf('txa') < 0){
								$('#divPoloDetailPanel').find(`#hdn-${key}_disp`).siblings('select.user-input').find('option').filter(function() {
									return $(this).text().trim() == _val;
								}).prop('selected', true);
								let _valText = $(`#hdn-${key}_disp`).siblings('select.user-input').find('option:selected').text();
								if(_valText){
									$('#divPoloDetailPanel').find(`#hdn-${key}_disp`).val(_valText);
									$('#divPoloDetailPanel').find(`#hdn-${key}_disp`).siblings('span.ui-combobox').find('input').val(_valText);
								}
							}else{
								$('#divPoloDetailPanel').find(`textarea#${key}`).val(_val);
							}
						});
	
						_arrSize.map((item, index) => {
							let _tdSizePool = item.type_disp.indexOf('ผู้ชาย') >= 0 ?  $('#divPoloOthersPanel').find($('#div_order_size_panel table#cat_id_5').find('tbody tr td')[1]) : $('#divPoloOthersPanel').find($('#div_order_size_panel table#cat_id_5').find('tbody tr td')[30]);
							$(_tdSizePool).find(`table tbody tr td.cls-col-size-qty[size="${item.size}"] input`).val(item.amount).trigger('change')
							$(_tdSizePool).find(`table tbody tr td.cls-col-size-price[size="${item.size}"] input`).val(item.price).trigger('change')
	
						});
	
						_doUpdateTotalValue(1);
					}else{
						alert(data.msg);
						$('.input-query-cqt input').val('');
					}
				}
				, error: function(jqXHR, textStatus, errorThrown) {
					
				}, statusCode: {
					404: function() {
						// doDisplayInfo("Page not found", "ErrorMessage", _index);
						// if (typeof opt_fncCallback == 'function') opt_fncCallback.apply(this, arguments);
						// $("#dialog-modal").dialog( "close" );
					}
				}
			});
		}else{
			let obj = { job_number: _jobNumber};
			let _str = JSON.stringify(obj);
			$.ajax({
				type:"POST",
				url:"./quotation_detail/clone",
				contentType:"application/json;charset=utf-8",
				dataType:"json",
				data:_str,
				success: function(data, textStatus, jqXHR) {
					$('#divPoloDetailPanel').find("input, select, span").val('');
					if(!data.error){
						let result = data.data;
						Object.keys(result).map( (key, index) => { 
							
							let _val = result[key];
							console.log(key)
							console.log(key.indexOf('detail_remark'))
							if(!_val || _val == '' || key == 'size_category' || key.indexOf('detail_remark') >= 0) return;
							let _ele = $('#divPoloDetailPanel').find("*[id*='"+key+"']");
							let _eleTag = _ele.prop("tagName").toLowerCase()
							let _eleTagType = _ele.attr('type');
							// console.log(_eleTag)
							// console.log(_eleTagType)
							
							if(_eleTag == 'select'){ 
								_ele.val(_val);
								let _text = _ele.find('option:selected').text();
								if(_text){
									_ele.siblings('input[id*="hdn-"]').val(_text)
									_ele.siblings('span.ui-combobox').find('input').val(_text);
								}
							}else if (_eleTag == 'textarea'){
								_ele.length > 1 ? $(_ele[0]).val(_val) : _ele.val(_val);
							}else if(_eleTag == 'input' && _eleTagType == 'checkbox'){
								_ele.prop('checked', true);
							};
						});
					}else{
						alert(data.msg);
					}
				}
				, error: function(jqXHR, textStatus, errorThrown) {
					// doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage", _index);
					// $("#dialog-modal").dialog( "close" );
				}, statusCode: {
					404: function() {
						doDisplayInfo("Page not found", "ErrorMessage", _index);
						$("#dialog-modal").dialog( "close" );
					}
				}
			});
		}
	});
	
	$('#sel-title_rowid').combobox({
		select: function() {
			var _sel = $('option:selected', this);
			if (_sel.length <= 0) return false;

			var _title_rowid = _sel.val();
			var _prnt = _sel.parents('div#divDetailTabs')[0];
			var _order_type_id = _sel.attr('order_type_id') || -1;
			//$('#hdn-title').val(_sel.text());
			
			$('#tabMnuDetail', _prnt).addClass('hidden');
			$('#tabMnuOthers', _prnt).addClass('hidden');
			$('.cls-detail-panel', _prnt).each(function() { $(this).addClass('hidden'); });
			$('.cls-others-panel', _prnt).each(function() { $(this).addClass('hidden'); });
			$('#txt-qty', _prnt).val('').attr('readonly', 'readonly');
			$('#txt-amount', _prnt).val('').attr('readonly', 'readonly');
			
			if(_order_type_id == 1){
				$('tr.query-cqt').remove();
				var _topPoloDetailPanel = $('#divPoloDetailPanel').find('#ord_dtl_container').find('tbody');
				var _trQueryCQT = `
					<tr class="query-cqt">
						<td colspan="3" class="td-align-center">
							<div class="frm-edit-row-group">
								<span class="group-title">อ้างอิงข้อมูลจากหมายเลขงาน / CQT</span>
								<div class="input-query-cqt" style="display:flex;padding-left:60px">
									<input type="text" name="cqt_job_number" autocomplete="off" style="width:30%;">
									<div class="submit-query-cqt" style="background-color: #FFF;padding: 2px 10px;margin: 0px 0px 0px 5px;cursor: pointer;">ค้นหา</div>
								</div>
							</div>
						</td>
					</tr>
				`
				$(_trQueryCQT).insertBefore(_topPoloDetailPanel);
			}

			_order_type_id = parseInt(_order_type_id)			
			switch (_order_type_id) {
				case 1:
				$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#tabMnuOthers', _prnt).removeClass('hidden');
					$('#divPoloDetailPanel', _prnt).removeClass('hidden');
					$('#divPoloOthersPanel', _prnt).removeClass('hidden');
					break;
				case 2:
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#tabMnuOthers', _prnt).removeClass('hidden');
					$('#divTshirtDetailPanel', _prnt).removeClass('hidden');
					$('#divTshirtOthersPanel', _prnt).removeClass('hidden');
					var _elmNeck = $('#sel-neck_type_rowid', '#divTshirtDetailPanel');
					$('option', _elmNeck).each(function() { $(this).removeAttr('disabled'); });
					if (_title_rowid == 3) {
						$('option', _elmNeck).each(function() { if ($(this).html().indexOf('คอกลม') < 0) $(this).attr('disabled', 'disabled'); });
						var _arrF = $('option', _elmNeck).filter(function() { if ($(this).html().indexOf('คอกลม') >= 0) return true; });
						if (_arrF.length > 0) {
							$(_arrF[0]).prop('selected', true);
						}
					} else if (_title_rowid == 5) {
						$('option', _elmNeck).each(function() { if ($(this).html().indexOf('คอวี') < 0) $(this).attr('disabled', 'disabled'); });
						var _arrF =  $('option', _elmNeck).filter(function() { if ($(this).html().indexOf('คอวี') >= 0) return true; });
						if (_arrF.length > 0) {
							$(_arrF[0]).prop('selected', true);
						}
					}
					break;
				case 3:
					$('#div_PO_remarks').css("display", "");
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#divPremadePoloDetailPanel', _prnt).removeClass('hidden');
					break;
				case 4:
					$('#div_PO_remarks').css("display", "");
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#divPremadeTshirtDetailPanel', _prnt).removeClass('hidden');
					break;
				case 5:
				case 6:
				case 9:
				case 10:
				case 11:
				case 15:
					if (_order_type_id == 5) {
						$('div.cls-pattern-code-header').html("รหัสหมวก");
					}
					var _product_type_id = parseInt(_order_type_id);
					var _divDtl = $('#divOtherDetailPanel', _prnt);
					var _divOth = $('#divOtherOthersPanel', _prnt)
					
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#tabMnuOthers', _prnt).removeClass('hidden');
					_divDtl.removeClass('hidden');
					_divOth.removeClass('hidden');
					setValue($("#sel-product_type_rowid", _divDtl), _product_type_id);
					$('option[product_type]', _divDtl).each(function() { $(this).attr('disabled', 'disabled'); });
					$('option[product_type="' + _product_type_id + '"]', _divDtl).each(function() { $(this).removeAttr('disabled'); });

					var _ctrlFabricType = $('#sel-fabric_type');
					var _ctrlPattern = $('#sel-pattern');
					var _ctrlDetail1 = $('#sel-detail1');
					var _ctrlDetail2 = $('#sel-detail2');
					var _elmTitleDetail = $('#txt-title_detail', _prnt);
					var _lblTitleDetail = _elmTitleDetail.parent().prev('div.table-title');
					$('#divOtherDetailPanel').find('.onfly-controller').remove();
					if (_order_type_id == 15) {
						_lblTitleDetail.css('visibility', '');
						_elmTitleDetail.val('').css('visibility', '');
						
						_ctrlFabricType.attr('disabled', 'disabled');
						_ctrlPattern.attr('disabled', 'disabled');
						_ctrlDetail1.attr('disabled', 'disabled');
						_ctrlDetail2.attr('disabled', 'disabled');
						_ctrlFabricType.next().hide();
						_ctrlPattern.next().hide();
						_ctrlDetail1.next().hide();
						_ctrlDetail2.next().hide();
						
						var _ofcFabricType = $('<input type="text" id="txt-fabric_type" class="user-input onfly-controller" data="fabric_type">').insertBefore(_ctrlFabricType);
						var _ofcPattern = $('<input type="text" id="txt-pattern" class="user-input onfly-controller" data="pattern">').insertBefore(_ctrlPattern);
						var _ofcDetail1 = $('<textarea id="txa-detail1" class="user-input onfly-controller" data="detail1" row="2">').insertBefore(_ctrlDetail1);
						var _ofcDetail2 = $('<textarea id="txa-detail2" class="user-input onfly-controller" data="detail2" row="2">').insertBefore(_ctrlDetail2);
						setTimeout(function() {
								var _dlgTitle = '';
								if ($('#divSublistFormEditDialog').length > 0) _dlgTitle = $('#divSublistFormEditDialog').dialog('option', 'title').substr(0, 5);
								if ($('#divFormEditDialog').length > 0) _dlgTitle = $('#divFormEditDialog').dialog('option', 'title').substr(0, 5);
								if (_dlgTitle == 'เรียก') {
									_ofcFabricType.attr('readonly', 'readonly');
									_ofcPattern.attr('readonly', 'readonly');
									_ofcDetail1.attr('readonly', 'readonly');
									_ofcDetail2.attr('readonly', 'readonly');
								}
							}
							, 200
						);
					} else {
						_lblTitleDetail.css('visibility', 'hidden');
						_elmTitleDetail.val('').css('visibility', 'hidden');

						_ctrlFabricType.removeAttr('disabled');
						_ctrlPattern.removeAttr('disabled');
						_ctrlDetail1.removeAttr('disabled');
						_ctrlDetail2.removeAttr('disabled');
						_ctrlFabricType.next().show();
						_ctrlPattern.next().show();
						_ctrlDetail1.next().show();
						_ctrlDetail2.next().show();
					}
					
					
					break;
				case 7:
					$('#div_PO_remarks').css("display", "");
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#divPremadeCapDetailPanel', _prnt).removeClass('hidden');
					break;
				case 8:
					$('#div_PO_remarks').css("display", "");
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					$('#divPremadeJacketDetailPanel', _prnt).removeClass('hidden');
					break;
				case 12:
				case 14:
					var _product_type_id = parseInt(_order_type_id);
					var _divDtl = $('#divPremadeOtherDetailPanel', _prnt);
					
					$('#div_PO_remarks').css("display", "");
					$('#tabMnuDetail', _prnt).removeClass('hidden');
					_divDtl.removeClass('hidden');
					if (parseInt(_order_type_id) == 12) {
						$('div.cls-pattern-code-header').html("รหัสผ้ากันเปื้อน");
					} else {
						$('div.cls-pattern-code-header').html("รหัสกระเป๋า");
					}
					setValue($("#sel-product_type_rowid", _divDtl), _product_type_id);
					$('option[product_type]', _divDtl).each(function() { $(this).attr('disabled', 'disabled'); });
					$('option[product_type="' + _product_type_id + '"]', _divDtl).each(function() { $(this).removeAttr('disabled'); });
					break;
				default:
					$('#txt-qty', _prnt).removeAttr('readonly');
					$('#txt-amount', _prnt).removeAttr('readonly');
					break;
			}

			//++ ONLY VISIBLE PANEL SET
			//++ set size cat and hide another table (case no value)
			var _divSQ = $('div#ord_size_quan_container').filter(__fnc_filterNotNestedHiddenClass);
			var _sel = $('#sel-size_category', _divSQ);
			_opt = $('option', _sel);
			if (_opt.length > 0) {
				setValue(_sel, _opt[0].value);
				_sel.trigger('change');
			}			
			//-- set size cat and hide another table (case no value)
			
			//enable others price controls
			var _divOP = $('div#ord_others_price_container').filter(__fnc_filterNotNestedHiddenClass);
			$('#tbl_op_list tbody tr td img', _divOP).css('visibility', '');
			$('#tbl_op_list #op_edit_panel', _divOP).css('display', '');

			//enable screen control 
			var _divSC = $('div#ord_scrn_container').filter(__fnc_filterNotNestedHiddenClass);
			$('#tbl_sc_list tbody tr td img', _divSC).css('visibility', '');
			$('#tbl_sc_list #sc_edit_panel', _divSC).css('display', '');	
		}
	});
	
	$('#txt-po_order_date, #txt-po_due_date, #txt-po_deliver_date').datepicker({ //
			showOn: "both",
			buttonImage: "public/images/select_day.png",
			buttonImageOnly: true,
			dateFormat: 'dd/mm/yy'
		});
	$('#txt-po_order_date').on('change', function() {
		doClearVldrErrorElement($(this));
		$("#txt-po_due_date").datepicker('option', 'minDate', null);
		$("#txt-po_deliver_date").datepicker('option', 'minDate', null);

		var _ord_date = $('#txt-po_order_date').datepicker('getDate');
		var _due_date = $('#txt-po_due_date').datepicker('getDate');
		var _dlv_date = $('#txt-po_deliver_date').datepicker('getDate');
		
		if ((_ord_date !== null) && (_ord_date instanceof Date)) {
			if ((_due_date !== null) && (_due_date instanceof Date)) {
				if ((_ord_date > _due_date)) {
					doSetVldrError($(this), '', 'InvalidOrderDate', 'Order_Date must lesser than Due_Date', 3);
					return false
				}
			}
			if ((_dlv_date !== null) && (_dlv_date instanceof Date)) {
				if ((_ord_date > _due_date)) {
					doSetVldrError($(this), '', 'InvalidOrderDate', 'Order_Date must lesser than Deliver_Date', 3);
					return false
				}
			}
			$("#txt-po_due_date").datepicker('option', 'minDate', _ord_date);
			$("#txt-po_deliver_date").datepicker('option', 'minDate', _ord_date);		
		} else {
			$(this).datepicker('setDate', null);
		}
	});

	$('#txt-po_due_date').on('change', function() {
		doClearVldrErrorElement($(this));
		$("#txt-po_order_date").datepicker('option', 'maxDate', null);
		$("#txt-po_deliver_date").datepicker('option', 'minDate', null);

		var _ord_date = $('#txt-po_order_date').datepicker('getDate');
		var _due_date = $('#txt-po_due_date').datepicker('getDate');
		var _dlv_date = $('#txt-po_deliver_date').datepicker('getDate');
		if ((_due_date !== null) && (_due_date instanceof Date)) {
			if ((_ord_date !== null) && (_ord_date instanceof Date)) {
				if ((_ord_date > _due_date)) {
					doSetVldrError($(this), '', 'InvalidDueDate', 'Due_Date must greater than Order_Date', 3);
					return false
				}
			}
			if ((_dlv_date !== null) && (_dlv_date instanceof Date)) {
				if ((_due_date > _dlv_date)) {
					doSetVldrError($(this), '', 'InvalidDueDate', 'Due_Date must lesser than Deliver_Date', 3);
					return false
				}
			}
			$("#txt-po_order_date").datepicker('option', 'maxDate', _due_date);
			$("#txt-po_deliver_date").datepicker('option', 'minDate', _due_date);
		} else {
			$(this).datepicker('setDate', null);
		}
	});
	
	$('#txt-po_deliver_date').on('change', function() {
		doClearVldrErrorElement($(this));
		$("#txt-po_order_date").datepicker('option', 'maxDate', null);
		$("#txt-po_due_date").datepicker('option', 'maxDate', null);

		var _ord_date = $('#txt-po_order_date').datepicker('getDate');
		var _due_date = $('#txt-po_due_date').datepicker('getDate');
		var _dlv_date = $('#txt-po_deliver_date').datepicker('getDate');		
		if ((_dlv_date !== null) && (_dlv_date instanceof Date)) {
			if ((_ord_date !== null) && (_ord_date instanceof Date)) {
				if ((_ord_date > _dlv_date)) {
					doSetVldrError($(this), '', 'InvalidDeliverDate', 'Deliver_Date must greater than Order_Date', 3);
					return false
				}
			}
			if ((_due_date !== null) && (_due_date instanceof Date)) {
				if ((_due_date > _dlv_date)) {
					doSetVldrError($(this), '', 'InvalidDeliverDate', 'Deliver_Date must greater than Due_Date', 3);
					return false
				}
			}
			$("#txt-po_order_date").datepicker('option', 'maxDate', _dlv_date);
			$("#txt-po_due_date").datepicker('option', 'maxDate', _dlv_date);
		} else {
			$(this).datepicker('setDate', null);
		}
	});
});

function _doUpdateDetailValues() {
	var _prnt = $('.cls-frm-edit[index=1]');
	var _elmQty = $('#txt-qty', _prnt);
	var _elmAmount = $('#txt-amount', _prnt);
	
	if ((_elmQty.attr('readonly') == 'readonly') && (_elmAmount.attr('readonly') == 'readonly')) {
		var _intQty = 0;
		var _dblAmount = 0;
		var _div = $('div#ord_size_quan_container').filter(__fnc_filterNotNestedHiddenClass);
		if (_div.length > 0) {
			_intQty = _cleanNumericValue($('div.total-value', _div[0]).html());
			var _prc = $('div.total-price', _div[0]).html() || ' ';
			if ((_prc.trim().length > 1) && (_prc.trim() != '--')) {
				_prc = _cleanNumericValue(_prc.substr(1));
				if (! isNaN(_prc)) _dblAmount = parseFloat(_prc);
			}		
		}
		
		_div = $('div#div_premade_detail_panel').filter(__fnc_filterNotNestedHiddenClass);
		if (_div.length > 0) {
			//var _tblHead = $(_div).find('table#tbl_detail_list');
			var _tblSize = $(_div).find('table#tbl_detail_size');
			$('tbody tr', _tblSize).each(function() {
				var _qty = _cleanNumericValue($('div.cls-sub-total', this).html() || 0);
				var _rowAmount = _cleanNumericValue($('div.cls-row-sum-amount', this).html() || 0);

				if (_qty > 0) _intQty += _qty;
				if (_rowAmount > 0) _dblAmount += _rowAmount;
			});
		}
		
		_div = $('div#ord_cap_quan_container').filter(__fnc_filterNotNestedHiddenClass);
		if (_div.length > 0) {
			_intQty = getValue($(_div).find('#txt-order_qty'), 0);
			_dblAmount = _cleanNumericValue($(_div).find('div.total-price').html() || '0');
		}

		var _dblOthers = 0;
		_tbl = $('table#tbl_op_list').filter(__fnc_filterNotNestedHiddenClass);
		if (_tbl.length > 0) {
			$('tbody tr', _tbl[0]).each(function() {
				_dblOthers += _cleanNumericValue($($('td', this)[1]).text());
			});
		}
		
		var _dblScrWea = 0;
		_tbl = $('table#tbl_sc_list').filter(__fnc_filterNotNestedHiddenClass);
		if (_tbl.length > 0) {
			$('tbody tr', _tbl[0]).each(function() {
				_dblScrWea += _cleanNumericValue($($('td', this)[5]).text());
			});
		}

		setValue(_elmQty, formatNumber(_intQty, 0));
		setValue(_elmAmount, formatNumber(_dblAmount + _dblOthers + (_dblScrWea * _intQty)));
	}
}