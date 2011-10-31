{pageaddvar name='javascript' value='javascript/helpers/Zikula.UI.js'}
{pagesetvar name='templatetitle' value=$title}
<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='Tasks'}</a> &#187;
    <a href="{modurl modname="Tasks" type='user' func='view' id=$tid}">{$title}</a>
</h2>

<br />
{actions tid=$tid owner=$cr_uid style="button"}<br /><br /><br />


<table class="z-datatable">
    <thead>
    <tr>
        <th>{gt text='Title'}</th>
        <th>{gt text='Value'}</th>
    </tr>
    </thead>
    <tbody>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Deadline'}</td>
        <td>
            {if $deadline}
            {$deadline->format('d.m.y')}
            {/if}
        </td>
    </tr>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Done date'}</td>
        <td>
            {if $done_date}
            {$done_date->format('d.m.y')}
            {/if}
        </td>
    </tr>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Creation date'}</td>
        <td>
            {if $cr_date}
            {$cr_date->format('d.m.y')}
            {/if}
        </td>
    </tr>
        <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Creator'}</td>
        <td>{$cr_uid|profilelinkbyuid}</td>
    </tr>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Priority'}</td>
        <td>{$priority}</td>
    </tr>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Progress'}</td>
        <td>{$progress} %</td>
    </tr>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Categories'}</td>
        <td>
        {foreach from=$categories item='name'}
            {$name|safetext}
        {/foreach}
        </td>
    </tr>
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Participants'}</td>
        <td>{$participants}</td>
    </tr>
    </tbody>
</table>


<br /><br />

<p>{$description|notifyfilters:'tasks.filter_hooks.description.filter'}</p>

{notifydisplayhooks eventname='tasks.hook.tasks.ui.view' area='modulehook_area.tasks.tasks' subject=$title id=$tid assign='hooks' caller="Tasks"}
{foreach from=$hooks key='provider_area' item='hook'}
{$hook}
{/foreach}