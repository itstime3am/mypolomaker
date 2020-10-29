<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tshirt_pattern extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_tshirt_pattern';
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
				'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
				'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);
		
		$this->_selOptions = $this->_prepareSelectOptions(array(
				'standard_pattern'=>array('where'=>array('is_tshirt'=>1)), 
				'neck_type'=>array('where'=>array('is_tshirt'=>1)), 
				'fabric'=>array('where'=>array('is_tshirt'=>1)), 
				'collar_type'=>array('where'=>array('is_tshirt'=>1)), 
				'sleeves_type'=>array('where'=>array('is_tshirt'=>1)), 
				'flap_type'=>array('where'=>array('is_tshirt'=>1)), 
				'flap_side_ptrn'=>array('where'=>array('is_tshirt'=>1)), 
				'pocket_type'=>array('where'=>array('is_tshirt'=>1))
			)
		);

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>0));
		$this->_setController("fabric", "ชนิดผ้า", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>1));
		$this->_setController("color", "สีหลัก", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>2));
		$this->_setController("sleeve_type", "แบบแขนเสื้อ", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>3));
		$this->_setController("neck_type_rowid", "");
		//$this->_setController("neck_type_detail", "", array("type"=>"txa", "maxlength"=>140));
		$this->_setController("standard_pattern_rowid", "");
		//$this->_setController("standard_pattern_detail", "", array("type"=>"txa", "maxlength"=>140));
		$this->_setController("fabric_rowid", "");
		$this->_setController("color", "สีหลัก", array("type"=>"txt", "maxlength"=>30));
		$this->_setController("color_add1", "สีตัดต่อ", array("type"=>"txa", "maxlength"=>50));
		//$this->_setController("color_add2", "");
		$this->_setController("collar_type_rowid", "แบบคอเสื้อ ซก/RIB");
		$this->_setController("collar_detail", "", array("type"=>"txt", "maxlength"=>85));
		$this->_setController("sleeves_type_rowid", "แบบแขนเสื้อ");
		$this->_setController("sleeves_detail", "", array("type"=>"txt", "maxlength"=>85));
		$this->_setController("flap_type_rowid", "ชายเสื้อ");
		$this->_setController("flap_type_detail", "", array("type"=>"txt", "maxlength"=>85));
		$this->_setController("m_flap_side", "ผ่าข้างเสื้อผู้ชาย", array("type"=>"chk"));
		$this->_setController("f_flap_side", "ผ่าข้างเสื้อผุ้หญิง", array("type"=>"chk"));
		$this->_setController("flap_side_ptrn_rowid", "แบบผ่าข้าง");
		$this->_setController("flap_side_ptrn_detail", "", array("type"=>"txt", "maxlength"=>50));
		$this->_setController("m_pocket", "กระเป๋าเสื้อชาย", array("type"=>"chk"));
		$this->_setController("f_pocket", "กระเป๋าเสื้อหญิง", array("type"=>"chk"));
		$this->_setController("pocket_type_rowid", "แบบกระเป๋าเสื้อ");
		$this->_setController("pocket_type_detail", "", array("type"=>"txt", "maxlength"=>50));
		$this->_setController("remark1", "", array("type"=>"txa", "maxlength"=>85));
		$this->_setController("remark2", "", array("type"=>"txa", "maxlength"=>120));
		//$this->_setController("remark3", "", array("type"=>"txa", "maxlength"=>180));
		//-- set special attributes	

		$this->_arrControlLayout = array(
			array('code', ''),
			"แบบคอเสื้อ" => array('neck_type_rowid', 'neck_type_detail'),
			"แบบทรงเสื้อ" => array('standard_pattern_rowid', 'standard_pattern_detail'),
			"ชนิดผ้า" => array('fabric_rowid', ''),
			'สี' => array(
				array('color', ''), 
				array('color_add1', '')
			),
			'แบบคอเสื้อ ซก/RIB' => array('collar_type_rowid', 'collar_detail'),
			'แขนเสื้อ' => array(
				array('sleeves_type_rowid', 'sleeves_detail')
			),
			'ชายเสื้อ' => array(
				array('flap_type_rowid', 'flap_type_detail')
			),
			'ผ่าข้าง' => array(
				array('m_flap_side', 'f_flap_side', '', ''),
				array('flap_side_ptrn_rowid', 'flap_side_ptrn_detail')
			),
			'กระเป๋าเสื้อ' => array(
				array('m_pocket', 'f_pocket', '', ''),
				array('pocket_type_rowid', 'pocket_type_detail')
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
						"label" => "ชนิดผ้า",
						"name" => "fabric_rowid",
						"sel_options" => $this->_selOptions['fabric'],
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