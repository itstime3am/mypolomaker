<?php $_index = isset($index)?$index:0; ?>
<ul class="ul-vldr-error-msg" index="<?php echo $_index; ?>"></ul>
<form id="frm_edit" class="cls-frm-edit" index="<?php echo $_index; ?>" controller="<?php echo isset($crud_controller)?$crud_controller:$this->uri->rsegment(1); ?>" enctype="multipart/form-data" >
<div id="tabs" style="display:none;">
	<input type="hidden" id="hdn-rowid" class="user-input" />
	<input type="hidden" id="hdn-old_file_image1" class="user-input" />
	<input type="hidden" id="hdn-old_file_image2" class="user-input" />
	<input type="hidden" id="hdn-old_file_image3" class="user-input" />
	<input type="hidden" id="hdn-old_file_image4" class="user-input" />
	<input type="hidden" id="hdn-old_file_image5" class="user-input" />
	<input type="hidden" id="hdn-old_file_image6" class="user-input" />
	<input type="hidden" id="hdn-old_file_image7" class="user-input" />
	<input type="hidden" id="hdn-old_file_image8" class="user-input" />
	<input type="hidden" id="hdn-old_file_image9" class="user-input" />
	<ul>
		<li><a href="#tab_order_main">ข้อมูลหลัก</a></li>
<?php if (!isset($type_premade_order)): ?>
		<li><a href="#tab_order_detail">รายละเอียดแบบเสื้อ</a></li>
		<li><a href="#tab_others_detail">ขนาด จำนวน งานปัก/สกรีน</a></li>
<?php else: ?>
		<li><a href="#tab_order_detail">แบบ จำนวน งานปัก/สกรีน</a></li>
<?php endif; ?>
		<li><a href="#tab_order_images">รูปตัวอย่าง</a></li>
	</ul>
	<div id="tab_order_main">
		<table class="rounded-corner cls-tbl-edit" autofocus > <!-- to prevent focus on first input that cause problem when have scroll bar (back to top after blur lower elements) -->
			<tbody>
			<tr>
				<td colspan="3" class="td-align-center">
					<div class="form-edit-elem-container">
						<div class="frm-edit-row-group" >
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%;">เลขที่งาน</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<input type="text" id="txt-job_number" class="user-input" maxlength="10"/>
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%;">เลขที่อ้างอิง</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<!--input type="text" id="txt-ref_number" class="user-input" /-->
									<select id="sel-ref_number" class="user-input" >
<?php if (isset($job_number_list)): ?>
<?php	foreach ($job_number_list as $row): ?>
										<option value="<?php echo $row['job_number']; ?>" rowid="<?php echo $row['rowid']; ?>"><?php echo $row['job_number']; ?></option>
<?php	endforeach; ?>
<?php endif; ?>
									</select>
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%;">ชื่อโปรโมชั่น</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<input type="text" id="txt-option_promotion" class="user-input" maxlength="50"/>
								</div>
							</div>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%">ชื่อลูกค้า</div>
								<div class="frm-edit-row-value" style="width:30%" >
									<input type="text" id="aac-customer" class="user-input ajax-autocomplete" />
									<input type="hidden" id="hdn-customer_rowid" class="user-input input-required" />
								</div>
								<div class="frm-edit-row-title table-title" style="width:5%">VAT</div>
								<div class="frm-edit-row-value" style="width:10%" >
									<select id="sel-is_vat" class="user-input">
										<option value="0">ไม่มี VAT</option>
										<option value="1">แยก VAT (นอก)</option>
										<option value="2">รวม VAT (ใน)</option>
									</select>
								</div>
								<div style="width:15%;display:inline;text-align:left;">
									<div class="frm-edit-row-value table-value-checkbox" style="width:20px;margin-left:20%;">
										<input type="checkbox" id="chk-is_tax_inv_req" class="user-input" />
									</div>
									<div class="frm-edit-row-title table-title table-title-checkbox" style="width:auto;">
										ขอใบกำกับภาษี
									</div>
								</div>
								<div style="width:20%;display:inline;text-align:left;">
									<div class="frm-edit-row-value table-value-checkbox" style="width:20px;margin-left:20%;">
										<input type="checkbox" id="chk-has_sample" class="user-input" />
									</div>
									<div class="frm-edit-row-title table-title table-title-checkbox" style="width:auto;">
										มีเสื้อตัวอย่าง
									</div>
								</div>
							</div>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%">ชื่อกิจการ</div>
								<div class="frm-edit-row-value cls-customer-detail" style="width:45%" >
									<input type="text" id="txt-company" class="user-input data-container set-disabled no-commit" readonly="readonly" data="company"/>
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%">สั่งจาก</div>
								<div class="frm-edit-row-value" style="width:25%;">
<?php if (isset($supplier_list)): ?>
<?php	foreach ($supplier_list as $row): ?>
									<input type="radio" name="supplier_rowid" id="rdo-supplier-<?php echo $row['name_en']; ?>" value="<?php echo $row['rowid']; ?>" class="user-input" data="supplier_rowid" />
									<label for="rdo-supplier-<?php echo $row['name_en']; ?>"><?php echo $row['name']; ?></label>
<?php	endforeach; ?>
<?php endif; ?>
								</div>
							</div>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%;">ที่อยู่</div>
								<div class="frm-edit-row-value cls-customer-detail" style="width:90%;" >
									<textarea id="txa-address" class="user-input data-container set-disabled no-commit" readonly="readonly" data="address" style="width:90%;" rows="3"></textarea>
									<!--input type="text" id="txt-address" class="user-input data-container set-disabled no-commit" readonly="readonly" data="address" style="width:90%;"/-->
								</div>
							</div>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%;">มือถือ</div>
								<div class="frm-edit-row-value cls-customer-detail" style="width:30%;" >
									<input type="text" id="txt-customer_mobile" class="user-input data-container set-disabled no-commit" readonly="readonly" data="customer_mobile"/>
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%;">โทรศัพท์</div>
								<div class="frm-edit-row-value cls-customer-detail" style="width:30%;" >
									<input type="text" id="txt-customer_tel" class="user-input data-container set-disabled no-commit" readonly="readonly" data="customer_tel"/>
								</div>
							</div>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%;">เลขที่ผู้เสียภาษี</div>
								<div class="frm-edit-row-value cls-customer-detail" style="width:20%;" >
									<input type="text" id="txt-tax_id" class="user-input data-container set-disabled no-commit" readonly="readonly" data="tax_id"/>
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%;">สาขา</div>
								<div class="frm-edit-row-value cls-customer-detail" style="width:20%;" >
									<input type="text" id="txt-tax_branch" class="user-input data-container set-disabled no-commit" readonly="readonly" data="tax_branch"/>
								</div>
<?php
	if ( ! isset($type_premade_order)) {
		echo <<<EOT
								<div class="frm-edit-row-title table-title" style="width:10%;">รูปแบบ</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<select id="sel-tshirt_pattern_rowid" class="user-input" >
EOT;
		$_arrPatternDetailObjects = array();
		if (isset($pattern_list)) {
			foreach ($pattern_list as $row) {
				echo '<option value="' . $row['rowid'] . '" >' . $row['code'] . '</option>' . "\n";

				if ($row['rowid'] != '') {
					$_arrPatternDetailObjects[$row['rowid']] = array();
					foreach ($row as $_name=>$_value) {
						$_arrPatternDetailObjects[$row['rowid']][$_name] = $_value;
					}
				}
			}
		}
		$_strJsonPattern = json_encode($_arrPatternDetailObjects);
		echo <<<EOT
									</select>
								</div>
EOT;
	}
?>
							</div>
						</div>
						<div class="frm-edit-row-group" >
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:10%;">วันที่สั่งงาน</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<input type="text" id="txt-order_date" class="user-input input-required" />
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%;">กำหนดส่ง</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<input type="text" id="txt-due_date" class="user-input" />
								</div>
								<div class="frm-edit-row-title table-title" style="width:10%;">วันที่ส่งลูกค้า</div>
								<div class="frm-edit-row-value" style="width:20%;" >
									<input type="text" id="txt-deliver_date" class="user-input" />
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center">
					<div class="form-edit-elem-container">
						<div class="frm-edit-row-group" >
							<span class="group-title">หมายเหตุ</span>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-value" style="width:50%" >
									<textarea id="txa-remark1" class="user-input" maxlength="120"></textarea>
								</div>
							</div>
							<div class="frm-edit-row" >
								<div class="frm-edit-row-value" style="width:50%" >
									<textarea id="txa-remark2" class="user-input" maxlength="120" ></textarea>
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="height:30px;"></td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center"><input type="button" id="btnFormSubmit" class="cls-btn-form-submit" value="บันทึก"/><input type="button" id="btnFormReset" class="cls-btn-form-reset" value="ค่าเริ่มต้น" /><input type="button" id="btnFormCancel"  class="cls-btn-form-cancel"value="ยกเลิก"/></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div id="tab_order_detail">
		<table class="rounded-corner cls-tbl-edit">
			<tbody>
			<tr>
				<td colspan="3" class="td-align-center">
					<div id="ord_dtl_container" class="form-edit-elem-container">
						<?php echo isset($details_panel)?$details_panel:''; ?>
					</div>
				</td>
			</tr>
<?php if (! isset($type_premade_order)): ?>
			<tr>
				<td colspan="3" style="height:30px;"></td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center"><input type="button" id="btnFormSubmit" class="cls-btn-form-submit" value="บันทึก"/><input type="button" id="btnFormReset" class="cls-btn-form-reset" value="ค่าเริ่มต้น" /><input type="button" id="btnFormCancel"  class="cls-btn-form-cancel"value="ยกเลิก"/></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div id="tab_others_detail">
		<table class="rounded-corner cls-tbl-edit">
			<tbody>
<?php endif; ?>
<?php if (isset($size_quan_panel)): ?>
			<tr>
				<td colspan="3" class="td-align-center">
					<div id="ord_size_quan_container" class="form-edit-elem-container">
						<?php echo isset($size_quan_panel)?$size_quan_panel:''; ?>
					</div>
				</td>
			</tr>
<?php endif; ?>
			<tr> <!--class="eventView-hide"-->
				<td colspan="3" class="td-align-center">
					<div id="ord_others_price_container" class="form-edit-elem-container">
						<?php echo isset($others_price_panel)?$others_price_panel:''; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center">
					<div id="ord_scrn_container" class="form-edit-elem-container">
						<?php echo isset($screen_panel)?$screen_panel:''; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="height:30px;"></td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center"><input type="button" id="btnFormSubmit" class="cls-btn-form-submit" value="บันทึก"/><input type="button" id="btnFormReset" class="cls-btn-form-reset" value="ค่าเริ่มต้น" /><input type="button" id="btnFormCancel"  class="cls-btn-form-cancel"value="ยกเลิก"/></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div id="tab_order_images">
		<table class="rounded-corner cls-tbl-edit">
			<tbody>
			<tr>
				<td colspan="3" class="td-align-center">
					<div class="form-edit-elem-container">
						<div class="frm-edit-row-group" >
							<div class="frm-edit-row" >
								<div class="progress">
									<div class="bar"></div >
									<div class="percent">0%</div >
								</div>
							</div>
							<div class="frm-edit-row" style="display:inline-block;" >
								<div class="frm-edit-row-value display-upload disp-upload-main" id="div_disp_upload1" >
									<div class="frm-edit-row-title table-title">รูปภาพประกอบ 1</div>
									<span class="spn-image-select eventView-hide">เพิ่ม/แก้ไขรูปภาพ<input id="fil-image1" type="file" name="image" ></span>
									<input type="hidden" id="hdn-file_image1" class="user-input" >
								</div>
							</div>
<?php $_currIndex = 1; ?>
<?php for($_r=0;$_r<2;$_r++): ?>
							<div class="frm-edit-row" style="display:inline-block;" >
<?php 	for($_c=1;$_c<=4;$_c++): ?>
<?php		$_id = $_currIndex + (($_r * 4) + $_c); ?>
								<div class="frm-edit-row-value display-upload disp-upload-sub" id="div_disp_upload<?php echo $_id; ?>" >
									<div class="frm-edit-row-title table-title">รูปภาพประกอบ <?php echo $_id; ?></div>
									<span class="spn-image-select eventView-hide">
										เพิ่ม/แก้ไขรูปภาพ
										<input id="fil-image<?php echo $_id; ?>" type="file" name="image" >
									</span>
									<input type="hidden" id="hdn-file_image<?php echo $_id; ?>" class="user-input" >
								</div>
<?php 	endfor; ?>
							</div>
<?php endfor; ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="height:30px;"></td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center"><input type="button" id="btnFormSubmit" class="cls-btn-form-submit" value="บันทึก"/><input type="button" id="btnFormReset" class="cls-btn-form-reset" value="ค่าเริ่มต้น" /><input type="button" id="btnFormCancel"  class="cls-btn-form-cancel"value="ยกเลิก"/></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
</form>
<form id="frm_upload_image" action="upload_temp_image" method="post" enctype="multipart/form-data">
	<input type="hidden" id="element_id" name="element_id" >
</form>
<?php
	if ( ! isset($type_premade_order)) {
		echo <<<EOT
<script language="javascript">
	var _arrPatternDetails = $_strJsonPattern;
	$(function() {
		$('#frm_edit #sel-tshirt_pattern_rowid').combobox({
			select:
				function (value) {
					var _pattern_rowid = $("#frm_edit #sel-tshirt_pattern_rowid").val();
					if ((_pattern_rowid > 0) && (_pattern_rowid in _arrPatternDetails)) {
						doSetPatternDetail(_arrPatternDetails[_pattern_rowid]);
						_doSetEnableFormUserInput($("#frm_edit #tbl_pattern_details"), false);
					} else {
						$("#frm_edit #sel-tshirt_pattern_rowid").val('');
						_doSetEnableFormUserInput($("#frm_edit #tbl_pattern_details"), true);
					}
					return false;
				},
			changed: function (value) {
					if (value == '') {
						_doSetEnableFormUserInput($("#frm_edit #tbl_pattern_details"), true);
					}
				}
		});
	});
</script>
<div id="div_status_remark_manu" style="display:none;">
	<span class="cls-label" style="font-weight:bold;">สาเหตุ</span>
	<textarea id="txa-status_remark" style="width:96%;" class="user-input" rows="3" placeholder="สาเหตุ หรือ ข้อมูลเพิ่มเติม"></textarea>
</div>
EOT;
	}
?>
<br style="clear:both" />
