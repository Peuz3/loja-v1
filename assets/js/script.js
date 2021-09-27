$(function() {

    $("#slider-range").slider({
        range: true,
        min: 0,
        max: maxslider,
        values: [$('#slider0').val(), $('#slider1').val()],
        slide: function(event, ui) {
            $("#amount").val("R$" + ui.values[0] + " - R$" + ui.values[1]);
        },
        change: function(event, ui) {
            $('#slider' + ui.handleIndex).val(ui.value);
            $('.filterarea form').submit();
        }
    });

    $("#amount").val("R$" + $("#slider-range").slider("values", 0) + " - R$" + $("#slider-range").slider("values", 1));


    $('.filterarea').find('input').on('change', function() {
        $('.filterarea form').submit();
    });

    $('.addtocartform button').on('click', function(e) {
        e.preventDefault();

        var quantity = parseInt($('.addtocart_quantity').val());
        var action = $(this).attr('data-action');
        if (action == 'decrease') {
            if (quantity - 1 >= 1) {
                quantity = quantity - 1;
            }
        } else if (action == 'increase') {
            quantity = quantity + 1;
        }

        $('.addtocart_quantity').val(quantity);
    });
});