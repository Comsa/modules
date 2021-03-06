<?php
namespace Backend\Modules\Gallery\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Modules\Gallery\Engine\Model as BackendGalleryModel;
use Backend\Modules\Tags\Engine\Model as BackendTagsModel;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the edit-action, it will display a form with the item data to edit
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @author Waldo Cosman <waldo@comsa.be>
 */
class EditAlbum extends BackendBaseActionEdit
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */

	private $images = array();

	private $frmAddImage;

	private $frmDeleteImage;

	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists?
		if($this->id !== null && BackendGalleryModel::existsAlbum($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
			$this->getData();

			// load the form
			$this->loadForm();
			$this->loadFormAddImage();
			$this->loadFormDeleteImage();

			// validate the form
			$this->validateForm();
			$this->validateFormAddImage();
			$this->validateFormDeleteImage();

			// parse the form
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exceptions, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('albums') . '&error=non-existing');
	}

	/**
	 * Get the data
	 *
	 * @return void
	 */
	private function getData()
	{
		$this->record = BackendGalleryModel::getAlbumFromId($this->id);

		//--Get the images
		$this->images = BackendGalleryModel::getImagesForAlbum($this->id);
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit_album');
		$this->frmAddImage = new BackendForm('add_image');
		$this->frmDeleteImage = new BackendForm('delete_image');

		// get values for the form
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// set show-in-overview values
		$rbtShowOverviewValues[] = array('label' => BL::lbl('Yes'), 'value' => 'Y');
		$rbtShowOverviewValues[] = array('label' => BL::lbl('No'), 'value' => 'N');

		// create elements
		$this->frm->addText('title', $this->record['title']);
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addEditor('description', $this->record['description']);
		$this->frm->addText('tags', BackendTagsModel::getTags($this->URL->getModule(), $this->id), null, 'inputText tagBox', 'inputTextError tagBox');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
		$this->frm->addRadiobutton('show_in_overview', $rbtShowOverviewValues, $this->record['show_in_overview']);
		$this->frm->addDropdown('category', BackendGalleryModel::getCategoriesForDropdown(), $this->record['category_id']);

        $this->frm->addCheckbox('gallery', $this->record['extra_id_gallery'] != 0);
        $this->frm->addCheckbox('slideshow', $this->record['extra_id_slideshow'] != 0);

        // meta object
		$this->meta = new BackendMeta($this->frm, $this->record['meta_id'], 'title', true);
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadFormAddImage()
	{
		//--Add file upload to the add_image form
		$this->frmAddImage->addImage('images');
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadFormDeleteImage()
	{

		if(!empty($this->images))
		{
			//--Add delete field to the image
			foreach($this->images as &$image)
			{
				//--Create the checkbox and add to the delete_image form
				$chkDelete = $this->frmDeleteImage->addCheckbox("delete_" . $image["id"]);
				$txtDescription = $this->frmDeleteImage->addTextarea("description_" . $image["id"], $image['description']);

				//--Add the parsed data to the array
				$image["field_delete"] = $chkDelete->parse();
				$image['field_description'] = $txtDescription->setAttribute('style', 'resize: none;')->parse();
			}

			//--Destroy the last $image (because of the reference) -- sugested by http://php.net/manual/en/control-structures.foreach.php
			unset($image);
		}
	}

	/**
	 * Parse the form
	 *
	 * @return void
	 */
	protected function parse()
	{
        $this->header->addCSS('jquery.ui.plupload/jquery-ui.css');
        $this->header->addCSS('jquery.ui.plupload/jquery.ui.plupload.css');

        $this->header->addJS('moxie.min.js', null,false);
        $this->header->addJS('plupload.full.min.js', null,false);
        $this->header->addJS('jquery.ui.plupload/jquery.ui.plupload.js', null,false);
        $this->header->addJS('i18n/nl.js', null,false);

        //--Add javascript file
		//$this->header->addJS('Jquery.uploadify.min.js', null,false);
        //$this->header->addCSS('Uploadify.css');

        $this->header->addJS('Edit.js', null,false);

        // call parent
		parent::parse();

		// assign the category
		$this->tpl->assign('album', $this->record);
		$this->tpl->assign('images', $this->images);

		if($this->frmAddImage) $this->frmAddImage->parse($this->tpl);

		if($this->frmDeleteImage) $this->frmDeleteImage->parse($this->tpl);

		//--Add data to Javascript
		$this->header->addJsData("Gallery", "id", $this->id);

		// can the category be deleted?
		if(BackendGalleryModel::deleteAlbumAllowed($this->id)) $this->tpl->assign('showDelete', true);

		// get url
		$url = BackendModel::getURLForBlock($this->URL->getModule(), 'group');
		$url404 = BackendModel::getURL(404);
		if($url404 != $url) $this->tpl->assign('detailURL', SITE_URL . $url);
	}

	/**
	 * Validate the form
	 *
	 * @return void
	 */
	private function validateForm()
	{
		//--Check if the form is submitted
		if($this->frm->isSubmitted())
		{

			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('title')->isFilled(BL::err('TitleIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// first, build the album array
				$album['id'] = (int)$this->id;
				$album['extra_id_gallery'] = $this->record['extra_id_gallery'];
                $album['extra_id_slideshow'] = $this->record['extra_id_slideshow'];
				$album['title'] = (string)$this->frm->getField('title')->getValue();
				$album['description'] = (string)$this->frm->getField('description')->getValue();
				$album['category_id'] = (int)$this->frm->getField('category')->getValue();
				$album['meta_id'] = $this->meta->save();
				$album['language'] = (string)BL::getWorkingLanguage();
				$album['hidden'] = (string)$this->frm->getField('hidden')->getValue();
				$album['show_in_overview'] = (string)$this->frm->getField('show_in_overview')->getValue();

				// ... then, update the album
				BackendGalleryModel::updateAlbum($album, (bool) $this->frm->getField('gallery')->getValue(), (bool) $this->frm->getField('slideshow')->getValue());

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_edit_album', array('item' => $album));

				// save the tags
				BackendTagsModel::saveTags($album['id'], $this->frm->getField('tags')->getValue(), $this->URL->getModule());

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('albums') . '&report=edited-album&var=' . urlencode($album['title']) . '&highlight=row-' . $album['id']);
			}
		}
	}

	/**
	 * Validate the form add image
	 *
	 * @return void
	 */
	private function validateFormAddImage()
	{

		//--Check if the add-image form is submitted
		if($this->frmAddImage->isSubmitted())
		{
			//--Clean up fields in the form
			$this->frmAddImage->cleanupFields();

			//--Get image field
			$filImage = $this->frmAddImage->getField('images');

			//--Check if the field is filled in
			if($filImage->isFilled())
			{
				//--Image extension and mime type
				$filImage->isAllowedExtension(array('jpg', 'png', 'gif', 'jpeg'), BL::err('JPGGIFAndPNGOnly'));
				$filImage->isAllowedMimeType(array('image/jpg', 'image/png', 'image/gif', 'image/jpeg'), BL::err('JPGGIFAndPNGOnly'));

				//--Check if there are no errors.
				$strError = $filImage->getErrors();

				if($strError === null)
				{

					//--Get the filename
					$strFilename = BackendGalleryModel::checkFilename(substr($filImage->getFilename(), 0, 0 - (strlen($filImage->getExtension()) + 1)), $filImage->getExtension());

					//--Fill in the item
					$item = array();
					$item["album_id"] = (int)$this->id;
					$item["user_id"] = BackendAuthentication::getUser()->getUserId();
					$item["language"] = BL::getWorkingLanguage();
					$item["filename"] = $strFilename;
					$item["description"] = "";
					$item["publish_on"] = BackendModel::getUTCDate();
					$item["hidden"] = "N";
					$item["sequence"] = BackendGalleryModel::getMaximumImageSequence($this->id) + 1;

					//--the image path
					$imagePath = FRONTEND_FILES_PATH . '/Gallery/Images';

					//--create folders if needed
					if(!\SpoonDirectory::exists($imagePath . '/Source')) \SpoonDirectory::create($imagePath . '/Source');
					if(!\SpoonDirectory::exists($imagePath . '/128x128')) \SpoonDirectory::create($imagePath . '/128x128');

					//--image provided?
					if($filImage->isFilled())
					{
						//--upload the image & generate thumbnails
						$filImage->generateThumbnails($imagePath, $item["filename"]);
					}

					//--Add item to the database
					BackendGalleryModel::insert($item);

					//--Redirect
					$this->redirect(BackendModel::createURLForAction('edit_album') . '&id=' . $item["album_id"] . '&report=added-image&var=' . urlencode($item["filename"]) . '#tabImages');
				}
			}
		}
	}

	/**
	 * Validate the form delete image
	 *
	 * @return void
	 */
	private function validateFormDeleteImage()
	{
		//--Check if the delete-image form is submitted
		if($this->frmDeleteImage->isSubmitted())
		{
			//--Clean up fields in the form
			$this->frmDeleteImage->cleanupFields();

			//--Check if the image-array is not empty.
			if(!empty($this->images))
			{
				//--Loop the images
				foreach($this->images as $row)
				{
					//--Check if the delete parameter is filled in.
					if(\SpoonFilter::getPostValue("delete_" . $row["id"], null, "") == "Y")
					{
						//--Delete the image
						BackendGalleryModel::delete($row["id"]);
					}

					//--Update item
					$item['id'] = $row['id'];
					$item['language'] = $row['language'];
					$item['description'] = \SpoonFilter::getPostValue("description_" . $row["id"], null, "");
					BackendGalleryModel::updateImage($item);
				}

				$this->redirect(BackendModel::createURLForAction('edit_album') . '&id=' . $this->id . '&report=deleted-images#tabImages');
			}
		}
	}
}