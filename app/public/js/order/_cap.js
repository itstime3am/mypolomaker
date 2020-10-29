if (typeof _CPDtl == 'undefined') {
	var _CPDtl = true;

	$( document ).ready(function() {
		var _itSWR = setInterval(function() {
			var _elemSWRCol = $('#sel-swr_color_rowid');
			if ((_elemSWRCol.length > 0) && (_elemSWRCol.data('ui-combobox') != undefined)) {
				var __fncFormerSWRChanged = _elemSWRCol.combobox("option", "changed") || false;
				_elemSWRCol.combobox("option", "changed", function(value, event, ui) {
					var _prntTbl = $(event.target).parents('#tbl_pattern_details')[0];
					var _elmSel = $('#sel-swr_color_rowid', _prntTbl);
					if (typeof __fncFormerSWRChanged == 'function') __fncFormerSWRChanged.apply(this, arguments);
					var _val = getValue(_elmSel, 0);
					var _elem = $('#chk-is_sandwich_rim', _prntTbl);
					if (_val > 0) {
						setValue(_elem, 1);
					} else {
						setValue(_elem, 0);
					}
				});
				clearInterval(_itSWR);
			}
		}, 5);	

		$('div#ord_dtl_container').on('change', '#txt-air_flow_holes_number', _fncCheckIsAirFlowSelected);

		var _itAFH = setInterval(function() {
			var _elemAfhCol = $('#sel-afh_color_rowid');
			if ((_elemAfhCol.length > 0) && (_elemAfhCol.data('ui-combobox') != undefined)) {
				var __fncFormerAfhChanged = _elemAfhCol.combobox("option", "changed") || false;
				_elemAfhCol.combobox("option", "changed", function(value, event, ui) {
					if (typeof __fncFormerAfhChanged == 'function') __fncFormerAfhChanged.apply(this, arguments);
					_fncCheckIsAirFlowSelected.apply(this, arguments);
				});
				clearInterval(_itAFH);
			}
		}, 5);
		//$('.cls-frm-edit').on('change', '#sel-afh_color_rowid', _fncCheckIsAirFlowSelected);
		
		$('div#ord_cap_quan_container').on('change', 'input.user-input', function() {
			var _prntDiv = $(this).parents('#ord_cap_quan_container')[0];
			if (! _prntDiv) return false;
			
			var _qty = $('#txt-order_qty', _prntDiv).val() || 0;
			var _price_ea = $('#txt-order_price_each', _prntDiv).val() || 0;
			
			$('div.total-price', _prntDiv).html(formatNumber(_qty * _price_ea));
		});
	});

	function _fncCheckIsAirFlowSelected() {
		var _prntTbl = $('table#tbl_pattern_details').filter(__fnc_filterNotNestedHiddenClass)[0];

		var _elem = $('#chk-is_air_flow', _prntTbl);
		var _holes = getValue($('#txt-air_flow_holes_number', _prntTbl)) || 0;
		var _colId = getValue($('#sel-afh_color_rowid', _prntTbl)) || 0;
		if ((! isNaN(_holes)) && ((_holes > 0) || (_colId > 0))) {
			setValue(_elem, 1);
		} else {
			setValue(_elem, 0);
		}
	}
}