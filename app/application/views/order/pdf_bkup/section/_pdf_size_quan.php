<?php
	if (! isset($data)) return FALSE;

	$_TABLE_WIDTH = 15.5;
	$_TD_PAD = 0.04;
	$_strSizeQuanTable = '';
	$_sizeTotal = 0;
	
	$_blnDispPrice = FALSE;
	if (isset($code) && (substr(trim($code), -2) == '01')) {
		$_blnDispPrice = TRUE;
	} 
	if (isset($data['size_quan'])) {
//print_r($data['size_quan']);exit;
		$_arrData = $data['size_quan'];
		$_strDummy = '';
		$_dblTotalPrice = 0;
		if (is_array($_arrData)) {
			$_arrRowSize = array('m' => '', 'f' => '', 'c' => '', '-' => '');
			$_arrRowChest = array('m' => '', 'f' => '', 'c' => '', '-' => '');
			$_arrRowQty = array('m' => '', 'f' => '', 'c' => '', '-' => '');
			$_arrRowPrc = array('m' => '', 'f' => '', 'c' => '', '-' => '');
			$_arrCount = array('m' => 0, 'f' => 0, 'c' => 0, '-' => '');
			foreach ($_arrData as $_ea) {
				$_intQty = (int) $_ea['qty'];
				$_arrRowSize[$_ea['type']] .= '<div class="size-item" style="width:XXwXX;">' . $_ea['size'] . '</div>';
				$_arrRowChest[$_ea['type']] .= '<div class="size-item" style="width:XXwXX;">' . $_ea['chest'] . '</div>';
				if ($_intQty <= 0) {
					$_arrRowQty[$_ea['type']] .= '<div class="size-item size-qty" style="width:XXwXX;">-</div>';
				} else {
					$_arrRowQty[$_ea['type']] .= '<div class="size-item size-qty" style="width:XXwXX;">' . $_intQty . '</div>';
				}
				if ($_blnDispPrice) {
					$dblPrc = (double) $_ea['price'];
					if (($dblPrc <= 0) || ($_intQty <= 0)) {
						$_arrRowPrc[$_ea['type']] .= '<div class="size-item size-prc" style="width:XXwXX;">&nbsp;</div>';
					} else {
						$_dblTotalPrice += ($dblPrc * $_intQty);
						$_arrRowPrc[$_ea['type']] .= '<div class="size-item size-prc" style="width:XXwXX;"><div>' . number_format($dblPrc, 2) . '</div></div>';
					}
				}
				$_arrCount[$_ea['type']] += 1;
				$_sizeTotal += $_intQty;
			}
			$_colTotal = $_arrCount['m'] + $_arrCount['f'] + $_arrCount['c'] + $_arrCount['-'];

			$_eaCm = floor(($_TABLE_WIDTH / $_colTotal) * 100) / 100;
			if ($_arrCount['m'] > 0) {
				$_w = (($_eaCm + $_TD_PAD) * $_arrCount['m']);
				$_strDummy = <<<EOT
				<div style="width:{$_w}cm;display:inline-block;">
					<div class="size-item pdf-text-center w-100p t-bold">Size / จำนวนเสื้อผู้ชาย</div>
					<div class="line-row">
						{$_arrRowSize['m']}
					</div>
					<div class="line-row">
						{$_arrRowChest['m']}
					</div>
					<div class="line-row">
						{$_arrRowQty['m']}
					</div>

EOT;
				if ($_blnDispPrice) $_strDummy .= <<<EOT
					<div class="line-row">
						{$_arrRowPrc['m']}
					</div>

EOT;
				$_strDummy .= '				</div>';
			}

			if ($_arrCount['f'] > 0) {
				$_w = (($_eaCm + $_TD_PAD) * $_arrCount['f']);
				$_strDummy .= <<<EOT
				<div style="width:{$_w}cm;display:inline-block;">
					<div class="size-item pdf-text-center w-100p t-bold">Size / จำนวนเสื้อผู้หญิง</div>
					<div class="line-row">
						{$_arrRowSize['f']}
					</div>
					<div class="line-row">
						{$_arrRowChest['f']}
					</div>
					<div class="line-row">
						{$_arrRowQty['f']}
					</div>

EOT;
				if ($_blnDispPrice) $_strDummy .= <<<EOT
					<div class="line-row">
						{$_arrRowPrc['f']}
					</div>

EOT;
				$_strDummy .= '				</div>';
			}

			if ($_arrCount['c'] > 0) {
				$_w = (($_eaCm + $_TD_PAD) * $_arrCount['c']);
				$_strDummy .= <<<EOT
				<div style="width:{$_w}cm;display:inline-block;">
					<div class="size-item pdf-text-center w-100p t-bold">Size / จำนวนเสื้อเด็ก</div>
					<div class="line-row">
						{$_arrRowSize['c']}
					</div>
					<div class="line-row">
						{$_arrRowChest['c']}
					</div>
					<div class="line-row">
						{$_arrRowQty['c']}
					</div>

EOT;
				if ($_blnDispPrice) $_strDummy .= <<<EOT
					<div class="line-row">
						{$_arrRowPrc['c']}
					</div>

EOT;
				$_strDummy .= '				</div>';
			}

			if ($_arrCount['-'] > 0) {
				$_w = (($_eaCm + $_TD_PAD) * $_arrCount['-']);
				$_strDummy .= <<<EOT
				<div style="width:{$_w}cm;display:inline-block;">
					<div class="size-item pdf-text-center w-100p t-bold">Size / จำนวนเสื้อ</div>
					<div class="line-row">
						{$_arrRowSize['-']}
					</div>
					<div class="line-row">
						{$_arrRowChest['-']}
					</div>
					<div class="line-row">
						{$_arrRowQty['-']}
					</div>

EOT;
				if ($_blnDispPrice) $_strDummy .= <<<EOT
					<div class="line-row">
						{$_arrRowPrc['-']}
					</div>

EOT;
				$_strDummy .= '				</div>';
			}
		}

		$_strTotPrice = '&nbsp;';
		if ($_blnDispPrice && ($_dblTotalPrice > 0)) $_strTotPrice = "&#3647;&nbsp;" . number_format($_dblTotalPrice, 2);
		$_others = '&nbsp;';
		$_others_price = '&nbsp;';
		if (isset($data['others']) && (! empty($data['others']))) $_others = $data['others'];
		if (isset($data['others_price']) && (! empty($data['others_price']))) $_others_price = number_format((double) $data['others_price'], 2);
		
		$_strDummy = str_replace("XXwXX", $_eaCm."cm", $_strDummy);
		$_strSizeQuanTable = <<<EOT
			<div class="size-block">
				<div class="w-1">
					<div class="w-1"></div>
					<div class="w-1 t-bold">SIZE</div>
					<div class="w-1 t-bold">รอบอก</div>
					<div class="w-1 t-bold">จำนวน</div>

EOT;
		if ($_blnDispPrice) $_strSizeQuanTable .= <<<EOT
					<div class="w-1 t-bold">ราคา</div>
EOT;
		$_strSizeQuanTable .= <<<EOT
				</div>
				$_strDummy
				<div class="f-r w-2">
					<div class="w-2 pdf-text-center size-item t-bold">จำนวนรวม</div>
					<div id="div_total_cntr" class="cls-div-total-cntr w-2">
						<div id="size_total" class="cls-size-total">$_sizeTotal</div>

EOT;
		if ($_blnDispPrice) $_strSizeQuanTable .= <<<EOT
						<div id="price_total" class="cls-price-total">$_strTotPrice</div>

EOT;
		$_strSizeQuanTable .= <<<EOT
					</div>
				</div>

EOT;
		if ($_blnDispPrice) $_strSizeQuanTable .= <<<EOT
				<div class="line-row line-sum" style="clear:both;width:100%;">
					<div class="w-1_8 pdf-text-right t-bold">อื่นๆ (ระบุ)</div>
					<div class="w-12 size-item pdf-text-right sum-others">$_others</div>
					<div class="w-2 pdf-text-right t-bold">ราคา (บาท)</div>
					<div class="w-3 size-item pdf-text-right sum-price">$_others_price</div>
				</div>

EOT;
		$_strSizeQuanTable .= '</div>';
/*
			<div class="pdf-row">
				<div class="pdf-title w-3">รายละเอียดเพิ่มเติม</div><div class="pdf-value w-all">{$data['remark1']}</div>
			</div>
			<div class="pdf-row">
				<div class="ml-0_2 pdf-value w-all">{$data['remark2']}</div>
			</div>
*/
		echo $_strSizeQuanTable;
	}
?>