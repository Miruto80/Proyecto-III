(function($) {

  "use strict";
 var initPreloader = function() {
  $('body').addClass('preloader-site');

  function hidePreloader() {
    console.log("Preloader eliminado");
    $('.preloader-wrapper').fadeOut('slow', function() {
      $('body').removeClass('preloader-site');
    });
  }

  if (document.readyState === "complete") {
    // Página ya está completamente cargada
    hidePreloader();
  } else {
    // Esperamos a que se cargue
    $(window).on('load', hidePreloader);
  }
};

  // document ready
  $(document).ready(function() {
    
    initPreloader();

  }); // End of a document
  })(jQuery);