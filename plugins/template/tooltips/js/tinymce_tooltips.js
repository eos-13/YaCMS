tinymce.PluginManager.add('tooltips', function(editor, url)
{
    // Add a button that opens a window
    editor.addButton('tooltips',
    {
        text: 'Ttips',
        icon: false,
        onclick: function()
        {
            var tmp = editor.selection.getContent();
            console.log(tmp);
            if (typeof tmp !== "undefined")
            {
                console.log(tmp);
                tmp = jQuery(tmp).attr('href').replace(/^#/,"");
            } else {
                tmp = "";
            }
            // Open window
            editor.windowManager.open(
            {
                title: 'Tooltips plugin',
                body: [
                    {type: 'textbox', name: 'id', label: 'Identifiant', value: tmp}
                ],
                onsubmit: function(e)
                {
                    // Insert content when the window form is submitted
                    var newref = e.data.id;
                    var selected_text = editor.selection.getContent();
                    var return_text = '';
                        return_text = '<a class="click"  title="Cliquez pour en savoir plus"  href="#' + newref + '">' + selected_text + '</div> ';
                    editor.execCommand('mceInsertContent', 0, return_text);

                }
            });
        }
    });

    // Adds a menu item to the tools menu
    editor.addMenuItem('tooltips',
    {
        text: 'Tooltips plugin',
        context: 'tools',
        onclick: function()
        {
            // Open window
            var tmp = editor.selection.getContent();
            if (typeof tmp !== "undefined")
            {
                tmp = jQuery(tmp).attr('href').replace(/^#/,"");
            } else {
                tmp = "";
            }
            editor.windowManager.open(
            {
                title: 'Tooltips plugin',
                body: [
                    {type: 'textbox', name: 'id', label: 'Identifiant', value: tmp}
                ],
                onsubmit: function(e)
                {
                    // Insert content when the window form is submitted
                    var newref = e.data.id;
                    var selected_text = editor.selection.getContent();
                    var return_text = '';
                        return_text = '<a class="click"  title="Cliquez pour en savoir plus"  href="#' + newref + '">' + selected_text + '</div> ';
                    editor.execCommand('mceInsertContent', 0, return_text);

                }
           });
        }
    });
});