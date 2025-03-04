
$(document).ready(function () {
    $("#nav").onePageNav({
        scrollChange: function ($currentListItem) {
            if ($currentListItem[0].outerText == "Whole Lechon") {
                $(".nav-holder").animate({ scrollLeft: 0 }, 500);
            } else {
                $(".nav-holder").animate(
                    { scrollLeft: $currentListItem[0].offsetLeft },
                    500
                );
            }
        },
    });

    $(".add").on("click", function () {
        var max = parseInt($(this).prev().attr("max"));
        if ($(this).prev().val() < max) {
            $(this)
                .prev()
                .val(+$(this).prev().val() + 1);
        }
    });
    $(".sub").on("click", function () {
        if ($(this).next().val() > 1) {
            if ($(this).next().val() > 1)
                $(this)
                    .next()
                    .val(+$(this).next().val() - 1);
        }
    });

    function add_to_cart(e) {
        $("#" + e.target.parentNode.parentNode.parentNode.parentNode.id).modal("hide");
        setTimeout(function() {
            swal({
                    toast: true,
                    position: "center",
                    title: "Product Added to your cart!",
                    icon: "success",
                    buttons: {
                        cancel: {
                            text: "Continue Shopping",
                            value: false,
                            visible: true,
                        },
                        confirm: {
                            text: "VIew Cart",
                            value: true,
                            visible: true,
                        }
                    },
                }).then((value) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (value) {
                        window.location.href = "cart.html";
                    }
                });
        }, 500);
	};

    $("#addon").on("click", function () {
        if ($("#addon").is(":checked")) {
            $(".addon-wrap").addClass(
                "bg-very-dark-cyan border-very-dark-cyan border-opacity-100"
            );
        } else {
            $(".addon-wrap").removeClass(
                "bg-very-dark-cyan border-very-dark-cyan border-opacity-100"
            );
        }
    });

    $("#addToCartBtn").on("click", add_to_cart);
});