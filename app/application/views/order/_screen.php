	<table id="tbl_sc_list">
		<caption>งานปัก/สกรีน</caption>
		<thead>
			<tr>
				<th style="width:12%;">ผู้ปัก/สกรีน</th>
				<th style="width:12%;">ตำแหน่งงาน</th>
				<th style="width:24%;">รายละเอียด</th>
				<th style="width:12em;">ขนาด</th>
				<th style="width:12%;">ประวัติงาน</th>
				<th style="width:12%;">สถานะ</th>
				<th style="width:12%;">จัดการสถานะ</th>
				<th style="width:12%;">วันที่ Approve</th>
				<th style="width:12%;">ช่างตีบล็อค</th>
				<th style="width:12%;">รูปภาพ</th>
				<th class="eventView-hide" style="width:12%;">ราคา (บาท)</th>
				<th class="eventView-hide" style="width:60px;"></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr id="tr_warn_max_row" style="display:none;">
				<td colspan="5">
					<span id="spn_warn_max_row" class="warning-message" style="font-size:small;">
						งานปัก/สกรีน จำกัดจำนวนที่ 9 รายการต่อใบสั่ง
					</span>
				</td>
				<td colspan="2" class="eventView-hide"></td>
			</tr>
<?php
	echo <<<EOT
			<tr id="sc_edit_panel" class="eventView-hide">
				<td>
					<select id="sel-sc_order_screen_rowid" class="sc-edit-ctrl" >

EOT;
	if (isset($order_screen)) {
		foreach ($order_screen as $row) {
			echo "\t\t\t\t" . '<option value="' . $row['rowid'] . '" >' . $row['name'] . '</option>' . "\n";
		}
	}
?>
						</select>
						<!--input type="text" id="txt-sc_position" class="sc-edit-ctrl" /-->	
					</td>
					<td>
						<select id="sel-sc_position" class="sc-edit-ctrl" >
<?php 
	if (isset($arr_position_list)) {
		foreach ($arr_position_list as $_row) {
			echo "\t\t\t\t" . '<option value="' . $_row['name'] . '" >' . $_row['name'] . '</option>' . "\n";
		}
	} else {
// ++ Inferno 201708:: Change by new requirements 
		echo <<<EOT
						<option value="อกซ้าย">อกซ้าย</option>
						<option value="อกขวา">อกขวา</option>
						<option value="กระเป๋า">กระเป๋า</option>
						<option value="ชิดขวาสาบกระเป๋า">ชิดขวาสาบกระเป๋า</option>
						<option value="ชิดซ้ายสาบกระเป๋า">ชิดซ้ายสาบกระเป๋า</option>
						<option value="กลางสาบกระเป๋า">กลางสาบกระเป๋า</option>
						<option value="ชิดใต้สาบกระเป๋า">ชิดใต้สาบกระเป๋า</option>
						<option value="เหนือกระเป๋า">เหนือกระเป๋า</option>
						<option value="แขนซ้าย">แขนซ้าย</option>
						<option value="แขนขวา">แขนขวา</option>
						<option value="ในสาบหลัง">ในสาบหลัง</option>
						<option value="ใต้สาบหลัง">ใต้สาบหลัง</option>
						<option value="กลางหลัง">กลางหลัง</option>
						<option value="กลางอก">กลางอก</option>
						<option value="บ่าเสื้อซ้าย">บ่าเสื้อซ้าย</option>
						<option value="บ่าเสื้อขวา">บ่าเสื้อขวา</option>
						<option value="ชายเสื้อซ้าย">ชายเสื้อซ้าย</option>
						<option value="ชายเสื้อขวา">ชายเสื้อขวา</option>
						<option value="ชายเสื้อตรงกลาง">ชายเสื้อตรงกลาง</option>
						<option value="ปกเสื้อ">ปกเสื้อ</option>
						<option value="แถบตัดต่อ">แถบตัดต่อ</option>
						<option value="ใต้สาบกระดุมเสื้อ">ใต้สาบกระดุมเสื้อ</option>
EOT;

	}
?>
					</select>
				</td>
				<td>
					<!--input type="text" id="txt-sc_detail" class="sc-edit-ctrl" maxlength="50" /-->
					<textarea id="txa-sc_detail" class="sc-edit-ctrl" maxlength="50"></textarea>
				</td>
				<td>
					<!-- input type="text" id="txt-sc_size" class="sc-edit-ctrl input-double"  maxlength="5" style="width:45%;"/>
					<select id="sel-sc_size_unit" class="sc-edit-ctrl" style="width:50px;padding-top:.5px;">
						<option value="นิ้ว">นิ้ว</option>
					</select -->
					<input type="text" id="txt-sc_size" class="sc-edit-ctrl"/>
				</td>
				<td>
					<select id="sel-sc_job_hist" class="sc-edit-ctrl" >
						<option value=""></option>
<!-- ++ Inferno 201708:: Change by new requirements -->
<!-- 
						<option value="งานใหม่">งานใหม่</option>
						<option value="ปรับจากงานเก่า">ปรับจากงานเก่า</option>
						<option value="งานเก่า">งานเก่า</option>
-->
						<option value="งานใหม่(ขึ้นตัวอย่าง)">งานใหม่(ขึ้นตัวอย่าง)</option>
						<option value="งานใหม่(ไม่ดูตัวอย่าง ผลิตได้เลย)">งานใหม่(ไม่ดูตัวอย่าง ผลิตได้เลย)</option>
						<option value="งานเก่า(ผลิตได้เลย)">งานเก่า(ผลิตได้เลย)</option>
						<option value="งานเก่า(ขึ้นตัวอย่างอีกครั้ง)">งานเก่า(ขึ้นตัวอย่างอีกครั้ง)</option>
						<option value="ปรับจากงานเก่า">ปรับจากงานเก่า</option>
<!-- -- Inferno 201708:: Change by new requirements -->
					</select>
				</td>
				<td class="eventView-hide"><input type="text" id="txt-sc_price" class="sc-edit-ctrl input-double" /></td>
				<td class='control-button eventView-hide'>
					<img src="public/images/b_edit.png" id="btnScSubmit" class="sc-edit-ctrl" act='insert' title='Insert' /><img src="public/images/details_close.png" id="btnScCancel" class="sc-edit-ctrl" act='cancel' title='Reset' />
				</td>
			</tr>
		</tfoot>
	</table>