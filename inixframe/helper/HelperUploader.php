<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!class_exists('Uploader')) {
    require_once _PS_MODULE_DIR_ . 'inixframe/classes/Uploader.php';
    eval('class Uploader extends UploaderCore {}');
}


class Inix2HelperUploader extends Uploader
{
    const DEFAULT_TEMPLATE_DIRECTORY = 'helpers/uploader';
    const DEFAULT_TEMPLATE = 'simple.tpl';
    const DEFAULT_AJAX_TEMPLATE = 'ajax.tpl';

    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';

    private $_context;
    private $_drop_zone;
    private $_id;
    private $_files;
    private $_name;
    private $_max_files;
    private $_multiple;
    private $_post_max_size;
    protected $_template;
    private $_template_directory;
    private $_title;
    private $_url;
    private $_use_ajax;
    private $_force_upload = false;

    /**
     * @var Inix2Module
     */
    private $module;

    /**
     * @param Inix2Module $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return Inix2Module
     */
    public function getModule()
    {
        return $this->module;
    }


    public function setContext($value)
    {
        $this->_context = $value;

        return $this;
    }

    public function getContext()
    {
        if (!isset($this->_context)) {
            $this->_context = Context::getContext();
        }

        return $this->_context;
    }

    public function setDropZone($value)
    {
        $this->_drop_zone = $value;

        return $this;
    }

    public function getDropZone()
    {
        if (!isset($this->_drop_zone)) {
            $this->setDropZone("$('#" . $this->getId() . "-add-button')");
        }

        return $this->_drop_zone;
    }

    public function setId($value)
    {
        $this->_id = (string) $value;

        return $this;
    }

    public function getId()
    {
        if (!isset($this->_id) || trim($this->_id) === '') {
            $this->_id = $this->getName();
        }

        return $this->_id;
    }

    public function setFiles($value)
    {
        $this->_files = $value;

        return $this;
    }

    public function getFiles()
    {
        if (!isset($this->_files)) {
            $this->_files = array();
        }

        return $this->_files;
    }

    public function setMaxFiles($value)
    {
        $this->_max_files = isset($value) ? intval($value) : $value;

        return $this;
    }

    public function getMaxFiles()
    {
        return $this->_max_files;
    }

    public function setMultiple($value)
    {
        $this->_multiple = (bool) $value;

        return $this;
    }

    public function setName($value)
    {
        $this->_name = (string) $value;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPostMaxSize($value)
    {
        $this->_post_max_size = $value;

        return $this;
    }

    public function getPostMaxSize()
    {
        if (!isset($this->_post_max_size)) {
            $this->_post_max_size = parent::getPostMaxSize();
        }

        return $this->_post_max_size;
    }

    public function setTemplate($value)
    {
        $this->_template = $value;

        return $this;
    }

    public function getTemplate()
    {
        if (!isset($this->_template)) {
            $this->setTemplate(self::DEFAULT_TEMPLATE);
        }

        return $this->_template;
    }

    public function setTemplateDirectory($value)
    {
        $this->_template_directory = $value;

        return $this;
    }

    public function getTemplateDirectory()
    {
        if (!isset($this->_template_directory)) {
            $this->_template_directory = self::DEFAULT_TEMPLATE_DIRECTORY;
        }

        return $this->_normalizeDirectory($this->_template_directory);
    }

    /**
     * @return boolean
     */
    public function isForceUpload()
    {
        return $this->_force_upload;
    }

    /**
     * @param boolean $force_upload
     *
     * @return $this
     */
    public function setForceUpload($force_upload)
    {
        $this->_force_upload = $force_upload;

        return $this;
    }


    public function getTemplateFile($template)
    {
        if (preg_match_all('/((?:^|[A-Z])[a-z]+)/', get_class($this->getContext()->controller), $matches) !== false) {
            $controllerName = strtolower($matches[0][1]);
        }

        if ($this->getContext()->controller instanceof Inix2AdminController and file_exists(
            $this->_normalizeDirectory(
                $this->getContext()->controller->getTemplatePath()
            ) .
            $controllerName . DIRECTORY_SEPARATOR . $this->getTemplateDirectory() . $template
        )
        ) {
            return $this->_normalizeDirectory(
                $this->getContext()->controller->getTemplatePath()
            ) .
                   $controllerName . DIRECTORY_SEPARATOR . $this->getTemplateDirectory() . $template;

        } elseif (file_exists(
            $this->_normalizeDirectory(
                $this->module->getLocalPath() . 'views/templates/admin/inixframe/'
            ) . $this->getTemplateDirectory() . $template
        )) {
            return $this->_normalizeDirectory(
                $this->module->getLocalPath() . 'views/templates/admin/inixframe/'
            ) . $this->getTemplateDirectory() . $template;

        } elseif (file_exists(
            $this->_normalizeDirectory(
                $this->module->getLocalPath() . 'views/templates/inixframe/'
            ) . $this->getTemplateDirectory() . $template
        )) {
            return $this->_normalizeDirectory(
                $this->module->getLocalPath() . 'views/templates/inixframe/'
            ) . $this->getTemplateDirectory() . $template;

        } else {
            return $this->_normalizeDirectory(
                $this->module->getFrameLocalPath() . 'template/'
            ) . $this->getTemplateDirectory() . $template;
        }

    }

    public function setTitle($value)
    {
        $this->_title = $value;

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setUrl($value)
    {
        $this->_url = (string) $value;

        return $this;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUseAjax($value)
    {
        $this->_use_ajax = (bool) $value;

        return $this;
    }

    public function isMultiple()
    {
        return (isset($this->_multiple) && $this->_multiple);
    }

    public function render()
    {

        $this->getContext()->controller->addJqueryUI('ui.core');
        $this->getContext()->controller->addJqueryUI('ui.widget');
        $this->getContext()->controller->addJs($this->module->getFramePathUri() . 'js/jquery.iframe-transport.js');
        $this->getContext()->controller->addJs($this->module->getFramePathUri() . 'js/jquery.fileupload.js');
        $this->getContext()->controller->addJs($this->module->getFramePathUri() . 'js/jquery.fileupload-process.js');
        $this->getContext()->controller->addJs($this->module->getFramePathUri() . 'js/jquery.fileupload-validate.js');
        $this->getContext()->controller->addJs($this->module->getFramePathUri() . 'js/vendor/spin.js');
        $this->getContext()->controller->addJs($this->module->getFramePathUri() . 'js/vendor/ladda.js');

        if ($this->useAjax() && !isset($this->_template)) {
            $this->setTemplate(self::DEFAULT_AJAX_TEMPLATE);
        }

        $template = $this->getContext()->smarty->createTemplate(
            $this->getTemplateFile($this->getTemplate()),
            $this->getContext()->smarty
        );

        $template->assign(array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'url'           => $this->getUrl(),
            'multiple'      => $this->isMultiple(),
            'files'         => $this->getFiles(),
            'title'         => $this->getTitle(),
            'max_files'     => $this->getMaxFiles(),
            'post_max_size' => $this->getPostMaxSizeBytes(),
            'drop_zone'     => $this->getDropZone()
        ));

        return $template->fetch();
    }

    public function useAjax()
    {
        return (isset($this->_use_ajax) && $this->_use_ajax);
    }


    public function upload($file, $dest = null)
    {
        if ($this->validate($file)) {
            if (isset($dest) && is_dir($dest)) {
                $file_path = $dest;
            } else {
                $file_path = $this->getFilePath(isset($dest) ? $dest : $file['name']);
            }


            if ($file['tmp_name']) {
                if (is_uploaded_file($file['tmp_name'])) {
                    move_uploaded_file($file['tmp_name'], $file_path);
                } elseif ($this->isForceUpload()) {
                    rename($file['tmp_name'], $file_path);
                }
            } else // Non-multipart uploads (PUT method support)
            {
                file_put_contents($file_path, fopen('php://input', 'r'));
            }

            $file_size = $this->_getFileSize($file_path, true);

            if ($file_size === $file['size']) {
                $file['save_path'] = $file_path;
            } else {
                $file['size'] = $file_size;
                unlink($file_path);
                $file['error'] = Tools::displayError('Server file size is different from local file size');
            }
        }

        return $file;
    }
}
