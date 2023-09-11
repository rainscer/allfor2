@foreach($reviews as $review)
    <div class="review-item container-fluid">
        <div class="col-md-3">
            @if ($review->user)
                <div class="user-name">
                    {{ $review->user->getFullName() }}
                    {{-- */ if(!is_array($review->user->contacts)){
                                $review->user->contacts = unserialize($review->user->contacts);
                                $review->user->contacts = (array)$review->user->contacts;
                             }
                        /* --}}
                    {{ isset($review->user->contacts['d_user_city']) ? ', '.$review->user->contacts['d_user_city'] :
                     $review->city ? ', '.$review->city : ''}}
                </div>
            @else
                <div class="user-name">
                    {{ $review->quest }}{{ $review->city ? ', '.$review->city : '' }}
                </div>
            @endif
            <div class="date">

                {{ date_rus($review->created_at, 'j MMM Y H:i' ) }}
            </div>
        </div>
        <div class="col-md-9">
            <div class="review-text-rew">
                {!! str_replace("\n", '<br />',$review->text) !!}
            </div>
            <div class="star_review" data-score="{{ $review->rating }}"></div>
            @if($review->images && $review->images->count())
                <div class="review-images">
                    <p class="added-files">
                        <img src="{{ asset('/images/skrepka.png') }}">
                        Прикреплённые файлы</p>
                    <div class="row">
                        @foreach($review->images as $image)
                            <div class="menu-image-preview-block col-md-2 col-lg-2 col-xs-6 col-sm-4 menu-preview-icon">
                                <img src="{{ asset(\App\Models\CatalogProduct::REVIEW_ASSET_PATH . $image->image) }}"
                                     onclick = 'openImageWindow(this.src);' class="menu-image-preview-review">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($review->answer)
                <div class="row admin-answer">
                    <div class="col-md-2">
                        <div class="user-name">
                            Админ
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="review-text-rew">
                            {{ $review->answer }}
                        </div>
                    </div>
                </div>
                @endif
        </div>
    </div>
@endforeach
<div class="review-render" data-owner-id="review">
    {!! $reviews->render()  !!}
</div>