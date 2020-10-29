		<ul class="ul-vldr-error-msg" index="<?php echo isset($index)?$index:-1; ?>"></ul>
		<form id="frm_edit" class="cls-frm-edit" index="<?php echo isset($index)?$index:-1; ?>" controller="delivery" >
			<!-- method="post" action="delivery/gen_report" -->
			<input type="hidden" name="hdn-rowid" id="hdn-rowid" class="user-input no-validate commit-data" />			
			<table id="tbl_edit" class="rounded-corner cls-tbl-edit" autofocus > <!-- to prevent focus on first input that cause problem when have scroll bar (back to top after blur lower elements) -->
				<tbody>
				<tr>
					<td colspan="3" class="td-align-center">
						<div class="form-edit-elem-container">
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:10%">วันที่</div>
									<div class="frm-edit-row-value" style="width:20%" >
										<input type="text" id="txt-report_create_date" name="txt-report_create_date" class="user-input data-commit" />
									</div>
									<div class="frm-edit-row-title table-title" style="width:10%">ส่งมอบวันที่</div>
									<div class="frm-edit-row-value" style="width:20%" >
										<input type="text" id="txt-deliver_date" name="txt-deliver_date" class="user-input data-commit" />
									</div>
									<div class="frm-edit-row-title table-title" style="width:10%">เลขที่ใบส่งสินค้า</div>
									<div class="frm-edit-row-value" style="width:20%" >
										<input type="text" id="txt-code" name="txt-code" class="user-input" disabled="disabled" placeholder="autogenerate on insert"/>
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:20%;">
										เลขที่งาน
										<input type="text" id="acc-job_number" class="user-input" style="width:120px;" />: 
									</div>
									<div class="frm-edit-row-value" style="width:80%;">
										<div id="divDispSelectedJobNumber"></div>
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row">
									<!--div class="frm-edit-row-title table-title" style="width:10%">เลขที่งาน</div>
									<div class="frm-edit-row-value" style="width:40%" >
										<input type="text" id="txt-deliver_job_number" class="user-input data-commit" data="deliver_job_number"/>
									</div-->
									<div class="frm-edit-row-title table-title" style="width:10%"></div>
									<div class="frm-edit-row-value" style="width:10%"></div>
									<div class="frm-edit-row-title table-title" style="width:20%">การส่งมอบ / วัตถุประสงค์ของงาน</div>
									<div class="frm-edit-row-value" style="width:60%">
										<select name="sel-title" id="sel-title" class="user-input input-required data-commit">
											<option value="ส่งมอบสินค้าถึงลูกค้าตามที่ระบุ">ส่งมอบสินค้าถึงลูกค้าตามที่ระบุ</option>
											<option value="เช็คจำนวนและจัดสินค้าสำเร็จรูป เพื่อรอแจ้งส่งสินค้าอีกครั้ง">เช็คจำนวนและจัดสินค้าสำเร็จรูป เพื่อรอแจ้งส่งสินค้าอีกครั้ง</option>
											<option value="จัดสินค้ารอส่ง (รับเองที่ฝ่ายจัดส่งโบ๊เบ๊)">จัดสินค้ารอส่ง(รับเองที่ฝ่ายจัดส่งโบ๊เบ๊)</option>
											<option value="ส่งสาขาราชครู">ส่งสาขาราชครู</option>
											<option value="ส่งสาขาโบ๊เบ๊ทาวเวอร์">ส่งสาขาโบ๊เบ๊ทาวเวอร์</option> <!-- Edit:: 20170605 --><!--option value="ส่งสาขาโปโลโอทอป">ส่งสาขาโปโลโอทอป</option-->
											<!-- Edit:: 20170605 --><!--option value="ส่งสาขา TeeSH">ส่งสาขา TeeSH</option-->
											<option value="ส่งสาขาโรบินสัน สมุทรปราการ">ส่งสาขาโรบินสัน สมุทรปราการ</option>
											<option value="ส่งสาขาโปโลเมกเกอร์ ระยอง">ส่งสาขาโปโลเมกเกอร์ ระยอง</option>
											<option value="ส่งสาขาหน้าร้านโรงงาน">ส่งสาขาหน้าร้านโรงงาน</option>
											<option value="ส่งสาขา MRT พระราม9">ส่งสาขา MRT พระราม9</option>
											<option value="ส่งถึงฝ่าย CS โรงงาน">ส่งถึงฝ่าย CS โรงงาน</option>
											<!--option value="ส่งสาขาอารีย์">ส่งสาขาอารีย์</option-->
											<option value="ส่งโรงปักโรงงาน">ส่งโรงปักโรงงาน</option>
											<option value="ส่งโรงสกรีนโรงงาน">ส่งโรงสกรีนโรงงาน</option>
											<option value="ส่งโรงสกรีน DTG โรงงาน">ส่งโรงสกรีน DTG โรงงาน</option>
											<option value="ส่งถึงฝ่าย QA โรงงาน">ส่งถึงฝ่าย QA โรงงาน</option>
											<option value="ส่งสาขา SMT เมืองทองธานี">ส่งสาขา SMT เมืองทองธานี</option>
											<!-- Edit:: 20170605 -->
											<!--option value="ส่งโรงปักร้านร๊อค">ส่งโรงปักร้านร๊อค</option>
											<option value="ส่งโรงสกรีนพี่ณี">ส่งโรงสกรีนพี่ณี</option>
											<option value="ส่งโรงสกรีนร้านปุัย">ส่งโรงสกรีนร้านปุัย</option-->
										</select>
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:10%">ชื่อลูกค้า</div>
									<div class="frm-edit-row-value" style="width:20%" >
										<!-- ++ mail issue 20160225 ++ -->
										<!--select id="sel-customer_rowid" name="sel-customer_rowid" class="user-input input-required data-commit" >
					<?php
						/*
						if (isset($customer_list)) {
							foreach ($customer_list as $row) {
								echo '<option value="' . $row['rowid'] . '" company="' . $row['company'] . '" customer_name="' . $row['customer_name'] . '">' . $row['display_name'] . '</option>' . "\n";
							}
						}
						*/
					?>
										</select-->
										<input type="text" id="aac-customer_display" class="user-input" />
										<input type="hidden" name="hdn-customer_rowid" id="hdn-customer_rowid" class="user-input data-commit" />
										<input type="hidden" name="hdn-customer_name" id="hdn-customer_name" class="user-input data-commit" />
									</div>
									<div class="frm-edit-row-title table-title" style="width:10%">ขื่อกิจการ</div>
									<div class="frm-edit-row-value" style="width:60%" >
										<input type="text" id="txt-company" name="txt-company" class="user-input data-commit set-disabled" />
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:10%">เบอร์โทร</div>
									<div class="frm-edit-row-value" style="width:20%" >
										<input type="text" id="txt-tel" name="txt-tel" class="user-input data-commit set-disabled" />
									</div>
									<div class="frm-edit-row-title table-title" style="width:10%">ที่อยู่</div>
									<div class="frm-edit-row-value" style="width:60%" >
										<select id="sel-customer_address" name="sel-customer_address" class="user-input" >
										</select>
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title group-title" style="width:150px">ส่วนติดต่อส่งสินค้า</div>
									<div class="frm-edit-row-value" style="width:80%" >
										<textarea id="txt-contact" name="txt-contact" class="user-input data-commit"></textarea>
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" > 
								<span class="group-title">วิธีการจัดส่งสินค้า</span>
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px">&nbsp;</div>
									<input type="radio" id="rdo-deliver_route_1" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="ขนส่ง">
									<label class="cls-radio-label" for="rdo-deliver_route_1">ขนส่ง</label>
									<input type="radio" id="rdo-deliver_route_2" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="มอเตอร์ไซค์">
									<label class="cls-radio-label" for="rdo-deliver_route_2">มอเตอร์ไซค์</label>
									<input type="radio" id="rdo-deliver_route_3" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="รถโรงงาน">
									<label class="cls-radio-label" for="rdo-deliver_route_3">รถโรงงาน</label>
									<input type="radio" id="rdo-deliver_route_4" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="รถตู้">
									<label class="cls-radio-label" for="rdo-deliver_route_4">รถตู้</label>
									<input type="radio" id="rdo-deliver_route_5" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="รถทัวร์">
									<label class="cls-radio-label" for="rdo-deliver_route_5">รถทัวร์</label>
									<input type="radio" id="rdo-deliver_route_6" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="ไปรษณีย์ ลงทะเบียน">
									<label class="cls-radio-label" for="rdo-deliver_route_6">ไปรษณีย์ ลงทะเบียน</label>
									<input type="radio" id="rdo-deliver_route_7" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="ไปรษณีย์ EMS ลงทะเบียน">
									<label class="cls-radio-label" for="rdo-deliver_route_7">ไปรษณีย์ EMS ลงทะเบียน</label>
									<input type="radio" id="rdo-deliver_route_8" name="deliver_route" class="cls-toggle-label user-input input-required data-commit" value="KERRY">
									<label class="cls-radio-label" for="rdo-deliver_route_8">KERRY</label>
								</div>
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px">หมายเหตุ</div>
									<div class="frm-edit-row-value" style="width:80%" >
										<input type="text" id="txt-deliver_route_remark" name="txt-deliver_route_remark" class="user-input data-commit" />
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<span class="group-title">ประเภทสินค้า ( เลือกได้มากกว่า 1 ประเภท )</span>
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px">&nbsp;</div>
									<input type="checkbox" id="chk-product_deliver_1" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="1">
									<label class="cls-checkbox-label" for="chk-product_deliver_1">เสื้อโปโลสั่งตัด</label>
									<input type="checkbox" id="chk-product_deliver_2" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="2">
									<label class="cls-checkbox-label" for="chk-product_deliver_2">เสื้อยืดสั่งตัด</label>
									<input type="checkbox" id="chk-product_deliver_5" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="5">
									<label class="cls-checkbox-label" for="chk-product_deliver_5">หมวกสั่งตัด</label>
									<input type="checkbox" id="chk-product_deliver_6" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="6">
									<label class="cls-checkbox-label" for="chk-product_deliver_6">เสื้อแจ๊กเก็ตสั่งตัด</label>
									<input type="checkbox" id="chk-product_deliver_3" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="3">
									<label class="cls-checkbox-label" for="chk-product_deliver_3">เสื้อโปโลสำเร็จรูป</label>
									<input type="checkbox" id="chk-product_deliver_4" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="4">
									<label class="cls-checkbox-label" for="chk-product_deliver_4">เสื้อยืดสำเร็จรูป</label>
									<input type="checkbox" id="chk-product_deliver_7" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="7">
									<label class="cls-checkbox-label" for="chk-product_deliver_7">หมวกสำเร็จรูป</label>
									<input type="checkbox" id="chk-product_deliver_8" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="8">
									<label class="cls-checkbox-label" for="chk-product_deliver_8">เสื้อแจ๊กเก็ตสำเร็จรูป</label>
									<input type="checkbox" id="chk-product_deliver_9" name="product_deliver[]" class="cls-toggle-label user-input data-commit data-commit" value="8">
									<label class="cls-checkbox-label" for="chk-product_deliver_8">ผ้ากันเปื้อน</label>
								</div>
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px">อื่นๆ</div>
									<div class="frm-edit-row-value" style="width:80%" >
										<input type="text" id="txt-product_deliver_other" name="txt-product_deliver_other" class="user-input data-commit" />
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="group-title" style="width:150px">เอกสารแนบ</div>
									<div>
										<input type="checkbox" id="chk-attachment_1" name="attachment[]" class="cls-toggle-label user-input data-commit" value="1">
										<label class="cls-checkbox-label" for="chk-attachment_1">ใบกำกับภาษี</label>
										<input type="checkbox" id="chk-attachment_2" name="attachment[]" class="cls-toggle-label user-input data-commit" value="2">
										<label class="cls-checkbox-label" for="chk-attachment_2">ใบแจ้งหนี้</label>
										<input type="checkbox" id="chk-attachment_3" name="attachment[]" class="cls-toggle-label user-input data-commit" value="3">
										<label class="cls-checkbox-label" for="chk-attachment_3">บิลเงินสด</label>
										<input type="checkbox" id="chk-attachment_4" name="attachment[]" class="cls-toggle-label user-input data-commit" value="4">
										<label class="cls-checkbox-label" for="chk-attachment_4">บิลส่งของ</label>
									</div>
								</div>
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px">อื่นๆ</div>
									<div class="frm-edit-row-value" style="width:80%" >
										<input type="text" id="txt-attachment_other" name="txt-attachment_other" class="user-input data-commit" />
									</div>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<span class="group-title" style="width:100px;margin:0 auto;">สรุปราคาสินค้า</span>
								<div class="frm-edit-row" >
									<table id="tbl_detail" style="table-layout:fixed;margin:5px auto;">
										<thead>
											<tr>
												<th style="width:10%;">จำนวน</th>
												<th>รายการ</th>
												<th style="width:15%;">หน่วยละ</th>
												<th style="width:15%;">% ส่วนลด</th>
												<th style="width:15%;">จำนวนเงิน</th>
												<th style="width:8%;" class="eventView-hide"></th>
											</tr>
										</thead>
										<tbody>
											<tr id="edit_panel" class="eventView-hide">
												<td style="text-align:center;"><input type="text" id="txt-detail_qty" class="edit-ctrl input-integer input-format-integer" style="width:3em;"/><span id="disp_left_qty"></span></td>
												<td><input type="text" id="txt-detail_title" class="edit-ctrl" /></td>
												<td><input type="text" id="txt-detail_price" class="edit-ctrl input-double input-format-number" /></td>
												<td><input type="text" id="txt-percent_discount" readonly="readonly" class="edit-ctrl input-double input-format-number" /></td>
												<td><input type="text" id="txt-detail_amount" class="edit-ctrl input-double input-format-number" readonly="true" /></td>
												<td class='control-button'><img src="public/images/b_edit.png" id="btnPanelSubmit" class="edit-ctrl" act='insert' title='Insert' style="cursor:pointer;"/><img src="public/images/details_close.png" id="btnPanelCancel" class="edit-ctrl" act='cancel' title='Reset' style="cursor:pointer;" /></td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="2" rowspan="3" style="text-align:left;">
													<ul style="vertical-align:top;">
														<li>ท่านสามารถคืนสินค้าได้ตามข้อตกลงและเงื่อนไข โดยบริษัทขอสงวนสิทธิ์ในการรับเปลี่ยนหรือคืนสินค้าในสภาพสมบูรณ์ ยังไม่ผ่านการใช้งาน ภายใน 7 วัน</li>
														<li>บริษัทขอรักษาสิทธิ์ในจำนวนสินค้าจากการลงนามรับสินค้าเป็นสำคัญ</li>
													</ul>
												</td>
												<td class="table-title">Total รวมเงิน</td>
												<td><input type="text" id="txt-total" name="txt-total" class="user-input input-double input-format-number data-commit set-disabled" readonly="true" /></td>
												<td></td>
											</tr>
											<tr>
												<td class="table-title">
													<select id="sel-is_vat" class="user-input data-commit set-disabled">
														<option value="0">ไม่มี VAT</option>
														<option value="1">แยก VAT (นอก)</option>
														<option value="2">รวม VAT (ใน)</option>
													</select>
												</td>
												<td>
													<input type="text" id="txt-vat" name="txt-vat" class="user-input input-double input-format-number data-commit set-disabled" readonly="true" /></td>
												</td>
												<td></td>
											</tr>
											<tr>
												<td class="table-title">รวมสุทธิ</td>
												<td>
													<input type="text" id="txt-grand_total" name="txt-grand_total" class="user-input input-double input-format-number data-commit set-disabled" readonly="true" />
												</td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="frm-edit-row" >
									<table style="table-layout:fixed;width:100%;">
										<tbody>
											<tr>
												<td class="table-title">
													<input type="text" id="txt-deposit_date" class="user-input data-commit set-disabled" data="deposit_date" style="width:10em !important;">&nbsp;
													ลุกค้าชำระมัดจำแล้วโดย&nbsp;
													<select id="sel-deposit_route" name="sel-deposit_route" class="user-input data-commit set-disabled" style="width:220px;" >
<?php	if (isset($arrDepositRoute)): ?>
<?php		foreach ($arrDepositRoute as $_row): ?>
														<option value="<?php echo isset($_row['name'])?$_row['name']:''; ?>"><?php echo isset($_row['name'])?$_row['name']:''; ?></option>
<?php		endforeach; ?>
<?php	endif; ?>
														<!--
														<option value="SCB POLOMAKER">SCB POLOMAKER</option>
														<option value="KBANK POLOMAKER">KBANK POLOMAKER</option>
														<option value="SCB สุนิสา">SCB สุนิสา</option>
														<option value="KBank สุนิสา">KBank สุนิสา</option>
														<option value="KTB สุนิสา">KTB สุนิสา</option>
														<option value="SCB สุรศักดิ์">SCB สุรศักดิ์</option>
														<option value="ลูกค้าเครดิต">ลูกค้าเครดิต</option>
														<option value="SCB POLO TALAD">SCB POLO TALAD</option>
														<option value="SCB POLO PLUS">SCB POLO PLUS</option>
														<option value="เงินสดหน้าร้านสาขา">เงินสดหน้าร้านสาขา</option>
														<option value="บัตรเครดิต">บัตรเครดิต</option>
														<option value="Cheque">Cheque</option>
														-->
													</select>&nbsp;
													จำนวน
												</td>
												<td style="width:10%;">
													<input type="text" id="txt-deposit_amount" name="txt-deposit_amount" class="user-input input-double input-format-number data-commit set-disabled" />
												</td>
												<td class="table-title" style="width:5%;text-align:left;">บาท</td>
											</tr>
											<tr>
												<td class="table-title">
													<input type="text" id="txt-payment_date" class="user-input data-commit set-disabled" data="payment_date" style="width:10em !important;">&nbsp;
													ลุกค้าชำระสินค้าแล้วโดย&nbsp;
													<select id="sel-payment_route" name="sel-payment_route" class="user-input data-commit set-disabled" style="width:220px;" >
<?php	if (isset($arrCloseRoute)): ?>
<?php		foreach ($arrCloseRoute as $_row): ?>
														<option value="<?php echo isset($_row['name'])?$_row['name']:''; ?>"><?php echo isset($_row['name'])?$_row['name']:''; ?></option>
<?php		endforeach; ?>
<?php	endif; ?>
														<!--
														<option value="SCB POLOMAKER">SCB POLOMAKER</option>
														<option value="KBANK POLOMAKER">KBANK POLOMAKER</option>
														<option value="SCB สุนิสา">SCB สุนิสา</option>
														<option value="KBank สุนิสา">KBank สุนิสา</option>
														<option value="KTB สุนิสา">KTB สุนิสา</option>
														<option value="SCB สุรศักดิ์">SCB สุรศักดิ์</option>
														<option value="ลูกค้าเครดิต">ลูกค้าเครดิต</option>
														<option value="SCB POLO TALAD">SCB POLO TALAD</option>
														<option value="SCB POLO PLUS">SCB POLO PLUS</option>
														<option value="เงินสดหน้าร้านสาขา">เงินสดหน้าร้านสาขา</option>
														<option value="บัตรเครดิต">บัตรเครดิต</option>
														<option value="Cheque">Cheque</option>
														-->
													</select>&nbsp;
													จำนวน
												</td>
												<td style="width:10%;">
													<input type="text" id="txt-payment_amount" name="txt-payment_amount" class="user-input input-double input-format-number data-commit set-disabled" />
												</td>
												<td class="table-title" style="width:5%;text-align:left;">บาท</td>
											</tr>
											<tr>
												<td class="table-title">ค่าจัดส่งสินค้าเรียกเก็บลูกค้าจำนวน</td>
												<td><input type="text" id="txt-deliver_amount" name="txt-deliver_amount" class="user-input input-double input-format-number data-commit" /></td>
												<td class="table-title" style="text-align:left;">บาท</td>
											</tr>
											<tr>
												<td class="table-title">เรียกเก็บเงินกับลูกค้าทั้งสิ้นจำนวน</td>
												<td><input type="text" id="txt-left_amount" name="txt-left_amount" readonly="true" class="user-input input-double input-format-number data-commit set-disabled" readonly="true" /></td>
												<td class="table-title" style="text-align:left;">บาท</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px">หมายเหตุ</div>
									<div class="frm-edit-row-value" style="width:80%;" >
										<textarea id="txt-remark1" name="txt-remark1" class="user-input data-commit"></textarea>
									</div>
								</div>
								<div class="frm-edit-row" >
									<div class="frm-edit-row-title table-title" style="width:150px"></div>
									<div class="frm-edit-row-value" style="width:80%;" >
										<textarea id="txt-remark2" name="txt-remark2" class="user-input data-commit"></textarea>
									</div>
								</div>
							</div>
							<!--div class="frm-edit-row-group" >
								<div class="frm-edit-row" >
									<div style="margin:0 auto;width:500px;text-align:center;">
										<a id="btnSubmit">ออกรายงาน</a> <a id="btnReset">ล้างค่า</a>
									</div>
								</div>
							</div-->
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="td-align-center"><input type="button" id="btnFormSubmit" class="cls-btn-form-submit" value="บันทึก"/><input type="button" id="btnFormReset" class="cls-btn-form-reset" value="ค่าเริ่มต้น" /><input type="button" id="btnFormCancel"  class="cls-btn-form-cancel"value="ยกเลิก"/></td>
				</tr>
				</tbody>
			</table>
		</form>
		<br style="clear:both" />
