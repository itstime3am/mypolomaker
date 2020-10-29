<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cap_pattern extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_cap_pattern';
	}
	
	public function index() {
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
				'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
				'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);
		
		$this->_selOptions = $this->_prepareSelectOptions(array(
				'cap_type', 
				'cap_belt_type'
			)
		);

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>0));
		$this->_setController("cap_type_rowid", "แบบหมวก");
		$this->_setController("color", "สีหลัก (หน้าหมวก)", array("type"=>"txt", "maxlength"=>50), array("selectable"=>TRUE,"default"=>TRUE,"order"=>2));
		$this->_setController("color_add1", "สีรอง (หลังหมวก)", array("type"=>"txt", "maxlength"=>50), array("selectable"=>TRUE,"default"=>FALSE,"order"=>3));
		$this->_setController("brim_color", "สีปีกหมวก", array("type"=>"txt", "maxlength"=>50), array("selectable"=>TRUE,"default"=>TRUE,"order"=>4));
		//$this->_setController("color_add2", "");
		$this->_setController("is_sandwich_rim", "มีกุ๊นขอบแซนวิช", array("type"=>"chk"));
		$this->_setController("sandwich_rim_color", "กุ๊นสี");
		$this->_setController("is_air_flow", "เจาะรูระบายอากาศ", array("type"=>"chk"));
		$this->_setController("air_flow_color", "สี");
		$this->_setController("cap_button_color", "กระดุมสี");
		$this->_setController("cap_belt_type_rowid", "แบบเข็มขัดหมวก");
		$this->_setController("remark1", "", array("type"=>"txa", "maxlength"=>85));
		$this->_setController("remark2", "", array("type"=>"txa", "maxlength"=>120));
		//$this->_setController("remark3", "", array("type"=>"txa", "maxlength"=>180));

		//-- set special attributes	
		$this->_setController("cap_type", "แบบหมวก", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("cap_belt_type", "แบบเข็มขัดหมวก", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>5));

		$this->_arrControlLayout = array(
			array('code', ''),
			array('cap_type_rowid', ''),
			'สี' => array(
				array('color', ''), 
				array('color_add1', ''),
				array('brim_color', '')
			),
			'กุ๊นขอบแซนวิช (เฉพาะหมวกพีชพรีเมียม)' => array(
				array('is_sandwich_rim', 'sandwich_rim_color')
			),
			'เจาะรูระบายอากาศ (เฉพาะหมวกพีชพรีเมียม)' => array(
				array('is_air_flow', 'air_flow_color')
			),
			'กระดุมหมวก' => array(
				array('cap_button_color')
			),
			'เข็มขัด ด้านหลังหมวก' => array(
				array('cap_belt_type_rowid')
			),
			'รายละเอียดเพิ่มเติม' => array(
				array('remark1'),
				array('remark2')/*,
				array('remark3')*/
			)
		);
		
		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel', 
			array(
				'controls' => array(
					array(
						"type" => "txt",
						"label" => "รหัส",
						"name" => "code"
					),
					array(
						"type" => "sel",
						"label" => "ชนิดหมวก",
						"name" => "cap_type_rowid",
						"sel_options" => $this->_selOptions['cap_type'],
						"sel_val" => "rowid",
						"sel_text" => "name"
					)
				)
			), TRUE
		);
		
		$template['dataview_fields'] = $this->_arrDataViewFields;
		$template['edit_template'] = $this->load->view(
			'_public/_form', 
			array(
				'controls' => $this->_arrGetEditControls(),
				'layout' => $this->_arrControlLayout
			),
			TRUE
		);

		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);

		$pass['title'] = "แบบหมวก";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}