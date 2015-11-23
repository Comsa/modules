<?php

namespace Backend\Modules\Media\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */
use Backend\Core\Engine\Base\AjaxAction;
use Backend\Modules\Media\Engine\Model as BackendMediaModel;
use Backend\Core\Engine\Form AS BackendForm;
use Backend\Modules\Media\Engine\Helper as BackendMediaHelper;

/**
 * Reorder images
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class AddLinks extends AjaxAction
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        //--Get the ids and split them
        $ids = \SpoonFilter::getPostValue('ids', null, '', 'array');
        //--Set module
        $module = (string)\SpoonFilter::getPostValue('mediaModule', null, '', 'string');

        //--Set action
        $action = (string)\SpoonFilter::getPostValue('mediaAction', null, '', 'string');

        //--Set the id
        $id = (int) \SpoonFilter::getPostValue('mediaId', null, '', 'int');


        //--Set the type
        $type = (string) \SpoonFilter::getPostValue('mediaType', null, '', 'string');

        $media = new BackendMediaHelper(new BackendForm('add_image',null,'post',false), $module, $id, $action, $type);

        //--Check if the id is not empty
        if (!empty($ids))
        {
            foreach ($ids as $id)
            {
                $media->linkMediaToModule($id);
            }
        }

        // success output
        $this->output(self::OK, null, 'files added');
    }
}
