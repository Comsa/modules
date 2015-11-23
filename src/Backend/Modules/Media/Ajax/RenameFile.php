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
class RenameFile extends AjaxAction
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        //--Get the ids and split them
        $id = \SpoonFilter::getPostValue('id', null, '', 'string');
        $nameGet = \SpoonFilter::getPostValue('name', null, '', 'string');

        //--Check if the id is not empty
        if(!empty($id))
        {
            //--Set the sequence to 1
            $mediaModule = BackendMediaModel::getMediaModule($id);
            $media = BackendMediaModel::get($mediaModule['media_id']);
            $name = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $nameGet);
            $folders = BackendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/Media/Images', true);
            $fs = new Filesystem();
            if($media['filename'] != $name . '.' . $media['extension'])
            {
                //--Create the image-links to the thumbnail folders
                if ($media['filetype'] == 1)
                {

                    if ($fs->exists(FRONTEND_FILES_PATH . '/Media/Images/Source/' . $media['filename']))
                    {
                        $fs->rename(FRONTEND_FILES_PATH . '/Media/Images/Source/' . $media['filename'], FRONTEND_FILES_PATH . '/Media/Images/Source/' . $name . '.' . $media['extension']);
                    }

                    foreach ($folders as $folder)
                    {
                        if ($fs->exists(FRONTEND_FILES_PATH . '/Media/Images/' . $folder['dirname'] . '/' . $media['filename']))
                        {
                            $fs->rename(FRONTEND_FILES_PATH . '/Media/Images/' . $folder['dirname'] . '/' . $media['filename'], FRONTEND_FILES_PATH . '/Media/Images/' . $folder['dirname'] . '/' . $name . '.' . $media['extension']);
                        }
                    }
                }
                else
                {
                    if ($fs->exists(FRONTEND_FILES_PATH . '/Media/Files/' . $media['filename']))
                    {
                        $fs->rename(FRONTEND_FILES_PATH . '/Media/Files/' . $media['filename'], FRONTEND_FILES_PATH . '/Media/Files/' . $name . '.' . $media['extension']);
                    }
                }

                $media['filename'] = $name . '.' . $media['extension'];

                BackendMediaModel::update($mediaModule['media_id'], $media);
                $url = FRONTEND_FILES_URL . '/Media/Files/' . $media['filename'];
                $this->output(self::OK, $url, 'file renamed');
            }else{
                $this->output(self::OK, null, 'file name is the same');
            }
        }
        // success output
    }
}
