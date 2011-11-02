{pageaddvar name="javascript" value="prototype"}
{pageaddvar name="javascript" value="modules/Tasks/javascript/facebooklist.js"}
{pageaddvar name="stylesheet" value="modules/Tasks/style/facebooklist.css"}

<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='Tasks'}</a> &#187;
    <a href="{modurl modname="Tasks" type='user' func='view' tid=$tid}">{$title}</a> &#187;
    {$templatetitle}
</h2>

<div id="intercom">


{insert name='getstatusmsg'}

{form cssClass="z-form z-linear"}
{formvalidationsummary}


<fieldset>
    <div class="z-formrow">
        {formlabel for="title"  __text='Title'}
        {formtextinput size="40" maxLength="255" id="title"}
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
        {formlabel for="categories" __text='Categories'}
        {formtextinput size="40" maxLength="255" id="categories"}
        <div id="list-categories">
            <p class="default">{gt text="Notice: To send a private message to multiple groups, enter the group names separated by commas."}</p>
            <ul class="feed">
                {if $categories2}
                {foreach from=$categories2 item='item'}
                <li value="{$item|safetext}">{$item|safetext}</li>
                {/foreach}
                {/if}
            </ul>
        </div>
        <em class="z-formnote z-sub">{gt text="Available categories"}: {$availableCategories}.</em>
    </div>


    
    
    <div class="z-formrow">
        {formlabel for="participants" __text='Participants'}
        {formtextinput size="40" maxLength="255" id="participants"}
        <div id="list-user">
            <p class="default">{gt text="Notice: Please choose a user"}</p>
            <ul class="feed">
                {if $participants2}
                {foreach from=$participants2 item='item'}
                <li value="{$item|safetext}">{$item|safetext}</li>
                {/foreach}
                {/if}
            </ul>
        </div>
    </div>




    <div class="z-formrow">
        {formlabel for="deadline" __text='Deadline'}
        {formdateinput id="deadline" useSelectionMode=1 ifFormat=$dateformat daFormat='%e. %B %Y'}
    </div>


    <div class="z-formrow">
        {formtextinput textMode="multiline" rows='4' cols='100' id="description" style="width:98%;height:200px"}
    </div>
    {notifydisplayhooks eventname='tasks.ui_hooks.editor.display_view' id='description'}


</fieldset>



<div class="z-formbuttons z-buttons">
    {formbutton class="z-bt-ok" commandName="save" __text="Save"}
    {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
</div>




<script type="text/javascript">
    document.observe('dom:loaded', function() {
        // init
        tlist1 = new FacebookList(
            'participants',
            'list-user',
            {fetchFile:document.location.pnbaseURL + 'ajax.php?module=Tasks'+'&'+'func=getusers'}
        );
        tlist2 = new FacebookList(
            'categories',
            'list-categories',
            {fetchFile:document.location.pnbaseURL + 'ajax.php?module=Tasks'+'&'+'func=getcategories'}
        );
    });
</script>


{/form}


</div>

