<?php
namespace Backend\Modules\Media\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * BackendPagesGenerateImages
 * This action searches for every modules if there are files, and checks if the images in the source-folder also are generated in the size-folders
 *
 * @author Waldo Cosman <waldo@comsa.be> <waldo_cosman@hotmail.com>
 */

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Model as BackendModel;

class ResizeImages extends ActionEdit
{

	/**
	 * Execute the action
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();
		ini_set('max_execution_time', 150);

		// Get all the modules
		$modules = BackendModel::getModules();

		// Check if modules is not empty
		if(!empty($modules))
		{
			// Create filesystem object
			$filesystem = new Filesystem();

			// Loop the modules
			foreach($modules as $module)
			{

				// Create var dir for ease of use
				$dir = FRONTEND_FILES_PATH . '/' . $module . '/Images/';

				// Check if dir exists
				if($filesystem->exists($dir . 'Source/'))
				{

					// Create Finder object
					$finderDir = new Finder();

					// Get all the dirs in the modules/images folder.
					$dirs = $finderDir->directories()->in($dir)->exclude(array("Source", 'source'));

					// Check if $dirs is not empty
					if(!empty($dirs))
					{
						// Create Finder object for the files
						$finderFiles = new Finder();

						// Get all the files in the source-dir
						$files = $finderFiles->files()->in($dir . 'Source/');

						// Check if $files is not empty
						if(!empty($files))
						{
							// Loop all the dirs
							foreach($dirs as $row)
							{

								// Explode the dir-name
								$chunks = explode("x", $row->getBasename(), 2);

								// Create folder array
								$folder = array();
								$folder['dirname'] = $row->getBasename();
								$folder['path'] = $row->getRealPath();
								$folder['width'] = ($chunks[0] != '') ? (int)$chunks[0] : null;
								$folder['height'] = ($chunks[1] != '') ? (int)$chunks[1] : null;

								// Loop all the files
								foreach($files as $file)
								{

									set_time_limit(150);

									// Check if the file exists
									if(!$filesystem->exists($dir . $row->getBasename() . '/' . $file->getBasename()))
									{


										// generate the thumbnail
										$thumbnail = new \SpoonThumbnail($dir . 'Source/' . $file->getBasename(), $folder['width'], $folder['height']);
										$thumbnail->setAllowEnlargement(true);

										// if the width & height are specified we should ignore the aspect ratio
										if($folder['width'] !== null && $folder['height'] !== null) $thumbnail->setForceOriginalAspectRatio(false);
										$thumbnail->parseToFile($folder['path'] . '/' . $file->getBasename());
									}
								};
							}
						}
					}
				}
			}
		}

		echo "ok";
		die();
		// redirect
		//$this->redirect(BackendModel::createURLForAction('index') . '&report=generate_images');
	}
}