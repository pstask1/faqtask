<?php


class InixHelper2TreeFaq extends InixHelper2TreeCategories
{

	public function getData()
	{

		if (!isset($this->_data))
			$this->setData(self::getNestedCategories(
				$this->getRootCategory(), $this->getLang(), false, null, $this->useShopRestriction()));

		return $this->_data;


	}


	public static function getNestedCategories($root_category = null, $id_lang = false, $active = true, $groups = null,
	                                           $use_shop_restriction = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
	{
		if (isset($root_category) && !Validate::isInt($root_category))
			die(Tools::displayError());

		if (!Validate::isBool($active))
			die(Tools::displayError());

		if (isset($groups) && Group::isFeatureActive() && !is_array($groups))
			$groups = (array)$groups;

		$cache_id = 'FaqCategory::getNestedCategories_'.md5((int)$root_category.(int)$id_lang.(int)$active.(int)$active
				.(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance()->executeS('
				SELECT c.*, cl.*, c.id_faq_category as id_category
				FROM `'._DB_PREFIX_.'faq_category` c
				LEFT JOIN `'._DB_PREFIX_.'faq_category_lang` cl ON c.`id_faq_category` = cl.`id_faq_category`

				WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
				'.($active ? ' AND c.`active` = 1' : '').'
				'.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
				'.($sql_limit != '' ? $sql_limit : '')
			);

			$categories = array();
			$buff = array();
			if (!isset($root_category))
				$root_category = 1;

			foreach ($result as $row)
			{
				$current = &$buff[$row['id_faq_category']];
				$current = $row;

				if ($row['id_faq_category'] == $root_category) {
					$categories[$row['id_faq_category']] = &$current;
				} else {
					$buff[$row['id_parent']]['children'][$row['id_faq_category']] = &$current;
				}


			}

			Cache::store($cache_id, $categories);
		}

		$retrieve = Cache::retrieve($cache_id);

		return $retrieve;
	}



}
