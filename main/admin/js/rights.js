jQuery(document).ready(function()
{
    jQuery('button').button();

    jQuery('input').each(function(){
        var self = jQuery(this),
          label = self.next(),
          label_text = label.text();
          label.find('label').css('display','none');

        //label.remove();
        self.icheck({
          checkboxClass: 'icheckbox_line-red',
          insert: '<div class="icheck_line-icon"></div>' + label_text,
          cursor: true,
          handle: "checkbox",
          aria: true,
          mirror: false,
          checkedClass: 'icheckbox_line-green',
        });
        self.on('ifToggled', function(event) {
            id = jQuery(event.target).attr('id');
            if (jQuery(event.target).prop('checked'))
            {
                jQuery("#label_"+id).text(jQuery(event.target).attr('onval'));

                jQuery(event.target).parent().contents().filter(function(){ return this.nodeType == 3; }).first().replaceWith(jQuery(event.target).attr('onval'));
            } else {
                jQuery("#label_"+id).text(jQuery(event.target).attr('offval'));
                jQuery(event.target).parent().contents().filter(function(){ return this.nodeType == 3; }).first().replaceWith(jQuery(event.target).attr('offval'));

            }


        });
    });
    jQuery('select#user').selectmenu(
    {
        width: 400,
        change: function()
        {
            id = jQuery(this).val();
            location.href='rights?id='+id;
        },
    });
});