jQuery(document).ready(function ($) {
  var $widget = $(".fgp-products-widget");
  var $container = $widget.find(".fgp-products-grid");
  var $items = $container.find(".fgp-product-item");
  var $searchInput = $widget.find(".fgp-search-input");

  // Mostrar todos los productos al cargar si quieres descomentar:
  // $container.show();

  // Filtrar por categoría
  $widget.find(".fgp-product-categories li.fgp-cat").on("click", function () {
    var cat = $(this).data("cat");

    $container.show(); // mostrar productos al hacer click
    $items.hide();

    if (cat) {
      $items.filter('[data-cats*="' + cat + '"]').show();
    }

    $(this).siblings().removeClass("active");
    $(this).addClass("active");

    $searchInput.val(""); // limpiar buscador
  });

  // Filtrar por buscador
  $searchInput.on("input", function () {
    var term = $(this).val().toLowerCase();

    // Mostrar contenedor
    $container.show();

    $items.each(function () {
      var title = $(this).find("h3").text().toLowerCase();
      $(this).toggle(title.indexOf(term) !== -1);
    });

    // Si borramos todo, mostramos todos los productos
    if (!term) $items.show();
  });

  // Botón borrar
  if (!$widget.find(".fgp-clear-search").length) {
    $searchInput.after('<button type="button" id="fgp-clear-search">Borrar Filtro</button>');
  }

  $widget.on("click", "#fgp-clear-search", function () {
    $searchInput.val(""); // limpiar input
    $items.show(); // mostrar todos los productos
    $widget.find(".fgp-product-categories li.fgp-cat").removeClass("active"); // quitar active
    $container.show(); // asegurarse que se vea el contenedor
  });
});
