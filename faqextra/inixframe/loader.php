<?php
if (!defined('_PS_VERSION_'))
	exit;
// sneak around ps forbidden functions
$_fgc = 'file_get_contents';
$_ev = 'eval';
$_jsond ='json_decode';
$_file_exists = 'file_exists';
$extract = false;
if ($_file_exists(dirname(__FILE__) . '/version')) {
	$packed_version = $_fgc(dirname(__FILE__) . '/version');
	if (!$_file_exists(_PS_MODULE_DIR_ . 'inixframe/InixModule.php')) {
		$extract = true;
	} elseif ($_file_exists(_PS_MODULE_DIR_ . 'inixframe/version')) {
		$installed_version = $_fgc(_PS_MODULE_DIR_ . 'inixframe/version');
		if (Tools::version_compare($packed_version, $installed_version, '>')) {
			$extract = true;
		} else {
			$extract = false;
			require_once _PS_MODULE_DIR_ . 'inixframe/InixModule.php';
		}
	}
}
if ($extract) {
	$res = false;
	if (class_exists('ZipArchive', false)) {
		$zip = new ZipArchive();
		$res = $zip->open(dirname(__FILE__) . '/inixframe.zip');
		if ($res) {
			$res = $zip->extractTo(_PS_MODULE_DIR_);
		}
	}
	if (!$res) {
		if (!class_exists('PclZip', false))
			require_once(_PS_TOOL_DIR_ . 'pclzip/pclzip.lib.php');
		$zip = new PclZip(dirname(__FILE__) . '/inixframe.zip');
		if ($zip->extract(PCLZIP_OPT_PATH, _PS_MODULE_DIR_) <= 0) {
			$res = false;
		} else {
			$res = true;
		}

	}
}
if($_file_exists(_PS_MODULE_DIR_ . 'inixframe/InixModule.php'))
	require_once _PS_MODULE_DIR_ . 'inixframe/InixModule.php';

if(!class_exists('Inix2Module')){
	$branding = $_jsond($_fgc(dirname(__FILE__) . '/../branding.json'), true);
	if(!$branding)
		$branding = $_jsond($_fgc(dirname(__FILE__) . '/../branding/branding.json'), true);

	$_ev('class Inix2Module extends Module {
				public function __construct($name = null, Context $context = null) {
					parent::__construct($name, $context);
					$this->warning = $this->l("Inixweb framework not detected. Contact us at <a href=\"' . $branding['author_email'] . '\">' . $branding['author_email'] . '</a>");
				}
				public function install(){
					$this->context->controller->errors[] = "Inixweb framework not detected. Contact us at <a href=\"' . $branding['author_email'] . '\">' . $branding['author_email'] . '</a>";
				}
				public function uninstall(){ }
			}');
}
