<div class="popup_block" id="deliveryInfo">
    {!! trans('home.deliveryInfo2') !!}
</div>
<script>
    $( document ).ready(function() {
        $('.popup_block').closest('.modal-body').addClass('popup_info').closest('.modal-dialog').removeClass('modal-lg');
    });
</script>