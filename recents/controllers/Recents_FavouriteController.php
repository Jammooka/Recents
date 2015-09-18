<?php
namespace Craft;

/**
 * Instead of just tracking a recent, you can manually add
 */
class Recents_FavouriteController extends BaseController
{
	protected $allowAnonymous = true;
	
	public function actionSaveFavourite()
	{
		// Get params
		$entry_id = craft()->request->getParam('entryId');
		$redirect = craft()->request->getParam('redirect');
		
		// Track the relevant entry
		craft()->recents_utils->trackEntry('favourites', $entry_id);
		
		// Get flash message text
		$addText = craft()->plugins->getPlugin('recents')->getSettings()->addText;
		
		// Set a flash message
		craft()->userSession->setFlash('notice', $addText);
		
		// Return user to page to avoid 404
		$this->redirect($redirect);
	}
	
	public function actionDeleteFavourite()
	{
		// Get params
		$entry_id = craft()->request->getParam('entryId');
		$redirect = craft()->request->getParam('redirect');
		
		// Delete the relevant entry
		craft()->recents_utils->removeEntry('favourites', $entry_id);
		
		// Get flash message text
		$removeText = craft()->plugins->getPlugin('recents')->getSettings()->removeText;
		
		// Set a flash message
		craft()->userSession->setFlash('notice', $removeText);
		
		// Return user to page to avoid 404
		$this->redirect($redirect);
	}
}