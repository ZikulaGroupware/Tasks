{pageaddvar name='javascript' value='javascript/helpers/Zikula.UI.js'}
<a id="r_{$tid}">
    {img modname=core set=icons/extrasmall src='14_layer_deletelayer.png' alt='Remove'}
</a>

<script type="text/javascript">
    reloadIt = function() {
        window.location.href=window.location.href;
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