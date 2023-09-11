<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalForProduct" aria-hidden="true">
{{--    <button type="button" class="close modal-product-close-btn" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content scroll_y">

            <div class="modal-body" id="product_body"></div>

        </div>
{{--        <div id="btnUp" class="btnUp"><img src="{{ asset('images/up.png') }}" alt="button"></div>--}}
    </div>
</div>
<style>
    #product_body {
        text-align: center;
    }
</style>
@push('scripts')
    <script>
        // $('.scroll_y') ? $('.scroll_y').mCustomScrollbar({
        //     axis:"y",
        //     theme:"dark",
        //     callbacks:{
        //         onScrollStart: function(){
        //             $('.btnUp').addClass('show');
        //         },
        //         onTotalScrollBack: function(){
        //             $('.btnUp').removeClass('show');
        //         }
        //     }
        // })
        // :
        // null;
        //
        // // Up Page
        // $('#btnUp').click(function (e) {
        //     e.preventDefault();
        //     // $('.scroll_y').mCustomScrollbar("scrollTo", $('.product-name.title-ajax') );
        //     $('.scroll_y').mCustomScrollbar('scrollTo',['top',null]);
        //     return false;
        // });
    </script>
@endpush