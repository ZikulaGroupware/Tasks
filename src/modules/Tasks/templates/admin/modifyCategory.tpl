{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="info" size="small"}
    <h3>{gt text="Categories"}</h3>
</div>


<table class="z-datatable">
    <thead>
    <tr>
        <th>{gt text='Id'}</th>
        <th width=100%>{gt text='Name'}</th>
        <th>{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$categories item='category'}
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{$category.id}</td>
        <td>{$category.name}</td>
        <td>
            <a href="{modurl modname='Tasks' type='admin' func='viewCategories' id=$category.id}">
                {img modname='core' set='icons/extrasmall' src="xedit.png" __alt="Edit" __title="Edit"}
            </a>
            {remove function='removeCategory' id=$category.id}
        </td>
    </tr>
    {/foreach}
</tbody>
</table>

{form cssClass="z-form"}
{formvalidationsummary}



<fieldset>
    {if isset($id)}
    <legend>{gt text="Edit category"}</legend>
    {else}
    <legend>{gt text="Add category"}</legend>
    {/if}
    <div class="z-formrow">
        {formlabel for="name"  __text='Name'}
        {formtextinput size="40" maxLength="255" id="name"}
    </div>

    <div class="z-formbuttons z-buttons">
        {formbutton class="z-bt-ok" commandName="save" __text="Save"}
        {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
    </div>
</fieldset>



{/form}

{adminfooter}