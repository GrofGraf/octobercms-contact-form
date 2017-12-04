(function() {
  $('#contact-form').on('ajaxSuccess', function(ev, context, data, status, jqXHR) {
    if (data.error === true) {
      return console.log(arguments);
    } else {
      return $(this).hide(1000);
    }
  });
}).call(this);
