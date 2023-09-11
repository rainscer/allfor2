@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Отзывы
            <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                    data-owner-id="{{ url('administrator/reviewsQa/delete') }}">Удалить</button>

            @if(Request::route()->getName() == 'reviews.archived')
                <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                        data-owner-id="{{ url('administrator/reviewsQa/restore') }}" style="margin-right: 10px !important;">
                    Востановить
                </button>
            @else
                <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                        data-owner-id="{{ url('administrator/reviewsQa/archive') }}" style="margin-right: 10px !important;">
                    Архивировать
                </button>
            @endif

        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped" id="review_table">
                    <tr>
                        <th>Пользователь</th>
                        <th>Товар</th>
                        <th>Отзыв</th>
                        <th>Активный</th>
                        <th></th>
                    </tr>
                    @foreach($reviews as $review)
                        <tr id="item-{{ $review->id }}">
                            <td>
                                @if (!$review->user)
                                    {{ $review->quest }}
                                    <span class="badge">{{ trans('cart.guest') }}</span>
                                @else
                                    {{ $review->user->getFullName() }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('product.url',[$review->product->upi_id, $review->product->slug]) }}" class="link_modal">
                                    {{ $review->product->$local }}
                                </a>
                            </td>
                            <td class="width-2">
                                <p>Дата: {{ date_rus($review->created_at, 'j MMM Y H:i' ) }}
                                    @if($review->new)
                                        <span class="badge">new</span>
                                    @endif
                                </p>
                                <div class="star_admin" data-score="{{ $review->rating }}"></div>
                                {!! Form::model($review, array('action' => 'AdminController@storeReview'))!!}
                                {!! Form::hidden('id', $review->id) !!}
                                {!! Form::hidden('type', $review->type) !!}
                                <p>
                                    <span class="bold">Отзыв:</span>
                                    {{ $review->text }}
                                </p>
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
                                <div class="form-group" style="margin-top: 10px;">
                                    {!! Form::textarea('answer', null, ['class'=>'form-control', 'size' => '50x5', 'placeholder' => 'Коментарий админа'] ) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::submit('Коментировать', ['class'=>'btn btn-success']) !!}
                                </div>
                                {!! Form::close()!!}
                            </td>
                            <td>
                                <input type="checkbox" id="review-active-box-{{ $review->id }}"
                                       data-owner-id ="{{ url('administrator/reviewsQa/update') }}"
                                       class="review-active change-active-admin" name="review_active_item[]"
                                       value="{{ $review->id }}"
                                       {{ $review->active == 1 ? 'checked' : '' }}
                                       >
                                <label for="review-active-box-{{ $review->id }}"><span></span></label>
                            </td>
                            <td>
                                <input type="checkbox" class="delete-box-admin" id="review-box-{{ $review->id }}"
                                       class="review-delete" name="review_item[]" value="{{ $review->id }}">
                                <label for="review-box-{{ $review->id }}"><span></span></label>
                            </td>
                            @endforeach
                        </tr>
                </table>
            </div>
            {!! $reviews->render()  !!}
        </div>
    </div>
@endsection