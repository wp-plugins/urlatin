(function() {
    //tinymce.PluginManager.requireLangPack('hidepost');
    tinymce.create('tinymce.plugins.UrlatinPlugin', {
        init : function(ed, url) {
            ed.addCommand('mce_urlat_in_insert', function() {
                ed.execCommand('mceReplaceContent', 0, urlat_in_insert_tag_code('visual', ''));
            });
            ed.addButton('urlat_in', {
                title : 'Proteger y acortar con URLatin',
                cmd : 'mce_urlat_in_insert',
                image : 'http://urlat.in/favicon.ico'
            });
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('urlat_in', n.nodeName == 'IMG');
            });
        },

        createControl : function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('urlat_in', tinymce.plugins.UrlatinPlugin);
})();

function urlat_in_insert_tag_code( ) {
    var _selection = urlat_in_get_selected_text();
    if (_selection == '') return '';
    var _url = _selection.replace('\n', '<br/>');
    return '[urlatin]'+_url+'[/urlatin]';
}

function urlat_in_get_selected_text(){
    var inst = tinyMCE.selectedInstance;
   
    if (tinyMCE.isMSIE) {
        var doc = inst.getDoc();
        var rng = doc.selection.createRange();
        var selectedText = rng.text;
    } else {
        var sel = inst.contentWindow.getSelection();
   
        if (sel && sel.toString){
            selectedText = sel.toString();
        }else{
            selectedText = '';
        }
    }
    return selectedText;
} 