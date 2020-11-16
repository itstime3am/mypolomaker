<!DOCTYPE html>
<html>
	<head>
        <base href="<?php echo base_url(); ?>" />
        <title><?php echo isset($title)?$title:DEFAULT_TITLE; ?></title>
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Last-Modified" content="Fri, 13 Feb 2004 09:49:40 GMT">
		<meta http-equiv="Expires" content="Fri, 13 Feb 2004 09:49:40 GMT">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;" />
		<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon" />
		<link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
		<?php echo isset($_scripts_include)?$_scripts_include:''; ?>
 	</head>
	<body>
		<div id="content">
			<div id="top_wrapper">
				<div id="disp_message">
					<?php echo isset($msg_panel)?$msg_panel:'';?>
					<div id="divInfo" class="cls-div-info" index="0"></div>
					<ul class="ul-vldr-error-msg" index="0"></ul>
				</div>
			</div>
			<div id="mid_wrapper">
<?php 
	if (isset($title) && (!(empty($title)))) {
		echo '<div id="div_title" class="cls-div-title"><span>รายการ:</span><div>' . $title . '</div></div>';
	}
?>
				<div id="left_panel"><?php echo isset($left_panel)?$left_panel:'';?></div>
				<div id="work_panel"><?php echo isset($work_panel)?$work_panel:'';?></div>
				<div id="right_panel"><?php echo isset($right_panel)?$right_panel:'';?></div>
				<?php
	echo <<<EOT
<div id="div_status_remark_manu" style="display:none;">
	<span class="cls-label" style="font-weight:bold;">สาเหตุ</span>
	<textarea id="txa-status_remark" style="width:96%;" class="user-input" rows="3" placeholder="สาเหตุ หรือ ข้อมูลเพิ่มเติม"></textarea>
</div>
EOT;
?>
			</div>
			<div id="bot_wrapper">
				<div id="bottom_panel">Page rendered in <strong>{elapsed_time}</strong> seconds</div>
			</div>
		</div>
	</body>
</html>