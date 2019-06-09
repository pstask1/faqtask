<?php

class Link extends LinkCore
{	
	
	/**
	 * Create a link to a FAQ page
	 *
	 * @param mixed $faq FAQ object (can be an ID FAQ, but deprecated)
	 * @param string $alias
	 * @param bool $ssl
	 * @param int $id_lang
	 * @return string
	 */

	public function getFAQLink($faq, $alias = null, $ssl = null, $id_lang = null, $id_shop = null)
	{
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;

		$url = $this->getBaseLink($id_shop, $ssl).$this->getLangLink($id_lang, null, $id_shop);

		$dispatcher = Dispatcher::getInstance();
		if (!is_object($faq))
		{
			if ($alias !== null && !$dispatcher->hasKeyword('faq_rule', $id_lang, 'meta_keywords', $id_shop) && !$dispatcher->hasKeyword('faq_rule', $id_lang, 'meta_title', $id_shop))
				return $url.$dispatcher->createUrl('faq_rule', $id_lang, array('id' => (int)$faq, 'rewrite' => (string)$alias), $this->allow, '', $id_shop);
			$faq = new FAQ($faq, $id_lang);
		}

		// Set available keywords
		$params = array();
		$params['id'] = $faq->id;
		$params['rewrite'] = (!$alias) ? (is_array($faq->link_rewrite) ? $faq->link_rewrite[(int)$id_lang] : $faq->link_rewrite) : $alias;

		$params['meta_keywords'] = '';
		if (isset($faq->meta_keywords) && !empty($faq->meta_keywords))
			$params['meta_keywords'] = is_array($faq->meta_keywords) ?  Tools::str2url($faq->meta_keywords[(int)$id_lang]) :  Tools::str2url($faq->meta_keywords);

		$params['meta_title'] = '';
		if (isset($faq->meta_title) && !empty($faq->meta_title))
			$params['meta_title'] = is_array($faq->meta_title) ? Tools::str2url($faq->meta_title[(int)$id_lang]) : Tools::str2url($faq->meta_title);

		return $url.$dispatcher->createUrl('faq_rule', $id_lang, $params, $this->allow, '', $id_shop);
	}

	/**
	 * Create a link to a FAQ category
	 *
	 * @param mixed $category FAQCategory object (can be an ID category, but deprecated)
	 * @param string $alias
	 * @param int $id_lang
	 * @return string
	 */
	public function getFAQCategoryLink($faq_category, $alias = null, $id_lang = null, $id_shop = null)
	{
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;

		$url = $this->getBaseLink($id_shop).$this->getLangLink($id_lang, null, $id_shop);

		$dispatcher = Dispatcher::getInstance();
		if (!is_object($faq_category))
		{
			if ($alias !== null && !$dispatcher->hasKeyword('faq_category_rule', $id_lang, 'meta_keywords', $id_shop) && !$dispatcher->hasKeyword('faq_category_rule', $id_lang, 'meta_title', $id_shop))
				return $url.$dispatcher->createUrl('faq_category_rule', $id_lang, array('id' => (int)$faq_category, 'rewrite' => (string)$alias, 'fc' => 'module', 'controller' => 'category', 'module' => 'faqextra'), $this->allow, '', $id_shop);
			$faq_category = new FAQCategory($faq_category, $id_lang);			
		}

		// Set available keywords
		$params = array();
		$params['id'] = $faq_category->id;
		$params['rewrite'] = (!$alias) ? $faq_category->link_rewrite : $alias;
		$params['fc'] = 'module';
		$params['controller'] = 'category';				
		$params['module'] = 'faqextra';	
		$params['meta_keywords'] = '';
		if (isset($faq_category->meta_keywords) && !empty($faq_category->meta_keywords))
			$params['meta_keywords'] = is_array($faq_category->meta_keywords) ?  Tools::str2url($faq_category->meta_keywords[(int)$id_lang]) :  Tools::str2url($faq_category->meta_keywords);

		$params['meta_title'] = '';
		if (isset($faq_category->meta_title) && !empty($faq_category->meta_title))
			$params['meta_title'] = is_array($faq_category->meta_title) ? Tools::str2url($faq_category->meta_title[(int)$id_lang]) : Tools::str2url($faq_category->meta_title);	

		return $url.$dispatcher->createUrl('faq_category_rule', $id_lang, $params, $this->allow, '', $id_shop);
	}
}