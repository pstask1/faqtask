<?php

class FaqExtraCategoryModuleFrontController  extends ModuleFrontController
{
	public $faq_category;
	public $ssl = false;


	/**
	 * Initialize faq controller
	 * @see FrontController::init()
	 */
	public function init()
	{
		 
		if ($id_faq_category = (int)Tools::getValue('id_faq_category'))
			$this->faq_category = new FAQCategory($id_faq_category, $this->context->language->id);

		parent::init();

	}

	public function setMedia()
	{ 
		parent::setMedia();
		$this->context->controller->addCSS($this->module->getFramePathUri().'css/bootstrap.css');
		$this->context->controller->addJS($this->module->getFramePathUri().'js/vendor/bootstrap.min.js');
		$this->context->controller->addCSS($this->module->getFramePathUri().'css/style.css');
	}

	/**
	 * Assign template vars related to page content
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{ 
		parent::initContent();

		$parent_cat = new FAQCategory(1, $this->context->language->id);
		$this->context->smarty->assign('id_current_lang', $this->context->language->id);
		$this->context->smarty->assign('home_title', $parent_cat->name);

		if ($this->faq_category->indexation == 0)
			$this->context->smarty->assign('nobots', true);
			
		$id_shop = null;
		if (Shop::isFeatureActive())
			$id_shop = (int)$this->context->shop->id;		
		$categories = FAQCategory::getCategories($this->context->language->id, false);
		$selected_category = 0;
		$query = '';
		if (Tools::isSubmit('submitSearch'))
			{
			$selected_category = (int)Tools::getValue('id_faq_category');
			$query = Tools::getValue('query');
			$this->context->smarty->assign(array(
				'search_result' => FAQ::getFAQPages($this->context->language->id, (int)($selected_category), true, $id_shop, null, $query),
			));
			}
			
		$this->context->smarty->assign(array(
			'faq_category' => $this->faq_category,
			'sub_category' => $this->faq_category->getSubCategories($this->context->language->id),
			'faq_pages' => FAQ::getFAQPages($this->context->language->id, (int)($this->faq_category->id), true, $id_shop),
			'path' => $this->getPath($this->faq_category->id, $this->faq_category->name),	
			'html_categories' => FAQCategory::recurseFAQCategory($categories, $categories[0][1], 1, $selected_category, 1),
			'query' => $query,	
		));
		
                if(Tools::version_compare(_PS_VERSION_, '1.7')){
		$this->setTemplate('faq_front.tpl');
                }
                else{
                    $this->setTemplate('module:faqextra/views/templates/front/ps17/faq_front.tpl');
                }
	}

	
	private function getPath($id_category, $path = '', Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();

		$id_category = (int)$id_category;
		if ($id_category == 0)
			return '<span class="navigation_end">'.$path.'</span>';

		$pipe = Configuration::get('PS_NAVIGATION_PIPE');
		if (empty($pipe))
			$pipe = '>';

		$full_path = '';
		$category = new FAQCategory($id_category, $context->language->id);
		if (!Validate::isLoadedObject($category))
			die(Tools::displayError());
		$category_link = $context->link->getFAQCategoryLink($category);

		if ($path != $category->name)
			$full_path .= '<a href="'.Tools::safeOutput($category_link).'">'.htmlentities($category->name, ENT_NOQUOTES, 'UTF-8').'</a><span class="navigation-pipe">'.$pipe.'</span>'.$path;
		else
			$full_path = htmlentities($path, ENT_NOQUOTES, 'UTF-8');

		return $this->getPath($category->id_parent, $full_path);
		
	}
}
