

function liveusersearch()
{
    $('liveusersearch').removeClassName('z-hide');
    var options = Zikula.Ajax.Request.defaultOptions({
        paramName: 'fragment',
        minChars: 3
    });
    new Ajax.Autocompleter('uname', 'username_choices', Zikula.Config.baseURL + 'ajax.php?module=users&func=getusers', options);
}
