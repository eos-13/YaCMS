define([
        "underscore" , "backbone"
       , "collections/snippets" , "collections/my-form-snippets"
       , "views/tab" , "views/my-form"
       , "text!data/input.json", "text!data/radio.json", "text!data/select.json", "text!data/buttons.json"
       , "text!templates/app/render.html",
], function(
  _, Backbone
  , SnippetsCollection, MyFormSnippetsCollection
  , TabView, MyFormView
  , inputJSON, radioJSON, selectJSON, buttonsJSON
  , renderTab
){
  return {
    initialize: function(){
        var jsonData = [{
            "title": "Form Name",
            "fields": {
                "name": {
                    "label": "Formulaire",
                    "type": "input",
                    "value": "Mon formulaire"
                },
            }
        }];
        if (jQuery('#fromJson').text().length>0)
        {
            jsonData = JSON.parse(jQuery('#fromJson').text());
        }
      //Bootstrap tabs from json.
      new TabView({
          title: "Texte"
        , collection: new SnippetsCollection(JSON.parse(inputJSON))
      });
      new TabView({
          title: "Cases à cocher"
        , collection: new SnippetsCollection(JSON.parse(radioJSON))
      });
      new TabView({
          title: "Liste déroulante"
        , collection: new SnippetsCollection(JSON.parse(selectJSON))
      });
      new TabView({
          title: "Bouttons"
        , collection: new SnippetsCollection(JSON.parse(buttonsJSON))
      });
      new TabView({
          title: "Rendu HTML"
        , content: renderTab
      });

      //Make the first tab active!
      $("#components .tab-pane").first().addClass("active");
      $("#formtabs li").first().addClass("active");
      new MyFormView({
        title: "Original"
        , collection: new MyFormSnippetsCollection(jsonData)
      });
    }
  }
});
