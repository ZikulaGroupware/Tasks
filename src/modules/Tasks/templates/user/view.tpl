{pagesetvar name='templatetitle' value=$title}
<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='To-do list'}</a> ::
    <a href="{modurl modname="Tasks" type='user' func='view' id=$tid}">{$title}</a>
</h2>

<br />
{edit tid=$tid owner=$cr_uid button="true"}

<p>{$description|notifyfilters:'tasks.filter_hooks.description.filter'}</p>

{notifydisplayhooks eventname='tasks.hook.tasks.ui.view' area='modulehook_area.tasks.tasks' subject=$title id=$tid assign='hooks' caller="Tasks"}
{foreach from=$hooks key='provider_area' item='hook'}
{$hook}
{/foreach}