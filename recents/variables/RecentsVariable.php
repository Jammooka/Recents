<?php
namespace Craft;

class RecentsVariable
{

/* --------------------------------------------------------------------------------
	Variables
-------------------------------------------------------------------------------- */

	function trackEntry($name = "", $entry_id = "")
	{
		craft()->recents_utils->trackEntry($name, $entry_id);
	} /* -- trackEntry */

	function getEntries($name = "")
	{
		return craft()->recents_utils->getEntries($name);
	} /* -- getEntries */

	function numEntries($name = "")
	{
		return craft()->recents_utils->numEntries($name);
	} /* -- numEntries */

	function removeEntry($name = "", $entry_id = "")
	{
		return craft()->recents_utils->removeEntry($name, $entry_id);
	} /* -- removeEntry */

	function entryTracked($name = "", $entry_id = "")
	{
		return craft()->recents_utils->entryTracked($name, $entry_id);
	} /* -- entryTracked */

}