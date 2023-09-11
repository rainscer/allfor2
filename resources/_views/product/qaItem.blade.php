@foreach($qas as $qa)
    <div class="review-item">
        <div class="container-fluid">
            <div class="col-md-3 col-sm-3">
                @if ($qa->user)
                    <div class="user-name">
                        {{-- */
                        if(!is_array($qa->user->contacts)){
                            $qa->user->contacts = unserialize($qa->user->contacts);
                            $qa->user->contacts = (array)$qa->user->contacts;
                        }
                        /* --}}
                        {{ $qa->user->getFullName() }}{{ isset($qa->user->contacts['d_user_city']) ? ', '.$qa->user->contacts['d_user_city'] :
                         $qa->city ? ', '.$qa->city : ''}}
                    </div>
                @else
                    <div class="user-name">
                        {{ $qa->quest }}{{ $qa->city ? ', '.$qa->city : '' }}
                    </div>
                @endif
            </div>
            <div class="col-md-9 col-sm-9">
                <div class="review-text-qa">
                    {!! str_replace("\n", '<br />',$qa->text) !!}
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-md-3 col-sm-3">
                <img class="img-qa-answer" src="{{ asset('images/logo.png')  }}" alt="allfor2">
            </div>
            <div class="col-md-9 col-sm-9">
                <div class="review-text-qa">
                    {!! str_replace("\n", '<br />',$qa->answer) !!}
                </div>
                <div class="date">
                    {{ date_rus($qa->created_at, 'j MMM Y H:i' ) }}
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="review-render" data-owner-id="qa">
    {!! $qas->render()  !!}
</div>