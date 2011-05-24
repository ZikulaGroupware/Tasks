<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='To-do list'}</a> ::
    {if !empty($title)}
    <a href="{modurl modname="Tasks" type='user' func='view' id=$tid}">{$title}</a> ::
    {/if}
    {$templatetitle}
</h2>


{form cssClass="z-form"}
{formvalidationsummary}

<fieldset>
    <legend>{gt text='Task'}</legend>
    <div class="z-formrow">
        {formlabel for="title"  __text='Title'}
        {formtextinput size="40" maxLength="100" id="title"}
    </div>


    <div class="z-formrow">
        {formlabel for="priority" __text='Priority'}
        {formdropdownlist id="priority" items=$priorities}
    </div>

    <div class="z-formrow">
        {formlabel for="progress" __text='Progress'}
        {formdropdownlist id="progress" items=$percentages}
    </div>

    <div class="z-formrow">
        {formlabel for="deadline" __text='Deadline'}
        {formdateinput id="deadline" useSelectionMode=1 ifFormat='%e. %B %Y' dateformat='%e. %B %Y' defaultdate=$today }
    </div>



    {foreach from=$registries item="registryCid" key="property"}
        <div class="z-formrow">
            {formlabel for="category_`$property`" __text="Category"}
            {formcategoryselector id="category_`$property`" category=$registryCid dataField=$property enableDoctrine=true}
        </div>
    {/foreach}

    
</fieldset>



{editor}
{formtextinput textMode="multiline" rows='4' cols='100' id="description" style="width:98%;height:200px"}

<div class="z-formbuttons z-buttons">
    {formbutton class="z-bt-ok" commandName="save" __text="Save"}
    {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
</div>

{/form}
