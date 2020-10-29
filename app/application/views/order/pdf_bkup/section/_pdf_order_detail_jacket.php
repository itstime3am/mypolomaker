			<div class="pdf-row">
				<div class="pdf-title w-1_2">แบบเสื้อ</div><div class="pdf-value w-5"><?php echo $data['standard_pattern_name']; ?></div>
				<div class="pdf-title w-1_2">ชนิดผ้า</div><div class="pdf-value w-all"><?php echo $data['fabric_pattern']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_2">สีผ้าหลัก</div><div class="pdf-value w-6"><?php echo $data['color']; ?></div>
				<div class="pdf-title w-1_4">สีผ้าตัดต่อ</div><div class="pdf-value w-all"><?php echo $data['color_add1']; ?></div>
			</div>
			<!--div class="pdf-row">
				<div class="ml-1_7 pdf-value w-all"><?php //echo $data['color_add2']; ?></div>
			</div-->
			<div class="pdf-row">
				<div class="pdf-title w-1_6">แบบปก</div>
				<?php hlpr_displayValue($data['collar_detail'], 70, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_6">แบบแขน</div>
				<?php hlpr_displayValue($data['sleeves_detail'], 70, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_6">แบบสาบ</div>
				<?php hlpr_displayValue($data['clasper_detail'], 70, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_6">แบบกระเป๋า</div>
				<?php hlpr_displayValue($data['pocket_detail'], 70, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2">ลักษณะพิเศษ</div>
				<?php hlpr_displayValue($data['special_detail'], 70, TRUE, 2); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2_8">รายละเอียดเพิ่มเติม</div>
				<?php hlpr_displayValue($data['detail_remark1'], 70, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<?php hlpr_displayValue($data['detail_remark2'], 85, TRUE, 1); ?>
			</div>
