<?php

$group = elgg_get_page_owner_entity();

if (!$group || !elgg_instanceof($group, 'group')) {
	register_error("Could not find the specified group");
	forward();
}

$title = $group->name;

$composer = '';
if (elgg_is_logged_in()) {
	$composer = elgg_view('page/elements/composer', array('entity' => $group));
}

$db_prefix = elgg_get_config('dbprefix');
$activity = elgg_list_river(array(
	'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
	'wheres' => array("e.container_guid = $group->guid")
));

if (!$activity) {
	$activity = elgg_view('output/longtext', array('value' => elgg_echo('group:activity:none')));
}

$body = elgg_view_layout('two_sidebar', array(
	'content' => $composer . $activity,
	'title' => $title,
));

echo elgg_view_page($title, $body);
