<?php

require _PS_MODULE_DIR_.'inixframe/InixAdminController.php';

class AdminFaqContentController extends Inix2AdminController
{
	/** @var object Category() instance for navigation*/
	protected static $category = null;

	public $id_faq_category;

	protected $position_identifier = 'id_faq_to_move';

	public function __construct($module)
	{
		$this->controller_type = 'moduleadmin';
		$this->module = $module;
		$this->table = 'faq';
		$this->className = 'FAQ';
		$this->identifier = 'id_faq';
		$this->_defaultOrderBy = 'position';
		$this->_defaultOrderWay = 'asc';
		$this->lang = true;
parent::__construct();
		$this->addRowAction('edit');
		$this->addRowAction('delete');
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

		$this->fields_list = array(
			'id_faq' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'question' => array('title' => $this->l('Title'),  'filter_key' => 'b!question'),
			'position' => array('title' => $this->l('Position'), 'width' => 40,'filter_key' => 'position', 'align' => 'center', 'position' => 'position'),
			'active' => array('title' => $this->l('Displayed'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false)
		);



		if ((int)Tools::getValue('id_faq_category'))
			$this->fields_list['position'] = array(
				'title' => $this->l('Position'),
				'width' => 70,
				'filter_key' => 'position',
				'align' => 'center',
				'position' => 'position',

			);

		if ($id_category = Tools::getvalue('id_faq_category'))
			$this->_category = new FAQCategory((int)$id_category);
		else
			$this->_category = new FAQCategory(1);


		$join_category = false;
		if (Validate::isLoadedObject($this->_category) && empty($this->_filter))
			$join_category = true;


		$this->_select .= 'cl.name `name_category` ,  a.`active`';

		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'faq_category_lang` cl ON (a.`id_faq_category` = cl.`id_faq_category` AND b.`id_lang` = cl.`id_lang`) ';
		if($join_category)
			$this->_where .= ' AND a.id_faq_category = '.(int)  $this->_category->id;

		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

		

	}

	public function renderForm()
	{
		if (!$this->loadObject(true))
			return;

		//Helper->override_folder = '___';

		if (Validate::isLoadedObject($this->object))
			$this->display = 'edit';
		else
			$this->display = 'add';

		$this->breadcrumbs[0] = 'FAQ Pages';
		$this->initToolbar();

		$this->show_cancel_button = true;

		$this->fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('FAQ Page'),

			),
			'input' => array(
				// custom template
				array(
					'type' => 'faq_categories',
					'label' => $this->l('FAQ Category'),
					'name' => 'id_faq_category',
					'html' => $this->module->renderCategoryTree(array(
							'name' => 'id_faq_category',
							'tree' => array(
								'id' => 'categories-tree',
								'selected_categories' =>array( Tools::getValue('id_faq_category',$this->object->id)),
								'disabled_categories' => array(),
								'use_search' => true,
								'use_checkbox' => false,
								'root_category' => 1,
							),
						)),
				),
				array(
					'type' => 'text',
					'label' => $this->l('Question:'),
					'name' => 'question',
					'lang' => true,
					'required' => true,
					'hint' => $this->l('Invalid characters:').' <>;=#{}',
					'size' => 50
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Answer:'),
					'name' => 'answer',
					'autoload_rte' => true,
					'required' => true,
					'lang' => true,
					'rows' => 5,
					'cols' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Displayed:'),
					'name' => 'active',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),

			)
		);

		if (Shop::isFeatureActive())
		{
			$this->fields_form['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association:'),
				'name' => 'checkBoxShopAsso',
			);
		}

		$this->tpl_form_vars = array(
			'active' => $this->object->active,
			'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')
		);
		return parent::renderForm();
	}

	protected function afterAdd($object) {
		$this->redirect_after = self::$currentIndex.'&token='.$this->token.'&conf=3&id_faq_category='.  $object->id_faq_category;
		return parent::afterAdd($object);
	}

	protected function afterUpdate($object) {
		$this->redirect_after = self::$currentIndex.'&token='.$this->token.'&conf=4&id_faq_category='.  $object->id_faq_category;
		return parent::afterUpdate($object);
	}


	public function renderList()
	{

		$this->toolbar_btn['new'] = array(
			'href' => self::$currentIndex.'&amp;add'.$this->table.'&amp;id_faq_category='.(int)$this->_category->id.'&amp;token='.$this->token,
			'desc' => $this->l('Add new')
		);


		$this->tpl_list_vars['categories'] =$this->module->renderCategoryTree(array(
			'name' => 'id_faq_category',
			'tree' => array(
				'id' => 'categories-tree',
				'selected_categories' =>array( Tools::getValue('id_faq_category',1)),
				'disabled_categories' => array(),
				'use_search' => true,
				'use_checkbox' => false,
				'root_category' => 1,
			),
		));
		return parent::renderList();
	}


	public function processDelete() {



		parent::processDelete();

		$this->redirect_after = self::$currentIndex.'&token='.$this->token.'&conf=1&id_faq_category='.  Tools::getValue('id_faq_category');
	}
	protected function processBulkDelete() {
		parent::processBulkDelete();

		$this->redirect_after = self::$currentIndex.'&token='.$this->token.'&conf=2&id_faq_category='.  Tools::getValue('id_faq_category');
	}


	public function processStatus() {
		parent::processStatus();
		$this->redirect_after = self::$currentIndex.'&conf=5&id_faq_category='.  (int)Tools::getValue('id_faq_category').'&token='.$this->token;
	}
	public function initContent() {

		if ($id_category = (int)Tools::getValue('id_faq_category'))
			self::$currentIndex .= '&id_faq_category='.(int)$id_category;
		else
			self::$currentIndex .= '&id_faq_category=1';
		parent::initContent();
	}

	public function ajaxProcessUpdatePositions()
	{

		$id_faq_to_move = (int)(Tools::getValue('id'));
		$way = (int)(Tools::getValue('way'));
		$positions = Tools::getValue('faq');



		if (is_array($positions))
			foreach ($positions as $key => $value)
			{
				$pos = explode('_', $value);
				if (isset($pos[2]) &&  $pos[2] == $id_faq_to_move)
				{
					$position = $key;
					break;
				}
			}


		$picture = new FAQ($id_faq_to_move);
		if (Validate::isLoadedObject($picture))
		{



			if (isset($position) && $picture->updatePosition($way, $position))
			{

				die(true);
			}
			else
				die('{"hasError" : true, "errors" : "Can not update Faq position"}');
		}
		else
			die('{"hasError" : true, "errors" : "This faq can not be loaded"}');
	}
	/**
	 * Return current category
	 *
	 * @return object
	 */
	public static function getCurrentFAQCategory()
	{

		if(Tools::isSubmit('id_faq_category')){
			self::$category = new FAQCategory((int)Tools::getValue('id_faq_category'));
		} else {
			self::$category = new FAQCategory(1);
		}
		return self::$category;
	}
}


