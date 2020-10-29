<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_payment_condition extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_payment_condition';
	}
	
	public function index()
	{
		$this->add_css(
			array(
				'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
				'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
				'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css'
			)
		);
		$this->add_js(
			array(
				'public/js/jquery/1.11.0/jquery.js',
				'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				//'public/js/jquery/dataTable/1.10.0/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/TableTools.min.js'
				//'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js'
			)
		);

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();

		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>1));
		$this->_setController("name", "รูปแบบ/เงื่อนไข การขำระเงิน", array("class"=>"input-required"), array("selectable"=>TRUE,"default"=>TRUE,"order"=>2));
		$this->_setController("description", "รายละเอียด", array("type"=>"txa"), array("selectable"=>TRUE,"default"=>TRUE,"order"=>3));
		$this->_setController("remark", "หมายเหตุ", array("type"=>"txa"), array("selectable"=>TRUE,"default"=>FALSE,"order"=>4));
		$this->_setController("is_cancel", "", array("type"=>"hdn"));
		$this->_setController("sort_index", "ลำดับการแสดงผล", array("type"=>"txt", "class"=>"input-integer"));

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel'
			, array(
				'controls' => array(
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('code'),
						"name" => "code"
					)
					, array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('name'),
						"name" => "name"
					)
				)
				, "layout" => array(
					array("return &nbsp;")
					, array("code")
					, array("name")
					, array("description")
					, array("remark")
				)
			)
			, TRUE
		);
		
		$template['dataview_fields'] = $this->_arrDataViewFields;
		//$template['custom_columns'] = $_arr_custom_columns;
		$template['list_deleteable'] = False;
		$template['list_cancelable'] = True;
		$template['edit_template'] = $this->load->view(
			'_public/_form'
			, array(
				'controls' => $this->_arrGetEditControls(),
				'layout' => $this->_arrControlLayout
			)
			, TRUE
		);
			
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);

		$pass['title'] = "รูปแบบ / เงื่อนไข การชำระเงิน";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}