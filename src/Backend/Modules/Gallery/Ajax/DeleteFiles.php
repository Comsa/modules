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
class DeleteFiles extends AjaxAction
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        //--Get the ids and split them
        $ids = \SpoonFilter::getPostValue('ids', null, '', 'array');

        //--Check if the id is not empty
        if (!empty($ids))
        {
            foreach ($ids as $id)
            {
                BackendGalleryModel::delete($id);
            }
        }

        // success output
        $this->output(self::OK, null, 'images deleted');
    }
}
