<?php if (isset($data) && array_key_exists('screen', $data) && is_array($data['screen'])) : ?>
<?php
	$_blnDispPrice = FALSE;
	if (isset($code) && (substr(trim($code), -2) == '01')) {
		$_blnDispPrice = TRUE;
	} 
?>
			<div class="pdf-row screen-row">
				<div class="screen-header f-none w-all">โลโก้ งานปัก/สกรีน</div>
			</div>
			<div class="pdf-row screen-row">
				<div class="screen-header screen-title w-2">ผู้ปัก/สกรีน</div>
				<div class="screen-header screen-title w-2_5">ตำแหน่งงาน</div>
				<div class="screen-header screen-title w-7">รายละเอียด</div>
				<div class="screen-header screen-title w-2_8">ขนาดงาน</div>
			<?php if ($_blnDispPrice): ?>
				<div class="screen-header screen-title w-2_5">ประวัติ</div>
				<div class="screen-header screen-title w-all">ราคา (บาท)</div>
			<?php else: ?>
				<div class="screen-header screen-title w-all">ประวัติ</div>
			<?php endif; ?>
			</div>
	<?php $arrScreen = $data['screen']; ?>
	<?php if (is_array($arrScreen)) : ?>
		<?php for($i=0;$i<count($arrScreen);$i++): ?>
			<?php $_ea = $arrScreen[$i]; ?>
			<div class="pdf-row screen-row">
				<div class="screen-item w-2"><?php echo $_ea['order_screen']; ?></div>
				<div class="screen-item w-2_5"><?php echo $_ea['position']; ?></div>
				<div class="screen-item w-7"><?php echo $_ea['detail']; ?></div>
				<div class="screen-item w-2_8"><?php echo $_ea['size']; ?></div>
			<?php if ($_blnDispPrice): ?>
				<div class="screen-item w-2_5"><?php echo $_ea['job_hist']; ?></div>
				<div class="screen-item w-all"><?php echo number_format(floatval(str_replace(',', '', $_ea['price'])), 2); ?></div>
			<?php else: ?>
				<div class="screen-item w-all"><?php echo $_ea['job_hist']; ?></div>
			<?php endif; ?>
			</div>
		<?php endfor; ?>
	<?php endif; ?>
	<?php if (count($arrScreen) < 6) : ?>
		<?php for ($i=count($arrScreen);$i<6;$i++): ?>
			<div class="pdf-row screen-row">
				<div class="screen-item w-2"></div>
				<div class="screen-item w-2_5"></div>
				<div class="screen-item w-7"></div>
				<div class="screen-item w-2_8"></div>
			<?php if ($_blnDispPrice): ?>
				<div class="screen-item w-2_5"></div>
			<?php endif; ?>
				<div class="screen-item w-all"></div>
			</div>
		<?php endfor; ?>
	<?php endif; ?>
<?php endif; ?>
