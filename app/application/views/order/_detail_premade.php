	<div class="frm-edit-row-group" >
		<span class="group-title" style="text-align:center;">ขนาด-จำนวน</span>
		<div class="frm-edit-row cls-premade-order-detail-container" id="div_premade_detail_panel">
			<div class="frm-edit-row-value" id="div_premade_detail_main">
				<table id="tbl_detail_list" class="cls-premade-detail-list">
					<thead>
						<tr>
							<th class="eventView-hide"></th>
							<th colspan="2"></th>
						</tr>
						<tr>
							<th style="width:50px;" class="eventView-hide"></th>
							<th colspan="2"></th>
						</tr>
						<tr>
							<th class="eventView-hide"></th>
							<th style="width:80px;">รหัสเสื้อ</th>
							<th style="width:100px;">สี</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr id="edit_panel1" class="tr-edit-panel cls-edit-panel-1 eventView-hide">
							<td style="width:50px;" class="control-button">
								<div class="control-button">
									<img src="public/images/b_edit.png" class="edit-ctrl bttn-update" act="insert" title="Insert"> 
									<img src="public/images/details_close.png" class="edit-ctrl bttn-reset" act="cancel" title="Reset">
								</div>
							</td>
							<td style="width:80px;">
								<select id="sel-pattern_rowid" data="pattern_rowid" class="edit-ctrl input-required" >
<?php
	if (isset($pattern_list)) {
		foreach ($pattern_list as $row) {
			$_strOpt = '<option value="' . $row['rowid'] . '" ref_col="' . $row['color'] . '" ';
			if (isset($row['product_type_rowid'])) $_strOpt .= 'product_type="' . $row['product_type_rowid'] . '" ';
			$_strOpt .= '>' . $row['code'] . '</option>' . "\n";
			echo $_strOpt;
		}
	}
?>
								</select>
							</td>
							<td style="width:100px;"><input type="text" id="txt-color" data="color" class="edit-ctrl input-required" readonly /></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="frm-edit-row-value" id="div_premade_detail_size">
<?php
	if (isset($size_quan_matrix)) {
		$_th1 = '';
		$_th2 = '';
		$_th3 = '';
		$_trtd = '';
		$_td_edit = '';
		$_intItemCount = 0;
		$_intDispItemCount = 0;
		$_intSubCount = 0;
		$_intDispSubCount = 0;
		$_strIsCustomSize = 'false';
		if (! is_array($size_quan_matrix)) {
			$_strIsCustomSize = 'true';
			$_th1 = '<th colspan="10" cat_id="0" class="th-cat-title">' . $size_quan_matrix . '</th>';
			for ($_i=0;$_i<10;$_i++) {
				$_indx = (string)($_i + 1);
				$_th2 .= '<th><input type="text" id="txt-sq_0_0_text' . $_indx . '" class="user-input sq-size-text" custom_index="' . $_indx . '" placeholder="รหัสไซส์"></th>';
				$_th3 .= '<th><input type="text" id="txt-sq_0_0_chest' . $_indx . '" class="user-input input-double sq-size-chest" custom_index="' . $_indx . '" placeholder="ขนาด (นิ้ว)"></th>';
				$_td_edit .= '<td class="td-qty"><input type="text" id="txt-sq_0_0_qty' . $_indx . '" class="user-input input-integer sq-qty" custom_index="' . $_indx . '" placeholder="จำนวน (ชิ้น)"></td>';
			}
		} else {
			$_strIsCustomSize = 'false';
			$_arrAppendex = $size_quan_matrix["appendix"];
			$_arrData = $size_quan_matrix["data"];
			foreach ($_arrData as $_catID => $_arrSubCat) {
				$_cat = $_arrAppendex["cat"][$_catID];
				$_isCatExpired = TRUE;
				$_intSubCount = 0;
				$_intDispSubCount = 0;
				foreach ($_arrSubCat as $_subCatID => $_arrItems) {
					$_intItemCount = 0;
					$_intDispItemCount = 0;
					$_isSubCatExpired = TRUE;
					foreach ($_arrItems as $_text => $_obj) {
						$_intItemCount ++;
						$_isExpired = ($_obj['is_expired'] == 1);
						if ($_isExpired) {
							$_addClass = ' cls-is-expired';
						} else {
							$_addClass = '';
							$_intDispItemCount += 1;
						}
						if ($_catID == 3) {
							$_th3 .= '<th class="th-chest'.$_addClass.'" cat_id="'.$_catID.'" sub_cat_id="'.$_subCatID.'" order_size_rowid="'.$_obj['rowid'].'">'.$_text.'</th>';						
						} else {
							$_th3 .= '<th class="th-chest'.$_addClass.'" cat_id="'.$_catID.'" sub_cat_id="'.$_subCatID.'" order_size_rowid="'.$_obj['rowid'].'">'.$_text;
							if (! empty($_obj['chest'])) $_th3 .= ':' . $_obj['chest'];
							$_th3 .= '</th>';
						}
						$_td_edit .= '<td class="td-qty' . $_addClass . '"><input type="text" class="user-input input-integer' . $_addClass . '" id="txt-' . $_intItemCount . '" cat_id="' . $_catID . '" sub_cat_id="' . $_subCatID . '" order_size_rowid="' . $_obj['rowid'] . '" /></td>';

						$_isSubCatExpired = ($_isSubCatExpired && $_isExpired);
						$_isCatExpired = ($_isCatExpired && $_isExpired);
					}
					$_th2 .= '<th colspan="' . $_intDispItemCount . '" org_colspan="' . $_intItemCount . '" cat_id="' . $_catID . '" class="th-sub-cat-title';
					if ($_isSubCatExpired) $_th2 .= ' cls-is-expired';
					$_th2 .= '" sub_cat_id="' . $_subCatID . '">' . $_arrAppendex["sub"][$_subCatID] . '</th>';
					$_intSubCount += $_intItemCount;
					$_intDispSubCount += $_intDispItemCount;
				}
				$_th1 .= '<th colspan="' . $_intDispSubCount . '" org_colspan="' . $_intSubCount . '" cat_id="' . $_catID . '" class="th-cat-title';
				if ($_isCatExpired) $_th1 .= ' cls-is-expired';
				$_th1 .= '">' . $_cat . '</th>';
				//  style="width:' . ($_intSubCount * 40) . 'px;"
			}
		}
		$_th1 .= '<th rowspan="3" style="width:210px;">รวม</th><th rowspan="3" style="width:50px;" class="eventView-hide"></th>';
		$_td_edit .= <<<EOT
							<td>
								<div class="cls-sub-total" style="width:4em;">0</div> x <input type="text" class="user-input input-double cls-price-each no-validate" style="width:4em;" placeholder="ราคา/ตัว (บาท)"> = <div class="cls-row-sum-amount" style="width:5em;">0</div>
							</td>
							<td class="control-button">
								<div class="control-button">
									<img src="public/images/b_edit.png" class="edit-ctrl bttn-update" act="insert" title="Insert"> 
									<img src="public/images/details_close.png" class="edit-ctrl bttn-reset" act="cancel" title="Reset">
								</div>
							</td>
EOT;
		echo <<<EOT
					<table id="tbl_detail_size" premade_custom_size="$_strIsCustomSize" >
						<thead>
							<tr>
								$_th1
							</tr>
							<tr class="cls-header-row-size-text">
								$_th2
							</tr>
							<tr class="cls-header-row-size-chest">
								$_th3
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr id="edit_panel2" class="tr-edit-panel cls-edit-panel-2 eventView-hide">
								$_td_edit
							</tr>
						</tfoot>
					</table>
EOT;
	}
?>
			</div>
		</div>
		<!-- span id="size-grand-total" class="no-commit"> -- </span>
		<span id="spn-total_price" class="user-input input-double"></span -->
		<div class="frm-edit-row">
			<div class="table-title frm-edit-row-title" style="width:15%;">จำนวนเสื้อทั้งหมด (ตัว)</div>
			<div class="table-value frm-edit-row-value total-value"></div>
			<div class="table-title frm-edit-row-title eventView-hide" style="width:20%;">ราคาเฉพาะเสื้อทั้งหมด (บาท)</div>
			<div class="table-value frm-edit-row-value total-price eventView-hide"></div>
		</div>
	</div>
