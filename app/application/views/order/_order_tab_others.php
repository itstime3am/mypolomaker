<?php if (! (isset($type_premade_order) && ($type_premade_order == TRUE))): ?>
	<div id="tab_others_detail">
		<table class="rounded-corner cls-tbl-edit">
			<tbody>
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
			</tbody>
		</table>
	</div>
<?php endif; ?>