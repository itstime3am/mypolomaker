<?php
//var_dump($size_quan_matrix);exit;
	$_strOptions = '';
	$_strCatagoryTable = '';
	$_subCatRow1 = '';
	$_subCatRow2 = '';
	$_subCatRow3 = '';
	$_subCatRow4 = '';
	$_arrAppendex = [];
	$_arrData = [];
	if (isset($size_quan_matrix)) {
		if (is_array($size_quan_matrix)) {
			if (isset($size_quan_matrix["appendix"]) && is_array($size_quan_matrix["appendix"]) && (count($size_quan_matrix["appendix"]) > 0)) $_arrAppendex = $size_quan_matrix["appendix"];
			if (isset($size_quan_matrix["data"]) && is_array($size_quan_matrix["data"]) && (count($size_quan_matrix["data"]) > 0)) $_arrData = $size_quan_matrix["data"];
		} else {
			$_cat = $size_quan_matrix;
		}
	}
	if ((count($_arrAppendex) > 0) && (count($_arrData) > 0)) {
		$_cat = '';
		foreach ($_arrData as $_catID => $_arrSubCat) {
			$_cat = $_arrAppendex["cat"][$_catID];
			$_tblHead = '';
			$_tblBody = '';
			$_blnMainCatExp = TRUE;
			foreach ($_arrSubCat as $_subCatID => $_arrItems) {
				$_subCatRow1 = '';
				$_subCatRow2 = '';
				$_subCatRow3 = '';
				$_subCatRow4 = '';
				$_blnSubCatExp = TRUE;
				foreach ($_arrItems as $_text => $_obj) {
					if ($_obj['is_expired'] > 0) {
						$_clsExpired = ' cls-is-expired';
					} else {
						$_blnMainCatExp = FALSE;
						$_blnSubCatExp = FALSE;
						$_clsExpired = '';
					}
					$_subCatRow1 .= '<th class="cls-col-size-txt' . $_clsExpired . '">' . $_text . '</th>';
					$_subCatRow2 .= '<th class="cls-col-size-chest' . $_clsExpired . '">' . $_obj['chest'] . '</th>';
					$_subCatRow3 .= '<td class="cls-col-size-qty' . $_clsExpired . '" size="'.$_text.'"><input type="text" id="txt-sq_' . $_obj['rowid'] . '" class="user-input input-integer sq-qty' . $_clsExpired . '" /></td>';
					$_subCatRow4 .= '<td class="cls-col-size-price' . $_clsExpired . '" size="'.$_text.'"><input type="text" id="txt-sp_' . $_obj['rowid'] . '" class="user-input input-double sp-price' . $_clsExpired . '" /></td>';
				}					
				$_ref = $_catID.'_'.$_subCatID;
				//++ Feed 4 block of custom edit size 
				for ($_i=0;$_i<4;$_i++) {
					$_subCatRow1 .= '<th><input type="text" id="txt-sq_'.$_ref.'_text' . (string)($_i + 1) . '" class="user-input">';
					$_subCatRow2 .= '<th><input type="text" id="txt-sq_'.$_ref.'_chest' . (string)($_i + 1) . '" class="user-input input-integer"></th>';
					$_subCatRow3 .= '<td><input type="text" id="txt-sq_'.$_ref.'_qty' . (string)($_i + 1) . '" class="user-input input-integer sq-qty"></td>';
					$_subCatRow4 .= '<td><input type="text" id="txt-sp_'.$_ref.'_price' . (string)($_i + 1) . '" class="user-input input-double sp-price"></td>';
				}
				/*
				$_subCatRow1 .= '<th><input type="text" id="txt-sq_'.$_ref.'_text1" class="user-input"></th><th><input type="text" id="txt-sq_'.$_ref.'_text2" class="user-input"></th><th><input type="text" id="txt-sq_'.$_ref.'_text3" class="user-input"></th><th><input type="text" id="txt-sq_'.$_ref.'_text4" class="user-input"></th>';
				$_subCatRow2 .= '<th><input type="text" id="txt-sq_'.$_ref.'_chest1" class="user-input input-integer"></th><th><input type="text" id="txt-sq_'.$_ref.'_chest2" class="user-input input-integer"></th><th><input type="text" id="txt-sq_'.$_ref.'_chest3" class="user-input input-integer"></th><th><input type="text" id="txt-sq_'.$_ref.'_chest4" class="user-input input-integer"></th>';
				$_subCatRow3 .= '<td><input type="text" id="txt-sq_'.$_ref.'_qty1" class="user-input input-integer sq-qty"></td><td><input type="text" id="txt-sq_'.$_ref.'_qty2" class="user-input input-integer sq-qty"></td><td><input type="text" id="txt-sq_'.$_ref.'_qty3" class="user-input input-integer sq-qty"></td><td><input type="text" id="txt-sq_'.$_ref.'_qty4" class="user-input input-integer sq-qty"></td>';
				$_subCatRow4 .= '<td><input type="text" id="txt-sp_'.$_ref.'_price1" class="user-input input-double sp-price"></td><td><input type="text" id="txt-sp_'.$_ref.'_price2" class="user-input input-double sp-price"></td><td><input type="text" id="txt-sp_'.$_ref.'_price3" class="user-input input-double sp-price"></td><td><input type="text" id="txt-sp_'.$_ref.'_price4" class="user-input input-double sp-price"></td>';
				*/
				//-- Feed 4 block of custom edit size 
				if ($_blnSubCatExp) {
					$_tblHead .= '<th class="cls-is-expired">' . $_arrAppendex["sub"][$_subCatID] . '</th>';
					$_tblBody .= '<td class="cls-is-expired"><table><tr>' . $_subCatRow1 . '</tr><tr>' . $_subCatRow2 . '</tr><tr>' . $_subCatRow3 . '</tr><tr class="eventView-hide">' . $_subCatRow4 . '</tr></table></td>';
				} else {
					$_tblHead .= '<th>' . $_arrAppendex["sub"][$_subCatID] . '</th>';
					$_tblBody .= '<td><table><tr>' . $_subCatRow1 . '</tr><tr>' . $_subCatRow2 . '</tr><tr>' . $_subCatRow3 . '</tr><tr class="eventView-hide">' . $_subCatRow4 . '</tr></table></td>';
				}
			}
			$_clsExpired = '';
			if ($_blnMainCatExp) {
				$_clsExpired = ' cls-is-expired';
				$_strOptions .= '<option class="cls-is-expired" value="' . $_catID . '">' . $_cat . '</option>';
				$_tblHead = '<th class="cls-is-expired" style="display:block;min-width:70px;"></th>' . $_tblHead . '<th class="cls-is-expired" style="display:block;min-width:160px;">รวม</th>';
			} else {
				$_strOptions .= '<option value="' . $_catID . '">' . $_cat . '</option>';
				$_tblHead = '<th style="display:block;min-width:70px;"></th>' . $_tblHead . '<th style="display:block;min-width:160px;">รวม</th>';
			}
			$_strCatagoryTable .= <<<EOT
<table id="cat_id_$_catID" class="tbl_size_cat{$_clsExpired}" style="display:none;">
	<caption>$_cat</caption>
	<thead>
		<tr>
			$_tblHead
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><table class="tbl-row-title"><thead><tr><th>Size</th></tr><tr><th>รอบอก (นิ้ว)</th></tr><tr><th>จำนวน</th></tr><tr class="eventView-hide"><th>ราคา (บาท)</th></tr></thead></table></td>
			$_tblBody
			<td><table class="tbl-sum-value"><thead><tr><th class="total-value">0</td></tr><tr class="eventView-hide"><th class="total-price">0</th></tr></thead></table></td>
		</tr>
	</tbody>
</table>
EOT;
		}
	}

	$_strOptions .= '<option value="0">กำหนดเอง</option>';
	$_subCatRow1 = '';
	$_subCatRow2 = '';
	$_subCatRow3 = '';
	$_subCatRow4 = '';
		for ($_i=0;$_i<10;$_i++) {
			$_subCatRow1 .= '<th><input type="text" id="txt-sq_0_0_text' . (string)($_i + 1) . '" class="user-input sq-size-text"></th>';
			$_subCatRow2 .= '<th><input type="text" id="txt-sq_0_0_chest' . (string)($_i + 1) . '" class="user-input input-integer sq-size-chest"></th>';
			$_subCatRow3 .= '<td><input type="text" id="txt-sq_0_0_qty' . (string)($_i + 1) . '" class="user-input input-integer sq-qty"></td>';	
			$_subCatRow4 .= '<td><input type="text" id="txt-sp_0_0_price' . (string)($_i + 1) . '" class="user-input input-double sp-price"></td>';		
		}
		$_strCatagoryTable .= <<<EOT
<table id="cat_id_0" class="tbl_size_cat">
	<thead>
		<tr>
			<th style="display:block;min-width:70px;"></th>
			<th>กำหนดเอง</th>
			<th style="display:block;min-width:160px;">รวม</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><table class="tbl-row-title"><thead><tr><th>รหัสไซส์</th></tr><tr><th>ขนาด/รอบอก (นิ้ว)</th></tr><tr><th>จำนวน</th></tr><tr class="eventView-hide"><th>ราคา (บาท)</th></tr></thead></table></td>
			<td>
				<table>
					<tr>$_subCatRow1</tr>
					<tr>$_subCatRow2</tr>
					<tr>$_subCatRow3</tr>
					<tr class="eventView-hide">$_subCatRow4</tr>
				</table>
			</td>
		
			<td><table class="tbl-sum-value"><thead><tr><th class="total-value">0</td></tr><tr class="eventView-hide"><th class="total-price">0</th></tr></thead></table></td>
		</tr>
	</tbody>
</table>
EOT;
?>
					<div class="frm-edit-row-group" id="div_order_size_select_cate">
						<span class="group-title">ขนาด - จำนวน</span>
<?php if (! empty($_strOptions)): ?>
						<div class="frm-edit-row">
							<div class="frm-edit-row-value" style="width:50%" >
								<select id="sel-size_category" class="user-input no-validate">
									<?php echo $_strOptions; ?>
								</select>
							</div>
 						</div>
<?php endif; ?>
						<div class="frm-edit-row" id="div_order_size_panel">
							<?php echo (isset($_strCatagoryTable)) ? $_strCatagoryTable : ''; ?>
 						</div>
						<div class="frm-edit-row">
							<div class="table-title frm-edit-row-title" style="width:15%;">จำนวนเสื้อทั้งหมด (ตัว)</div>
							<div class="table-value frm-edit-row-value total-value">0</div>
							<div class="table-title frm-edit-row-title eventView-hide" style="width:20%;">ราคาเฉพาะเสื้อทั้งหมด (บาท)</div>
							<div class="table-value frm-edit-row-value total-price eventView-hide">0</div>
 						</div>
 					</div>
