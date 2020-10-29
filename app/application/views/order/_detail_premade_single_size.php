	<div class="frm-edit-row-group" >
		<span class="group-title" style="text-align:center;">รายละเอียด</span>
		<div class="frm-edit-row" id="div_premade_detail_panel">
			<div class="frm-edit-row-value" id="div_premade_detail_main" style="width:40%;margin-left:10%;">
				<table id="tbl_detail_list">
					<thead>
						<tr>
							<th style="width:80px;" class="eventView-hide"></th>
							<th style="width:120px;"><div class="cls-pattern-code-header">รหัสหมวก</div></th>
							<th style="width:160px;">สี</th>
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
							<td style="width:100px;"><input type="text" id="txt-color" data="color" class="edit-ctrl input-required" /></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="frm-edit-row-value" id="div_premade_detail_size" style="width:60%;">
				<table id="tbl_detail_size" style="width:auto;">
					<thead>
						<tr>
							<th style="width:160px;">จำนวน</th>
							<th class="cls-td-row-sum" style="width:210px;">รวม</th>
							<th style="width:80px;" class="eventView-hide"></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr id="edit_panel2" class="tr-edit-panel cls-edit-panel-2 eventView-hide">
							<td>
								<input type="text" class="user-input input-integer" id="0" cat_id="0" sub_cat_id="0" order_size_rowid="0" />
							</td>
							<td class="cls-sum-row">
								<div class="cls-sub-total"></div> x <input type="text" class="user-input input-double cls-price-each no-validate"> = <div class="cls-row-sum-amount">0.00</div>
							</td>
							<td class="control-button">
								<div class="control-button">
									<img src="public/images/b_edit.png" class="edit-ctrl bttn-update" act="insert" title="Insert"> 
									<img src="public/images/details_close.png" class="edit-ctrl bttn-reset" act="cancel" title="Reset">
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="frm-edit-row">
			<div class="table-title frm-edit-row-title" style="width:15%;margin-left:10%;">จำนวนรวมทั้งหมด ( ใบ )</div>
			<div class="table-value frm-edit-row-value total-value"><span id="size-grand-total" class="no-commit"> -- </span></div>
			<div class="table-title frm-edit-row-title eventView-hide" style="width:20%;">ราคาเฉพาะสินค้าทั้งหมด ( บาท )</div>
			<div class="table-value frm-edit-row-value total-price eventView-hide"><span id="spn-total_price" class="user-input input-double"></span></div>
		</div>
	</div>
