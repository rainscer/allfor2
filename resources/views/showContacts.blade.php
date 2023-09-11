<div class="popup_block" id="contacts">
    <div class="info">
        <div class="row">
            <h2>{{ trans('home.contactUsTitle') }}</h2>
            <div class="col-xs-12 col-md-6 connection">
                <div>
                    <span class="icon_c viber"></span>
                        <a href="viber://add?number={{ preg_replace('~[^0-9]+~','',config('app.kievstar_phone')) }}" >
                            {{ config('app.kievstar_phone') }}
                        </a>
                </div>
                <div>
                    <span class="icon_c watsapp"></span>
                    <a href="https://api.whatsapp.com/send?phone={{ preg_replace('~[^0-9]+~','',config('app.kievstar_phone')) }}&text=Я%20заитересован%20в%20покупке%20наушников%20Klangdorf">
                        {{ config('app.kievstar_phone') }}
                    </a>
                </div>
                <div>
                    <span class="icon_c telegram"></span><span>{{ config('app.kievstar_phone') }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 connection">
                <div>
                    <span class="icon_c phone-img"></span><span>{{ config('app.mts_phone') }}</span>
                </div>
                <div>
                    <span class="icon_c email"></span><span>{{ config('mail.admin_question') }}</span>
                </div>
                <div>
                    <span class="icon_c livechat"></span><span>Live Chat</span>
                </div>
            </div>

            <h2>{{ trans('callback.orderCall') }}</h2>
            <div class="row form_callback">

                {!! Form::open(['url' => 'call-me-save', 'id' => 'call-me']) !!}
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::text('phone',null, ['class'=>'form-control phone', 'require', 'placeholder' => trans('callback.phonePlaceholder')] ) !!}
                    </div>
                </div>
                <div class="col-sm-12">
                    {!! Form::submit('Заказать звонок', ['class'=>'phone-btn'] ) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <h2>{{ trans('home.social') }} </h2>
        <div class="row social">
            <div class="col-xs-12">
                <div class="col-xs-12 col-md-6">
                    <a href="https://www.facebook.com/allfor2" target="_blank">
                        <img src="{{ asset('/images/fb_contacts.png') }}" alt="">
                    </a>
                </div>
                <div class="col-xs-12 col-md-6">
                    <a href="https://www.instagram.com/korovooo/" target="_blank">
                        <img src="{{ asset('/images/insta.png') }}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row info-ok">
        <div class="col-xs-offset-1 col-xs-3 col-sm-offset-1 col-sm-4">
            <hr>
        </div>
        <div class="col-xs-4 col-sm-2 align-center">
            <img src="{{ asset('/images/ok.png') }}" >
            <br>
            <br>
        </div>
        <div class="col-xs-3 col-sm-4">
            <hr>
        </div>
        <div class="row">
            <h2 class="col-xs-12">{!! trans('home.orderSuccess') !!}</h2>
            <div class="col-xs-12 align-center">
                <br>
                <br>
                <img src="{{ asset('/images/ok_bottom.png') }}" style="width: 100%; max-width: 304px;">
            </div>
        </div>


    </div>
</div>

<script>
    $( document ).ready(function() {
        $('.popup_block').closest('.modal-body').addClass('popup_info').closest('.modal-dialog').removeClass('modal-lg');

        // Phone mask
        $(".phone").mask("+38(099) 999-9999");
    });
</script>