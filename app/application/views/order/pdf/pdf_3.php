<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
.pdf-row {width:100%;display:block;position:relative;height:0.6cm;padding-right:0.2cm;border-left:1px solid black;border-right:1px solid black;}
.pdf-row div {display:block;float:left;vertical-align:middle;line-height:0.6cm;height:0.6cm;}
.line-row {display:block;position:relative;height:0.6cm;}
.line-row div {display:block;float:left;vertical-align:middle;line-height:0.6cm;height:0.6cm;}
.pdf-value {font-size:10px;}
.pdf-checkbox {margin-left:0.2cm;margin-right:0px;width:0.6cm;padding-top:2px;}
.pdf-checkbox img {}

.pdf-tag-block {display:flex;border:1px solid black;}
.pdf-tag-left {border-right:1px solid black;display:block;float:left;width:3cm;padding-bottom:0.3cm;}
.pdf-tag-border {border-width:0px;width:3cm;text-align:center;line-height:2em;height:2em;}
.pdf-tag-detail {display:block;float:left;margin-top:0.4cm;}
.pdf-tag-row {display:inline-block;padding-left:0.1cm;clear:both;}
.pdf-tag-row div {display:block;float:left;vertical-align:middle;height:1cm;line-height:1.1cm;}
		</style>
	</head>
	<body>
		<div class="page">
<?php if (isset($data)) : ?>
<?php
	$_strHasSample = ($data['has_sample'] == 1) ? 'public/images/checkbox_yes.png' : 'public/images/checkbox_no.png';
	$_sectionHeader = <<<HTML
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
HTML;

?>
			<?php echo $_sectionHeader; ?>
			<div class="pdf-row" style="height:0.3cm;"></div>
			
			<?php echo isset($detail_section)?$detail_section:''; ?>
			
			<div class="pdf-row"></div>
			<div class="pdf-row pdf-tag-row">
				<div class="pdf-title w-3">QC1:</div><div class="pdf-user-input w-3_5"></div>
				<div class="pdf-title w-1">วันที่</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
			<div class="pdf-row pdf-tag-row">
				<div class="pdf-title w-3">QC2:</div><div class="pdf-user-input w-3_5"></div>
				<div class="pdf-title w-1">วันที่</div><div class="pdf-user-input w-3"></div>
				<div class="pdf-title w-2">สุ่มวัดรอบอก:</div><div class="pdf-user-input w-2"></div>
				<div class="pdf-title w-2_2">สุ่มวัดความยาว:</div><div class="pdf-user-input w-all"></div>
			</div>
<?php
	$_currTime = date('d/m/Y h:i:sa');
	$_sectionBottom = <<<HTML
			<div class="pdf-row pdf-underline">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร {$_currTime}</div>
				<div class="pdf-title w-all">{$code}</div>
			</div>

HTML;
?>
			<div class="pdf-row" style="line-height:1cm;height:1cm;"></div>
			<?php echo $_sectionBottom; ?>
			<div class="line-row" style="text-align:right;">
				<div class="pdf-title w-all">(หน้าที่ 1)</div>
			</div>
		</div>
		<pagebreak>
		
<!-------------------------------------------------------------  new page  -------------------------------------------------------------->

		<div class="page">
			<?php echo $_sectionHeader; ?>

			<div class="pdf-tag-block">
				<div class="pdf-tag-left">
					<div class="pdf-tag-border">ชื่อช่างผ่าข้าง</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
					<div class="pdf-tag-border">ผู้ตรวจรับงาน/วันที่</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
				</div>
				<div class="pdf-tag-detail">
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_4">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
						<div class="pdf-title w-1_4">เลขที่ขาย</div><div class="pdf-value w-2_5"><?php echo $data['job_number']; ?></div>
						<div class="pdf-title w-1_3">มัดเลขที่</div><div class="pdf-user-input w-all"></div>
					</div>
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_2 pl-1">จำนวน</div><div class="pdf-user-input w-2"></div><div class="pdf-title w-0_6">(ตัว)</div>
						<div class="pdf-title w-2">ราคาต่อหน่วย</div><div class="pdf-user-input w-2_5"></div>
						<div class="pdf-title w-1_8">รวมเป็นเงิน</div><div class="pdf-user-input w-all"></div>
					</div>
				</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร <?php echo $_currTime; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-tag-separator"></div>
			</div>
			<!----------------------------------------------------->

			<div class="pdf-tag-block">
				<div class="pdf-tag-left">
					<div class="pdf-tag-border">ชื่อช่างลา</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
					<div class="pdf-tag-border">ผู้ตรวจรับงาน/วันที่</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
				</div>
				<div class="pdf-tag-detail">
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_4">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
						<div class="pdf-title w-1_4">เลขที่ขาย</div><div class="pdf-value w-2_5"><?php echo $data['job_number']; ?></div>
						<div class="pdf-title w-1_3">มัดเลขที่</div><div class="pdf-user-input w-all"></div>
					</div>
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_2 pl-1">จำนวน</div><div class="pdf-user-input w-2"></div><div class="pdf-title w-0_6">(ตัว)</div>
						<div class="pdf-title w-2">ราคาต่อหน่วย</div><div class="pdf-user-input w-2_5"></div>
						<div class="pdf-title w-1_8">รวมเป็นเงิน</div><div class="pdf-user-input w-all"></div>
					</div>
				</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร <?php echo $_currTime; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-tag-separator"></div>
			</div>
			<!----------------------------------------------------->
			
			<div class="pdf-tag-block">
				<div class="pdf-tag-left">
					<div class="pdf-tag-border">ชื่อช่างโพ้ง</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
					<div class="pdf-tag-border">ผู้ตรวจรับงาน/วันที่</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
				</div>
				<div class="pdf-tag-detail">
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_4">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
						<div class="pdf-title w-1_4">เลขที่ขาย</div><div class="pdf-value w-2_5"><?php echo $data['job_number']; ?></div>
						<div class="pdf-title w-1_3">มัดเลขที่</div><div class="pdf-user-input w-all"></div>
					</div>
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_2 pl-1">จำนวน</div><div class="pdf-user-input w-2"></div><div class="pdf-title w-0_6">(ตัว)</div>
						<div class="pdf-title w-2">ราคาต่อหน่วย</div><div class="pdf-user-input w-2_5"></div>
						<div class="pdf-title w-1_8">รวมเป็นเงิน</div><div class="pdf-user-input w-all"></div>
					</div>
				</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร <?php echo $_currTime; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-tag-separator"></div>
			</div>
			<!----------------------------------------------------->

			<div class="pdf-tag-block">
				<div class="pdf-tag-left">
					<div class="pdf-tag-border">ชื่อช่างเย็บโปโล</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
					<div class="pdf-tag-border">ผู้ตรวจรับงาน/วันที่</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
				</div>
				<div class="pdf-tag-detail">
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_4">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
						<div class="pdf-title w-1_4">เลขที่ขาย</div><div class="pdf-value w-2_5"><?php echo $data['job_number']; ?></div>
						<div class="pdf-title w-1_3">มัดเลขที่</div><div class="pdf-user-input w-all"></div>
					</div>
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_2 pl-1">จำนวน</div><div class="pdf-user-input w-2"></div><div class="pdf-title w-0_6">(ตัว)</div>
						<div class="pdf-title w-2">ราคาต่อหน่วย</div><div class="pdf-user-input w-2_5"></div>
						<div class="pdf-title w-1_8">รวมเป็นเงิน</div><div class="pdf-user-input w-all"></div>
					</div>
				</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร <?php echo $_currTime; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-tag-separator"></div>
			</div>
			<!----------------------------------------------------->
			
			<div class="pdf-tag-block">
				<div class="pdf-tag-left">
					<div class="pdf-tag-border">ชื่อช่างต่อ/แล็บ</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
					<div class="pdf-tag-border">ผู้ตรวจรับงาน/วันที่</div>
					<div class="pdf-tag-border pdf-user-input w-all ml-0_2" style="margin-right:0.2cm;"></div>
				</div>
				<div class="pdf-tag-detail">
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_4">ชื่อลูกค้า</div><div class="pdf-value w-5"><?php echo $data['customer_name']; ?></div>
						<div class="pdf-title w-1_4">เลขที่ขาย</div><div class="pdf-value w-2_5"><?php echo $data['job_number']; ?></div>
						<div class="pdf-title w-1_3">มัดเลขที่</div><div class="pdf-user-input w-all"></div>
					</div>
					<div class="pdf-tag-row">
						<div class="pdf-title w-1_2 pl-1">จำนวน</div><div class="pdf-user-input w-2"></div><div class="pdf-title w-0_6">(ตัว)</div>
						<div class="pdf-title w-2">ราคาต่อหน่วย</div><div class="pdf-user-input w-2_5"></div>
						<div class="pdf-title w-1_8">รวมเป็นเงิน</div><div class="pdf-user-input w-all"></div>
					</div>
				</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-6 ml-0_5">วันที่เอกสาร <?php echo $_currTime; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-tag-separator"></div>
			</div>
			<!----------------------------------------------------->

			<div class="pdf-row" style="line-height:1cm;height:1cm;"></div>
			<?php echo $_sectionBottom; ?>
			<div class="line-row" style="text-align:right;">
				<div class="pdf-title w-all">(หน้าที่ 2)</div>
			</div>
<?php endif; ?>
		</div>
	</body>
</html>