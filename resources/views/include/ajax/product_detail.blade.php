<div class="modal-dialog modal-lg">
    <div class="modal-content bg-transparent border-0">
        <div class="product-promotion">
            <h6>{{ __('Promotions') }}</h6>
            <ul id="wg-list">
                @if ($products)
                    @foreach($products as $product)
                        <li class="d-flex">
                            <div class="img"><img src="{{ $product['image_url'] }}" width="100%" height="100%">
                            </div>
                            <div class="content">
                                <a class="h5">{{ html_entity_decode(stripslashes($product['name'])) }}</a>
                                <p>
                                    <a href="{{ route('merchant.detail', ['slug' => $product['merchant_slug'],'merchant_id' => $product['merchant_id']]) }}">{{ $product['merchant_name'] }}</a>
                                </p>
                                <p>{{ $product['description'] }}</p>
                                <div class="footer">

                                    @if ($product['promotions'])
                                        @foreach($product['promotions'] as $scence => $promotion)
                                            <span class="price"> {{ $scence }}: {{ $promotion['price'] }}&nbsp;&nbsp;Code:{{ $promotion['promotion']['coupon_code'] }}</span> <br />
                                        @endforeach
                                    @endif

                                    @if ($product['price'] != $product['real_price'])
                                        <span class="price"><b
                                                    class="">${{ $product['real_price'] }}</b><u>${{ $product['price'] }}</u></span>
                                    @else
                                        <span class="price"><b class="">${{ $product['real_price'] }}</b></span>
                                    @endif



                                    <div class="float-right">
                                        <a href="#product-promotion-modal" class="btn btn-primary"
                                           data-toggle="modal" data-product="{{ $product['promotions_json']}}"><i
                                                    class="iconfont
                                               icon-open
                                               mr-1"></i>{{ __('Get promotion') }}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>