			<div class="pdf-row">
				<div class="pdf-title w-1_6">แบบหมวก</div><div class="pdf-value w-all"><?php echo $data['cap_type_name']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-all" style="font-weight:bold;padding-top:.2cm;margin-left:.5cm;">สี</div>
				<div style="margin-left:1cm;">
					<div class="pdf-title w-2_7">หลัก (หน้าหมวก)</div><div class="pdf-value w-3"><?php echo $data['color']; ?></div>
					<div class="pdf-title w-2_4">รอง (หลังหมวก)</div><div class="pdf-value w-3"><?php echo $data['color_add1']; ?></div>
					<div class="pdf-title w-1_6">ปีกหมวก</div><div class="pdf-value w-all"><?php echo $data['color_add1']; ?></div>
				</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-all" style="font-weight:bold;padding-top:.2cm;margin-left:.5cm;">เฉพาะหมวกพีชพรีเมียม</div>
				<div style="margin-left:1cm;">
					<div class="pdf-checkbox"><img src="<?php echo ($data['is_sandwich_rim'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div>
					<div class="pdf-title w-8" style="margin-left:0;">มีกุ๊นขอบแซนวิช (เฉพาะหมวกพีชพรีเมียม)</div>
					<div class="pdf-title w-0_8">กุ๊นสี</div><?php hlpr_displayValue($data['sandwich_rim_color'], 30, TRUE, 1); ?>
				</div>
			</div>
			<div class="pdf-row">
				<div style="margin-left:1cm;">
					<div class="pdf-checkbox"><img src="<?php echo ($data['is_air_flow'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div>
					<div class="pdf-title w-8" style="margin-left:0;">เจาะรูระบายอากาศ (เฉพาะหมวกพีชพรีเมียม)</div>
					<div class="pdf-title w-0_8">สี</div><?php hlpr_displayValue($data['air_flow_color'], 30, TRUE, 1); ?>
				</div>
			</div>
			<div class="pdf-row" style="padding-top:.2cm;">
				<div class="pdf-title w-2_2">สีกระดุมหมวก</div><div class="pdf-value w-5"><?php echo $data['cap_button_color']; ?></div>
				<div class="pdf-title w-3_2">แบบเข็มขัดหลังหมวก</div><div class="pdf-value w-all"><?php echo $data['cap_belt_type_name']; ?></div>
			</div>
			<div class="pdf-row" style="padding-top:.2cm;">
				<div class="pdf-title w-2_8">รายละเอียดเพิ่มเติม</div>
				<?php hlpr_displayValue($data['detail_remark1'], 70, TRUE, 1); ?>
			</div>
			<div class="pdf-row">
				<?php hlpr_displayValue($data['detail_remark2'], 85, TRUE, 1); ?>
			</div>
			<div class="size-block" style="clear:both;padding-top:0.5cm;padding-bottom:0.5cm;"> <!-- size-block -->
				<div style="float:right;width:8cm;">
					<div class="line-row">
						<div class="pdf-title w-4" style="line-height:1.6cm;">จำนวนรวม</div>
						<div id="size_total" class="pdf-text-center w-all"><?php echo $data['order_qty']; ?></div>
					</div>
					<div class="line-row">
						<div class="pdf-title w-4">ราคารวม</div>
						<div id="price_total" class="pdf-text-center w-all"><?php echo (isset($data['order_price_sum']) && (! empty($data['order_price_sum']))) ? ("&#3647;&nbsp;" . $data['order_price_sum']) : '&nbsp;'; ?></div>
					</div>
				</div>
				<div class="line-row line-sum" style="clear:both;width:100%;display:inline-block;padding-right:0.03cm;">
					<div class="pdf-title w-2_5 pdf-text-right">อื่นๆ (ระบุ)</div>
					<div class="size-item pdf-text-right sum-others" style="width:8.6cm;"><?php echo (isset($data['others']) && (! empty($data['others']))) ? $data['others'] : '&nbsp;'; ?></div>
					<div class="pdf-title w-4 pdf-text-right">ราคา (บาท)</div>
					<div class="w-all size-item pdf-text-right sum-price"><?php echo (isset($data['others_price']) && (! empty($data['others_price']))) ? ("&#3647;&nbsp;" . number_format((double) $data['others_price'], 2)) : '&nbsp;'; ?></div>
				</div>
			</div>
