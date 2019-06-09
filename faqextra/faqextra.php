<?php

require dirname(__FILE__) . '/inixframe/loader.php';

class FaqExtra extends Inix2Module
{

    function __construct()
    {
        $this->name = 'faqextra';
        $this->tab = 'front_office_features';
        $this->version = '2.0.4';
        $this->author = 'Presta-Apps';
        $this->displayName = $this->l('FAQ Extra');
        $this->description = $this->l('Frequently Asked Questions');
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5.1.0', 'max' => '1.7');

        parent::__construct();
    }

    public function install()
    {        
        $this->install_hooks = array('displayLeftColumn', 'displayRightColumn', 'ModuleRoutes', 'displayFooter', 'displayHeader');

        $this->install_tabs = array(
            array('class' => 'AdminFaqCategories', 'name' => 'FAQ Categories', 'parent' => 'AdminParentModulesSf'),
            array('class' => 'AdminFaqContent', 'name' => 'FAQ Content', 'parent' => 'AdminParentModulesSf'),
        );

        foreach (Language::getLanguages(false) as $language) {
            $init_block_name[$language['id_lang']] = 'FAQ';
            $init_link_rewrite[$language['id_lang']] = 'faq';
        }

        Configuration::updateValue('FAQ_BLOCK_NAME', $init_block_name);
        Configuration::updateValue('FAQ_BLOCK_COL', 0);
        Configuration::updateValue('FAQ_BLOCK_CAT', 1);
        Configuration::updateValue('FAQ_BLOCK_NUM', 5);

        $install = parent::install();

        if ($install) {
            $faq = new FAQCategory();
            $faq->id_parent = 0;
            $faq->level_depth = 1;
            $faq->active = 1;
            $faq->active = 1;
            $faq->indexation = 1;
            $faq->name = $init_block_name;
            $faq->link_rewrite = $init_link_rewrite;
            $faq->save(true);
        }
        
        return $install;
    }

    public function uninstall()
    {
        Configuration::deleteByName('FAQ_BLOCK_NAME');
        Configuration::deleteByName('FAQ_BLOCK_COL');
        Configuration::deleteByName('FAQ_BLOCK_CAT');
        Configuration::deleteByName('FAQ_BLOCK_NUM');
        $this->uninstall_tabs = array('AdminFaqCategories', 'AdminFaqContent');

        return parent::uninstall();
    }

    public function hookDisplayLeftColumn()
    {
        if (Configuration::get('FAQ_BLOCK_COL') == 1)
            return $this->displayBlockColumn();
    }

    public function hookDisplayRightColumn()
    {
        if (Configuration::get('FAQ_BLOCK_COL') == 2)
            return $this->displayBlockColumn();
    }

    public function displayBlockColumn()
    {
        $id_shop = null;
        if (Shop::isFeatureActive()) {
            $id_shop = (int)$this->context->shop->id;
        }
        $current_category = Configuration::get('FAQ_BLOCK_CAT');

        $this->context->smarty->assign(
                array(
                    'faq_module_name' => Configuration::get('FAQ_BLOCK_NAME', $this->context->language->id),
                    'faq_module_link' => $this->context->link->getFAQCategoryLink($current_category),
                    'faq_pages' => FAQ::getFAQPages($this->context->language->id, (int) ($current_category), true, $id_shop, Configuration::get('FAQ_BLOCK_NUM')),
                    'FAQ_DISPLAY' => Configuration::get('FAQ_DISPLAY'),
                )
        );
        $this->context->controller->addCSS($this->getFramePathUri() . 'css/font-awesome.css');
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/faq_column.css');
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/sss.css');
        $this->context->controller->addJS($this->getPathUri() . 'views/js/sss.js');
        if(Configuration::get('FAQ_DISPLAY')) {
            $this->context->controller->addJS($this->getPathUri() . 'views/js/faqextra.js');
        }

        return $this->display(__FILE__, 'faq_hook.tpl');
    }
    
    public function hookDisplayHeader($param)
    {
        if(Tools::version_compare(_PS_VERSION_, '1.7.0', '>=')){
         $this->context->controller->addCSS($this->getFramePathUri() . 'css/font-awesome.css');
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/faq_column.css');
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/sss.css');
        $this->context->controller->addJS($this->getPathUri() . 'views/js/sss.js');
        }
    }

    public function getContent()
    {

        $this->object_table = 'configuration';
        $this->className = 'Configuration';
        $this->object_identifier = 'id_configuration';
        if (!$this->context->controller instanceof AdminModulesController) {
            $this->bootstrap = 1;
        }

        $this->context = Context::getContext();
        $this->token = Tools::getAdminTokenLite('AdminModules');
        $this->override_folder = 'inixframe/';
        // Get the name of the folder containing the custom tpl files
        $this->tpl_folder = 'inixframe/';
        if (!$this->context->controller instanceof AdminModulesController) {
            $this->bootstrap = 1;
        }




        $pos_options = array(
            array(
                'FAQ_BLOCK_COL' => 0,
                'name' => $this->l('Dont show')
            ),
            array(
                'FAQ_BLOCK_COL' => 1,
                'name' => $this->l('Left column')
            ),
            array(
                'FAQ_BLOCK_COL' => 2,
                'name' => $this->l('Right column')
            ),
        );


        $categories = FAQCategory::getCategories($this->context->language->id, false);
        $cat_options = FAQCategory::recurseFAQCategoryOptions($categories, $categories[0][1], 1, 'FAQ_BLOCK_CAT');
        $this->fields_options = array(
            'general' => array(
                'title' => '',
                'icon' => '',
                'description' => '',
                'info' => '',
                'fields' => array(
                    'FAQ_BLOCK_COL' => array(
                        'type' => 'select',
                        'title' => $this->l('FAQ block position:'),
                        'name' => 'FAQ_BLOCK_COL',
                        'required' => true,
                        'list' => $pos_options,
                        'identifier' => 'FAQ_BLOCK_COL',
                        'cast' => 'intval',
                        'validation' => 'isUnsignedInt'
                    ),
                    'FAQ_BLOCK_NAME' => array(
                        'type' => 'textLang',
                        'title' => $this->l('FAQ block title:'),
                        'name' => 'FAQ_BLOCK_NAME',
                        'size' => 20,
                        'lang' => true,
                        'required' => true,
                        'cast' => 'strval',
                        'validation' => 'isGenericName',
                    ),
                    'FAQ_BLOCK_NUM' => array(
                        'type' => 'text',
                        'title' => $this->l('Number to show:'),
                        'name' => 'FAQ_BLOCK_NUM',
                        'size' => 1,
                        'required' => true,
                        'cast' => 'intval',
                        'validation' => 'isUnsignedInt'
                    ),
                    'FAQ_BLOCK_CAT' => array(
                        'type' => 'select',
                        'title' => $this->l('FAQ Category:'),
                        'name' => 'FAQ_BLOCK_CAT',
                        'required' => true,
                        'list' => $cat_options,
                        'identifier' => 'FAQ_BLOCK_CAT',
                        'cast' => 'intval',
                        'validation' => 'isUnsignedInt'
                    ),
                    'FAQ_DISPLAY' => array(
                        'title' => $this->l('Display as slideshow'),
                        'cast' => 'intval',
                        'validation' => 'isBool',
                        'type' => 'bool',
                    ),
                ),
                'submit' => array('title' => $this->l('Save')),
            ),
        );


        return parent::getContent();
    }

    public function displayForm()
    {

        // Get default Language
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $pos_options = array(
            array(
                'FAQ_BLOCK_COL' => 0,
                'name' => $this->l('Dont show')
            ),
            array(
                'FAQ_BLOCK_COL' => 1,
                'name' => $this->l('Left column')
            ),
            array(
                'FAQ_BLOCK_COL' => 2,
                'name' => $this->l('Right column')
            ),
        );


        $categories = FAQCategory::getCategories($this->context->language->id, false);
        $cat_options = FAQCategory::recurseFAQCategoryOptions($categories, $categories[0][1], 1, 'FAQ_BLOCK_CAT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings')
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('FAQ block position:'),
                    'name' => 'FAQ_BLOCK_COL',
                    'required' => true,
                    'options' => array(
                        'query' => $pos_options,
                        'id' => 'FAQ_BLOCK_COL',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('FAQ block title:'),
                    'name' => 'FAQ_BLOCK_NAME',
                    'size' => 20,
                    'lang' => true,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Number to show:'),
                    'name' => 'FAQ_BLOCK_NUM',
                    'size' => 1,
                    'required' => true
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('FAQ Category:'),
                    'name' => 'FAQ_BLOCK_CAT',
                    'required' => true,
                    'options' => array(
                        'query' => $cat_options,
                        'id' => 'FAQ_BLOCK_CAT',
                        'name' => 'name'
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );

        $helper = new HelperForm();

        // Module, t    oken and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->identifier = $this->identifier;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->languages = Language::getLanguages(false);
        foreach ($helper->languages as $k => $language)
            $helper->languages[$k]['is_default'] = ((int) ($language['id_lang'] == $default_lang));

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true; // false -> remove toolbar
        $helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        foreach (Language::getLanguages(false) as $language) {
            if (Tools::getValue('FAQ_BLOCK_NAME_' . $language['id_lang']))
                $helper->fields_value['FAQ_BLOCK_NAME'][$language['id_lang']] = Tools::getValue('FAQ_BLOCK_NAME_' . $language['id_lang']);
            else
                $helper->fields_value['FAQ_BLOCK_NAME'][$language['id_lang']] = Configuration::get('FAQ_BLOCK_NAME', $language['id_lang']);
        }


        if (Tools::getValue('FAQ_BLOCK_COL'))
            $helper->fields_value['FAQ_BLOCK_COL'] = Tools::getValue('FAQ_BLOCK_COL');
        else
            $helper->fields_value['FAQ_BLOCK_COL'] = Configuration::get('FAQ_BLOCK_COL');

        if (Tools::getValue('FAQ_BLOCK_NUM'))
            $helper->fields_value['FAQ_BLOCK_NUM'] = Tools::getValue('FAQ_BLOCK_NUM');
        else
            $helper->fields_value['FAQ_BLOCK_NUM'] = Configuration::get('FAQ_BLOCK_NUM');

        if (Tools::getValue('FAQ_BLOCK_CAT'))
            $helper->fields_value['FAQ_BLOCK_CAT'] = Tools::getValue('FAQ_BLOCK_CAT');
        else
            $helper->fields_value['FAQ_BLOCK_CAT'] = Configuration::get('FAQ_BLOCK_CAT');

        return $helper->generateForm($fields_form);
    }

    public function renderCategoryTree($field)
    {
        require_once dirname(__FILE__) . '/classes/helpers/HelperTreeFaq.php';

        $this->context->controller->addJS($this->getFramePathUri() . 'js/tree.js');
        $this->context->controller->addCSS($this->getFramePathUri() . 'css/tree.css');

        $tree = new InixHelper2TreeFaq($field['tree']['id'], isset($field['tree']['title']) ? $field['tree']['title'] : null);


        if (isset($field['name']))
            $tree->setInputName($field['name']);

        if (isset($field['tree']['selected_categories']))
            $tree->setSelectedCategories($field['tree']['selected_categories']);

        if (isset($field['tree']['disabled_categories']))
            $tree->setDisabledCategories($field['tree']['disabled_categories']);

        if (isset($field['tree']['root_category']))
            $tree->setRootCategory($field['tree']['root_category']);

        if (isset($field['tree']['use_search']))
            $tree->setUseSearch($field['tree']['use_search']);

        if (isset($field['tree']['use_checkbox']))
            $tree->setUseCheckBox($field['tree']['use_checkbox']);


        $tree->setModule($this);

        return $tree->render();
    }

    public function hookModuleRoutes($params)
    {
        return array(
            'faq_category_rule' => array(
                'controller' => 'category',
                'rule' => 'faq/{id}-{rewrite}',
                'keywords' => array(
                    'id' => array('regexp' => '[0-9]+', 'param' => 'id_faq_category'),
                    'rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                    'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                    'meta_title' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                    'fc' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                    'controller' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                    'module' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'faqextra',
                    'controller' => 'category',
                ),
            )
        );
    }

    public function hookFooter($params)
    {
        if (!$this->context->controller instanceof CmsController)
            return;

        $this->context->controller->addCSS($this->getFramePathUri() . 'css/bootstrap.css');
        $this->context->controller->addJS($this->getFramePathUri() . 'js/vendor/bootstrap.min.js');
        $this->context->controller->addCSS($this->getFramePathUri() . 'css/style.css');

        $cms = $this->context->smarty->getVariable('cms');
        $cms = $cms->value;

        if(Tools::version_compare(_PS_VERSION_,'1.7')) {
            if (isset($cms->content) && !empty($cms->content)) {
                $cms->content = $this->doShortcode($cms->content);
            }
        }
        else{
            if (isset($cms['content']) && !empty($cms['content'])) {
                $page_content = ob_get_contents();
                $new_tpl = $this->doShortcode($page_content);

                ob_get_clean();
                echo $new_tpl;
            }
        }
    }

    private function doShortcode($string, $shortcode = 'faq')
    {
        preg_match_all('/\[(.*?):(.*?)\]/', $string, $matches);
        foreach ($matches[1] as $k => $m) { // get only shortcodes for slidersEverywhere, you don't know if someone else start placing shortcodes
            if ($m == $shortcode) {
                $pos = strpos($string, $matches[0][$k]);
                if ($pos !== false) {
                    $string = substr_replace($string, $this->prepareFaq($matches[2][$k]), $pos, strlen($matches[0][$k]));
                }
            }
        }
        return $string;
    }

    private function prepareFaq($id_faq_category)
    {

        if (!Validate::isUnsignedId($id_faq_category))
            return;

        if (!Validate::isLoadedObject($faq = new FAQCategory((int) $id_faq_category, $this->context->language->id)))
            ;

        $id_shop = null;
        if (Shop::isFeatureActive())
            $id_shop = (int) $this->context->shop->id;

        $id_lang = $this->context->language->id;
        $faq_categories = array($faq);
        $faq_pages = array();



        $faq_pages[$faq->id] = FAQ::getFAQPages($id_lang, $faq->id, true, $id_shop);

        $this->context->smarty->assign(array(
            'faq_categories' => $faq_categories,
            'faq_pages1' => $faq_pages
        ));



        return $this->display(__FILE__, 'cms.tpl');
    }

}
