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

div.pdf-sample-img-main-row {padding-top:0.1cm;width:100%;display:block;height:7.8cm;position:relative;border-left:1px solid black;border-right:1px solid black;}
div.pdf-sample-img-main {display:block;;height:7.6cm;line-height:7.6cm;margin:0 auto;border:1px solid black;padding-left:0.5cm;padding-right:0.5cm;background-clip:padding-box;background-position:center;background-repeat:no-repeat;background-size:contain;background-origin:border-box;}
div.pdf-sample-img-sub-row {padding-top:0.1cm;width:100%;display:inline-block;height:5.2cm;position:relative;border-left:1px solid black;border-right:1px solid black;}
div.pdf-sample-img-sub {display:inline-block;float:left;width:6.1cm;height:5cm;line-height:5cm;border:1px solid black;padding-left:0.2cm;padding-right:0.1cm;background-clip:padding-box;background-position:center;background-repeat:no-repeat;background-size:contain;background-origin:border-box;}

div.screen-row {padding-right:0;padding-bottom:0;box-sizing:border-box;}
div.screen-row div {border:1px solid gray;height:0.5cm;}
div.screen-header {margin:0 0.01cm;text-align:center;font-size:10px;font-weight:bold;padding-top:0.05cm;}
div.screen-item {margin:0 0.01cm;text-align:center;font-size:9px;height:100%;}

.pdf-tag-row {display:inline-block;padding-left:0.1cm;clear:both;}
.pdf-tag-row div {display:block;float:left;vertical-align:middle;height:1cm;line-height:1.1cm;}
		</style>
	</head>
	<body>
	<div class="page">
<?php if (isset($data)) : ?>
<?php
	$_currTime = date('d/m/Y h:i:sa');
	$_strHasSample = ($data['has_sample'] == 1) ? 'public/images/checkbox_yes.png' : 'public/images/checkbox_no.png';
	$images_section = isset($images_section)?$images_section:'';
	$_sectionHTML = <<<HTML
			<div class="pdf-header" style="height:1.1cm;line-height:1.1cm;">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm;height:1.1cm;">
					<!--{$data['pattern_name']}-->
				</div>
				<div class="f-left" style="text-align:right;width:7.8cm;padding-right:2.8cm;">
					{$title}
				</div>
				<div class="f-right" style="font-size:12px;font-weight:normal;text-align:left;">
					<div class="line-row">
						<div class="pdf-title w-2_3">เลขที่ขาย</div><div class="pdf-value pdf-text-center w-all">{$data['job_number']}</div>
					</div>
					<div class="line-row">
						<div class="w-1_5"></div>
						<div class="pdf-checkbox"><img src="{$_strHasSample}"></div><div class="pdf-title w-2" style="margin-left:0;">มีเสื้อตัวอย่าง</div>
					</div>
				</div>
			</div>
			<div class="pdf-row" style="clear:both;padding-top:0cm;">
				<div class="pdf-title w-3">ชื่อลูกค้า</div><div class="pdf-value w-9_5">{$data['customer_name']}</div>
				<div class="pdf-title w-2 pdf-border-left">ขนาด/จำนวน</div><div class="pdf-value w-all"></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ชื่อกิจการ</div><div class="pdf-value w-9_5">{$data['company']}</div>
				<div class="pdf-title w-2 pdf-border-left">มัดเลขที่</div><div class="pdf-user-input w-all"></div>				
			</div>
			<div class="pdf-row pdf-underline">
				<div class="pdf-title w-3">วันที่สั่งงาน</div><div class="pdf-value w-3_5">{$data['order_date']}</div>
				<div class="pdf-title w-2_3">กำหนดส่ง</div><div class="pdf-value w-3_5">{$data['due_date']}</div>
				<div class="pdf-title w-2 pdf-border-left" style="padding-bottom:0.4cm;">ลงชื่อช่างตัด</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row" style="height:0.5cm;"></div>
{$detail_section}
			<div class="pdf-row"></div>
{$screen_section}
			<div class="pdf-row" style="height:0.5cm;"></div>
			<div class="pdf-row pdf-tag-row">
				<div class="pdf-title w-0_8">QC1:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row pdf-tag-row">
				<div class="pdf-title w-0_8">QC2:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row" style="height:0.5cm;"></div>
			<div class="pdf-row pdf-underline">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร {$_currTime}</div>
				<div class="pdf-title w-all">{$code}</div>
			</div>

HTML;
?>
			<?php echo $_sectionHTML; ?>
			<div class="line-row" style="text-align:right;">
				<div class="pdf-title w-all">(หน้าที่ 1)</div>
			</div>
		</div>
		<pagebreak>
		
<!-------------------------------------------------------------  new page  -------------------------------------------------------------->

		<div class="page">
			<?php echo $_sectionHTML; ?>
			<div class="line-row" style="text-align:right;">
				<div class="pdf-title w-all">(หน้าที่ 2)</div>
			</div>
		</div>
<?php endif; ?>
	</body>
</html>