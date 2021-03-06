tinymce.PluginManager.add('meta', function(editor, url) {
    // Add a button that opens a window
    editor.addButton('meta', {
        text: 'Meta',
        icon: false,
        onclick: function() {
            // Open window
            editor.windowManager.open({
                title: 'Meta podaci',
                body: [
                    {type: 'textbox', name: 'nameVal', label: 'Akter','value':'test'}
                    //{type: 'textbox', name: 'keywordsVal', label: 'Keywords'}

                ],
                onsubmit: function(e) {
                    var selArrBlock = editor.selection.getSelectedBlocks();
                    var selEl = selArrBlock[0];

                    /*provera koje polje je setovano*/
                    //selEl.setAttribute("akter", e.data.nameVal );
                    applyChange(selEl, e.data.nameVal);
                }
            });
        }
    });

    // Adds a menu item to the tools menu
    editor.addMenuItem('meta', {
        text: 'Meta',
        context: 'tools',
        onclick: function() {
            // Open window with a specific url
            editor.windowManager.open({
                title: 'Meta podaci',
                url: 'http://www.tinymce.com',
                width: 800,
                height: 600,
                buttons: [{
                    text: 'Close',
                    onclick: 'close'
                }]
            });
        }
    });


    function applyChange( selectedElement , value) {
        editor.undoManager.transact(function() {
            editor.focus();
            selectedElement.setAttribute("akter", value );
           // editor.formatter.apply(change, {value: value});
            editor.nodeChanged();
        });
    }

    function removeChange(change) {
        editor.undoManager.transact(function() {
            editor.focus();
            editor.formatter.remove(change, {value: null}, null, true);
            editor.nodeChanged();
        });
    }
});