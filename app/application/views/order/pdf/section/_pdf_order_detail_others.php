			<div class="pdf-row">
				<div class="pdf-title w-3">ประเภทสินค้า</div><div class="pdf-value w-6"><?php echo $data['product_type']; ?></div>
				<div class="pdf-title w-3">ชนิดผ้า</div><div class="pdf-value w-all"><?php echo $data['fabric_type']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">สีผ้าหลัก</div><div class="pdf-value w-3_5"><?php echo $data['main_color']; ?></div>
				<div class="pdf-title w-2">สีผ้าตัดต่อ</div><div class="pdf-value w-3_5"><?php echo $data['sub_color1']; ?></div>
				<div class="pdf-title w-2">สีเพิ่มเติม</div><div class="pdf-value w-all"><?php echo $data['color_detail']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2">ทรง</div><div class="pdf-value w-all"><?php echo $data['pattern']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2_5">ลักษณะพิเศษ</div><div class="pdf-value w-all"><?php echo $data['detail1']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">รายละเอียดเพิ่มเติม</div><div class="pdf-value w-all"><?php echo $data['detail2']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-value w-all ml-0_2"><?php echo $data['detail_remark1']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-value w-all ml-0_2"><?php echo $data['detail_remark2']; ?></div>
			</div>
			<div class="pdf-row pdf-underline" style="line-height:0.3cm;height:0.3cm;"></div>
