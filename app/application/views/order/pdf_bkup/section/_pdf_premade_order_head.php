<?php if (isset($data)) : ?>
			<div class="pdf-row" style="clear:both;padding-top:0.1cm;">
				<div class="pdf-title w-1_2">ชื่อลูกค้า</div><div class="pdf-value w-4_7"><?php echo $data['customer_name']; ?></div>
				<div class="pdf-title w-1_3">ชื่อกิจการ</div><div class="pdf-value w-all"><?php echo $data['company']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-0_6">ที่อยู่</div>
				<?php hlpr_displayValue($data['address'], 180, TRUE, 2, 'w-all', 10, 'margin-left:1cm;'); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_3">เบอร์โทร</div><div class="pdf-value w-4"><?php echo $data['contact_no']; ?></div>
				<div class="pdf-title w-2">เลขที่ผู้เสียภาษี</div><div class="pdf-value w-4"><?php echo $data['tax_id']; ?></div>
				<div class="pdf-title w-0_8">สาขา</div><div class="pdf-value w-3_5"><?php echo $data['tax_branch']; ?></div>
				<div class="pdf-value ml-0_5 w-all pdf-text-center"><?php echo $data['disp_vat_type']; ?></div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_6">วันที่สั่งงาน</div><div class="pdf-value w-2_2"><?php echo $data['order_date']; ?></div>
				<div class="pdf-title w-1_4">กำหนดส่ง</div><div class="pdf-value w-2_2"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-1_8">วันที่ส่งลูกค้า</div><div class="pdf-value w-2_2"><?php echo $data['deliver_date']; ?></div>
				<!--<div class="pdf-title t-bold w-3 pdf-text-center"><?php echo $data['disp_vat_type']; ?></div>-->
				<div class="pdf-checkbox pl-0_2"><img src="<?php echo ($data['is_tax_inv_req'] > 0)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-2_3" style="margin-left:0;">ขอใบกำกับภาษี</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-all" style="margin-left:0;">
				<?php echo (strpos($title, 'หมวก') === FALSE)?'มีเสื้อตัวอย่าง':'มีหมวกตัวอย่าง'; ?>
				</div>
			</div>
<?php endif; ?>