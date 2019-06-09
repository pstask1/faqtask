<?php
class CmsController extends CmsControllerCore
{

	public function setMedia()
	{ 
		parent::setMedia();
//		$this->context->controller->addJS(_PS_MODULE_DIR_.'faqextra/js/faq.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'modules/faqextra/views/js/faq.js');
		$this->context->controller->addCSS(__PS_BASE_URI__.'modules/faqextra/views/css/faq.css');

	}
	
	public function initContent()
	{
		$id_shop = null;
		if (Shop::isFeatureActive())
			$id_shop = (int)$this->context->shop->id;	
		$is_cms_category = '|1_'.$this->context->controller->cms->id_cms_category.'|';
		$is_cms = '|0_'.$this->context->controller->cms->id.'|';
		$id_lang = $this->context->language->id;
		
		$sql = 'SELECT c.`id_faq_category`, cl.`name`
				FROM `'._DB_PREFIX_.'faq_category` c
				LEFT JOIN `'._DB_PREFIX_.'faq_category_lang` cl ON c.`id_faq_category` = cl.`id_faq_category`
				WHERE (c.`cms_hook` LIKE \'%'.$is_cms_category.'%\' 
				OR c.`cms_hook` LIKE \'%'.$is_cms.'%\')
				AND c.`active` = 1
				AND `id_lang` = '.(int)$id_lang;				 
		$faq_categories = Db::getInstance()->executeS($sql);
		$faq_pages = array();

		include_once(_PS_MODULE_DIR_.'faqextra/classes/FAQ.php');
		foreach ($faq_categories as $row)
			$faq_pages[$row['id_faq_category']] = FAQ::getFAQPages($id_lang, $row['id_faq_category'], true, $id_shop);
		
		$this->context->smarty->assign(array(
		'faq_categories' => $faq_categories,
		'faq_pages1' => $faq_pages
		));

		parent::initContent();
		$this->setTemplate(_PS_MODULE_DIR_.'faqextra/views/templates/front/cms.tpl');
	}
}
