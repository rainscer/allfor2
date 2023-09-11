<div class="popup_block" id="aboutUs">

    {!! trans('home.aboutUs2') !!}

</div>
<script>
    $( document ).ready(function() {
        $('.popup_block').closest('.modal-body').addClass('popup_info').closest('.modal-dialog').removeClass('modal-lg');

    });
</script>