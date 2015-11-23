<?php

namespace Backend\Modules\Media\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */
use Backend\Core\Engine\Base\AjaxAction;
use Backend\Modules\Media\Engine\Helper as BackendMediaHelper;
use Backend\Core\Engine\Template;

/**
 * Reorder images
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class GetLink extends AjaxAction
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        //--Get the ids and split them
        $id = \SpoonFilter::getPostValue('id', null, '', 'string');

        //--Check if the id is not empty
//        if(!empty($id))
//        {
//            //--Set the sequence to 1
//                BackendMediaModel::deleteLink($id);
//        }

        $tpl = new Template();

        $tpl->assign('mediaItems', BackendMediaHelper::getAll());

        $html = $tpl->getContent(BACKEND_MODULES_PATH . '/Media/Layout/Templates/Ajax/Link.tpl');

        // success output
        $this->output(self::OK, $html, '');
    }
}
