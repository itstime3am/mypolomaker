<?php
	if (! isset($data)) return FALSE;
	$_blnDispPrice = FALSE;
	if (isset($is_show_price) && is_bool($is_show_price)) {
		$_blnDispPrice = $is_show_price;
	} elseif (isset($is_display_price) && is_bool($is_display_price)) {
		$_blnDispPrice = $is_display_price;
	}
	$_countOP = 0;
?>
				<div class="line-row line-sum" style="clear:both;width:100%;">
					<div class="w-1_8 pl-0_2 t-bold">อื่นๆ (ระบุ)</div>
<?php if (isset($data['others_price']) && is_array($data['others_price']) && (count($data['others_price']) > 0)): ?>
<?php	foreach ($data['others_price'] as $_row): ?>
<?php		if ($_countOP > 0): ?>
				<div class="line-row line-sum" style="clear:both;width:100%;">
					<div class="w-1_8 pl-0_2">&nbsp;</div>
<?php 		endif; ?>
					<div class="w-10_3 ml-0_5 size-item pdf-text-right sum-others"><?php echo $_row['detail']; ?></div>
<?php		if ($_blnDispPrice): ?>
					<div class="w-2 pdf-text-right t-bold">ราคารวม</div>
					<div class="w-3 ml-0_2 size-item sum-price pdf-text-right"><?php echo $_row['price']; ?></div>
					<div class="w-0_8 pl-0_2 t-bold">บาท</div>
<?php		endif; ?>
				</div>
<?php		$_countOP++; ?>
<?php	endforeach; ?>
<?php else: ?>
					<div class="w-12 size-item pdf-text-right sum-others">ไม่มี</div>
				</div>
<?php endif; ?>
