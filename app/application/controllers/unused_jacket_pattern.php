<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jacket_pattern extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_jacket_pattern';
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
				'jacket_pattern_type'
			));

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>0));
		$this->_setController("jacket_pattern_type_rowid", "");
		$this->_setController("fabric_pattern", "");
		$this->_setController("color", "สีหลัก", array("type"=>"txt", "maxlength"=>30), array("selectable"=>TRUE,"default"=>TRUE,"order"=>2));
		$this->_setController("color_add1", "สีตัดต่อ", array("type"=>"txa", "maxlength"=>50));
		//$this->_setController("color_add2", "");
		$this->_setController("collar_detail", "", array("type"=>"txa", "maxlength"=>100));
		$this->_setController("sleeves_detail", "", array("type"=>"txa", "maxlength"=>100));
		$this->_setController("clasper_detail", "", array("type"=>"txa", "maxlength"=>100));
		$this->_setController("pocket_detail", "", array("type"=>"txa", "maxlength"=>100));
		$this->_setController("special_detail", "", array("type"=>"txa", "add_attr"=>'rows="8"', "maxlength"=>500));
		$this->_setController("remark1", "", array("type"=>"txa", "maxlength"=>85));
		$this->_setController("remark2", "", array("type"=>"txa", "maxlength"=>120));
		//$this->_setController("remark3", "", array("type"=>"txa", "maxlength"=>180));
		//-- set special attributes
		$this->_setController("jacket_pattern_type", "แบบเสื้อ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("special_detail_abbr", "ลักษณะพิเศษ", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>3));

		$this->_arrControlLayout = array(
			array('code', ''),
			"แบบเสื้อ" => array('jacket_pattern_type_rowid', ''),
			"แบบผ้า" => array('fabric_pattern', ''),
			'สี' => array(
				array('color', ''), 
				array('color_add1', '')
			),
			"แบบปก" => array('collar_detail', ''),
			"แบบแขน" => array('sleeves_detail', ''),
			"แบบสาบ" => array('clasper_detail', ''),
			"แบบกระเป๋า" => array('pocket_detail', ''),
			"ลักษณะพิเศษ" => array('special_detail', ''),
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
						"label" => "แบบเสื้อ",
						"name" => "jacket_pattern_type_rowid",
						"sel_options" => $this->_selOptions['jacket_pattern_type'],
						"sel_val" => "rowid",
						"sel_text" => "name"
					)
				)
			),
			TRUE
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

		$pass['title'] = "แบบเสื้อยืดสำเร็จรูป";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}