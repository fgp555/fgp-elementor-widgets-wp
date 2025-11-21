jQuery(document).ready(function ($) {
  var $widget = $(".fgp-products-widget");
  var $container = $widget.find(".fgp-products-grid");
  var $items = $container.find(".fgp-product-item");
  var $searchInput = $widget.find(".fgp-search-input");
  var $categories = $widget.find(".fgp-product-categories");
  var $clearBtn = $widget.find("#fgp-clear-search");

  // Crear contenedor para mensaje si no existe
  var $message = $widget.find(".fgp-search-message");
  if (!$message.length) {
    $message = $('<p class="fgp-search-message" style="text-align:center; color:#fff; margin-bottom:20px;"></p>');
    $container.before($message);
  }

  // Inicializar texto del botón
  function updateButtonText() {
    if ($searchInput.val().trim() === "") {
      $clearBtn.text("Ver todos los productos");
    } else {
      $clearBtn.text("Borrar filtro");
    }
  }

  function updateMessage(term) {
    if (!term) {
      $message.text("Mostrando todos los productos").show();
    } else {
      var anyVisible = $items.filter(":visible").length > 0;
      if (!anyVisible) {
        $message.text("No se encontraron productos para: '" + term + "'").show();
      } else {
        $message.hide();
      }
    }
  }

  updateButtonText();
  updateMessage("");

  // -------------------------------
  // Filtrar por categoría
  // -------------------------------
  $widget.find(".fgp-product-categories li.fgp-cat").on("click", function () {
    var cat = $(this).data("cat");
    $("html, body").scrollTop($container.offset().top - 80);
    $container.show();

    if (cat === "all") {
      $items.show();
      $categories.show();
      updateMessage(""); // mensaje "Mostrando todos los productos"
    } else {
      $items
        .hide()
        .filter('[data-cats*="' + cat + '"]')
        .show();
      $categories.show();
      updateMessage(""); // opcional: mensaje vacío al filtrar por categoría
    }

    $widget.find(".fgp-product-categories li.fgp-cat").removeClass("active");
    $(this).addClass("active");

    $searchInput.val("");
    updateButtonText();
  });

  // -------------------------------
  // Filtrar por buscador
  // -------------------------------
  $searchInput.on("input", function () {
    var term = $(this).val().toLowerCase();
    $container.show();

    if (term.length > 0) {
      $categories.hide();
    } else {
      $categories.show();
    }

    $items.each(function () {
      var title = $(this).find("h3").text().toLowerCase();
      $(this).toggle(title.indexOf(term) !== -1);
    });

    if (!term) $items.show();

    updateButtonText();
    updateMessage(term);
  });

  // -------------------------------
  // Botón borrar filtro
  // -------------------------------
  $widget.on("click", "#fgp-clear-search", function () {
    var hasText = $searchInput.val().trim() !== "";
    $searchInput.val("");
    $categories.show();
    if (hasText) {
      $items.show();
    } else {
      $widget.find(".fgp-product-categories li.fgp-cat[data-cat='all']").trigger("click");
    }
    updateButtonText();
    updateMessage("");
  });
});
