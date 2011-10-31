<h2>
    <a href="{modurl modname="Tasks" type='user' func='main'}">{gt text='Tasks'}</a> &#187;
    {gt text='Import CSV'}
</h2>

<br />
{insert name='getstatusmsg'}

<p class="z-informationmsg">
    {gt text='Order:'} title, description, priority, progress, deadline, cr_uid, 
    cr_date, done_date, participants, categories, approved<br />
    CSV: $delimiter = ',', $enclosure = '"', $escape = '\\'<br />
    dateformat: %Y-%m-%d (2011-20-02)
</p>


{form cssClass="z-form z-linear"}
    {formvalidationsummary}


    <fieldset>


        <div class="z-formrow">
            {formtextinput textMode="multiline" rows='4' cols='100' id="input" text='title,description,9,90,2011-01-01,200,2011-01-01,2011-01-01,admin,"Finanzen,test",0'}
        </div>


    </fieldset>



    <div class="z-formbuttons z-buttons">
        {formbutton class="z-bt-view" commandName="test" __text="Test"}
        {formbutton class="z-bt-ok" commandName="save" __text="Save"}
        {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
    </div>


{/form}



{if isset($test)}

<br /><br />
<table class="z-datatable">
    <thead>
    <tr>
        <th>title</th>
        <th>description</th>
        <th>priority</th>
        <th>progress</th>
        <th>deadline</th>
        <th>cr_uid</th>
        <th>cr_date</th>
        <th>done_date</th>
        <th>participants</th>
        <th>categories</th>
        <th>approved</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$test item='task'}
    <tr class="{cycle values='z-odd,z-even'}">
        <td>{$task.title}</td>
        <td>{$task.description}</td>
        <td>{$task.priority}</td>
        <td>{$task.progress}</td>
        <td>{$task.deadline}</td>
        <td>{$task.cr_uid}</td>
        <td>{$task.cr_date}</td>
        <td>{$task.done_date}</td>
        <td>{$task.participants}</td>
        <td>{$task.categories}</td>
        <td>{$task.approved}</td>
    </tr>
    {/foreach}
</tbody>
</table>
{/if}