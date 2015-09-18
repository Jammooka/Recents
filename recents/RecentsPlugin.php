<?php
namespace Craft;

class RecentsPlugin extends BasePlugin
{
	/* This plugin uses the great cookies plugin by khalwat as a foundation
	 * https://github.com/khalwat/cookies
	 */
	
	function getName()
	{
		return Craft::t('Recents');
	}

	function getVersion()
	{
		return '1.0.0';
	}

	function getDeveloper()
	{
		return 'Amity Web';
	}

	function getDeveloperUrl()
	{
		return 'http://www.amitywebsolutions.co.uk';
	}

	public function addTwigExtension()
	{
		Craft::import('plugins.recents.twigextensions.RecentsTwigExtension');

		return new RecentsTwigExtension();
	}
	
	protected function defineSettings()
	{
		return array(
			'limit'			=>	array(
									AttributeType::Number,
									'default' => 10,
									'required' => true
								),
			'sections'		=>	AttributeType::Mixed,
			'addText'		=>	array(
									AttributeType::String,
									'default' => 'Entry added to favourites',
									'required' => true
								),
			'removeText'	=>	array(
									AttributeType::String,
									'default' => 'Entry removed from favourites',
									'required' => true
								),
		);
	}
	
	public function getSettingsHtml()
    {
        $sections = array();

        foreach(craft()->sections->getAllSections() as $section)
        {
            $sections[] = array(
                'label' => $section->name,
                'value' => $section->id
            );
        }

        return craft()->templates->render('recents/settings', array(
            'sections' => $sections,
            'settings' => $this->getSettings()
        ));
    }
}