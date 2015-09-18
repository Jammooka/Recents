<?php 
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class RecentsTwigExtension extends \Twig_Extension
{

/* --------------------------------------------------------------------------------
	Expose our filters and functions
-------------------------------------------------------------------------------- */

	public function getName()
	{
		return 'Recents';
	}

/* -- Return our twig filters */

	public function getFilters()
	{
		return array(
			'trackEntry' => new \Twig_Filter_Method($this, 'trackEntry_filter'),
			'getEntries' => new \Twig_Filter_Method($this, 'getEntries_filter'),
			'numEntries' => new \Twig_Filter_Method($this, 'numEntries_filter'),
			'removeEntry' => new \Twig_Filter_Method($this, 'removeEntry_filter'),
			'entryTracked' => new \Twig_Filter_Method($this, 'entryTracked_filter'),
		);
	} /* -- getFilters */

/* -- Return our twig functions */

	public function getFunctions()
	{
		return array(
			'trackEntry' => new \Twig_Function_Method($this, 'trackEntry_filter'),
			'getEntries' => new \Twig_Function_Method($this, 'getEntries_filter'),
			'numEntries' => new \Twig_Function_Method($this, 'numEntries_filter'),
			'removeEntry' => new \Twig_Function_Method($this, 'removeEntry_filter'),
			'entryTracked' => new \Twig_Function_Method($this, 'entryTracked_filter'),
		);
	} /* -- getFunctions */

/* --------------------------------------------------------------------------------
	Filters
-------------------------------------------------------------------------------- */

	public function trackEntry_filter($name = "", $entry_id = "")
	{
		craft()->recents_utils->trackEntry($name, $entry_id);
	} /* -- trackEntry_filter */

	public function getEntries_filter($name)
	{
		return craft()->recents_utils->getEntries($name);
	} /* -- getEntries_filter */

	public function numEntries_filter($name)
	{
		return craft()->recents_utils->numEntries($name);
	} /* -- numEntries_filter */

	public function removeEntry_filter($name = "", $entry_id = "")
	{
		craft()->recents_utils->removeEntry($name, $entry_id);
	} /* -- removeEntry_filter */

	public function entryTracked_filter($name = "", $entry_id = "")
	{
		return craft()->recents_utils->entryTracked($name, $entry_id);
	} /* -- entryTracked_filter */

} /* -- RecentsTwigExtension */
