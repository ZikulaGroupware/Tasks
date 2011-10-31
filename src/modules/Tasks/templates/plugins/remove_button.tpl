<div class="z-buttons z-floatleft">
    <a id="r_{$tid}">
        {img src='edit.png' modname='core' set='icons/extrasmall' __alt='Edit task' __title='Edit task'}
        {gt text="Remove task"}
    </a>
</div>


<script type="text/javascript">
    reloadIt = function() {
        window.location.href='index.php?module=tasks';
    }

    deleteIt = function(res) {
        if(res) {
            var pars = "module=Tasks&func=remove&id={{$tid}}";
            var myAjax = new Ajax.Request(
                "ajax.php",
                {
                    method: 'get',
                    parameters: pars,
                    onComplete: reloadIt
                });
        }
    }
    $('r_{{$tid}}').observe(
        'click',
        Zikula.UI.IfConfirmed(
            'Do you want to remove this entry?',
            'Confirm action',
            deleteIt
        )
    );
</script>