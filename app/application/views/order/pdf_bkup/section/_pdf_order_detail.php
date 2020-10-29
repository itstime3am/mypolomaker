			<div class="pdf-row">
	<?php if (isset($is_tshirt) && ($is_tshirt == TRUE)) : ?>
				<div class="pdf-title w-1_1">คอเสื้อ</div><div class="pdf-value w-4"><?php echo $data['neck_type_name']; ?></div>
				<div class="pdf-title w-1_2">ทรงเสื้อ</div><div class="pdf-value w-4"><?php echo $data['standard_pattern_name']; ?></div>
				<div class="pdf-title w-1_1">ชนิดผ้า</div><div class="pdf-value w-all"><?php echo $data['fabric_name']; ?></div>
	<?php else: ?>
				<div class="pdf-title w-1">คอเสื้อ</div><div class="pdf-value w-3_3"><?php echo $data['neck_type_name']; ?></div>
				<div class="pdf-title w-1_1">ทรงเสื้อ</div><div class="pdf-value w-3_8"><?php echo $data['base_pattern_name']; ?></div>
				<div class="pdf-title w-1_1">แบบเสื้อ</div><div class="pdf-value w-3"><?php echo $data['standard_pattern_name']; ?></div>
				<div class="pdf-title w-1_1">ชนิดผ้า</div><div class="pdf-value w-all"><?php echo $data['fabric_name']; ?></div>
	<?php endif; ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_2">สีผ้าหลัก</div><div class="pdf-value w-6"><?php echo $data['color']; ?></div>
				<div class="pdf-title w-1_4">สีผ้าตัดต่อ</div><div class="pdf-value w-all"><?php echo $data['color_add1']; ?></div>
			</div>
			<!--div class="pdf-row">
				<div class="ml-1_7 pdf-value w-all"><?php //echo $data['color_add2']; ?></div>
			</div-->
	<?php if (isset($is_tshirt) && ($is_tshirt == TRUE)) : ?>
			<div class="pdf-row">
				<div class="pdf-title w-2_8">แบบคอเสื้อ ซก/RIB</div><div class="pdf-value w-3_2"><?php echo $data['collar_name']; ?></div>
				<div class="ml-0_2 pdf-value w-all"><?php echo $data['collar_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2">แบบแขนเสื้อ</div><div class="pdf-value w-4"><?php echo $data['sleeves_name']; ?></div>
				<?php hlpr_displayValue($data['sleeves_detail'], 85, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2">แบบชายเสื้อ</div><div class="pdf-value w-4"><?php echo isset($data['flap_type_name'])?mb_substr($data['flap_type_name'], 0, 30, 'UTF-8'):''; ?></div>
				<div class="ml-0_2 pdf-value w-all"><?php echo $data['flap_type_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-0_9">ผ่าข้าง</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['m_flap_side'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-0_8" style="margin-left:0;">ผู้ชาย</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['f_flap_side'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-0_9" style="margin-left:0;">ผู้หญิง</div>
				<div class="ml-0_2 pdf-value w-3"><?php echo $data['flap_side_ptrn_name']; ?></div>
				<div class="ml-0_2 pdf-value w-all"><?php echo $data['flap_side_ptrn_detail']; ?></div>
			</div>
	<?php else: ?>
			<div class="pdf-row">
				<div class="pdf-title w-2_2">สาบกระดุมชาย</div><div class="pdf-value w-3_4"><?php echo $data['m_clasper_name']; ?></div>
				<div class="pdf-title w-2_2">สาบกระดุมหญิง</div><div class="pdf-value w-3_4"><?php echo $data['f_clasper_name']; ?></div>
				<div class="pdf-title w-2_2">แบบสาบกระดุม</div><div class="pdf-value w-all"><?php echo $data['clasper_pattern_name']; ?></div>
			</div>
			<div class="pdf-row">
				<?php hlpr_displayValue($data['clasper_detail'], 85, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_2">แบบปก</div><div class="pdf-value w-4"><?php echo $data['collar_name']; ?></div>
				<?php hlpr_displayValue($data['collar_detail'], 100, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_8">แขนเสื้อชาย</div><div class="pdf-value w-3_5"><?php echo $data['m_sleeves_name']; ?></div>
				<div class="pdf-title w-1_8">แขนเสื้อหญิง</div><div class="pdf-value w-3_5"><?php echo $data['f_sleeves_name']; ?></div>
				<?php hlpr_displayValue($data['sleeves_detail'], 30, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_6">แบบชายเสื้อ</div>
				<?php hlpr_displayValue($data['flap_type_name'] . ' ' . $data['flap_type_detail'], 100, FALSE, 0, 'w-7'); ?>
				<div class="pdf-title w-0_9">ผ่าข้าง</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['m_flap_side'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-0_8" style="margin-left:0;">ผู้ชาย</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['f_flap_side'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-0_9" style="margin-left:0;">ผู้หญิง</div>
				<div class="ml-0_2 pdf-value w-all"><?php echo $data['flap_side_ptrn_name']; ?></div>
			</div>
			<div class="pdf-row">
				<?php hlpr_displayValue($data['flap_side_ptrn_detail'], 85); ?>
			</div>
	<?php endif; ?>
			<div class="pdf-row">
				<div class="pdf-checkbox"><img src="<?php echo ($data['m_pocket'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-2" style="margin-left:0;">กระเป๋าเสื้อชาย</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['f_pocket'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-2_1" style="margin-left:0;">กระเป๋าเสื้อหญิง</div>
				<div class="pdf-title w-1_8">แบบกระเป๋า</div>
				<div class="pdf-value w-3_5"><?php echo $data['pocket_type_name']; ?></div>
				<?php hlpr_displayValue($data['pocket_type_detail'], 30, TRUE, 1); ?>
				<!--div class="ml-0_2 pdf-value w-all"><?php //echo $data['pocket_type_detail']; ?></div-->
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_8">ที่เสียบปากกา</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['m_pen'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-1_4" style="margin-left:0;">เสื้อผู้ชาย</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['f_pen'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-1_4" style="margin-left:0;">เสื้อผู้หญิง</div>
				<div class="pdf-title w-0_7">แบบ</div><div class="pdf-value w-3"><?php echo $data['pen_pattern_name']; ?></div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['pen_position_rowid'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-1_2" style="margin-left:0;">แขนซ้าย</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['pen_position_rowid'] == 2)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-1_2" style="margin-left:0;">แขนขวา</div>
				<?php hlpr_displayValue($data['pen_detail'], 20, TRUE, 1); ?>
				<!--div class="ml-0_1 pdf-value w-all"><?php //echo $data['pen_detail']; ?></div-->
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2_8">รายละเอียดเพิ่มเติม</div>
				<?php hlpr_displayValue($data['detail_remark1'], 70, TRUE, 1); ?>
				<!--div class="pdf-value w-all"><?php echo $data['detail_remark1']; ?></div-->
			</div>
			<div class="pdf-row">
				<?php hlpr_displayValue($data['detail_remark2'], 85, TRUE, 1); ?>
				<!--div class="ml-0_2 pdf-value w-all"><?php echo $data['detail_remark2']; ?></div-->
			</div>
