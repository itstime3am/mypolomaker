			<div class="pdf-sample-img-main-row">
				<div class="pdf-sample-img-main" style="<?php echo ($data['file_image1']) ? "background-image:url('"._url_upload_path().$data['file_image1']."')" : ""; ?>"></div>
			</div>
			<div class="pdf-sample-img-sub-row">
<?php
	for ($_i=2;$_i<8;$_i++) {
		$_file = 'file_image'.$_i;
		$_str = ($data[$_file]) ? "background-image:url('"._url_upload_path().$data[$_file]."');" : "";
		echo <<<SECT
			<div class="pdf-sample-img-sub" style="{$_str}"></div>
SECT;
		if ($_i == 4) {
			echo <<<HTML
			</div>
			<div class="pdf-sample-img-sub-row">
HTML;
		}
	}
?>
			</div>
