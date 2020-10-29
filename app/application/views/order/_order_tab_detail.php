	<div id="tab_order_detail">
		<table class="rounded-corner cls-tbl-edit">
			<tbody>
			<tr>
				<td colspan="3" class="td-align-center">
					<div id="ord_dtl_container" class="form-edit-elem-container">
						<?php echo isset($details_panel) ? $details_panel : ''; ?>
					</div>
				</td>
			</tr>
<?php if ((isset($type_premade_order) && ($type_premade_order == TRUE)) || (isset($is_cap_order) && ($is_cap_order == TRUE))): ?>
<?php 	if (!(isset($type_premade_order) && ($type_premade_order == TRUE))): ?>
			<tr>
				<td colspan="3" class="td-align-center">
					<div class="form-edit-elem-container">
						<div id="ord_cap_quan_container" class="frm-edit-row-group" >
							<div class="frm-edit-row" >
								<div class="frm-edit-row-title table-title" style="width:20%">จำนวนหมวก ( ใบ )</div>
								<div class="frm-edit-row-value">
									<input type="text" id="txt-order_qty" class="user-input input-integer input-required" data="order_qty"/>
								</div>
								<div class="frm-edit-row-title table-title eventView-hide" style="width:20%">ราคาต่อหน่วย ( บาท )</div>
								<div class="frm-edit-row-value eventView-hide">
									<input type="text" id="txt-order_price_each" class="user-input input-double input-required " data="order_price_each"/>
								</div>
								<div class="table-title frm-edit-row-title eventView-hide" style="width:20%;">ราคาเฉพาะหมวกทั้งหมด (บาท)</div>
								<div class="table-value frm-edit-row-value total-price eventView-hide">0</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
<?php 	endif; ?>
<?php 	if (isset($size_quan_panel)): ?>
			<tr>
				<td colspan="3" class="td-align-center">
					<div id="ord_size_quan_container" class="form-edit-elem-container">
						<?php echo isset($size_quan_panel) ? $size_quan_panel : ''; ?>
					</div>
				</td>
			</tr>
<?php 	endif; ?>
			<tr> <!--class="eventView-hide"-->
				<td colspan="3" class="td-align-center">
					<div id="ord_others_price_container" class="form-edit-elem-container">
						<?php echo isset($others_price_panel) ? $others_price_panel : ''; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="td-align-center">
					<div id="ord_scrn_container" class="form-edit-elem-container">
						<?php echo isset($screen_panel) ? $screen_panel : ''; ?>
					</div>
				</td>
			</tr>
<?php endif; ?>
			</tbody>
		</table>
	</div>
