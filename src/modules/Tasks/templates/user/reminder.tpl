{pageaddvar name="javascript" value="prototype"}
{pageaddvar name="javascript" value="modules/Tasks/javascript/facebooklist.js"}
{pageaddvar name="stylesheet" value="modules/Tasks/style/facebooklist.css"}

<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='Tasks'}</a> &#187;
    <a href="{modurl modname="Tasks" type='user' func='view' tid=$tid}">{$title}</a> &#187;
    {gt text='Send reminder'}
</h2>

<div id="intercom">


{form cssClass="z-form z-linear"}
{formvalidationsummary}


    <fieldset>

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
            {formtextinput textMode="multiline" rows='5' cols='100' id="message"}
        </div>

    </fieldset>

<div class="z-formbuttons z-buttons">
    {formbutton class="z-bt-ok" commandName="save" __text="Send"}
    {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
</div>

{/form}

</div>

<script type="text/javascript">
    document.observe('dom:loaded', function() {
        // init
        tlist1 = new FacebookList(
            'participants',
            'list-user',
            {fetchFile:document.location.pnbaseURL + 'ajax.php?module=Tasks'+'&'+'func=getusers'}
        );
    });
</script>