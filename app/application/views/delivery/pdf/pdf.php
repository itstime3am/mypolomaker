<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<link type="text/css" href="public/css/pdf.css" rel="stylesheet" />	
		<style>
.pdf-row {width:100%;display:block;position:relative;height:0.7cm;padding-right:0.2cm;border-left:1px solid black;border-right:1px solid black;clear:both;}
.pdf-row div {display:block;float:left;vertical-align:middle;line-height:0.6cm;height:0.6cm;padding-bottom:0px;margin-bottom:0px;} 

/*
.row-highlight {background-color:#eeeeee;height:0.8cm;padding-top:0.1cm;}
.row-highlight .pdf-value {background:lightgray;height:0.6cm;} 
*/

div.detail-row {width:80%;margin-left:8%;height:0.5cm;line-height:0.5cm;clear:both;}
div.detail-row div {display:block;float:left;vertical-align:middle;height:0.5cm;line-height:0.4cm;padding:2px;border:1px solid black;}
div.detail-header {margin:0 0.05cm;text-align:center;font-weight:bold;}
div.detail-item {margin:0 0.05cm;text-align:center;font-size:10px;}

.pdf-value {background-position: 0 75%;}
		</style>
	</head>
	<body>
		<div class="page">
<?php if (isset($data)) : ?>
			<div class="pdf-row" style="border-top:1px solid black;padding-top:0.2cm;">
					<div class="pdf-title w-1_2" style="padding-left:2.5cm;">วันที่</div>
					<div class="w-2"><?php echo isset($data['disp_report_create_date'])?$data['disp_report_create_date']:''; ?></div>
					<div class="pdf-title w-1_8">ส่งมอบวันที่</div>
					<div class="w-2"><?php echo isset($data['disp_deliver_date'])?$data['disp_deliver_date']:''; ?></div>
					<div class="pdf-text-center w-2">เลขที่งาน</div>
					<div class="pdf-value w-all"><?php echo isset($data['deliver_job_number'])?$data['deliver_job_number']:''; ?></div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-text-center f-none w-100p" style="font-weight:bold;height:1cm;line-height:1cm;font-size:16px;">
					ใบนำส่งสินค้า ( ไม่ใช่ใบเสร็จรับเงิน )
				</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-text-center f-none w-100p" style="font-weight:bold;"><?php echo isset($data['title'])?$data['title']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-1_4">ชื่อลูกค้า</div>
				<div class="pdf-value w-15"><?php echo isset($data['customer_name'])?$data['customer_name']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-1_4">ขื่อกิจการ</div>
				<div class="pdf-value w-all"><?php echo isset($data['company'])?$data['company']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-1_4">ที่อยู่</div>
				<!--<?php hlpr_displayValue($data['disp_customer_address'], 70, TRUE, 2, 'w-all', 10); ?> -->
				<div class="pdf-value w-all"><?php echo isset($data['disp_customer_address'])?$data['disp_customer_address']:''; ?></div>
				<!--<div class="w-all" style="text-decoration:underline;"><?php echo isset($data['customer_address'])?$data['customer_address']:''; ?></div>-->
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-1_4">เบอร์โทร</div>
				<div class="pdf-value w-6"><?php echo isset($data['tel'])?$data['tel']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-2_5">ส่วนติดต่อส่งสินค้า</div>
				<div class="pdf-value w-all"><?php echo isset($data['contact'])?$data['contact']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-label w-2_5">วิธีการจัดส่งสินค้า</div>
				<div class="pdf-value w-4"><?php echo isset($data['deliver_route'])?$data['deliver_route']:''; ?></div>
				<div class="pdf-title w-1_5">หมายเหตุ</div>
				<div class="pdf-value w-all"><?php echo isset($data['deliver_route_remark'])?$data['deliver_route_remark']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2">ประเภทสินค้า</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',1,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2">เสื้อโปโลสั่งตัด</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',2,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-1_8">เสื้อยืดสั่งตัด</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',3,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2_5">เสื้อโปโลสำเร็จรูป</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',4,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2_2">เสื้อยืดสำเร็จรูป</div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-1_3"></div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',5,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-1_8">หมวกสั่งตัด</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',6,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2_5">เสื้อแจ๊คเก็ตสั่งตัด</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',7,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2">หมวกสำเร็จรูป</div>
				<div class="pdf-checkbox">
					<img src="<?php echo ((isset($data['product_deliver'])) && (strpos($data['product_deliver'], ',8,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2_8">เสื้อแจ๊คเก็ตสำเร็จรูป</div>
				<div class="pdf-title w-0_7">อื่นๆ</div><div class="pdf-value w-all"><?php echo $data['product_deliver_other']; ?></div>
			</div>
			<div class="pdf-row">
				<div class="pdf-title w-2">เอกสารแนบ</div>
				<div class="pdf-checkbox">
					<img src="<?php echo (isset($data['attachment']) && (strpos($data['attachment'], ',1,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-2">ใบกำกับภาษี</div>				
				<div class="pdf-checkbox">
					<img src="<?php echo (isset($data['attachment']) && (strpos($data['attachment'], ',2,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-1_8">ใบแจ้งหนี้</div>				
				<div class="pdf-checkbox">
					<img src="<?php echo (isset($data['attachment']) && (strpos($data['attachment'], ',3,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-1_8">บิลเงินสด</div>				
				<div class="pdf-checkbox">
					<img src="<?php echo (isset($data['attachment']) && (strpos($data['attachment'], ',4,') !== FALSE))?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
				</div>
				<div class="pdf-title w-1_8">บิลส่งของ</div>				
				<div class="pdf-title w-0_7">อื่นๆ</div><div class="pdf-value w-all"><?php echo $data['attachment_other']; ?></div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pl-0_2 pdf-title f-none" style="font-weight:bold;width:7%;">ประเภท</div>
				<div class="pdf-value pdf-text-center f-none" style="width:15%;"><?php echo $data['disp_vat_type']; ?></div>
				<div class="pdf-text-center f-none" style="font-weight:bold;width:50%;">สรุปราคาสินค้า</div>
			</div>
			<div class="pdf-row" style="height:80px;">
				<div class="detail-row" style="padding-bottom:2px;">
					<div class="detail-header w-2">จำนวน</div>
					<div class="detail-header w-8">รายการ</div>
					<div class="detail-header w-2">หน่วยละ</div>
					<div class="detail-header w-all">จำนวนเงิน</div>
				</div>
<?php
	$_details_row_limit = (isset($DETAIL_ROWS_LIMIT))?$DETAIL_ROWS_LIMIT:16;
	$_eaQty = 0;$_eaTitle = '';$_eaPrice = 0;$_eaAmount = 0;$_totAmount = 0;$_vat = 0;$_deposit = 0;$_deliver = 0;$_payment = 0;
	$_strQty = '';$_strPrice = '';$_strAmount = '';$_strTotAmount = '';$_strVat = '';$_strDeposit = '';$_strDeliver = '';
	$_strPayment = '';$_strGrandTotal = '';$_strLeft = '';
	$_count = 0;
	if (isset($data['details']) && (is_array($data['details']))) {
		for ($i=0;(($i<count($data['details'])) && ($i < $_details_row_limit));$i++) {
			$_ea = $data['details'][$i];
			$_eaQty = intval(str_replace(',', '', $_ea['qty']));
			$_eaTitle = $_ea['title'];
			$_eaPrice = floatval(str_replace(',', '', $_ea['price']));
			$_eaAmount = $_eaQty * $_eaPrice;
			//$_totAmount += $_eaAmount;
			$_strQty = number_format($_eaQty, 0);$_strPrice = number_format($_eaPrice, 2);$_strAmount = number_format($_eaAmount, 2);
			echo <<<ROW
				<div class="detail-row pb-0_05">
					<div class="detail-item w-2">$_strQty</div>
					<div class="detail-item w-8">$_eaTitle</div>
					<div class="detail-item w-2">$_strPrice</div>
					<div class="detail-item w-all" style="text-align:right;">$_strAmount</div>
				</div>			
ROW;
			$_count++;
		}
	}
	if ($_count < $_details_row_limit) {
		for ($i=$_count;$i<$_details_row_limit;$i++) {
			echo <<<FROW
				<div class="detail-row pb-0_05">
					<div class="detail-item w-2"></div>
					<div class="detail-item w-8"></div>
					<div class="detail-item w-2"></div>
					<div class="detail-item w-all"></div>
				</div>			
FROW;
		}
	}
	
	$_totAmount = floatval(str_replace(',', '', $data['total']));
	$_vat = floatval(str_replace(',', '', $data['vat']));
	$_grandTotal = floatval(str_replace(',', '', $data['grand_total']));
	$_deposit = floatval(str_replace(',', '', $data['deposit_amount']));
	$_payment = floatval(str_replace(',', '', $data['payment_amount']));
	$_deliver = floatval(str_replace(',', '', $data['deliver_amount']));
	$_left = floatval(str_replace(',', '', $data['left_amount']));

	if ($_totAmount == '') $_totAmount = 0;
	if ($_vat == '') $_vat = 0;
	if ($_grandTotal == '') $_grandTotal = 0;
	if ($_deposit == '') $_deposit = 0;
	if ($_payment == '') $_payment = 0;
	if ($_deliver == '') $_deliver = 0;
	if ($_left == '') $_left = 0;
	
	if ($_totAmount != 0) $_strTotAmount = number_format($_totAmount, 2);
	if ($_vat != 0) $_strVat = number_format($_vat, 2);
	if ($_grandTotal != 0) $_strGrandTotal = number_format($_grandTotal, 2);
	if ($_deposit != 0) $_strDeposit = number_format($_deposit, 2);
	if ($_payment != 0) $_strPayment = number_format($_payment, 2);
	if ($_deliver != 0) $_strDeliver = number_format($_deliver, 2);
	if ($_left != 0) $_strLeft = number_format($_left, 2);
	
?>
			</div>
			<div class="pdf-row" style="height:78px;">
				<div class="detail-row">
					<div class="detail-item w-10_2" style="height:2cm;text-align:left;">
						<ul>
							<li>ท่านสามารถคืนสินค้าได้ตามข้อตกลงและเงื่อนไข โดยบริษัทขอสงวนสิทธิ์ในการรับเปลี่ยน หรือคืนสินค้าในสภาพสมบูรณ์ ยังไม่ผ่านการใช้งาน ภายใน 7 วัน</li>
							<li>บริษัทขอรักษาสิทธิ์ในจำนวนสินค้าจากการลงนามรับสินค้าเป็นสำคัญ</li>
						</ul>
					</div>
					<div style="border:0px;padding:0px;margin:0px;width:4.8cm;">
						<div style="border:0px;padding-top:2px;padding-bottom:2px;margin:0px;width:100%;">
							<div class="w-2_1" style="border:0px;text-align:right;">Total รวมเงิน</div>
							<div class="w-all" style="text-align:right;"><?php echo $_strTotAmount; ?></div>
						</div>
						<div style="border:0px;padding-top:2px;padding-bottom:2px;margin:0px;width:100%;">
							<div class="w-2_1" style="border:0px;text-align:right;">
								<img style="vertical-align:middle;" src="<?php echo ($_vat > 0)?'public/images/checkbox_yes.png':'public/images/checkbox_no.png'; ?>">
								Vat 7%
							</div>
							<div class="w-all" style="text-align:right;padding-left:2px;padding-right:2px;"><?php echo $_strVat; ?></div>
						</div>
						<div style="border:0px;padding-top:2px;padding-bottom:2px;margin:0px;width:100%;">
							<div class="w-2_1" style="border:0px;text-align:right;">รวมสุทธิ</div>
							<div class="w-all" style="text-align:right;"><?php echo $_strGrandTotal; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="pdf-row pr-0 clr-bth">
				<div class="pdf-title" style="text-align:right;width:5.4cm;">ลูกค้าชำระมัดจำแล้วโดย</div>
				<div class="w-4" style="text-align:right;"><?php echo isset($data['deposit_route'])?$data['deposit_route']:''; ?></div>
				<div class="pdf-title w-2" style="text-align:right;">จำนวน</div>
				<div class="w-4" style="text-align:right;"><?php echo $_strDeposit; ?></div>
				<div class="pdf-title w-all">บาท</div>
			</div>
			<div class="pdf-row pr-0 clr-bth">
				<div class="pdf-title" style="text-align:right;width:5.4cm;">ลูกค้าชำระสินค้าแล้วโดย</div>
				<div class="w-4" style="text-align:right;"><?php echo isset($data['payment_route'])?$data['payment_route']:''; ?></div>
				<div class="pdf-title w-2" style="text-align:right;">จำนวน</div>
				<div class="w-4" style="text-align:right;"><?php echo $_strPayment; ?></div>
				<div class="pdf-title w-all">บาท</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-11_6" style="text-align:right;">ค่าจัดส่งสินค้าเรียกเก็บลูกค้าจำนวน</div>
				<div class="w-4" style="text-align:right;"><?php echo $_strDeliver; ?></div>
				<div class="pdf-title w-all">บาท</div>
			</div>
			<div class="pdf-row pr-0">
				<div class="pdf-title w-11_6" style="text-align:right;">เรียกเก็บเงินกับลูกค้าทั้งสิ้นจำนวน</div>
				<div class="w-4" style="text-align:right;"><?php echo $_strLeft; ?></div>
				<div class="pdf-title w-all">บาท</div>
			</div>
			<div class="pdf-row" style="claer:both;">
				<div class="pdf-title w-1_4">หมายเหตุ</div><div class="pdf-value w-all"><?php echo isset($data['remark1'])?$data['remark1']:''; ?></div>
			</div>
			<div class="pdf-row">
				<div class="ml-0_2 pdf-value w-all"><?php echo isset($data['remark2'])?$data['remark2']:''; ?></div>
			</div>
			<!--div class="pdf-row" style="height:0.5cm;">
			</div-->
			<div class="pdf-row">
				<div class="pdf-title w-1_4">ผู้ส่งสินค้า</div><div class="pdf-value w-3_5"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-value w-3"></div>
				<div class="pdf-title w-1_4">ผู้รับสินค้า</div><div class="pdf-value w-3_5"></div>
				<div class="pdf-title w-0_8">วันที่</div><div class="pdf-value w-all"></div>
			</div>
			<div class="pdf-row pdf-underline">
				<div class="pdf-title w-1_2">ผู้สั่งงาน</div>
				<div class="pdf-value w-4"><?php echo isset($data['user_name'])?$data['user_name']:''; ?></div>
				<div class="pdf-title w-0_8">สาขา</div>
				<div class="pdf-value w-3">
	<?php 
		/* ++ Remove "สาขา" if exists in field value */
		$_branch = isset($data['user_branch'])?$data['user_branch']:'';
		$_branch = trim($_branch);
		if (strpos($_branch, "สาขา") !== FALSE) $_branch = trim(str_replace('สาขา', '', $_branch));
		echo $_branch;
	?>
				</div>
				<div class="pdf-title w-1_2">เบอร์โทร</div>
				<div class="pdf-value w-2_5"><?php echo isset($data['user_tel'])?$data['user_tel']:''; ?></div>
				<div class="pdf-title w-2">ผู้จัดการอนุมัติ</div>
				<div class="pdf-value w-all"></div>
			</div>
<?php endif; ?>
			<div>
				<strong style="color:red">กรุณาตรวจสอบสินค้าให้ครบถ้วนก่อนเซ็นรับทุกครั้ง หากเซ็นรับสินค้าทางบริษัทถือว่าลูกค้าได้รับสินค้าครบถ้วนสมบูรณ์ทุกประการ</strong>
			</div>
		</div>
		
	</body>
</html>