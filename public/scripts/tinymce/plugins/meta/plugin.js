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
                    {type: 'textbox', name: 'nameVal', label: 'Akter'}
                ],
                onsubmit: function(e) {
                    var selArrBlock = editor.selection.getSelectedBlocks();
                    var selEl = selArrBlock[0];
                    selEl.setAttribute("akter", e.data.nameVal );

                    //editor.insertContent('Title: ' + e.data.nameVal);
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
});