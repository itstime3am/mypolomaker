<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
.pdf-row {width:100%;display:block;position:relative;height:0.7cm;padding-top:0.1cm;padding-right:0.2cm;border-left:1px solid black;border-right:1px solid black;}
.pdf-row div {display:block;float:left;vertical-align:middle;line-height:0.6cm;height:0.6cm;}
.line-row {display:block;position:relative;height:0.7cm;padding-top:0.1cm;}
.line-row div {display:block;float:left;vertical-align:middle;line-height:0.6cm;height:0.6cm;}
.pdf-value {font-size:10px;}

.pdf-header { height:1.5cm;line-height:1.5cm; }

.cls-page-2 .pdf-header { height:1.1cm;line-height:1.1cm; }
.cls-page-2 .line-row { height:0.5cm;padding-top:0; }
.cls-page-2 .line-row div { height:0.5cm;line-height:0.5cm; }
.cls-page-2 .pdf-row { height:0.5cm;padding-top:0; }
.cls-page-2 .pdf-row div { height:0.5cm;line-height:0.5cm; }

.size-block {display:block;position:relative;width:100%;border-left:1px solid black;border-right:1px solid black;height:8cm;padding:0.05cm 0;}
.size-block div {display:block;float:left;margin:0 0.02cm;line-height:0.6cm;height:0.6cm;text-align:center;font-size:10px;}
.size-block div.line-row {margin:0;height:0.7cm;padding-top:0;}
.size-block div.size-item {border:0.01cm solid gray;font-size:10px;padding:0;}
.size-block div.line-row div.size-item { margin:0.1cm 0 0 0.02cm; }
.size-block div.size-item:first-child { margin-left:0; }
.size-block div.size-qty {}
.size-block div.size-prc {}
.size-block div.size-prc {text-align:center;font-size:8px;}
.size-block div.cls-div-total-cntr {font-weight:bold;display:block;height:2.01cm;box-sizing:border-box;margin:0.1cm 0 0 0.02cm;}
.size-block div.cls-div-total-cntr div {padding:0;width:100%;}
.size-block div.cls-size-total {display:block;font-size:16px;height:2.01cm;line-height:2cm;vertical-align:middle;background-color:lightgray;}
.size-block div.cls-price-total {margin-top:0.1cm;display:block;font-size:10px;background-color:lightgray;}

.size-block div.line-row.line-sum { padding-top:0.1cm; }
.size-block div.line-row.line-sum div.size-item { margin-top:0; }
.size-block div.line-row:last-child { margin-bottom: 0.5cm; }

.size-block div.sum-others {text-align:left;padding-left:0.1cm;font-size:11px;}
.size-block div.sum-price {text-align:right;padding-right:0.1cm;background-color:lightgray;font-size:11px;}

div.screen-row {padding-right:0;padding-bottom:0;box-sizing:border-box;height:0.55cm;padding-top:0;}
div.screen-row div {border:1px solid gray;height:0.5cm;}
div.screen-header {margin:0 0.01cm;text-align:center;font-size:11px;font-weight:bold;height:0.55cm;}
div.screen-item {margin:0 0.01cm;text-align:center;font-size:9px;height:100%;}

/* div.screen-title {background-color:lightgray;} */

div.pdf-sample-img-main-row {padding-top:0.1cm;width:100%;display:block;height:7.8cm;position:relative;border-left:1px solid black;border-right:1px solid black;}
div.pdf-sample-img-main {display:block;;height:7.6cm;line-height:7.6cm;margin:0 auto;border:1px solid black;padding-left:0.5cm;padding-right:0.5cm;background-clip:padding-box;background-position:center;background-repeat:no-repeat;background-size:contain;background-origin:border-box;}
div.pdf-sample-img-sub-row {padding-top:0.1cm;width:100%;display:inline-block;height:5.2cm;position:relative;border-left:1px solid black;border-right:1px solid black;}

/* div.pdf-sample-img-sub {display:inline-block;float:left;width:4.5cm;height:5cm;line-height:5cm;border:1px solid black;padding-left:0.2cm;padding-right:0.1cm;background-clip:padding-box;background-position:center;background-repeat:no-repeat;background-size:contain;background-origin:border-box;} */
div.pdf-sample-img-sub {display:inline-block;float:left;width:6.1cm;height:5cm;line-height:5cm;border:1px solid black;padding-left:0.2cm;padding-right:0.1cm;background-clip:padding-box;background-position:center;background-repeat:no-repeat;background-size:contain;background-origin:border-box;}
		</style>
	</head>
	<body>
		<div class="page">
<?php if (isset($data)) : ?>
<?php
	$_strHasSample = (isset($data['has_sample']) && ($data['has_sample'] == 1)) ? 'public/images/checkbox_yes.png' : 'public/images/checkbox_no.png';
	$_strIsTaxInvReq = (isset($data['is_tax_inv_req']) && ($data['is_tax_inv_req'] > 0)) ? 'public/images/checkbox_yes.png' : 'public/images/checkbox_no.png';
	$_sectionHeader = <<<HTML
			<div class="pdf-header">
				<div class="f-left" style="text-align:left;width:3cm;padding-left:0.5cm;"><!-- --></div>
				<div class="f-left" style="text-align:left;width:16cm;padding-left:0.5cm;">{$title}</div>
				<div class="f-right" style="font-size:12px;font-weight:normal;text-align:left;">
					<div class="line-row">
						<div class="pdf-title w-2_3">เลขที่ขาย</div><div class="pdf-value pdf-text-center w-all">{$data['job_number']}</div>
					</div>
					<div class="line-row">
						<div class="pdf-title w-2_4">เลขที่ใบเสนอราคา</div><div class="pdf-value pdf-text-center w-all">{$data['quotation_number']}</div>
					</div>
				</div>
			</div>
			<div class="pdf-row" style="line-height:0.1cm;height:0.1cm;"></div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ชื่อลูกค้า</div><div class="pdf-value w-9_5">{$data['customer_name']}</div>
				<div class="pdf-title w-2">PROMOTION</div><div class="pdf-value w-all">{$data['promotion']}</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">ชื่อกิจการ</div><div class="pdf-value w-9_5">{$data['company']}</div>
				<div class="pdf-checkbox ml-0_5"><img src="{$_strHasSample}"></div><div class="pdf-title w-2" style="margin-left:0;">มีเสื้อตัวอย่าง</div>
				<div class="pdf-checkbox"><img src="{$_strIsTaxInvReq}"></div><div class="pdf-title w-all" style="margin-left:0;">ขอใบกำกับภาษี</div>
			</div>
HTML;
?>
			<?php echo $_sectionHeader; ?>
			
			<div class="pdf-row">
				<div class="pdf-title w-3">ที่อยู่</div>
				<?php hlpr_displayValue($data['address'], 180, TRUE, 2, 'w-all', 10, 'margin-left:3cm;'); ?>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">มือถือ</div><div class="pdf-value w-3_5"><?php echo $data['customer_mobile']; ?></div>
				<div class="pdf-title w-2">โทรศัพท์</div><div class="pdf-value w-3_5"><?php echo $data['customer_tel']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">เลขที่ผู้เสียภาษี</div><div class="pdf-value w-3_5"><?php echo $data['tax_id']; ?></div>
				<div class="pdf-title w-2">สาขา</div><div class="pdf-value w-3_5"><?php echo $data['tax_branch']; ?></div>
				<div class="pdf-title w-2">VAT</div><div class="pdf-value w-all pdf-text-center"><?php echo $data['disp_vat_type']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-3">วันที่สั่งงาน</div><div class="pdf-value w-3_5"><?php echo $data['order_date']; ?></div>
				<div class="pdf-title w-2">กำหนดส่ง</div><div class="pdf-value w-3_5"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-2">วันที่ส่งลูกค้า</div><div class="pdf-value w-all"><?php echo $data['deliver_date']; ?></div>
			</div>
			<div class="pdf-row pdf-underline" style="line-height:0.3cm;height:0.3cm;">
			</div>
			<div class="pdf-row" style="line-height:0.5cm;height:0.5cm;"></div>
			<?php echo isset($detail_section)?$detail_section:''; ?>
			<div class="pdf-row" style="line-height:0.5cm;height:0.5cm;"></div>
			<?php echo isset($size_quan_section)?$size_quan_section:''; ?>
<?php
	$_currTime = date('d/m/Y h:i:sa');
	$_sectionBottom = <<<HTML
			<div class="pdf-row">
				<div style="float:right;width:10cm;">
					<div class="pdf-title w-2">ผู้สั่งงาน</div><div class="pdf-value pdf-text-center w-2_5">{$data['user_name']}</div>
					<div class="pdf-text-center w-2">วันที่สั่งงาน</div><div class="pdf-value pdf-text-center w-2_5">{$data['order_date']}</div>
				</div>
			</div>
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

		<div class="page cls-page-2">
			<?php echo $_sectionHeader; ?>
			<div class="pdf-row">
				<div class="pdf-title w-3">วันที่สั่งงาน</div><div class="pdf-value w-3_5"><?php echo $data['order_date']; ?></div>
				<div class="pdf-title w-2">กำหนดส่ง</div><div class="pdf-value w-3_5"><?php echo $data['due_date']; ?></div>
				<div class="pdf-title w-2">วันที่ส่งลูกค้า</div><div class="pdf-value w-all"><?php echo $data['deliver_date']; ?></div>
			</div>
			<div class="pdf-row pdf-underline" style="line-height:0.3cm;height:0.3cm;">
			</div>

			<?php echo isset($screen_section)?$screen_section:''; ?>

			<?php echo isset($images_section)?$images_section:''; ?>

			<?php echo $_sectionBottom; ?>

			<div class="line-row" style="text-align:right;">
				<div class="pdf-title w-all">(หน้าที่ 2)</div>
			</div>
<?php endif; ?>
		</div>
	</body>
</html>