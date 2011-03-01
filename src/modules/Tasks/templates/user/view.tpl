{pagesetvar name='templatetitle' value=$title}
<h2>{gt text="To-do"} :: {$title}</h2>
	
{edit tid=$tid owner=$cr_uid}

<b>{gt text="Description"}:</b> {$description}<br /><br />

<b>{gt text="Priority"}:</b> {$priority}<br />
<b>{gt text="Progress"}:</b> {$progress} %<br />
{if !empty($deadline) and $deadline != '0000-00-00'}
<b>{gt text="Deadline"}:</b> {$deadline}<br />
{/if}

<br /><a href="{modurl modname=Tasks type=user func=viewAll }">{gt text="Back to the list"}</a>