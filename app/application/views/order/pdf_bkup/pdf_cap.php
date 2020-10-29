<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
.pdf-row {width:100%;display:block;position:relative;height:0.5cm;padding-right:0.2cm;border-left:1px solid black;border-right:1px solid black;}
.pdf-row div {display:block;float:left;vertical-align:middle;line-height:0.5cm;height:0.5cm;}
.line-row {display:block;position:relative;height:0.5cm;}
.line-row div {display:block;float:left;vertical-align:middle;line-height:0.5cm;height:0.5cm;}
.pdf-value {font-size:10px;}
.pdf-checkbox {margin-left:0.2cm;margin-right:0px;width:0.6cm;padding-top:2px;}
.pdf-checkbox img {}

div.screen-row {padding-right:0;padding-bottom:0;box-sizing:border-box;}
div.screen-row div {border:1px solid gray;height:0.6cm;}
div.screen-header {margin:0 0.01cm;text-align:center;font-size:10px;font-weight:bold;padding-top:0.05cm;}
div.screen-item {margin:0 0.01cm;text-align:center;font-size:10px;height:100%;}

div.pdf-sample-img-row {padding-top:0.1cm;width:100%;display:block;height:6cm;position:relative;padding-left:1cm;border-left:1px solid black;border-right:1px solid black;}
div.pdf-sample-img {display:block;float:left;width:8.52cm;height:5.7cm;line-height:5.8cm;border:1px solid black;padding-left:0.5cm;padding:right:0.5cm;}
div.pdf-sample-img img {}

.size-block {display:block;position:relative;width:100%;border-left:1px solid black;border-right:1px solid black;height:3.6cm;padding:0.02cm;}
.size-block div {display:block;float:left;margin:0 0.02cm;text-align:center; height:0.5cm;line-height:0.5cm;vertical-align:center;font-size:10px;padding:0px;}
.size-block div.line-row {margin:0;padding:0;}
.size-block div.size-item {border:0.01cm solid gray;font-size:10px;margin:0 0 0 0.02cm;}
.size-block div.size-item:first-child {margin-left:0;}
.size-block div.size-qty {}
.size-block div#size_total {font-weight:bold;font-size:16px;vertical-align:middle;background-color:lightgray;display:block; height:1.56cm;line-height:3.5;}
.size-block div#price_total {font-weight:bold;font-size:16px;background-color:lightgray;display:block;}

.size-block div.pdf-title {font-weight:bold;font-size:16px;vertical-align:middle;display:block;}

.size-block div.line-sum {margin-top:0.05cm;}
.size-block div.sum-others {font-size:14px;text-align:left;padding-left:0.22cm;}
.size-block div.sum-price {font-weight:bold;font-size:16px;background-color:lightgray;text-align:center;}

		</style>
	</head>
	<body>
		<div class="page">
<?php if (isset($data)) : ?>
			<div class="pdf-header" style="height:1.1cm;line-height:1.1cm;">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm;height:1.1cm;">
					<?php echo isset($data['pattern_name'])?$data['pattern_name']:''; ?>
				</div>
				<div class="f-left" style="text-align:right;width:7.8cm;padding-right:2.8cm;">
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
			<div class="pdf-row" style="clear:both;padding-top:0.1cm;">
				<div class="pdf-title w-1_3">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
				<div class="pdf-title w-1_4">ชื่อกิจการ</div><div class="pdf-value w-all"><?php echo $data['company']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-0_6">ที่อยู่</div>
				<?php hlpr_displayValue($data['address'], 180, TRUE, 2, 'w-all', 10, 'margin-left:1cm;'); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_3">เบอร์โทร</div><div class="pdf-value w-4"><?php echo $data['contact_no']; ?></div>
				<div class="pdf-title w-2">เลขที่ผู้เสียภาษี</div><div class="pdf-value w-4"><?php echo $data['tax_id']; ?></div>
				<div class="pdf-title w-0_8">สาขา</div><div class="pdf-value w-3_5"><?php echo $data['tax_branch']; ?></div>
				<div class="pdf-value ml-0_5 w-all pdf-text-center"><?php echo $data['disp_vat_type']; ?></div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_6">วันที่สั่งงาน</div><div class="pdf-value w-2_2"><?php echo $data['disp_order_date']; ?></div>
				<div class="pdf-title w-1_4">กำหนดส่ง</div><div class="pdf-value w-2_2"><?php echo $data['disp_due_date']; ?></div>
				<div class="pdf-title w-1_8">วันที่ส่งลูกค้า</div><div class="pdf-value w-2_2"><?php echo $data['disp_deliver_date']; ?></div>
				<div class="pdf-checkbox pl-0_2"><img src="<?php echo ($data['is_tax_inv_req'] > 0)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-2_3" style="margin-left:0;">ขอใบกำกับภาษี</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-all" style="margin-left:0;">มีหมวกตัวอย่าง</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-text-center f-none w-all" style="font-weight:bold;">รายละเอียด</div>
			</div>
	<?php echo isset($head_section)?$head_section:''; ?>
	<?php echo isset($size_quan_section)?$size_quan_section:''; ?>
	<?php echo isset($screen_section)?$screen_section:''; ?>
			<div class="pdf-sample-img-row">
				<div class="pdf-sample-img">
	<?php
		set_error_handler(function() { /* ignore errors */ });
		$_mPDF_DPI = 96;
		$_max_w_cm = 8.5;
		$_max_h_cm = 5.6;
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
			//do nothing
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
			//do nothing
		}

	?>
				</div>
			</div>
			<div class="pdf-row" style="claer:both;">
				<div class="pdf-title w-1_3">หมายเหตุ</div>
				<?php hlpr_displayValue($data['remark1'], 80, TRUE, 1); ?>
				<!--div class="pdf-value w-all"><?php //echo $data['remark1']; ?></div-->
			</div>
			<div class="pdf-row">
				<?php hlpr_displayValue($data['remark2'], 85, TRUE, 1); ?>
				<!--div class="ml-0_2 pdf-value w-all"><?php //echo $data['remark2']; ?></div-->
			</div>
	<?php if (( ! isset($is_tshirt)) || ($is_tshirt == FALSE)) : ?>
			<div class="pdf-row">
			</div>
	<?php endif; ?>
			<div class="pdf-row pdf-underline">
				<div style="float:right;width:10cm;">
					<div class="pdf-title w-2">ผู้สั่งงาน</div><div class="pdf-text-center w-2_5"><?php echo $data['user_name']; ?></div>
					<div class="pdf-text-center w-2">วันที่สั่งซื้อ</div><div class="pdf-text-center w-2_5"><?php echo $data['disp_order_date']; ?></div>
				</div>
			</div>
			<div class="pdf-footer">
				<?php echo isset($code)?$code:''; ?>
			</div>
<?php endif; ?>
		</div>
	</body>
</html>