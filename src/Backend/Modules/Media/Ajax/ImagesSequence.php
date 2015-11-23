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
/**
 * Reorder images
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class ImagesSequence extends AjaxAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();

		//--Get the ids and split them
		$ids = explode(',', trim(\SpoonFilter::getPostValue('ids', null, '', 'string')));

		//--Check if the id is not empty
		if(!empty($ids))
		{
			//--Set the sequence to 1
			$sequence = 1;

			//--Loop the id's
			foreach($ids as $id)
			{
				//--Set the item array
				$item = array();

				$item["id"] = (int)$id;
				$item["sequence"] = $sequence;

				BackendMediaModel::exists($item["id"]) ? BackendMediaModel::update($item["id"], $item) : null;

				//--Add the sequence for each id
				$sequence++;
			}
		}

		// success output
		$this->output(self::OK, null, 'sequence updated');
	}
}
