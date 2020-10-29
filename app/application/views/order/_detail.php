<?php
	$_parrentTblID = uniqid('tbl_detail_');
?>
	<table id="<?php echo $_parrentTblID; ?>" class="rounded-corner cls-tbl-edit">
	<thead>
		<tr>
			<th class="rounded-top-left" style="width:30%"></th>
			<th style="width:30%">&nbsp;</th>
			<th class="rounded-top-right" style="width:40%"></th>
		</tr>
	</thead>
	<tbody>
<?php
	$_arrControls = array();
	$_startScript = '';
	if (isset($controls)) {
		if (! is_array($controls)) return;
		//++ Prepare controls elements 
		foreach ($controls as $_ctrl) {
			if (! is_array($_ctrl)) continue;
			$_type = array_key_exists('type', $_ctrl)?$_ctrl['type']:'txt';
			$_label = array_key_exists('label', $_ctrl)?$_ctrl['label']:'';
			$_name = array_key_exists('name', $_ctrl)?$_ctrl['name']:'';
			$_val = array_key_exists('value', $_ctrl)?$_ctrl['value']:'';
			$_class = 'user-input';
			$_class .= array_key_exists('add_class', $_ctrl)?' ' . $_ctrl['add_class']:'';
			$_maxlength = '';
			$_size = '';
			$_specStyle = '';
			if (array_key_exists('maxlength', $_ctrl)) {
				$_set_length = intval($_ctrl['maxlength']);
				if ($_set_length > 0) {
					$_maxlength = ' maxlength="' . $_set_length . '"';
					if (($_type == 'txt') || ($_type == 'sel')) {
						$_size = ' size="' . $_set_length . '"';
					}
				}
			}
			$_input_elem = '';
			switch ($_type) {
				case 'txt':
					$_input_elem = '<input type="text" id="' . $_type . '-' . $_name . '" value="' . $_val . '" class="' . $_class . '"' . $_maxlength . $_size . $_specStyle . ' />';
					break;
				case 'dpk': //date picker
					$_input_elem = '<input type="text" id="txt-' . $_name . '" value="' . $_val . '" class="' . $_class . '"' . $_maxlength . $_size . $_specStyle . ' />';
					$_startScript .= <<<EOT
		$('#$_parrentTblID #txt-$_name').datepicker({
			showOn: "both",
			buttonImage: "public/images/select_day.png",
			buttonImageOnly: true,
			dateFormat: 'dd/mm/yy'
		});
EOT;
					break;
				case 'sel':
					$_selVal = array_key_exists('sel_val', $_ctrl)?$_ctrl['sel_val']:'rowid';
					$_selText = array_key_exists('sel_text', $_ctrl)?$_ctrl['sel_text']:'name';
					$_input_elem = '<select id="' . $_type . '-' . $_name . '" class="' . $_class . '"' . $_maxlength . $_size . $_specStyle . ' >';
					$_arrAttr = array();
					if (array_key_exists('sel_attr', $_ctrl) && is_array($_ctrl['sel_attr'])) $_arrAttr = $_ctrl['sel_attr'];
					if (array_key_exists('sel_options', $_ctrl)) {
						if (is_array($_ctrl['sel_options'])) {
							foreach ($_ctrl['sel_options'] as $_opt) {
								$_input_elem .= '<option value="' . $_opt[$_selVal] . '" ';
								if (($_val != '') && ($_opt[$_selVal] == $_val)) $_input_elem .= 'selected ';
								foreach ($_arrAttr as $_key=>$_val) {
									if (array_key_exists($_val, $_opt)) $_input_elem .= $_key . '="' . $_opt[$_val] . '" ';
								}
								$_input_elem .= '>' . $_opt[$_selText] . '</option>';
							}
						}
					}
					$_input_elem .= '</select>';
					$_options = '';
					if (array_key_exists('allow_new', $_ctrl) && ($_ctrl["allow_new"] == true)) $_options = "is_allow_add: true, ";
					
					if (array_key_exists('hidden_name', $_ctrl)) {
						$_input_elem .= '<input type="hidden" id="hdn-' . $_ctrl['hidden_name'] . '" class="user-input" />';
						$_options .= "changed: function(str) { $('#hdn-" . $_ctrl['hidden_name'] . "').val(str);__fnc_eventSelectAddNew.apply(this);return false; }, ";
					} else {
						$_options .= "changed: function(value, event, ui) { return false; }, ";
					}
					$_options = '{ ' . substr($_options, 0, -2) . ' }';
					$_startScript .= "\t\t$('#". $_parrentTblID ." #" . $_type . '-' . $_name . "').combobox(" . $_options . ");\n";
					break;
				case 'txa':
					$_rows = (array_key_exists('rows', $_ctrl) && is_numeric($_ctrl['rows'])) ? intval($_ctrl['rows']) : 1;
					$_input_elem = '<textarea id="' . $_type . '-' . $_name . '" class="' . $_class . '" rows="' . $_rows . '"' . $_maxlength . $_size . $_specStyle . '>' . $_val . '</textarea>';
					break;
				case 'hdn':
					$_label = 'hidden';
					$_input_elem = '<input type="hidden" id="' . $_type . '-' . $_name . '" value="' . $_val . '" class="' . $_class . '"/>';
					break;
				case 'chk':
					$_input_elem = '<input type="checkbox" id="'. $_type . '-' . $_name .'" ';
					if ($_val !== '') $_input_elem .= 'checked';
					$_input_elem .= ' class="' . $_class . '" />';
					break;
				case 'rdo':
					$_selVal = array_key_exists('sel_val', $_ctrl)?$_ctrl['sel_val']:'rowid';
					$_selText = array_key_exists('sel_text', $_ctrl)?$_ctrl['sel_text']:'name';
					if (array_key_exists('sel_options', $_ctrl)) {
						if (is_array($_ctrl['sel_options'])) {
							foreach ($_ctrl['sel_options'] as $_opt) {
								if ($_opt[$_selText] != '') {
									$_id = $_type . '-' . $_name . '_' . $_opt[$_selVal];
									$_input_elem .= '<input type="radio" name="' . $_name . '" id="' . $_id . '" value="' . $_opt[$_selVal] . '" ';
									if (($_val != '') && ($_opt[$_selVal] == $_val)) $_input_elem .= 'checked ';
									$_input_elem .= 'name="' . $_type . '-' . $_name . '" class="' . $_class . '" >';
									$_input_elem .= '<label class="cls-radio-label" for="' . $_id . '" >' . $_opt[$_selText] . '</label>';
								}
							}
						}
					}
					break;
			}
			$_arrControls[$_name] = array($_label, $_input_elem, $_type);
		}
		//-- Prepare controls elements 
		$_display = '<tr><td colspan="3" class="td-align-center"><div class="form-edit-elem-container">';
		if (isset($layout)) {
			$_arrEditPanelDataKey = array();
			if (is_array($layout)) {
				$_display .= _getLayoutItemDisplay($layout, $_arrControls, $_arrEditPanelDataKey);
			}
		}
		$_display .= '</div></td></tr>';
		echo $_display;
		
		foreach ($_arrControls as $_item) { //left over from layout
			if ($_item[0] == 'hidden') {
				echo $_item[1];
			} elseif ($_item[2] == 'chk') {
				echo <<<EOT
		<tr>
			<td class="table-value table-value-checkbox">
				$_item[1]
			</td>
			<td class="table-title table-title-checkbox">$_item[0] :</td>
			<td></td>
		</tr>
EOT;
			} else {
				echo <<<EOT
		<tr>
			<td class="table-title">$_item[0] :</td>
			<td class="table-value">
				$_item[1]
			</td>
			<td></td>
		</tr>
EOT;
			}
		}
	}
?>
	</tbody>
	</table>
<script language="javascript">
	function __fnc_eventSelectAddNew() {
		console.log(this);
	};
	$(function() {
<?php echo $_startScript; ?>
	});
</script>