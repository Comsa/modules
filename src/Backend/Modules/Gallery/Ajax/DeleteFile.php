<?php

namespace Backend\Modules\Gallery\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */
use Backend\Core\Engine\Base\AjaxAction;
use Backend\Modules\Gallery\Engine\Model as BackendGalleryModel;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
/**
 * Reorder images
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class DeleteFile extends AjaxAction
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
        if(!empty($id))
        {
            BackendGalleryModel::delete($id);
        }

        // success output
        $this->output(self::OK, null, 'image deleted');
    }
}
