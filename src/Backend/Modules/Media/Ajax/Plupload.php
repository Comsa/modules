<?php

namespace Backend\Modules\Media\Ajax;
//Array
//(
//	[form] => add_image
//    [form_token] => b08e6c6965119246bf3ed489c6a954cc
//)
//Array
//(
//	[images] => Array
//	(
//		[name] => stock-photo-18118591-sausage-and-vegetables.jpg
//            [type] => image/jpeg
//            [tmp_name] => /Applications/MAMP/tmp/php/phpQ0RGte
//            [error] => 0
//            [size] => 95749
//        )
//
//)

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */
use Backend\Core\Engine\Base\AjaxAction;
use Backend\Core\Engine\Form AS BackendForm;
use Backend\Modules\Media\Engine\Model as BackendMediaModel;
use Backend\Modules\Media\Engine\Helper as BackendMediaHelper;
use Frontend\Core\Engine\Language AS FrontendLanguage;
use Backend\Core\Engine\Template;
use Backend\Core\Engine\Model as BackendModel;

/**
 * This is an ajax handler
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class Plupload extends AjaxAction
{
	/**
	 * @var BackendMediaHelper
	 */
	private $media;

	/**
	 * Contstructor
	 *
	 */
	public function execute()
	{
		parent::execute();

		//--Set post var to check submit
		$_POST["form"] = "add_image";

		//--Set module
        $module = (string)\SpoonFilter::getPostValue('mediaModule', null, '', 'string');

        //--Set action
        $action = (string)\SpoonFilter::getPostValue('mediaAction', null, '', 'string');

        //--Set the id
        $id = (int) \SpoonFilter::getPostValue('mediaId', null, '', 'int');


        //--Set the type
        $type = (string) \SpoonFilter::getPostValue('mediaType', null, '', 'string');

		//--Create media helper
		$this->media = new BackendMediaHelper(new BackendForm('add_image',null,'post',false), $module, $id, $action, $type);

		//--Validate media
		$this->media->validate();

        if($this->media->item['filetype'] == 1)
        {
            $tpl = new Template();

            $this->media->item['txtText'] = $this->media->frm->addTextarea("text-" . $this->media->item["id"], $this->media->item['text'])->setAttribute('style', 'resize: none;')->parse();

            $path_parts = pathinfo(FRONTEND_FILES_PATH . '/Media/Images/Source/' . $this->media->item['filename']);
            $this->media->item['name'] = $path_parts['filename'];
            $folders = BackendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/Media/Images', true);

            foreach ($folders as $folder)
            {
                $this->media->item['image_' . $folder['dirname']] = $folder['url'] . '/' . $folder['dirname'] . '/' . $this->media->item['filename'];
            }

            $tpl->assign('image', $this->media->item);

            $html = $tpl->getContent(BACKEND_MODULES_PATH . '/Media/Layout/Templates/Ajax/Image.tpl');
        }else{
            $tpl = new Template();

            $this->media->item['txtText'] = $this->media->frm->addTextarea("text-" . $this->media->item["id"], $this->media->item['text'])->setAttribute('style', 'resize: none;')->parse();

            $path_parts = pathinfo(FRONTEND_FILES_PATH . '/Media/Files/' . $this->media->item['filename']);
            $this->media->item['url'] = FRONTEND_FILES_URL . '/Media/Files/' . $this->media->item['filename'];
            $this->media->item['name'] = $path_parts['filename'];

            $tpl->assign('file', $this->media->item);

            $html = $tpl->getContent(BACKEND_MODULES_PATH . '/Media/Layout/Templates/Ajax/File.tpl');
        }
		// output
		$this->output(self::OK, array($this->media->item['filetype'], $html), FrontendLanguage::msg('Success'));
	}
}
