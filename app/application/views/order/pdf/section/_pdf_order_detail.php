			<div class="pdf-row">
				<div class="pdf-title w-3">แบบแพทเทิร์น</div><div class="pdf-value w-6"><?php echo $data['standard_pattern_name']; ?></div>
				<div class="pdf-title w-4">ชนิดผ้า</div><div class="pdf-value w-all"><?php echo $data['fabric_name']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบคอเสื้อ</div><div class="pdf-value w-6"><?php echo $data['neck_type_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['neck_type_detail']; ?></div>
			</div>
	<?php if (isset($is_tshirt) && ($is_tshirt)): ?>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบซก/ริบคอชาย</div><div class="pdf-value w-6"><?php echo $data['m_collar_type']; ?></div>
				<div class="pdf-title w-4">แบบซก/ริบคอหญิง</div><div class="pdf-value w-all"><?php echo $data['f_collar_type']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div><div class="pdf-value w-all"><?php echo $data['collar_detail']; ?></div>
			</div>
	<?php elseif (isset($is_jacket) && ($is_jacket)): ?>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบผ้าซับใน</div><div class="pdf-value w-6"><?php echo $data['lining_type_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['lining_type_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบทรงเสื้อชาย</div><div class="pdf-value w-6"><?php echo $data['m_base_shape']; ?></div>
				<div class="pdf-title w-4">แบบทรงเสื้อหญิง</div><div class="pdf-value w-all"><?php echo $data['f_base_shape']; ?></div>
			</div>
	<?php else: ?>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบกุ๊นคอเสื้อ</div><div class="pdf-value w-6"><?php echo $data['neck_hem_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['neck_hem_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบทรงเสื้อชาย</div><div class="pdf-value w-6"><?php echo $data['m_base_shape']; ?></div>
				<div class="pdf-title w-4">แบบทรงเสื้อหญิง</div><div class="pdf-value w-all"><?php echo $data['f_base_shape']; ?></div>
			</div>
	<?php endif; ?>
			<div class="pdf-row">
				<div class="pdf-title w-3">สีผ้าหลัก</div><div class="pdf-value w-6"><?php echo $data['main_color']; ?></div>
				<div class="pdf-title w-4">สีวิ่งเส้น</div><div class="pdf-value w-all"><?php echo $data['line_color']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">สีรอง1</div><div class="pdf-value w-3_5"><?php echo $data['sub_color1']; ?></div>
				<div class="pdf-title w-2">สีรอง2</div><div class="pdf-value w-3_5"><?php echo $data['sub_color2']; ?></div>
				<div class="pdf-title w-2">สีรอง3</div><div class="pdf-value w-all"><?php echo $data['sub_color3']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">เพิ่มกุ๊น</div>
				<div class="pdf-checkbox ml-0_5"><img src="<?php echo ($data['option_hem_rowid'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2" style="margin-left:0;">กุ๊นหน้า-หลัง</div>
				<div class="pdf-checkbox ml-0_5"><img src="<?php echo ($data['option_hem_rowid'] == 2)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2" style="margin-left:0;">กุ๊นเฉพาะหน้า</div>
				<div class="pdf-checkbox ml-0_5"><img src="<?php echo ($data['option_hem_rowid'] == 3)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2" style="margin-left:0;">กุ๊นเฉพาะหลัง</div>
				<div class="pdf-title w-1_5">สีกุ๊น</div><div class="pdf-value w-all"><?php echo $data['option_hem_color']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div><div class="pdf-value w-all"><?php echo $data['color_detail']; ?></div>
			</div>
	<?php if (! (isset($is_tshirt) && ($is_tshirt == TRUE))) : ?>	
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบปก</div><div class="pdf-value w-6"><?php echo $data['collar_name']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div><div class="pdf-value w-all"><?php echo $data['collar_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div><div class="pdf-value w-all"><?php echo $data['collar_detail2']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ทรงสาบเสื้อชาย</div><div class="pdf-value w-6"><?php echo $data['m_clasper_name']; ?></div>
				<div class="pdf-title w-4">ทรงสาบเสื้อหญิง</div><div class="pdf-value w-all"><?php echo $data['f_clasper_name']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบสาบกระดุม</div><div class="pdf-value w-6"><?php echo $data['clasper_pattern_name']; ?></div>
				<div class="pdf-title w-4">กระดุม/สีกระดุม</div><div class="pdf-value w-all"><?php echo $data['clasper_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div><div class="pdf-value w-all"><?php echo $data['clasper_detail2']; ?></div>
			</div>
	<?php endif; ?>
			<div class="pdf-row">
				<div class="pdf-title w-3">แขนเสื้อชาย</div><div class="pdf-value w-6"><?php echo $data['m_sleeves_name']; ?></div>
				<div class="pdf-title w-4">แขนเสื้อหญิง</div><div class="pdf-value w-all"><?php echo $data['f_sleeves_name']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div><div class="pdf-value w-all"><?php echo $data['sleeves_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบชายเสื้อ</div><div class="pdf-value w-6"><?php echo $data['flap_type_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['flap_type_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">กำหนดความยาวเสื้อ</div>
				<div class="pdf-checkbox ml-1_5"><img src="<?php echo ($data['option_is_mfl'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">เสื้อผู้ชาย</div>
				<div class="pdf-title w-3_5">ระบุ (ความยาวนิ้ว)</div><div class="pdf-value w-all"><?php echo $data['option_male_fix_length']; ?></div>				
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div>
				<div class="pdf-checkbox ml-1_5"><img src="<?php echo ($data['option_is_ffl'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">เสื้อผู้หญิง</div>
				<div class="pdf-title w-3_5">ระบุ (ความยาวนิ้ว)</div><div class="pdf-value w-all"><?php echo $data['option_female_fix_length']; ?></div>				
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบผ่าข้าง</div><div class="pdf-value w-6"><?php echo $data['flap_side_ptrn_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['flap_side_ptrn_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ผ่าข้าง</div>
				<div class="pdf-checkbox ml-1_5"><img src="<?php echo ($data['m_flap_side'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">เสื้อผู้ชาย</div>
				<div class="pdf-checkbox ml-3_5"><img src="<?php echo ($data['f_flap_side'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">เสื้อผู้หญิง</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบกระเป๋า</div><div class="pdf-value w-6"><?php echo $data['pocket_type_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['pocket_type_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">กระเป๋า</div>
				<div class="pdf-checkbox ml-1_5"><img src="<?php echo ($data['m_pocket'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">กระเป๋าเสื้อผู้ชาย</div>
				<div class="pdf-checkbox ml-3_5"><img src="<?php echo ($data['f_pocket'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">กระเป๋าเสื้อผู้หญิง</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">แบบที่เสียบปากกา</div><div class="pdf-value w-6"><?php echo $data['pen_pattern_name']; ?></div>
				<div class="pdf-value w-all ml-2"><?php echo $data['pen_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ที่เสียบปากกา</div>
				<div class="pdf-checkbox ml-1_5"><img src="<?php echo ($data['m_pen'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">เสื้อผู้ชาย</div>
				<div class="pdf-checkbox ml-3_5"><img src="<?php echo ($data['f_pen'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">เสื้อผู้หญิง</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ตำแหน่งที่เสียบปากกา</div>
				<div class="pdf-checkbox ml-1_5"><img src="<?php echo ($data['is_pen_pos_left'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">แขนซ้าย</div>
				<div class="pdf-checkbox ml-3_5"><img src="<?php echo ($data['is_pen_pos_right'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-2_5" style="margin-left:0;">แขนขวา</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ข้อมูลพิเศษอื่นๆ</div>
				<div class="pdf-checkbox ml-0_5"><img src="<?php echo ($data['option_is_no_neck_tag'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-4" style="margin-left:0;">ไม่ติดป้ายคอใดๆทั้งสิ้น</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['option_is_customer_size_tag'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-4" style="margin-left:0;">ติดป้ายไซส์ของลูกค้า</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['option_is_no_plmk_size_tag'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-all" style="margin-left:0;">ติดป้ายไซส์ ไม่เอา POLOMAKER</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3"></div>
				<div class="pdf-checkbox ml-0_5"><img src="<?php echo ($data['option_is_no_back_clasper'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-4" style="margin-left:0;">ไม่เอาสาบหลัง</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['option_is_pakaging_tpb'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-4" style="margin-left:0;">พับแพ็คเสื้อใส่ถุงใส</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['option_is_no_packaging_sep_tpb'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-label w-all" style="margin-left:0;">ไม่ต้องพับแพ็ค-แต่ขอถุงเสื้อแยกมา</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">รายละเอียดเพิ่มเติม</div><div class="pdf-value w-all"><?php echo $data['detail_remark1']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-value w-all ml-0_2"><?php echo $data['detail_remark2']; ?></div>
			</div>
			<div class="pdf-row pdf-underline" style="line-height:0.3cm;height:0.3cm;">
			</div>

