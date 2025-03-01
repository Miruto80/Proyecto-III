$(document).ready(function(){
    cargarNotificaciones();
    function cargarNotificaciones() {
        $.ajax({
            url: "?pagina=productos",
            type: "POST",
            data: { accion: 'obtenerNotificaciones' },
            success: function(respuesta) {
                var productos = JSON.parse(respuesta);
                var contenidoNotificaciones = "";
                var contadorNotificaciones = 0;
                productos.forEach(function(producto) {
                    var mensaje = "";
                    var icono = "";
                    if (producto.stock_total == 0) {
                        mensaje = "Producto agotado";
                        icono = "zmdi-alert-circle";

                    }  else if (producto.stock_total < 0) {
                        mensaje = "Stock negativo";
                        icono = "zmdi-alert-circle";
                    }


                    else if (producto.stock_total <= producto.stock_minimo) {
                        mensaje = "Stock bajo";
                        icono = "zmdi-alert-triangle";
                    }
                    contenidoNotificaciones += `
                    <a href="?pagina=productos" class="Notification" id="notifation-product-${producto.codigo}">
                    <div class="Notification-icon"><i class="zmdi ${icono} bg-danger"></i></div>
                    <div class="Notification-text">
                    <p>
                    <i class="zmdi zmdi-circle"></i>
                    <strong>${producto.nombre}</strong> 
                    <br>
                    <small>${mensaje}</small>
                    </p>
                    </div>
                    </a>
                    `;
                    contadorNotificaciones++;
                });
                $("#notificacionesContenedor").html(contenidoNotificaciones);
                if (contadorNotificaciones > 0) {
                    $(".btn-Notification").append(`<span class="badge bg-danger">${contadorNotificaciones}</span>`);
                }
            }
        });
    }

	/*Mostrar ocultar area de notificaciones*/
    $('.btn-Notification').on('click', function(){
        var ContainerNoty=$('.container-notifications');
        var NotificationArea=$('.NotificationArea');
        if(NotificationArea.hasClass('NotificationArea-show')&&ContainerNoty.hasClass('container-notifications-show')){
            NotificationArea.removeClass('NotificationArea-show');
            ContainerNoty.removeClass('container-notifications-show');
        }else{
            NotificationArea.addClass('NotificationArea-show');
            ContainerNoty.addClass('container-notifications-show');
        }
    });

    /*Mostrar y ocultar submenus*/
    $('.btn-subMenu').on('click', function(){
        var subMenu=$(this).next('ul');
        var icon=$(this).children("span");
        if(subMenu.hasClass('sub-menu-options-show')){
            subMenu.removeClass('sub-menu-options-show');
            icon.addClass('zmdi-chevron-left').removeClass('zmdi-chevron-down');
        }else{
            subMenu.addClass('sub-menu-options-show');
            icon.addClass('zmdi-chevron-down').removeClass('zmdi-chevron-left');
        }
    });

    /*Mostrar ocultar menu principal*/
    $('.btn-menu').on('click', function(){
    	var navLateral=$('.navLateral');
    	var pageContent=$('.pageContent');
    	var navOption=$('.navBar-options');
    	if(navLateral.hasClass('navLateral-change')&&pageContent.hasClass('pageContent-change')){
    		navLateral.removeClass('navLateral-change');
    		pageContent.removeClass('pageContent-change');
    		navOption.removeClass('navBar-options-change');
    	}else{
    		navLateral.addClass('navLateral-change');
    		pageContent.addClass('pageContent-change');
    		navOption.addClass('navBar-options-change');
    	}
    });

});

/*scrollbarra*/
(function($){
    $(window).on("load",function(){
        $(".NotificationArea, .pageContent").mCustomScrollbar({
            theme:"dark-thin",
            scrollbarPosition: "inside",
            autoHideScrollbar: true,
            scrollButtons:{ enable: true }
        });
        $(".navLateral-body").mCustomScrollbar({
            theme:"light-thin",
            scrollbarPosition: "inside",
            autoHideScrollbar: true,
            scrollButtons:{ enable: true }
        });
    });
})(jQuery);