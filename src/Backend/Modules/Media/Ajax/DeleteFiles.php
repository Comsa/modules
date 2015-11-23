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
use Backend\Core\Engine\Model as BackendModel;
use Symfony\Component\Filesystem\Filesystem;
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

        $fs = new Filesystem();
        $folders = BackendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/Media/Images', true);

        //--Check if the id is not empty
        if (!empty($ids))
        {
            foreach ($ids as $id)
            {
                $mediaModule = BackendMediaModel::getMediaModule($id);
                BackendMediaModel::deleteLink($id);
                if(!BackendMediaModel::existsMediaModules($id))
                {
                    $media = BackendMediaModel::get($mediaModule['media_id']);
                    if($media['filetype'] == 1){

                        if($fs->exists(FRONTEND_FILES_PATH . '/Media/Images/Source/' . $media['filename']))
                            $fs->remove(FRONTEND_FILES_PATH . '/Media/Images/Source/' . $media['filename']);

                        foreach($folders as $folder)
                        {
                            if($fs->exists(FRONTEND_FILES_PATH . '/Media/Images/' . $folder['dirname'] . '/' . $media['filename']))
                                $fs->remove(FRONTEND_FILES_PATH . '/Media/Images/' . $folder['dirname'] . '/' . $media['filename']);
                        }
                    }else
                    {
                        if($fs->exists(FRONTEND_FILES_PATH . '/Media/Files/' . $media['filename']))
                            $fs->remove(FRONTEND_FILES_PATH . '/Media/Files/' . $media['filename']);
                    }
                    BackendMediaModel::delete($mediaModule['media_id']);
                }
            }
        }

        // success output
        $this->output(self::OK, null, 'files deleted');
    }
}
