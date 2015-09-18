### Recents plugin for Craft CMS

Provides Recently Viewed or Favourited entries functionality within [Craft CMS](http://buildwithcraft.com) templates using cookies. This allows tracking without requiring a logged in user.

This plugin used the great [cookies plugin by khalwat](https://github.com/khalwat/cookies) as a foundation

**Installation**

1. Unzip file 
2. Place `recents` directory into your `craft/plugins` directory
3. Install plugin in the Craft Control Panel under Settings > Plugins

###Settings###

By default, the plugin will track 10 entries. You can change this in the Number of entries to track field

You will likely only want to track a certain section or two. By default, none are selected so you need to define which sections should be tracked.

If you use the controller routing to trigger the Favourites functionality, then a flash message returns a message when you add or remove an entry. You can control this with `Entry added text` and `Entry removed text` fields.

###Tracking entries###

The simplest use is to just throw `{{ trackEntry('name') }}` at the top of your layout file. This will track every entry from the section specified in the settings.

	{# Track recently viewed entries #}
	{{ trackEntry('recents') }}

You might have a need to track a particular entry, which you can do by passing the entry id as the second parameter
	
	{# Track recently viewed entries #}
	{{ trackEntry('recents', entry.id) }}
	{{ trackEntry('recents', '192') }}

Note that the entry is only tracked if it is a valid entry.

You may also want to use the plugin to allow the user to add things to a favourites list.
Note that if you have logged in members, it'd be better to save this to the database so it isn't tied to their browser.

You can provide an Add to favourites link and link to the controller itself. This currently only allows the cookie to be called "favourites"

	{% if not entryTracked('favourites', entry.id) %}
		<a href="{{ actionUrl('recents/favourite/saveFavourite', { entryId: entry.id, redirect: craft.request.path }) }}">
			{{ "site_lang_add_fave"|t }}
		</a>
	{% endif %}

See more about controller routing in the [Craft docs](http://buildwithcraft.com/docs/plugins/controllers#how-controller-actions-fit-into-routing)

###Retrieving tracked entries###

The plugin uses `{{ getEntries('name') }}` to return a string of comma delimited id numbers, which you can then pass to the id parameter of `craft.entries`

	{% set recents = getEntries('recents') %}
	{% set products = craft.entries.section('products').id(recents) %}
	
	<h1>{{ "site_lang_recently_viewed"|t }}</h1>
	{% if recents|length %}
		{% include 'products/_summary' %}
	{% else %}
		{{ "site_lang_no_recents"|t }}
	{% endif %}

###Returning number of recents###

Just returns the number of entries that are currently in the cookie

	{% set numrecents = numEntries('recents') %}
	<a href="{{ siteUrl }}products/recently-viewed">
		{{ "site_lang_recently_viewed"|t }}
		{% if numrecents %}({{ numrecents }}){% endif %}
	</a>

###Removing an entry###

You'll probably want to have a way for a user to remove an entry from their favourites list. You can do this with a tag on the page

	{# Remove entry from favourites #}
	{{ removeEntry('favourites', entry.id) }}
	{{ removeEntry('favourites', '192') }}

You can also provide a Remove from favourites link and link to the controller itself.

	{% if entryTracked('favourites', entry.id) %}
		<a href="{{ actionUrl('recents/favourite/deleteFavourite', { entryId: group.id, redirect: craft.request.path }) }}">
			{{ "site_lang_remove"|t }}
		</a>
	{% endif %}

###Check if an entry is tracked###

It's useful to know if an entry is already favourited, either to know whether to show an Add to favourites button or maybe to show a favourited icon on those entries.
Might also be useful to show which entries you've already viewed, a bit like a more useful a:visited

	{% if craft.request.getSegment(2) == "favourites" %}
		<a href="{{ actionUrl('recents/favourite/deleteFavourite', { entryId: entry.id, redirect: craft.request.path }) }}">
			{{ "site_lang_remove"|t }}
		</a>
	{% elseif entryTracked('favourites', entry.id) %}
		<span class="favourited"><img src="/assets/img/star-icon.svg" /></span>
	{% endif %}

## Changelog

### 1.0.0 -- 18/09/2015

* Initial release