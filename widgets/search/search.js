(function ($) {

    $(document).on('click', '.fgp-search__button', function () {

        const wrapper = $(this).closest('.fgp-search');
        const input   = wrapper.find('.fgp-search__input');
        const results = wrapper.find('.fgp-search-results');
        const keyword = input.val();
        const postType = wrapper.data('type');

        if (!results.length) {
            console.warn("Falta el contenedor .fgp-search-results en el widget");
            return;
        }

        if (keyword.length < 2) {
            alert("Escribe al menos 2 caracteres");
            return;
        }

        $.ajax({
            url: fgp_search.ajax_url,
            method: "POST",
            data: {
                action: "fgp_live_search",
                keyword: keyword,
                post_type: postType
            },
            success: function (response) {

                // Insertar resultados en el widget
                results.html(response);

                // Hacer que Elementor re-evalúe contenido dinámico
                if (window.elementorFrontend && elementorFrontend.elementsHandler) {
                    elementorFrontend.elementsHandler.runReadyTrigger(results[0]);
                }
            }
        });

    });

})(jQuery);
