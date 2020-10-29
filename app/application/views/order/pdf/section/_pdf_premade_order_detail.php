			<div class="pdf-row w-100p">
				<div class="t-bold pdf-text-center w-100p" style="font-size:14px;height:1cm;line-height:1cm;">รายละเอียด และขนาดเสื้อ</div>
			</div>
			<div class="size-block">
<?php if (isset($data)): ?>
	<?php
		$_INT_DETAILS_SIZE_WIDTH_CM = 12;
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
		$_sizeTotal = 0;
		$_grandTotal = 0;
		$_blnGenHead = TRUE;
		$_eaCm = 0;
		$_strBlankTemplate = '';
		$_dblTotalPrice = 0;
		if (isset($data['detail'])) {
			$_arrTemplate = (! is_array($data['detail']["template"])) ? json_decode($data['detail']['template'], TRUE) : $data['detail']['template'];
			$_arrDtls = (! is_array($data['detail']["data"])) ? json_decode($data['detail']['data'], TRUE) : $data['detail']['data'];
			$_trH1 = '<th class="size-header-title w-1 col-index" rowspan="2">ลำดับที่</th><th class="size-header-title w-1_2 col-code" rowspan="2">รหัสเสื้อ</th><th class="size-header-title w-1_6 col-color" rowspan="2">สี</th>';
			$_trH2 = '';
			$_strSizeTds = '';
			$_totalSizeTds = 0;
			foreach ($_arrTemplate as $_sub_cat_rowid => $_obj) {
				$_arrSizes = (! is_array($_obj["size"])) ? json_decode($_obj["size"], TRUE) : $_obj["size"];
				$_sub_category = $_obj['sub_category'];
				$_sizeCount = 0;
				foreach ($_arrSizes as $_order_size_rowid => $_dtl) {
					$_rowid = (array_key_exists("rowid", $_dtl)) ? (int) $_dtl["rowid"] : -1;
					$_text = (array_key_exists("text", $_dtl)) ? $_dtl["text"] : "-";
					$_chest = (array_key_exists("chest", $_dtl)) ? $_dtl["chest"] : "-";
					$_is_expired = (array_key_exists("is_expired", $_dtl)) ? (int) $_dtl["is_expired"] : 0;
					
					$_class = 'col-order_size';
					if ($_is_expired > 0) $_class .= ' cls-is-expired';
					$_trH2 .= '<th class="'.$_class.'" order_size_rowid="'.$_rowid.'" sub_cat_rowid="'.$_sub_cat_rowid.'" order_size_rowid="'.$_rowid.'" style="width:XXwXXcm;">'.$_text.'</th>';
					$_strSizeTds .= '<td class="'.$_class.'" sub_cat_rowid="'.$_sub_cat_rowid.'" order_size_rowid="'.$_rowid.'"></td>';
					$_sizeCount += 1;
				}
				$_trH1 .= '<th class="col-sub_category" sub_cat_rowid="'.$_sub_cat_rowid.'" colspan="'.$_sizeCount.'">'.$_sub_category.'</th>';
				$_totalSizeTds += $_sizeCount;
			}
			if ($_totalSizeTds > 0) {
				$_eaTdSizeWidth = number_format($_INT_DETAILS_SIZE_WIDTH_CM / $_totalSizeTds, 2);
				$_trH2 = str_replace('XXwXX', $_eaTdSizeWidth, $_trH2);
			}
			if ($_blnDispPrice) {
				$_trH1 .= '<th class="size-header-title w-1_8 col-total_qty" rowspan="2">จำนวนตัว</th><th class="size-header-title w-1_8 col-price_each" rowspan="2">ราคาต่อตัว</th>';
				$_strSizeTds .= '<td class="col-total_qty"></td><td class="col-price_each"></td>';
			} else {
				$_trH1 .= '<th class="size-header-title w-4 col-total_qty" rowspan="2">จำนวนรวม</th>';
				$_strSizeTds .= '<td class="col-total_qty"></td>';
			}
			
			$_countDtl = 0;
			$_sumAmount = 0;
			$_sumQty = 0;
			$_strDetailTds = '';
			if (! is_array($_arrDtls)) $_arrDtls = array();
			foreach ($_arrDtls as $_row) {
				$_countDtl += 1;
				$_code = $_row['code'];
				$_color = $_row['color'];
				$_price = (float) $_row['price'];
				$_arrSize = (! is_array($_row["size"])) ? json_decode($_row['size'], TRUE) : $_row['size'];
				if (! is_array($_arrSize)) $_arrSize = array();

				
				$_strTds = $_strSizeTds;
				$_totalQty = 0;
				foreach ($_arrSize as $_eaSize) {
					$_osrid = (int) $_eaSize['order_size_rowid'];
					$_qty = (int) $_eaSize['qty'];
					
					$_strTds = str_replace('order_size_rowid="'.$_osrid.'">', 'order_size_rowid="'.$_osrid.'">'.$_qty, $_strTds);
					$_totalQty += $_qty;
				}
				$_rowAmount = ($_totalQty * $_price);
				$_sumQty += $_totalQty;
				$_sumAmount += $_rowAmount;
				
				$_strPrice = number_format($_price, 2);
				$_strTds = str_replace('class="col-total_qty">', 'class="col-total_qty">'.$_totalQty, $_strTds);
				$_strTds = str_replace('class="col-price_each">', 'class="col-price_each">'.$_strPrice, $_strTds);
				$_strDetailTds .= <<<EOT
							<tr>
								<td class="col-index">{$_countDtl}.</td>
								<td class="col-code">{$_code}</td>
								<td class="col-color">{$_color}</td>
								{$_strTds}
							</tr>
EOT;
			}
			echo <<<EOT
					<table id="tbl_size_quan">
						<thead>
							<tr class="cls-tr-sub_category">
								{$_trH1}
							</tr>
							<tr class="cls-tr-order-size">
								{$_trH2}
							</tr>
						</thead>
						<tbody>
{$_strDetailTds}
EOT;
			for ($i=($_countDtl+1);$i<21;$i++) {
				echo <<<EOT
							<tr>
								<td class="col-index">{$i}.</td>
								<td class="col-code"></td>
								<td class="col-color"></td>
								{$_strSizeTds}
							</tr>
EOT;
			}
			echo <<<EOT
						</tbody>
					</table>
EOT;
		}
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
