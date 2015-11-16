<?php
namespace Backend\Modules\Gallery\Actions;


use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Gallery\Engine\Model as BackendGalleryModel;
/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the delete-action, it deletes an item
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @author Waldo Cosman <waldo@comsa.be>
 */
class DeleteCategory extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');
		
		// does the item exist
		if($this->id !== null && BackendGalleryModel::existsCategory($this->id))
		{
			parent::execute();
			
			// is this category allowed to be deleted?
			if(!BackendGalleryModel::deleteCategoryAllowed($this->id))
			{
				$this->redirect(BackendModel::createURLForAction('categories') . '&error=category-not-deletable');
			}

			else
			{
				// get category
				$this->record = BackendGalleryModel::getCategoryFromId($this->id);

				// delete category
				BackendGalleryModel::deleteCategoryById($this->id);

				BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('id' => $this->id));


				$this->redirect(BackendModel::createURLForAction('categories') . '&report=category-deleted&var=' . urlencode($this->record['title']));
			}
		}
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}
