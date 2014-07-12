{pageaddvar name="javascript" value="modules/Tasks/javascript/facebooklist.js"}
{pageaddvar name="stylesheet" value="modules/Tasks/style/facebooklist.css"}
{ajaxheader modname='Tasks' filename='chosen/chosen.proto.min.js'}
{pageaddvar name='stylesheet' value='modules/Tasks/javascript/chosen/chosen.css'}

{gt text="Tasks" assign='maintitle'}
{if isset($title)}
    {gt text="Modify" assign='action'}
{else}
    {gt text="New" assign='action'}
{/if}
{pagesetvar name='title' value="$maintitle :: $title :: $action"}

<h2>
    <a href="{modurl modname='Tasks' type='user' func='main'}">{$maintitle}</a> &#187;
    {if isset($title)}
    <a href="{modurl modname="Tasks" type='user' func='view' tid=$tid}">{$title}</a> &#187;
    {/if}
    {$action}
</h2>

<div id="intercom">


{insert name='getstatusmsg'}

{form cssClass="z-form z-linear"}
{formvalidationsummary}


<fieldset id="chosenCss" >
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
    
    
    <div class="z-formrow" >
    {foreach from=$registries item="registryCid" key="registryId"}
        {formlabel for="categories_`$registryId`" __text="Categories"}
        {formcategoryselector id="category_`$registryId`" category=$registryCid dataField="categories" group="task" registryId=$registryId doctrine2=true  cssClass="chzn-select"}
       
    {/foreach}
    </div>
    
    
    <div class="z-formrow">	  	
        {formlabel for="participants" __text='Participants'}
        {formtextinput size="40" maxLength="255" id="participants"}
        <div id="list-user">
            <p class="default">
                {gt text="Notice: Please choose a user"}
            </p>
            <ul class="feed">                
                {foreach from=$task->getParticipants() item='item'}
                    <li value="{$item->getUname()|safetext}">{$item->getUname()|safetext}</li>	  	
                {/foreach}
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
            {fetchFile:Zikula.Config.baseURL + 'ajax.php?module=Tasks'+'&'+'func=getusers'}
        );
    });
</script>


<script type="text/javascript">
    New Chosen($("chzn_select_field"),{no_results_text: "No results matched"});
</script>


{/form}


</div>

