{gt text="To-do list" assign='templatetitle'}
{pagesetvar name='templatetitle' value=$templatetitle}
<h2>{$templatetitle}</h2>

{add}

<form class="z-form" action="{modurl modname="Tasks" type="user" func="viewAll"}" method="post" enctype="application/x-www-form-urlencoded">
    <select class="formborder" id="mode" name="mode">
        <option value="undone" {if $mode eq "undone"}selected{/if}>
            {gt text="Undone tasks"}
        </option>
        <option value="done" {if $mode eq "done"}selected{/if}>
            {gt text="Completed tasks"}
        </option>
        <option value="all" {if $mode eq "all"}selected{/if}>
            {gt text="All tasks"}
        </option>
    </select>
    <input type="submit" name="submit" value="{gt text='Filter'}">  
</form>


<table class="z-datatable">
    <thead>
    <tr>
        <th>{gt text="Title"}</th>
        <th>{gt text="Priority"}</th>
        <th>{gt text="Progress"}</th>
        <th>{gt text="Deadline"}</th>
        <th>{gt text="Category"}</th>
    </tr>
    <thead>
    <tbody>
    {foreach from=$tasks item="task"}
    <tr class="{cycle values="z-odd,z-even"}">
        <td><a href="{modurl modname=Tasks type=user func=view tid=$task.tid }">{$task.title}</a></td>
        <td>{$task.priority}</td>
        <td>{$task.progress} %</td>
        <td>{if $task.deadline != '0000-00-00'}{$task.deadline|date_format:'%e. %B %Y'}{/if}</td>
        <td>
        {foreach from=$task.Categories item='c'}
            {$c.Category.name|safetext}
        {/foreach}
        </td>
    </tr>
    {/foreach}
</tbody>
</table>