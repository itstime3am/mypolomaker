<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
<?php if (!(isset($is_tshirt) && ($is_tshirt == TRUE))) : ?>
.pdf-header {width:100%;text-align:center;font-size:14px;font-weight:bold;border:1px solid black;border-top-left-radius:5px;border-top-right-radius:5px;height:0.6cm;line-height:0.6cm;}
.pdf-row {width:100%;display:block;position:relative;height:0.4cm;padding-right:0.2cm;border-left:1px solid black;border-right:1px solid black;}
.pdf-row div {display:block;float:left;vertical-align:middle;line-height:0.4cm;height:0.4cm;}
.line-row {display:block;position:relative;height:0.4cm;}
.line-row div {display:block;float:left;vertical-align:middle;line-height:0.4cm;height:0.4cm;}
.pdf-title {font-size:10px;}
.pdf-value {font-size:10px;}
.pdf-checkbox {margin-left:0.2cm;margin-right:0px;width:0.6cm;padding-top:0px;margin-top:0px;}

div.screen-row {padding-bottom:0.02cm;}
div.screen-row div {border:1px solid gray;}
div.screen-header {margin:0 0.05cm;text-align:center;font-size:11px;font-weight:bold;padding-top:0.05cm;}
div.screen-item {margin:0 0.05cm;text-align:center;font-size:10px;}

.pdf-footer {width:98%;text-align:right;font-size:14px;font-weight:bold;padding-right:2%;margin-top:0cm;height:auto;line-height:auto;}
<?php endif; ?>
		</style>
	</head>
	<body>
<?php if (isset($data)) : ?>
		<div class="page">
			<div class="pdf-header">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm;height:0.6cm;">
					<?php echo isset($data['pattern_name'])?$data['pattern_name']:''; ?>
				</div>
				<div class="f-left" style="text-align:right;width:7.5cm;padding-right:2.9cm;">
					<?php echo isset($title)?$title:''; ?>
				</div>
				<div class="f-right pdf-border-left line-row" style="height:0.6cm;line-height:0.6cm;">
					<div class="pdf-checkbox" style="width:0.7cm;padding-left:0.5cm;">
						<img style="padding-top:4px;" src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
					</div>
					<div class="pdf-title w-all" style="padding-top:3px;text-align:left;">มีเสื้อตัวอย่าง</div>
				</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_5">ชื่อลูกค้า</div><div class="pdf-value w-6"><?php echo $data['customer_name']; ?></div>
				<div class="pdf-title w-1_5">เลขที่ขาย</div><div class="pdf-value w-4_3"><?php echo $data['job_number']; ?></div>
				<div class="ml-0_2 pdf-border-left pdf-border-bottom w-all">
					<div class="pdf-text-center w-2">ขนาด/จำนวน</div><div class="pdf-border-left w-all"></div>
				</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_5">กำหนดส่ง</div><div class="pdf-value w-4"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-1_8">ลงชื่อช่างตัด</div><div class="pdf-user-input w-6"></div>
				<div class="ml-0_2 pdf-border-left pdf-border-bottom w-all">
					<div class="pdf-text-center w-2">มัดเลขที่</div><div class="pdf-border-left w-all"></div>
				</div>
			</div>
	<?php echo isset($head_section)?$head_section:''; ?>
	<?php echo isset($screen_section)?$screen_section:''; ?>
			<div class="pdf-row">
				<div class="pdf-title w-0_8">QC1:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row pdf-underline">
				<div class="pdf-title w-0_8">QC2:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-footer">
				<?php echo isset($code)?$code:''; ?>
			</div>
			<div class="pdf-tag-separator"></div>
<?php if (isset($is_tshirt) && ($is_tshirt == TRUE)) : ?>
			<div class="pdf-footer"></div>
<?php endif; ?>			
			<div class="pdf-header">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm">
					<?php echo isset($data['pattern_name'])?$data['pattern_name']:''; ?>
				</div>
				<div class="f-left" style="text-align:right;width:7.5cm;padding-right:2.9cm;">
					<?php echo isset($title)?$title:''; ?>
				</div>
				<div class="f-right pdf-border-left line-row">
					<div class="pdf-checkbox" style="width:0.7cm;padding-left:0.5cm;">
						<img style="padding-top:5px;" src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
					</div>
					<div class="pdf-title w-all" style="padding-top:3px;text-align:left;">มีเสื้อตัวอย่าง</div>
				</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_5">ชื่อลูกค้า</div><div class="pdf-value w-6"><?php echo $data['customer_name']; ?></div>
				<div class="pdf-title w-1_5">เลขที่ขาย</div><div class="pdf-value w-4_3"><?php echo $data['job_number']; ?></div>
				<div class="ml-0_2 pdf-border-left pdf-border-bottom w-all">
					<div class="pdf-text-center w-2">ขนาด/จำนวน</div><div class="pdf-border-left w-all"></div>
				</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-1_5">กำหนดส่ง</div><div class="pdf-value w-4"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-1_8">ลงชื่อช่างตัด</div><div class="pdf-user-input w-6"></div>
				<div class="ml-0_2 pdf-border-left pdf-border-bottom w-all">
					<div class="pdf-text-center w-2">มัดเลขที่</div><div class="pdf-border-left w-all"></div>
				</div>
			</div>
	<?php echo isset($head_section)?$head_section:''; ?>
	<?php echo isset($screen_section)?$screen_section:''; ?>
			<div class="pdf-row">
				<div class="pdf-title w-0_8">QC1:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row pdf-underline">
				<div class="pdf-title w-0_8">QC2:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-footer">
				<?php echo isset($code)?$code:''; ?>
			</div>
		</div>
<?php endif; ?>
	</body>
</html>