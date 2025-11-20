jQuery(document).ready(function ($) {
  var $widget = $(".fgp-products-widget");
  var $container = $widget.find(".fgp-products-grid");
  var $items = $container.find(".fgp-product-item");
  var $searchInput = $widget.find(".fgp-search-input");

  // -------------------------------
  // FILTRAR POR CATEGORÍA (Optimizado)
  // -------------------------------
  $widget.find(".fgp-product-categories li.fgp-cat").on("click", function () {
    var cat = $(this).data("cat");

    // 1️⃣ Scroll inmediato (sin esperar al filtrado)
    $("html, body").scrollTop($container.offset().top - 80);

    // 2️⃣ Filtrado rápido sin causar reflow-blocking
    setTimeout(
      function () {
        $container.show();
        $items.hide();

        if (cat) {
          $items.filter('[data-cats*="' + cat + '"]').show();
        }

        $widget.find(".fgp-product-categories li.fgp-cat").removeClass("active");
        $(this).addClass("active");

        $searchInput.val("");
      }.bind(this),
      10
    ); // 10 ms = suficiente para que el scroll ocurra primero
  });

  // -------------------------------
  // FILTRAR POR BUSCADOR
  // -------------------------------
  $searchInput.on("input", function () {
    var term = $(this).val().toLowerCase();

    $container.show();

    $items.each(function () {
      var title = $(this).find("h3").text().toLowerCase();
      $(this).toggle(title.indexOf(term) !== -1);
    });

    if (!term) $items.show();
  });

  // -------------------------------
  // BOTÓN BORRAR FILTRO
  // -------------------------------
  if (!$widget.find("#fgp-clear-search").length) {
    $searchInput.after('<button type="button" id="fgp-clear-search">Borrar Filtro</button>');
  }

  $widget.on("click", "#fgp-clear-search", function () {
    $searchInput.val("");
    $items.show();
    $widget.find(".fgp-product-categories li.fgp-cat").removeClass("active");
    $container.show();

    // Opcional: mover al inicio de los productos
    $("html, body").animate(
      {
        scrollTop: $container.offset().top - 100,
      },
      500
    );
  });
});
