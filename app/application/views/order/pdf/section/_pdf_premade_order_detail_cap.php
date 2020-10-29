			<div class="pdf-row w-100p">
				<div class="t-bold pdf-text-center w-100p" style="font-size:14px;height:1cm;line-height:1cm;">รายละเอียด และขนาดเสื้อ</div>
			</div>
			<div class="size-block">
<?php if (isset($data)): ?>
	<?php
		$_INT_DETAILS_SIZE_WIDTH_CM = 12.5;
		$_blnDispPrice = FALSE;
		if (isset($is_show_price) && is_bool($is_show_price)) {
			$_blnDispPrice = $is_show_price;
		} elseif (isset($is_display_price) && is_bool($is_display_price)) {
			$_blnDispPrice = $is_display_price;
		}
		if ($_blnDispPrice == TRUE) {
			$_INT_DETAILS_SIZE_WIDTH_CM = 11; // to display_price
		}

		$_arrRowSize = array();
		$_arrTemplate = array();
		$_arrCount = array();
		$_num = 0;
		$_sumAmount = 0;
		$_sumQty = 0;
		if (isset($data['detail'])) {
			$_trH1 = '<th class="size-header-title w-1 col-index">ลำดับที่</th><th class="size-header-title w-2_5 col-code">รหัส</th><th class="size-header-title w-2_5 col-color">สี</th>';
			if ($_blnDispPrice) {
				$_trH1 .= '<th class="size-header-title w-1_8 col-total_qty">จำนวน</th><th class="size-header-title w-2_5 col-price_each">ราคาต่อหน่วย</th><th class="size-header-title w-3 col-price_sum">รวม</th>';
			} else {
				$_trH1 .= '<th class="size-header-title w-4 col-total_qty">จำนวนรวม</th>';
			}
			$_strTbody = '';
			foreach ($data['detail'] as $_pattern_rowid => $_obj) {
				$_num += 1;
				$_qty = (int) $_obj['sum_qty'];
				$_price = (float) $_obj['price'];
				$_amount = $_qty * $_price;
				$_strTbody .= '<tr><td>'.$_num.'</td><td>'.$_obj["code"].'</td><td>'.$_obj["color"].'</td><td>'.number_format($_qty, 0).'</td>';
				if ($_blnDispPrice) {
					$_strTbody .= '<td>'.number_format($_price, 2).'</td><td>'.number_format($_amount, 2).'</td></tr>'."\n";
					$_sumAmount += $_amount;
				} else {
					$_strTbody .= '</tr>'."\n";
				}
				$_sumQty += $_qty;
			}
		}
		for ($_i = ($_num + 1);$_i<=18;$_i++) {
			$_strTbody .= '<tr><td></td><td></td><td></td><td></td>';
			if ($_blnDispPrice) {
				$_strTbody .= '<td></td><td></td></tr>'."\n";
			} else {
				$_strTbody .= '</tr>'."\n";
			}
		}
		echo <<<EOT
				<table id="tbl_size_quan">
					<thead>
						<tr class="cls-tr-sub_category">
							{$_trH1}
						</tr>
					</thead>
					<tbody>
						{$_strTbody}
					</tbody>
				</table>
EOT;

	?>
					<div class="line-row line-sum" style="clear:both;width:100%;">
						<div class="w-2_2 pl-0_2 t-bold">จำนวนตัวรวม</div>
						<div class="w-1_2 pdf-text-right"><?php echo isset($_sumQty)? number_format($_sumQty): "-"; ?></div>
						<div class="w-0_6 pl-0_2 t-bold">ตัว</div>
						<div class="w-8 ml-0_5"></div>
	<?php if ($_blnDispPrice): ?>
						<div class="w-2 ml-0_1 pdf-text-right t-bold">ราคารวม</div>
						<div class="w-3 ml-0_2 size-item sum-price pdf-text-right"><?php echo isset($_sumAmount)? number_format($_sumAmount, 2): "-"; ?></div>
						<div class="w-0_8 pl-0_2 t-bold">บาท</div>
	<?php endif; ?>
					</div>
	<?php echo isset($others_price_panel) ? $others_price_panel : ''; ?>
<?php endif; ?>
				</div>
