function updateCartState() {
    $.get('/cart/get-state', {})
        .done(function (data) {

            $('#shopping-card-btn').hide();

            if (data.count > 0) {
                // show button in the bottom of the page
                $('#shopping-card-btn').show();
                $('#shopping-card-btn #cart-sum').html(data.sum + '$');

                // TODO update basket counter in the header
            }
        });
}

$(document).ready(function () {
    updateCartState();
});