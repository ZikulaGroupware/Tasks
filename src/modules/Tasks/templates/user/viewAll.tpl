{pageaddvar name='javascript' value='javascript/helpers/Zikula.UI.js'}
{gt text="To-do list" assign='templatetitle'}
{pagesetvar name='templatetitle' value=$templatetitle}
<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">
        {$templatetitle}
    </a>
</h2>

<br />
{add}

<form class="z-form" action="{modurl modname="Tasks" type="user" func="viewAll"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <fieldset>
            <select class="formborder" id="mode" name="mode">
                <option value="undone" {if $mode eq "undone"}selected="selected"{/if}>
                    {gt text="Undone tasks"}
                </option>
                <option value="done" {if $mode eq "done"}selected="selected"{/if}>
                    {gt text="Completed tasks"}
                </option>
                <option value="all" {if $mode eq "all"}selected="selected"{/if}>
                    {gt text="All tasks"}
                </option>
            </select>
            <input name="onlyMyTasks" type="checkbox" {if $onlyMyTasks == 'on'}checked{/if} />
            <span>{gt text='Only my tasks'}</span>
            <span class="z-buttons">
                <input class="z-bt-filter z-bt-small" type="submit" name="submit" value="{gt text='Filter'}" />
            </span>
        </fieldset>
    </div>
</form>


<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text="Title"}</th>
            <th>{gt text="Priority"}<br />(1={gt text='highest'})</th>
            <th>{gt text="Progress"}</th>
            <th>{gt text="Deadline"}</th>
            <th>{gt text="Category"}</th>
            <th class="z-nowrap z-right">{gt text="Action"}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$tasks item="task"}
        <tr class="{cycle values="z-odd,z-even"}">
            <td>
                <a href="{modurl modname="Tasks" type='user' func="view" id=$task.tid}">{$task.title}</a>
                <div id="other{$task.tid}" class="tooltips2" title="#other{$task.tid}_content">{$task.description|notifyfilters:'tasks.filter_hooks.description.filter'}</div>
                <div id="other{$task.tid}_content" style="display:none;">{$task.description|notifyfilters:'tasks.filter_hooks.description.filter'}</div>
            </td>
            <td>{$task.priority}</td>
            <td>{$task.progress} %</td>
            <td>{if $task.deadline != '0000-00-00'}{$task.deadline|date_format:'%e. %B %Y'}{/if}</td>
            <td>
                {foreach from=$task.Categories item='c'}
                {$c.Category.name|safetext}
                {/foreach}
            </td>
            <td class="z-nowrap z-right">
                {edit tid=$task.tid owner=$task.cr_uid}
                {remove id=$task.tid}
            </td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='6' class='z-center'>{gt text='No tasks available'}</td></tr>
        {/foreach}
    </tbody>
</table>

<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips2'));
</script>
