<div class="popup_block" id="payment">
    {!! trans('home.paymentInfo2') !!}
</div>
<script>
    $( document ).ready(function() {
        $('.popup_block').closest('.modal-body').addClass('popup_info').closest('.modal-dialog').removeClass('modal-lg');
    });
</script>