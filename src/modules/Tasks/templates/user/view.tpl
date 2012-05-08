{pageaddvar name='javascript' value='javascript/helpers/Zikula.UI.js'}
{gt text="Tasks" assign='maintitle'}
{pagesetvar name='title' value="$maintitle :: $title"}


<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{$maintitle}</a> &#187;
    <a href="{modurl modname="Tasks" type='user' func='view' tid=$tid}">{$title}</a>
</h2>
{insert name='getstatusmsg'}

<br />
{actions tid=$tid owner=$cr_uid style="button"}


<div class="z-buttons">
    <a href="{modurl modname='Tasks' type='user' func='reminder' tid=$tid}">
        {img src='mail_generic.png' modname='core' set='icons/extrasmall' __alt='Send reminder' __title='Send reminder'}
        {gt text="Send reminder"}
    </a>
</div><br /><br />


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
        {foreach from=$categories item='c'}
            {$c.category.name|safetext}
        {/foreach}
        </td>
    </tr>
    {*<tr class="{cycle values='z-odd,z-even'}">
        <td>{gt text='Participants'}</td>
        <td>{$participants}</td>
    </tr>*}
    </tbody>
</table>


<br /><br />

<p>{$description|notifyfilters:'tasks.filter_hooks.description.filter'}</p>

{notifydisplayhooks eventname='tasks.hook.tasks.ui.view' area='modulehook_area.tasks.tasks' subject=$title id=$tid assign='hooks' caller="Tasks"}
{foreach from=$hooks key='provider_area' item='hook'}
{$hook}
{/foreach}