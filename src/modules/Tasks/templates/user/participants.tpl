{gt text="Participants" assign=templatetitle}
{pagesetvar name='title' value=$templatetitle}
{ajaxheader modname=$modinfo.name filename='users.js' ui=true}

<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='To-do list'}</a> ::
    <a href="{modurl modname="Tasks" type='user' func='view' id=$tid}">{$title}</a> ::
    {$templatetitle}
</h2>


{insert name='getstatusmsg'}

{form cssClass="z-form"}
{formvalidationsummary}

<fieldset>
    <legend>{gt text="Search for participants"}</legend>
    <div class="z-formrow" id="liveusersearch">
        {formlabel for="uname" __text="Search"}
        {formtextinput maxLength="25" id="uname"}
        {img id="ajax_indicator" style="display: none;" modname=core set="ajax" src="indicator_circle.gif" alt=""}
        <div id="username_choices" class="autocomplete_user"></div>
        <script type="text/javascript">
            liveusersearch();
        </script>
    </div>
    <div class="z-formbuttons z-buttons">
        {formbutton class="z-bt-ok" commandName="save" __text="Add participant"}
    </div>
</fieldset>

{/form}

<table class="z-admintable">
    <thead>
        <tr>
            <th>{gt text='Name'}</th>
            <th class="z-w05">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$participants item='participant'}
        <tr class="{cycle values='z-odd,z-even'}">
            <td>{$participant.uname}</td>
            <td>
                {assign var='tid'   value=$participant.tid}
                {assign var='uname' value=$participant.uname}
            </td>
        </tr>
        {foreachelse}
        <tr class="z-admintableempty"><td colspan="2">{gt text='No participants found.'}</td></tr>
        {/foreach}
    </tbody>
</table>