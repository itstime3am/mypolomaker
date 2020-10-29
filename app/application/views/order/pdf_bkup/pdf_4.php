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

.pdf-tag-block {display:block;border-top:1px solid black;border-bottom:1px solid black;height:2.5cm;border-left:1px solid black;border-right:1px solid black;}
.pdf-tag-left {border-right:1px solid black;display:block;float:left;width:3cm;height:2.5cm;}
.pdf-tag-border {border-width:0px;width:3cm;text-align:center;height:1.2cm;line-height:2;}
.pdf-tag-detail {display:block;float:left;height:2.5cm;}
.pdf-tag-row {display:block;padding-left:5px;width:100%;clear:both;height:0.6cm;line-height:0.6cm;padding-top:0.5cm;padding-right:0.2cm;}
.pdf-tag-row div {display:block;float:left;vertical-align:middle;line-height:0.6cm;height:0.6cm;}
		</style>
	</head>
	<body>
		<div class="page">
<?php if (isset($data)) : ?>
			<div class="pdf-header">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm;height:1cm;">
					<?php echo isset($data['pattern_name'])?$data['pattern_name']:''; ?>
				</div>
				<div class="f-left" style="text-align:right;width:8.1cm;padding-right:2.3cm;">
					<?php echo isset($title)?$title:''; ?>
				</div>
				<div class="f-right pdf-border-left line-row" style="height:1cm;line-height:1cm;">
					<div class="pdf-checkbox" style="height:1cm;line-height:1cm;width:0.7cm;padding-left:0.5cm;">
						<img style="padding-top:5px;" src="<?php echo ($data['has_sample'] == 1)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
					</div>
					<div class="pdf-title w-all" style="height:1cm;line-height:1cm;verical-align:bottom;text-align:left;">มีเสื้อตัวอย่าง</div>
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
	<?php 
		echo isset($head_section)?$head_section:'';
		$imgSrc = 'public/images/checkbox_no.png';
		if (isset($data)) $imgSrc = (($data['m_pen'] == 1) || ($data['f_pen'] == 1))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; 
	?>
	<?php for($i=0;$i<5;$i++) : ?>
			<div class="pdf-tag-block">
				<div class="pdf-tag-left">
					<div class="pdf-tag-border pdf-underline">ชื่อช่างเย็บแขน</div>
					<div class="pdf-tag-border">ผู้ตรวจรับงาน/วันที่</div>
				</div>
				<div class="pdf-tag-detail">
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_4">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
						<div class="pdf-title w-1_4">เลขที่ขาย</div><div class="pdf-value w-2_5"><?php echo $data['job_number']; ?></div>
						<div class="pl-1 pdf-checkbox"><img src="<?php echo $imgSrc; ?>"></div><div class="pdf-title w-2_5">ที่เสียบปากกา</div>
					</div>
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_2 pl-1">จำนวน</div><div class="pdf-user-input w-2"></div><div class="pdf-title w-0_6">(ตัว)</div>
						<div class="pdf-title w-2">ราคาต่อหน่วย</div><div class="pdf-user-input w-2_5"></div>
						<div class="pdf-title w-1_8">รวมเป็นเงิน</div><div class="pdf-user-input w-all"></div>
					</div>
				</div>
			</div>
			<div class="pdf-tag-separator"></div>
	<?php endfor; ?>
			<div class="pdf-footer">
				<?php echo isset($code)?$code:''; ?>
			</div>
<?php endif; ?>
		</div>
	</body>
</html>