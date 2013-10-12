// $Id: editarea.js,v 1.2 2009/06/06 05:56:34 sun Exp $

/**
 * Attach this editor to a target element.
 */
Drupal.wysiwyg.editor.attach.editarea = function(context, params, settings) {
  
  editAreaLoader.init({
    id : params.field		// textarea id
    ,syntax: "html"			// syntax to be uses for highlighting
    ,start_highlight: true		// to display with highlight mode on start-up
  });

  // Adjust CSS for editor buttons.
/*  $.each(settings.markupSet, function (button) {
    $('.' + settings.nameSpace + ' .' + this.className + ' a')
      .css({ backgroundImage: 'url(' + settings.root + 'sets/default/images/' + button + '.png' + ')' })
      .parents('li').css({ backgroundImage: 'none' });
  });
*/
};

/**
 * Detach a single or all editors.
 */
Drupal.wysiwyg.editor.detach.editarea = function(context, params) {
  if (typeof params != 'undefined') {
    editAreaLoader.delete_instance(params.field);
  }
};


