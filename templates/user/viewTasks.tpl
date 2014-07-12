{gt text="Tasks" assign='templatetitle'}
{pagesetvar name='title' value=$templatetitle}
<h2>
    <a href="{modurl modname='Tasks' type='user' func='viewTasks'}">
        {$templatetitle}
    </a>
</h2>

<br />

{add}

<form class="z-form" action="{modurl modname="Tasks" type="user" func="viewTasks"}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>

        <input type="text" name="search" value="{$search}" style="
            background-image:url(modules/Tasks/images/search.png);
            background-repeat:no-repeat;
            background-position:right center;
        " />

        <select id="mode" name="mode">
        {foreach from=$modes item="text" key='value'}
            <option value="{$value}" {if $mode eq $value}selected="selected"{/if}>
                {$text}
            </option>
        {/foreach}
        </select>


        <select id="participant" name="participant">
        
            <option value="1" {if $participant eq 1}selected="selected"{/if}>
                {gt text='Only my tasks'}
            </option>
            <option value="2" {if $participant eq 2}selected="selected"{/if}>
                {gt text='All tasks'}
            </option>

            {foreach from=$users item="value"}
                <option value="{$value.uname}" {if $participant eq $value.uname}selected="selected"{/if}>
                    {$value.uname}
                </option>
            {/foreach}
        </select>


        <select id="category" name="category">
            <option value="all" {if $category eq 'all'}selected="selected"{/if}>
                {gt text='All categories'}
            </option>
            
            {foreach from=$categories item="text" key='catid'}
                <option value="{$catid}" {if $category eq $catid}selected="selected"{/if}>
                    {$text}
                </option>
            {/foreach}
        </select>

        <select id="limit" name="limit">
        {foreach from=$items_per_page item="text" key='value'}
            <option value="{$value}" {if $limit eq $value}selected="selected"{/if}>
                {$text}
            </option>
        {/foreach}
        </select>


        <span class="z-buttons">
            <input class="z-bt-filter z-bt-small" type="submit" name="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname='Tasks' type=user func=main}" title="{gt text="Cancel"}" class="z-bt-small">
                {gt text="Clear"}
            </a>
        </span>

    </fieldset>
</form>


<table class="z-datatable">
    <thead>
    <tr>
        <th>{gt text='Title'}</th>
        <th>{gt text="Priority"}<br />(1={gt text='highest'})</th>
        <th>{gt text="Progress"}</th>
        <th>{gt text="Deadline"}</th>
        <th>{gt text="Participants"}</th>
        <th>{gt text="Categories"}</th>
        <th>{gt text="Action"}</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$tasks item='task'}
    <tr class="{cycle values='z-odd,z-even'}">
        <td>
            
            {if $task.description}
            <div id="other{$task.tid}" class="tooltips2" title="#other{$task.tid}_content">
                <a href="{modurl modname="Tasks" type='user' func="view" tid=$task.tid}">{$task.title}</a>
            </div>
            <div id="other{$task.tid}_content" style="display:none;">{$task.description|notifyfilters:'tasks.filter_hooks.description.filter'}</div>
            {else}
            <a href="{modurl modname="Tasks" type='user' func="view" tid=$task.tid}">{$task.title}</a>
            {/if}

        </td>
        <td>{$task.priority}</td>
        <td align="center">{$task.progress|formatProgress}</td>
        <td>
            {if $task.deadline}{$task.deadline|formatDeadline}{/if}
        </td>
        <td>
            {formatParticipants p=$task.participants}
        </td>
        <td>
        {foreach from=$task.categories item='cat' name=cagroriesloop}
            {$cat.category.displayName.$lang|safetext}{if !$smarty.foreach.cagroriesloop.last}, {/if}
        {/foreach}
        </td>
            <td class="z-nowrap">
                {actions tid=$task.tid owner=$task.cr_uid}
            </td>
    </tr>
    {/foreach}
</tbody>
</table>



<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips2'));
</script>


{pager show='page' rowcount=$tasks_count limit=$limit posvar='startnum' shift=1}