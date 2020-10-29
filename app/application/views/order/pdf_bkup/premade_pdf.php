<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
.pdf-row {width:100%;display:block;height:0.5cm;position:relative;padding-right:0.2cm;border-left:1px solid black;border-right:1px solid black;}
.pdf-row div {display:block;float:left;vertical-align:middle;line-height:0.5cm;height:0.5cm;}
.line-row {display:block;position:relative;height:0.5cm;}
.line-row div {display:block;float:left;vertical-align:middle;line-height:0.5cm;height:0.5cm;}
.pdf-value {font-size:10px;}
.pdf-checkbox {margin-left:0.2cm;margin-right:0px;width:0.6cm;padding-top:2px;}
.pdf-checkbox img {}

div.screen-row {padding-bottom:0.02cm;height:0.45cm;}
div.screen-row div {border:1px solid gray;}
div.screen-header {margin:0 0.02cm;text-align:center;font-size:11px;font-weight:bold;padding-top:0.05cm;}
div.screen-item {margin:0 0.02cm;text-align:center;font-size:10px;}

div.pdf-sample-img-row {padding-top:0.1cm;width:100%;display:block;height:6cm;position:relative;padding-left:1cm;border-left:1px solid black;border-right:1px solid black;}
div.pdf-sample-img {display:block;float:left;width:8.02cm;height:5.8cm;line-height:5.8cm;border:1px solid black;padding-left:0.5cm;padding:right:0.5cm;}
div.pdf-sample-img img {}

.size-block {display:block;position:relative;width:100%;border-left:1px solid black;border-right:1px solid black;height:7cm;padding:0.05cm;}
.size-block div {display:block;float:left;margin:0 0.02cm;text-align:center;font-size:10px;padding:0px;}
.size-block div.line-row {margin:0;padding:0;}
.size-block div.size-block-header-row {height:0.5cm;line-height:0.5cm;}
.size-block div.size-header-title {text-align:center;border:0.01cm solid gray;font-size:10px;font-weight:bold;height:0.95cm;padding-top:0.05cm;}
.size-block div.sub-header-row div.size-item {font-size:7px;height:0.45cm;line-height:0.45cm;}
.size-block-detail-row {display:block;position:relative;padding:0;margin:0;}
.size-block-detail-row div {display:block;float:left;text-align:center;vertical-align:middle;font-size:9px;height:0.45cm;line-height:0.45cm;}
.size-block div.size-item {border:0.01cm solid gray;margin:0 0 0 0.02cm;}
.size-block div.size-item:first-child {margin-left:0;}
.size-block div.size-qty {}
.size-qty {}

.size-block div.pdf-title {font-weight:bold;vertical-align:middle;display:block;}

.size-block div.line-sum {margin-top:0.05cm;}
.size-block div.sum-price {font-weight:bold;font-size:10px;background-color:lightgray;text-align:center;}
.size-block div.sum-others {font-size:10px;text-align:left;padding-left:0.22cm;}
.size-block div.sum-others-price {font-weight:bold;font-size:10px;background-color:lightgray;text-align:center;}

		</style>
	</head>
	<body>
		<div class="page">
			<div class="pdf-header" style="height:1.3cm;line-height:1.3cm;">
				<div class="f-left w-11_3" style="text-align:right;padding-right:1.8cm;">
					<?php echo isset($title)?$title:''; ?>
				</div>
				<div class="f-right" style="font-size:12px;font-weight:normal;text-align:left;">
					<div class="line-row">
						<div class="pdf-title w-2_3">เลขที่ขาย</div><div class="pdf-value pdf-text-center w-2_5"><?php echo $data['job_number']; ?></div>
					</div>
					<div class="line-row">
						<div class="pdf-title w-2_3">เลขที่งานอ้างอิง</div><div class="pdf-value pdf-text-center w-2_5"><?php echo $data['ref_number']; ?></div>
					</div>
				</div>
			</div>
<?php echo isset($head_section)?$head_section:''; ?>
			<div class="pdf-row w-100p">
				<div class="t-bold pdf-text-center w-100p">รายละเอียด และขนาดเสื้อ</div>
			</div>
			<div class="size-block">
<?php if (isset($data)) : ?>
	<?php
		$_INT_DETAILS_SIZE_WIDTH_CM = 12.5;
		$_blnDispPrice = FALSE;
		if (isset($code) && (substr(trim($code), -2) == '01')) {
			$_blnDispPrice = TRUE;
			$_INT_DETAILS_SIZE_WIDTH_CM = 11; // to display_price
		}
		
		$_arrRowSize = array();
		$_arrTemplate = array();
		//$_arrRowChest = array();
		$_arrCount = array();
		$_num = 0;
		$_sizeTotal = 0;
		$_grandTotal = 0;
		$_blnGenHead = TRUE;
		$_eaCm = 0;
		$_strBlankTemplate = '';
		$_dblTotalPrice = 0;
		
		if (isset($data['detail'])) {
			foreach ($data['detail'] as $_detail_rowid => $_obj) {
				$_arrData = $_obj['size'];
				$_strDummy = '';
				$_arrRowQty = array();
				$_sizeTotal = 0;
				$_colTotal = 0;
				$_dblEaPrice = isset($_obj['price']) ? (double) $_obj['price'] : 0;
				$_strEaPrice = ($_dblEaPrice > 0) ? number_format($_dblEaPrice, 2) : '-';
				if (is_array($_arrData)) {
					foreach ($_arrData as $_ea) {
						if ($_blnGenHead == TRUE) { 
							if ( ! isset($_arrRowSize[$_ea['type']])) $_arrRowSize[$_ea['type']] = '';
							$_arrRowSize[$_ea['type']] .= '<div class="size-item" style="width:XXwXX;">' . $_ea['size'] . '</div>';
							//$_arrRowChest[$_ea['type']] .= '<div class="size-item" style="width:XXwXX;">' . $_ea['chest'] . '</div>';
							if ( ! isset($_arrCount[$_ea['type']])) $_arrCount[$_ea['type']] = 0;
							$_arrCount[$_ea['type']] += 1;
							$_colTotal += 1;
							if ( ! isset($_arrTemplate[$_ea['type']])) $_arrTemplate[$_ea['type']] = '';
							$_arrTemplate[$_ea['type']] .= '<div class="size-item size-qty" style="width:XXwXX;">&nbsp;</div>';
						}
						if ( ! isset($_arrRowQty[$_ea['type']])) $_arrRowQty[$_ea['type']] = '';
						$_arrRowQty[$_ea['type']] .= '<div class="size-item size-qty" style="width:XXwXX;">' . $_ea['qty'] . '</div>';
						$_sizeTotal += (int) $_ea['qty'];
					}
					if ($_blnGenHead == TRUE) { 
						$_blnGenHead = FALSE;
						$_eaCm = floor(($_INT_DETAILS_SIZE_WIDTH_CM / $_colTotal) * 100) / 100;
						foreach ($_arrRowSize as $_type => $_str) {
							$_w = (($_eaCm + 0.04) * $_arrCount[$_type]);
							$_strDummy .= <<<EOT
				<div style="width:{$_w}cm;border:0;padding:0;margin:0;">
					<div class="size-item pdf-text-center w-100p t-bold">$_type</div>
					<div class="line-row sub-header-row">
						$_str
					</div>
				</div>
EOT;
						}
						$_strDummy = str_replace("XXwXX", $_eaCm."cm", $_strDummy);
						echo <<<EOT
				<div class="size-block-header-row">
					<div class="w-1 size-header-title">ลำดับที่</div>
					<div class="w-1_2 size-header-title">รหัสเสื้อ</div>
					<div class="w-1_8 size-header-title">สี</div>
					$_strDummy
EOT;
						if ($_blnDispPrice) {
							echo <<<EOT
					<div class="w-1_4 size-header-title">จำนวนตัว</div>
					<div class="w-all size-header-title">ราคาต่อตัว</div>
EOT;
						} else {
							echo <<<EOT
					<div class="w-all size-header-title">จำนวนรวม</div>
EOT;
						}
						echo <<<EOT
				</div>
EOT;
					}
					$_num += 1;
					echo <<<EOT
				<div class="size-block-detail-row">
					<div class="w-1 size-item">$_num</div>
					<div class="w-1_2 size-item">{$_obj['code']}</div>
					<div class="w-1_8 size-item size-qty">{$_obj['color']}</div>
EOT;
					foreach ($_arrRowQty as $_type=>$_str) {
						echo str_replace("XXwXX", $_eaCm."cm", $_str);
					}
					if ($_blnDispPrice) {
						echo <<<EOT
					<div class="w-1_4 size-item size-qty">$_sizeTotal</div>
					<div class="w-all size-item">$_strEaPrice</div>
EOT;
					} else {
						echo <<<EOT
					<div class="w-all size-item size-qty">$_sizeTotal</div>
EOT;
					}
					echo <<<EOT
				</div>
EOT;
					$_grandTotal += $_sizeTotal;
					unset($_arrRowQty);
				}
			}
		}
		foreach ($_arrTemplate as $_type=>$_str) {
			$_strBlankTemplate .= str_replace("XXwXX", $_eaCm."cm", $_str);
		}
		for ($_i = ($_num+1);$_i<=18;$_i++) {
			echo <<<EOT
				<div class="size-block-detail-row">
					<div class="w-1 size-item">$_i</div>
					<div class="w-1_2 size-item">&nbsp;</div>
					<div class="w-1_8 size-item">&nbsp;</div>
					$_strBlankTemplate
EOT;
			if ($_blnDispPrice) {
				echo <<<EOT
					<div class="w-1_4 size-item size-qty">&nbsp;</div>
					<div class="w-all size-item">&nbsp;</div>
EOT;
			} else {
				echo <<<EOT
					<div class="w-all size-item size-qty">&nbsp;</div>
EOT;
			}
			echo <<<EOT
				</div>
EOT;
		}
/*
				<div class="line-row line-sum" style="clear:both;width:100%;display:inline-block;padding-right:0.03cm;">
					<div class="pdf-title pdf-text-left" style="width:3.8cm;">ราคาเฉพาะเสื้อทั้งหมด</div>
					<div class="size-item pdf-text-center sum-price" style="width:5cm;"><?php echo (isset($data['total_price']) && (! empty($data['total_price']))) ? ("&#3647;&nbsp;" . $data['total_price']) : '&nbsp;'; ?></div>
					<div class="pdf-title" style="width:6.5cm;text-align:right;padding-right:0.5cm;">รวมทั้งหมด</div>
					<div class="size-item size-qty pdf-text-center w-all"><?php echo $_grandTotal; ?></div>
				</div>
*/

/*
				<div class="line-row line-sum" style="clear:both;width:100%;display:inline-block;padding-right:0.03cm;">
					<div class="pdf-title" style="width:15.4cm;text-align:right;padding-right:0.5cm;">รวมทั้งหมด</div>
	<?php if ($_blnDispPrice): ?>
					<div class="size-item size-qty sum-price pdf-text-center w-1_4"><?php echo $_grandTotal; ?></div>
					<div class="size-item sum-price pdf-text-center w-all"><?php echo (isset($data['total_price']) && (! empty($data['total_price']))) ? ("&#3647;" . $data['total_price']) : '&nbsp;'; ?></div>
	<?php else: ?>
					<div class="size-item size-qty sum-price pdf-text-center w-all"><?php echo $_grandTotal; ?></div>
	<?php endif; ?>
				</div>
*/
	?>
				<div class="line-row line-sum" style="clear:both;width:100%;display:inline-block;padding-right:0.03cm;">
	<?php if ($_blnDispPrice): ?>
					<div class="pdf-title pdf-text-left" style="width:3.8cm;">ราคาเฉพาะเสื้อทั้งหมด</div>
					<div class="size-item pdf-text-center sum-price" style="width:5cm;"><?php echo (isset($data['total_price']) && (! empty($data['total_price']))) ? ("&#3647;&nbsp;" . $data['total_price']) : '&nbsp;'; ?></div>
	<?php else: ?>
					<div class="pdf-title" style="width:9cm;">&nbsp;</div>
	<?php endif; ?>
					<div class="pdf-title" style="width:6.5cm;text-align:right;padding-right:0.5cm;">รวมทั้งหมด</div>
					<div class="size-item size-qty pdf-text-center w-all"><?php echo $_grandTotal; ?></div>
				</div>
	<?php if ($_blnDispPrice): ?>
				<div class="line-row line-sum" style="clear:both;width:100%;display:inline-block;padding-right:0.03cm;">
					<div class="pdf-title w-2_5">อื่นๆ (ระบุ)</div>
					<div class="size-item pdf-text-right sum-others" style="width:8.6cm;"><?php echo (isset($data['others']) && (! empty($data['others']))) ? $data['others'] : '&nbsp;'; ?></div>
					<div class="pdf-title w-4 pdf-text-right" style="text-align:right;padding-right:0.5cm;">ราคา (บาท)</div>
					<div class="w-all size-item sum-others-price"><?php echo (isset($data['others_price']) && (! empty($data['others_price']))) ? ("&#3647;&nbsp;" . number_format((double) $data['others_price'], 2)) : '&nbsp;'; ?></div>
				</div>
	<?php endif; ?>
			</div>
	<?php echo isset($screen_section)?$screen_section:''; ?>
			<div class="pdf-sample-img-row">
				<div class="ml-0_5 pdf-sample-img">
	<?php
		set_error_handler(function() { /* ignore errors */ });
		$_mPDF_DPI = 96;
		$_max_w_cm = 8;
		$_max_h_cm = 5.8;
		try {
			if ($data['file_image1']) {
				$_size = getimagesize('uploads/' . $data['file_image1']);
				$_W = $_size[0] * 2.54 / $_mPDF_DPI;
				$_H = $_size[1] * 2.54 / $_mPDF_DPI;
				$_ratio = 0;
				if ($_W > $_max_w_cm) {
					$_ratio = $_max_w_cm / $_W;
					$_W = $_max_w_cm;
					$_H = $_H * $_ratio;
				}
				if ($_H > $_max_h_cm) {
					$_ratio = $_max_h_cm / $_H;
					$_H = $_max_h_cm;
					$_W = $_W * $_ratio;
				}
				$_LM = ($_max_w_cm - $_W) / 2;
				$_TM = ($_max_h_cm - $_H) / 2;
				echo '<img src="uploads/'.$data['file_image1'].'" style="height:'.$_H.'cm;width:'.$_W.'cm;margin-top:'.$_TM.'cm;margin-left:'.$_LM.'cm">';
			}
		} catch (Exception $e) {
			//echo 'Error:' . $e->getMessage();
		}
	?>
				</div>
				<div class="pdf-sample-img">
	<?php
		try {
			if ($data['file_image2']) {
				$_size = getimagesize('uploads/' . $data['file_image2']);
				$_W = $_size[0] * 2.54 / $_mPDF_DPI;
				$_H = $_size[1] * 2.54 / $_mPDF_DPI;
				$_ratio = 0;
				if ($_W > $_max_w_cm) {
					$_ratio = $_max_w_cm / $_W;
					$_W = $_max_w_cm;
					$_H = $_H * $_ratio;
				}
				if ($_H > $_max_h_cm) {
					$_ratio = $_max_h_cm / $_H;
					$_H = $_max_h_cm;
					$_W = $_W * $_ratio;
				}
				$_LM = ($_max_w_cm - $_W) / 2;
				$_TM = ($_max_h_cm - $_H) / 2;
				echo '<img src="uploads/'.$data['file_image2'].'" style="height:'.$_H.'cm;width:'.$_W.'cm;margin-top:'.$_TM.'cm;margin-left:'.$_LM.'cm">';
			}
		} catch (Exception $e) {
			//echo 'Error:' . $e->getMessage();
		}
	?>
				</div>
			</div>
			<div class="pdf-row" style="claer:both;">
				<div class="pdf-title w-1_4">หมายเหตุ</div><div class="pdf-value w-all"><?php echo $data['remark1']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="ml-0_2 pdf-value w-all"><?php echo $data['remark2']; ?></div>
			</div>
			<div class="pdf-row">
			</div>
			<div class="pdf-row pdf-underline">
				<div style="float:right;width:10cm;">
					<div class="pdf-title w-2">พนักงานขาย</div><div class="pdf-text-center w-2_5"><?php echo $data['user_name']; ?></div>
					<div class="pdf-text-center w-2">วันที่สั่งซื้อ</div><div class="pdf-text-center w-2_5"><?php echo $data['order_date']; ?></div>
				</div>
			</div>
<?php endif; ?>
		</div>
	</body>
</html>