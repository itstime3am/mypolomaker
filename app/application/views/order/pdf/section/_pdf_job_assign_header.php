			<div class="pdf-header" style="">
				<div class="f-left div-head-left" style="">
					<?php echo isset($data['pattern_name'])?$data['pattern_name']:''; ?>
				</div>
				<div class="f-left div-head-mid" style="">
					<?php echo isset($title)?$title:''; ?>
				</div>
				<div class="f-right line-row div-head-right" style="">
					<div class="line-row">
						<div class="w-2">เลขที่ขาย</div><div class="pdf-value pdf-text-center w-all"><?php echo $data['job_number']; ?></div>
					</div>
					<div class="line-row">
						<div class="pdf-checkbox">
							<img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
						</div>
						<div class="w-all" style="verical-align:bottom;text-align:left;">มีเสื้อตัวอย่าง</div>
					</div>
				</div>
			</div>
			<div class="pdf-row" style="padding-right:0;">
				<div class="pdf-title w-3_5">ชื่อลูกค้า</div><div class="pdf-value w-9"><?php echo $data['customer_name']; ?></div>
				<div class="ml-0_2 pdf-border-left w-6" style="float:right;">
					<div class="pdf-text-center w-2">ขนาด/จำนวน</div><div class="pdf-value pdf-text-center w-all">&nbsp;</div>
				</div>
			</div>
			<div class="pdf-row" style="padding-right:0;">
				<div class="pdf-title w-3_5">ชื่อกิจการ</div><div class="pdf-value w-9"><?php echo $data['company']; ?></div>
				<div class="ml-0_2 pdf-border-left w-6" style="float:right;">
					<div class="pdf-text-center w-2">มัดเลขที่</div><div class="pdf-value pdf-text-center w-all">&nbsp;</div>
				</div>
			</div>
			<div class="pdf-row" style="padding-right:0;">
				<div class="pdf-title w-3_5">วันที่สั่งงาน</div><div class="pdf-value w-3_5 pdf-text-center"><?php echo $data['order_date']; ?></div>
				<div class="pdf-title w-2">กำหนดส่ง</div><div class="pdf-value w-3_5 pdf-text-center"><?php echo $data['due_date']; ?></div>
				<div class="ml-0_2 pdf-border-left w-6" style="float:right;">
					<div class="pdf-text-center w-2">ลงชื่อช่างตัด</div><div class="pdf-value pdf-text-center w-all">&nbsp;</div>
				</div>
			</div>
			<div class="pdf-row pdf-underline" style="height:0.2cm;padding-right:0;">
				<div class="ml-0_2 pdf-border-left w-6" style="height:0.2cm;float:right;"></div>
			</div>