<?php
namespace Craft;

class Recents_UtilsService extends BaseApplicationComponent
{

/* --------------------------------------------------------------------------------
	Security validated cookies
-------------------------------------------------------------------------------- */

	public function trackEntry($name = "", $entry_id = null)
	{
		if($entry_id == null)
		{
			// Get loaded element
			$element = craft()->urlManager->getMatchedElement();
		}
		else
		{
			// Get element from id
			$element = $this->getElementFromId($entry_id);
		}
		
		// Only proceed if element is a valid entry
		if ($this->isValidEntry($element))
		{
			// Store entryId
			$entry_id = $element->id;
			
			// Update entry list
			$entry_list = $this->entryList($name, $entry_id);
			
			// Update cookie
			$this->updateCookie($name, $entry_list);
		}
	} /* -- trackEntry */

	public function getEntries($name = "")
	{
		$cookie = craft()->request->getCookie($name);
		if ($cookie && !empty($cookie->value) && ($data = craft()->security->validateData($cookie->value)) !== false)
		{
			return @unserialize(base64_decode($data));
		}
	} /* -- getEntries */

	public function numEntries($name = "")
	{
		// Remove any invalid entries before proceeding
		$this->cleanseEntries($name);
		
		$entries = $this->entriesFromCookie($name);
		
		return count($entries);
		
	} /* -- numEntries */

	public function removeEntry($name = "", $entry_id = null)
	{
		// Get entries array
		$entries = $this->entriesFromCookie($name);
		
		// Remove specified entry
		$entries = $this->unsetEntry($entries, $entry_id);
		
		// Return to comma delimited string
		$entry_list = implode(',', $entries);
		
		// Update cookie
		$this->updateCookie($name, $entry_list);
		
	} /* -- removeEntry */

	public function entryTracked($name = "", $entry_id = null)
	{
		$entries = $this->entriesFromCookie($name);
				
		return in_array($entry_id, $entries);
		
	} /* -- entryTracked */
	
	private function cleanseEntries($name = "")
	{
		$entries = $this->entriesFromCookie($name);
		
		// Check each entry to see if it's valid
		foreach ($entries as $entry_id)
		{
			// Get element from entry id
			$element = $this->getElementFromId($entry_id);
			// Check if it's a valid entry
			$valid = $this->isValidEntry($element);
			//If not, remove it from the array
			if(!$valid)
			{
				$entries = $this->unsetEntry($entries, $entry_id);
			}
		}
		
		// Return to comma delimited string
		$entry_list = implode(',', $entries);
		
		// Update cookie
		$this->updateCookie($name, $entry_list);
	}
	
	private function getElementFromId($entry_id)
	{
		// Get element from id
		$criteria = craft()->elements->getCriteria(ElementType::Entry);
		$criteria->id = $entry_id;
		return $criteria->first();
	}
	
	private function entriesFromCookie($name)
	{
		// Retrieve any existing cookie
		$existing_cookie = $this->getEntries($name);
		
		// Explode to array of entries
		$entries = array_filter(explode(',', $existing_cookie));
		
		return $entries;
	}
	
	private function entryList($name = "", $entry_id = "")
	{
		$limit = craft()->plugins->getPlugin('recents')->getSettings()->limit;
		
		$entries = $this->entriesFromCookie($name);
		
		// Remove $entry_id from the array, if it exists
		foreach (array_keys($entries, $entry_id) as $key)
		{
			unset($entries[$key]);
		}
		
		// Remove any remainder off the end, ensuring the max length is $limit-1
		if(count($entries) >= $limit)
		{
			array_pop($entries);
		}
		
		// Add $entry_id to the beginning of the array
		array_unshift($entries, $entry_id);
		
		// Return to comma delimited string
		$entry_list = implode(',', $entries);
		
		return $entry_list;
	}
	
	private function isValidEntry($element = null)
	{
		return ($element && $element->getElementType() == ElementType::Entry && $this->isSectionTracked($element->sectionId));
	}
	
	private function isSectionTracked($sectionId = null)
	{
		$track_sections = craft()->plugins->getPlugin('recents')->getSettings()->sections;
		return in_array($sectionId, $track_sections);
	}
	
	private function unsetEntry($entries, $entry_id)
	{
		// Remove $entry_id from the array, if it exists
		foreach (array_keys($entries, $entry_id) as $key)
		{
			unset($entries[$key]);
		}
		return $entries;
	}
	
	private function updateCookie($name = "", $entry_list = array())
	{
		$expire = (int) strtotime("+1 month");
		$cookie = new HttpCookie($name, '');
	
		$cookie->value = craft()->security->hashData(base64_encode(serialize($entry_list)));
		$cookie->expire = $expire;
		$cookie->path = '/'; // Available to entire domain
		
		craft()->request->getCookies()->add($cookie->name, $cookie);
	}

} /* -- Cookies_UtilsService */