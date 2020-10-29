<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
.pdf-header {width:100%;text-align:center;font-size:14px;font-weight:bold;border:1px solid black;border-top-left-radius:5px;border-top-right-radius:5px;height:0.7cm;line-height:0.7cm;}	
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
div.screen-item {margin:0 0.01cm;text-align:center;font-size:9px;height:100%;}

/*div.screen-title {background-color:lightgray;}*/

div.pdf-sample-img-row {width:100%;display:block;height:5cm;position:relative;padding-left:0.2cm;border-left:1px solid black;border-right:1px solid black;}
div.pdf-sample-img {display:block;float:left;width:7cm;height:5cm;line-height:5cm;border:1px solid black;}
div.pdf-sample-img img {}

.size-block {display:block;position:relative;width:100%;border-left:1px solid black;border-right:1px solid black;height:3.6cm;padding:0.05cm;}
.size-block div {display:block;float:left;margin:0 0.02cm;text-align:center;height:0.5cm;line-height:0.5cm;font-size:10px;padding:0px;}
.size-block div.line-row {margin:0;padding:0;}
.size-block div.size-item {border:0.01cm solid gray;font-size:10px;margin:0 0 0 0.02cm;}
.size-block div.size-item:first-child {margin-left:0;}
.size-block div.size-qty {}
.size-block div.size-prc {}
.size-block div.size-prc div {float:right;margin-right:0.05cm;text-align:right;font-size:9px;}
.size-block div.cls-div-total-cntr {font-weight:bold;display:block;height:2.07cm;box-sizing:border-box;margin:0 0 0 0.02cm;padding:0;}
.size-block div.cls-div-total-cntr div {margin:0;padding:0;}
.size-block div.cls-size-total {display:block;font-size:16px;height:1.55cm;line-height:3.5;vertical-align:middle;background-color:lightgray;}
.size-block div.cls-price-total {margin-top:0.02cm;display:block;font-size:10px;background-color:lightgray;}

.size-block div.line-sum {margin-top:0.1cm;}
.size-block div.sum-others {text-align:left;padding-left:0.1cm;font-size:11px;}
.size-block div.sum-price {text-align:right;padding-right:0.1cm;background-color:lightgray;font-size:11px;}

<?php if (isset($is_tshirt) && ($is_tshirt == TRUE)) : ?>
.pdf-footer {width:98%;text-align:right;font-size:14px;font-weight:bold;padding-right:2%;margin-top:0.1cm;height:auto;line-height:auto}
<?php endif; ?>
		</style>
	</head>
	<body onload="init();">
		<div class="page">
<?php if (isset($data)) : ?>
	<?php if (isset($is_tshirt) && ($is_tshirt == TRUE)) : ?>
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
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_6">วันที่สั่งงาน</div><div class="pdf-value w-2_2"><?php echo $data['order_date']; ?></div>
				<div class="pdf-title w-1_4">กำหนดส่ง</div><div class="pdf-value w-2_2"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-1_8">วันที่ส่งลูกค้า</div><div class="pdf-value w-2_2"><?php echo $data['deliver_date']; ?></div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['is_vat'] > 0)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-1_3">มี Vat</div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-2">มีเสื้อตัวอย่าง</div>
			</div>
	<?php else: ?>
			<div class="pdf-header">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm;height:0.8cm;">
					<?php echo isset($data['pattern_name'])?$data['pattern_name']:''; ?>
				</div>
				<div class="f-left" style="text-align:right;width:8.1cm;padding-right:2cm;">
					<?php echo isset($title)?$title:''; ?>
				</div>
				<div class="f-right pdf-border-left line-row" style="height:0.8cm;">
					<div class="pdf-text-center w-2_3" style="height:0.8cm;">เลขที่ขาย</div><div class="pdf-border-left pdf-text-center w-2_5" style="height:0.8cm;"><?php echo $data['job_number']; ?></div>
				</div>
			</div>
			<div class="pdf-row pr-0" style="clear:both;padding-top:0.1cm;">
				<div class="pdf-title w-1_2">ชื่อลูกค้า</div><div class="pdf-value w-4_7"><?php echo $data['customer_name']; ?></div>
				<div class="pdf-title w-1_3">ชื่อกิจการ</div><div class="pdf-value w-5_8"><?php echo $data['company']; ?></div>
				<div class="ml-0_2 pdf-border-left pdf-border-bottom" style="margin-top:-0.1cm">
					<div class="pdf-checkbox" style="padding-left:2.5cm;"><img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-all">มีเสื้อตัวอย่าง</div>
				</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_6">วันที่สั่งงาน</div><div class="pdf-value w-2_3"><?php echo $data['order_date']; ?></div>
				<div class="pdf-title w-2">ส่งจากโรงงาน</div><div class="pdf-value w-2_3"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-2_2">กำหนดส่งลูกค้า</div><div class="pdf-value w-2_4"><?php echo $data['deliver_date']; ?></div>
				<div class="ml-0_2 pdf-border-left pdf-border-bottom" style="margin-top:-0.05cm">
					<div class="pdf-text-center w-2_3">พนักงานขาย</div><div class="pdf-border-left pdf-text-center w-2_5"><?php echo $data['user_name']; ?></div>
				</div>
			</div>
	<?php endif; ?>
	
	<?php echo isset($head_section)?$head_section:''; ?>
	<?php echo isset($screen_section)?$screen_section:''; ?>
			<div class="pdf-sample-img-row">
				<div class="pdf-sample-img">
	<?php
		set_error_handler(function() { /* ignore errors */ });
		$_mPDF_DPI = 96;
		$_max_w_cm = 7;
		$_max_h_cm = 4.9;
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
				<div class="f-right w-4_2" style="margin-top:5px;">
	<?php if (!(isset($is_tshirt) && ($is_tshirt == TRUE))) : ?>
					<div class="line-row">
						<div class="pdf-checkbox"><img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-all">มีเสื้อตัวอย่าง</div>
					</div>
	<?php endif; ?>
					<div class="ml-0_2 pdf-title pdf-text-center w-3_8 t-bold">หมายเหตุ ข้อความสำคัญ</div>
					<div class="ml-0_2 pdf-value w-3_8"></div>
					<div class="ml-0_2 pdf-value w-3_8"></div>
					<div class="ml-0_2 pdf-value w-3_8"></div>
					<div class="ml-0_2 pdf-value w-3_8"></div>
					<div class="ml-0_2 pdf-value w-3_8"></div>
					<div class="ml-0_2 pdf-value w-3_8"></div>
				</div>
			</div>
	<?php
		$_strSizeQuanTable = isset($size_quan_section) ? $size_quan_section . '<div class="pdf-row" style="line-height:0.4cm;height:0.4cm;"><div class="pdf-tag-separator" style="line-height:0.4cm;height:0.4cm;"></div></div>' : '';
		echo $_strSizeQuanTable;
		echo $_strSizeQuanTable;
	?>
			<div class="pdf-row">
				<div class="pdf-title w-1_2">ผู้รับงาน</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_7">ผู้ตัด</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-3">ราคาตัด x จำนวนตัว</div><div class="pdf-user-input w-2_5"></div>
				<div class="pdf-title w-1_2">วันที่ตัด</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row pdf-underline">
				<div class="pdf-title w-1_3">ชื่อลูกค้า</div><div class="pdf-value w-4"><?php echo $data['customer_name']; ?></div>
				<div class="pdf-checkbox"><img src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>"></div><div class="pdf-title w-2_5">มีเสื้อตัวอย่าง</div>
				<div class="pdf-title w-1_2">ชนิดผ้า</div><div class="pdf-value w-4"><?php echo $data['fabric_name']; ?></div>
				<div class="pdf-title w-1_5">เลขที่ขาย</div><div class="pdf-value w-all"><?php echo $data['job_number']; ?></div>
			</div>
			<div class="pdf-footer">
				<?php echo isset($code)?$code:''; ?>
			</div>
<?php endif; ?>
		</div>
	</body>
</html>