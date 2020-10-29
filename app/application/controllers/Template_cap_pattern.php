<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_cap_pattern extends MY_Ctrl_crud {
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
			'standard_pattern'=>array('where'=>array('is_cancel'=>0,'is_cap'=>1),'order_by'=>'sort_index')
			//,'cap_type'=>array('where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
			,'fabric'=>array('table_name'=>'pm_m_cap_fabric_type','where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
			,'front_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,front,%'",'order_by'=>'sort_index')
			,'back_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,back,%'",'order_by'=>'sort_index')
			,'brim_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,brim,%'",'order_by'=>'sort_index')
			,'button_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,button,%'",'order_by'=>'sort_index')
			,'swr_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,swr,%'",'order_by'=>'sort_index')
			,'afh_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,afh,%'",'order_by'=>'sort_index')
			,'cap_belt_type'=>array('where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
			,'order_screen' => array('where' => array('is_cancel'=>0,'is_cap'=>1),'order_by'=>'sort_index')
		));

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>0));
		$this->_setController("fabric", "ชนิดผ้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("front_color", "สีหน้าหมวก", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>2));
		$this->_setController("back_color", "สีหลังหมวก", array(), array("selectable"=>TRUE,"default"=>FALSE,"order"=>3));
		$this->_setController("brim_color", "สีปีกหมวก", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>4));

		$this->_setController('standard_pattern_rowid', '');
		$this->_setController('standard_pattern_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		$this->_setController('fabric_rowid', '');
		$this->_setController('front_color_rowid', 'สีหน้าหมวก');
		$this->_setController('back_color_rowid', 'สีหลังหมวก');
		$this->_setController('brim_color_rowid', 'สีปีกหมวก');
		$this->_setController('button_color_rowid', 'สีกระดุมหมวก');
		$this->_setController('is_sandwich_rim', 'มีกุ๊นขอบแซนวิช', array('type'=>'chk'));
		$this->_setController('swr_color_rowid', 'สีกุ๊นขอบแซนวิช');
		$this->_setController('is_air_flow', 'มีเจาะรูตาไก่', array('type'=>'chk'));
		$this->_setController('air_flow_holes_number', 'จำนวนรู', array('add_class'=>'input-integer'));
		$this->_setController('afh_color_rowid', 'สีรูตาไก่');
		$this->_setController('cap_belt_type_rowid', '');
		$this->_setController('cap_belt_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		$this->_setController('remark1', '', array('type'=>'txa', 'rows'=>2, 'maxlength'=>140));
		$this->_setController('remark2', '', array('type'=>'txa', 'rows'=>2, 'maxlength'=>140));

		$this->_arrControlLayout = array(
			array('code', '')
			,'แบบแพทเทิร์น' => array('standard_pattern_rowid', 'standard_pattern_detail')
			,'ชนิดผ้าหมวก' => array('fabric_rowid', '')
			,'สีหมวก' => array(
				array('front_color_rowid', '') 
				,array('back_color_rowid', '')
				,array('brim_color_rowid', '')
				,array('button_color_rowid', '')
			),
			'กุ๊นขอบแซนวิช' => array(
				array('is_sandwich_rim', 'swr_color_rowid', '')
			)
			,'รูตาไก่หมวก' => array(
				array('is_air_flow', 'air_flow_holes_number', 'afh_color_rowid')
			)
			,'อะไหล่หมวกด้านหลัง' => array(
				array('cap_belt_type_rowid', 'cap_belt_detail')
			)
			,'รายละเอียดเพิ่มเติม' => array(
				array('remark1')
				,array('remark2')
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
				, "layout" => array(
					array("return &nbsp;")
					, array("code")
					, array("name")
				)
			)
			, TRUE
		);
		
		$template['dataview_fields'] = $this->_arrDataViewFields;
		$template['list_deleteable'] = False;
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