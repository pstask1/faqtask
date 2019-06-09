<?php
if (!defined('_PS_VERSION_')) exit;
include_once(dirname(__FILE__) . '/../classes/FAQCategory.php');
function upgrade_module_1_1_0(FaqExtra $module){

	$module->registerHook('displayFooter');

	DB::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'faq` ADD `date_add` DATETIME NOT NULL , ADD `date_upd` DATETIME NOT NULL ;');
	DB::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'faq_category` ADD `nleft` INT(11) NOT NULL , ADD `nright` INT(11) NOT NULL ;');


	FAQCategory::regenerateEntireNtree();

	$module->uninstallModuleTab('AdminFaqContent');
	$module->installModuleTab('AdminFaqCategories','FAQ Categories','AdminParentModules');
	$module->installModuleTab('AdminFaqContent','FAQ Content','AdminParentModules');


	rename($module->getLocalPath().'override/controllers/front/_CmsController.php',$module->getLocalPath().'override/controllers/front/CmsController.php');
	$module->removeOverride('CmsController');
	unlink($module->getLocalPath().'override/controllers/front/CmsController.php');
	unlink($module->getLocalPath().'controllers/admin/AdminFaqCategoriesController.php');
	unlink($module->getLocalPath().'controllers/admin/AdminFaqController.php');
	unlink($module->getLocalPath().'controllers/admin/FaqHelperList.php');
	unlink($module->getLocalPath().'AdminFaqContent.php');
	unlink($module->getLocalPath().'views/templates/admin/faq_content/content.tpl');

	return true;
}

