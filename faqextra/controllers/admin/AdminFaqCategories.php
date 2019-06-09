<?php
require _PS_MODULE_DIR_ . 'inixframe/InixAdminController.php';

class AdminFaqCategoriesController extends Inix2AdminController
{

    /** @var object FAQCategory() instance for navigation */
    protected $_category;
    protected $position_identifier = 'id_faq_category_to_move';
    private $selected_cms = array();

    public function __construct()
    {

        $this->table = 'faq_category';
        $this->className = 'FAQCategory';
        $this->identifier = 'id_faq_category';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'asc';
        $this->lang = true;

        parent::__construct();
        $this->fields_list = array(
            'id_faq_category' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 'auto',
                'callback' => 'hideFAQCategoryPosition',
                'callback_object' => 'FAQCategory'
            ),
            'description' => array(
                'title' => $this->l('Description'),
                'width' => 500,
                'maxlength' => 90,
                'orderby' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'width' => 70,
                'filter_key' => 'position',
                'align' => 'center',
                'position' => 'position'
            ),
            'active' => array(
                'title' => $this->l('Displayed'),
                'width' => 25,
                'active' => 'status',
                'align' => 'center',
                'type' => 'bool',
                'orderby' => false
        ));


        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('view');
        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
        $this->tpl_list_vars['table_dnd'] = true;
        $this->show_cancel_button = true;
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {

        parent::getList($id_lang, 'position', $order_way, $start, $limit);


        $nb_items = count($this->_list);
        for ($i = 0; $i < $nb_items; $i++) {
            $item = &$this->_list[$i];
            $category_tree = FAQCategory::getChildren((int) $item['id_faq_category'], $this->context->language->id);

            if (!count($category_tree))
                $this->addRowActionSkipList('view', array($item['id_faq_category']));
        }
    }

    public function init()
    {
        parent::init();

        // context->shop is set in the init() function, so we move the _category instanciation after that
        if (($id_category = Tools::getvalue('id_faq_category')) && $this->action != 'select_delete') {
            $this->_category = new FAQCategory($id_category);
        } else {
            $this->_category = new FAQCategory(1);
        }
    }

    protected function copyFromPost(&$object, $table)
    {
        parent::copyFromPost($object, $table);

        if (!Tools::isSubmit('id_parent')) {
            $object->id_parent = 1;
        }
    }

    public function initToolbar()
    {
        if (empty($this->display)) {

            $this->toolbar_btn['new'] = array(
                'href' => self::$currentIndex . '&amp;add' . $this->table . '&amp;token=' . $this->token,
                'desc' => $this->l('Add new')
            );
        }
        if (Tools::getValue('id_faq_category') && (!Tools::isSubmit('updatefaq_category') AND ! Tools::isSubmit('addfaq_category'))) {
            $back = Tools::safeOutput(Tools::getValue('back', ''));
            if (empty($back))
                $back = self::$currentIndex . '&token=' . $this->token;

            $this->toolbar_btn['goback'] = array(
                'href' => $back,
                'desc' => $this->l('Back to list'),
                'class' => 'back',
                'imgclass' => "back",
            );

            $this->toolbar_btn['edit'] = array(
                'href' => self::$currentIndex . '&amp;update' . $this->table . '&amp;id_faq_category=' . (int) Tools::getValue('id_faq_category') . '&amp;token=' . $this->token,
                'desc' => $this->l('Edit')
            );
        }

        if ($this->display == 'view')
            $this->toolbar_btn['new'] = array(
                'href' => self::$currentIndex . '&amp;add' . $this->table . '&amp;id_parent=' . (int) Tools::getValue('id_faq_category') . '&amp;token=' . $this->token,
                'desc' => $this->l('Add new')
            );
        parent::initToolbar();

        if (isset($this->_category->id)) {
            if ($this->_category->id == FaqCategory::getTopCategory()->id && isset($this->toolbar_btn['new']))
                unset($this->toolbar_btn['new']);
        }
        if (empty($this->display)) {
            $id_category = (Tools::isSubmit('id_faq_category')) ? '&amp;id_parent=' . (int) Tools::getValue('id_faq_category') : '';
            $this->toolbar_btn['new'] = array(
                'href' => self::$currentIndex . '&amp;add' . $this->table . '&amp;token=' . $this->token . $id_category,
                'desc' => $this->l('Add new')
            );
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
    }

    public function renderList()
    {
        if (Tools::isSubmit('id_faq_category'))
            $id_parent = $this->_category->id;
        else
            $id_parent = 1;
        //$this->context->shop->id_category;

        $this->_select = 'a.position position';
        $this->_filter .= ' AND `id_parent` = ' . (int) $id_parent . ' ';

        $categories_tree = $this->_category->getParentsCategories();

        if (empty($categories_tree) && ($this->_category->id != 1 || Tools::isSubmit('id_faq_category')))
            $categories_tree = array(array('name' => $this->_category->name[$this->context->language->id]));


        krsort($categories_tree);


        $this->tpl_list_vars['categories_tree'] = $categories_tree;

        if (Tools::isSubmit('submitBulkdelete' . $this->table) || Tools::isSubmit('delete' . $this->table)) {
            $this->tpl_list_vars['delete_category'] = true;
            $this->tpl_list_vars['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
            $this->tpl_list_vars['POST'] = $_POST;
        }

        return parent::renderList();
    }

    public function renderView()
    {
        $this->initToolbar();
        return $this->renderList();
    }

    public function renderForm()
    {
        $this->initToolbar();
        $obj = $this->loadObject(true);

        $selected_cat = array(isset($obj->id_parent) ? (int) $obj->id_parent : (int) Tools::getValue('id_parent', FAQCategory::getTopCategory()->id));

        $this->show_cancel_button = true;
        $root_category = FAQCategory::getTopCategory();
        $root_category = array('id_faq_category' => $root_category->id, 'name' => $root_category->name);

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('FAQ Category'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name:'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                    'class' => 'copy2friendlyUrl',
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}'
                ),
                array(
                    'type' => 'category_box',
                    'label' => $this->l('Parent category:'),
                    'name' => 'id_parent',
                    'category_tree' => $this->module->renderCategoryTree(array(
                        'name' => 'id_parent',
                        'tree' => array(
                            'id' => 'categories-tree',
                            'selected_categories' => $selected_cat,
                            'disabled_categories' => Validate::isLoadedObject($this->object) ? array($this->object->id) : array(),
                            'use_search' => true,
                            'use_checkbox' => false,
                            'root_category' => $root_category['id_faq_category'],
                        ),
                    ))
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description:'),
                    'name' => 'description',
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}'
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
                array(
                    'type' => 'switch',
                    'label' => $this->l('Indexation (by search engines):'),
                    'name' => 'indexation',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'indexation_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'indexation_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta title:'),
                    'name' => 'meta_title',
                    'lang' => true,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta description:'),
                    'name' => 'meta_description',
                    'lang' => true,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta keywords:'),
                    'name' => 'meta_keywords',
                    'lang' => true,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Friendly URL:'),
                    'name' => 'link_rewrite',
                    'required' => true,
                    'lang' => true,
                    'hint' => $this->l('Only letters and the minus (-) character are allowed.')
                ),
                array(
                    'type' => 'cms_pages',
                    'label' => $this->l('CMS hook:'),
                    'name' => 'cms_categories',
                    'values' => FAQCategory::getAllCMSStructure(),
//					'desc' => $this->l('Please mark every page that you want this FAQ category to display.'),
                    'default_value' => array(),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }
        $this->tpl_form_vars = array(
            //'active' => $this->object->active,
            'PS_ALLOW_ACCENTED_CHARS_URL', (int) Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')
        );

        if (empty($this->selected_cms))
            $this->selected_cms = explode('|', trim($this->getFieldValue($this->object, 'cms_hook'), '|'));
        foreach ($this->selected_cms as $key => $value)
            $this->fields_value[$value] = true;

        return parent::renderForm();
    }

    public function processPosition()
    {

        if ($this->tabAccess['edit'] !== '1')
            $this->errors[] = Tools::displayError('You do not have permission to edit here.');
        else if (!Validate::isLoadedObject($object = new FaqCategory((int) Tools::getValue($this->identifier, Tools::getValue('id_faq_category_to_move', 1)))))
            $this->errors[] = Tools::displayError('An error occurred while updating status for object.') . ' <b>' .
                $this->table . '</b> ' . Tools::displayError('(cannot load object)');
        if (!$object->updatePosition((int) Tools::getValue('way'), (int) Tools::getValue('fposition')))
            $this->errors[] = Tools::displayError('Failed to update the position.');
        else {
            $object->regenerateEntireNtree();
            Tools::redirectAdmin(self::$currentIndex . '&' . $this->table . 'Orderby=position&' . $this->table . 'Orderway=asc&conf=5' . (($id_category = (int) Tools::getValue($this->identifier, Tools::getValue('id_category_parent', $object->id_parent))) ? ('&' . $this->identifier . '=' . $id_category) : '') . '&token=' . Tools::getAdminTokenLite('AdminFaqCategories'));
        }
    }

    public function ajaxProcessUpdatePositions()
    {
        $id_category_to_move = (int) (Tools::getValue('id'));

        $way = (int) (Tools::getValue('way'));
        $positions = Tools::getValue('faq_category');
        if (is_array($positions))
            foreach ($positions as $key => $value) {
                $pos = explode('_', $value);

                if (isset($pos[2]) && $pos[2] == $id_category_to_move) {
                    $position = $key;
                    break;
                }
            }


        $category = new FaqCategory($id_category_to_move);
        if (Validate::isLoadedObject($category)) {

            if (isset($position) && $category->updatePosition($way, $position)) {
                $category->regenerateEntireNtreeNonStatic();
                die(true);
            } else
                die('{"hasError" : true, "errors" : "Can not update categories position"}');
        } else
            die('{"hasError" : true, "errors" : "This scategory can not be loaded"}');
    }

    public function processStatus()
    {
        $object = parent::processStatus();
        $this->redirect_after = self::$currentIndex . '&' . $this->identifier . '=' . $this->object->id_parent . '&conf=5&token=' . $this->token;
        return $object;
    }
}
