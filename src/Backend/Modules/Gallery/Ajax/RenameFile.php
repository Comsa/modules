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
            $image = BackendGalleryModel::get($id);
            $name = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $nameGet);
            $folders = BackendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/Gallery/Images', true);
            $fs = new Filesystem();
            $extension = pathinfo($image['filename'], PATHINFO_EXTENSION);

            if($image['filename'] != $name . '.' . $extension)
            {
                if(!$fs->exists(FRONTEND_FILES_PATH . '/Gallery/Images/Source/' . $name . '.' . $extension)){
                    if ($fs->exists(FRONTEND_FILES_PATH . '/Gallery/Images/Source/' . $image['filename']))
                    {
                        $fs->rename(FRONTEND_FILES_PATH . '/Gallery/Images/Source/' . $image['filename'], FRONTEND_FILES_PATH . '/Gallery/Images/Source/' . $name . '.' . $extension);
                    }

                    foreach ($folders as $folder)
                    {
                        if ($fs->exists(FRONTEND_FILES_PATH . '/Gallery/Images/' . $folder['dirname'] . '/' . $image['filename']))
                        {
                            $fs->rename(FRONTEND_FILES_PATH . '/Gallery/Images/' . $folder['dirname'] . '/' . $image['filename'], FRONTEND_FILES_PATH . '/Gallery/Images/' . $folder['dirname'] . '/' . $name . '.' . $extension);
                        }
                    }

                    $image['filename'] = $name . '.' . $extension;

                    BackendGalleryModel::update($image);
                    $this->output(self::OK, null, 'file renamed');
                }else{
                    $this->output(self::ERROR, null, 'file name already exists');
                }
            }else{
                $this->output(self::OK, null, 'file name is the same');
            }
        }
        // success output
    }
}
