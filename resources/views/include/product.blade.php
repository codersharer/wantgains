<ul id="wg-list">
            @if($products)
                @foreach($products as $product)
                    <li class="d-flex">
                        <div class="img"><img src="{{ $product['image_url'] }}" width="100%" height="100%">
                        </div>
                        <div class="content">
                            <a class="h5">{{ html_entity_decode(stripslashes($product['name'])) }}</a>
                            <p>
                                <a href="{{ route('merchant.detail', ['slug' => $product['merchant_slug'],'merchant_id' => $product['merchant_id']]) }}">{{ $product['merchant_name'] }}</a>
                            </p>
                            <div class="footer">

                                {{--@if ($product['promotions'])--}}
                                {{--@foreach($product['promotions'] as $scence => $promotion)--}}
                                {{--<span class="price"> {{ $scence }}: {{ $promotion['price'] }}</span>||--}}
                                {{--@endforeach--}}
                                {{--@endif--}}

                                @if ($product['price'] != $product['real_price'])
                                    <span class="price"><b
                                                class="">${{ $product['real_price'] }}</b><u>${{ $product['price'] }}</u></span>
                                @else
                                    <span class="price"><b class="">${{ $product['real_price'] }}</b></span>
                                @endif


                                <div class="float-right">
                                    {{--<a href="{{ route('product.out', ['productId' => $product['id']]) }}"--}}
                                    {{--class="btn btn-primary product-out"><i--}}
                                    {{--class="iconfont icon-open mr-1"></i>Coupon--}}
                                    {{--code</a>--}}
                                    {{--<a href="{{ route('product.out', ['productId' => $product['id']]) }}"--}}
                                    {{--class="btn btn-primary product-out"><i--}}
                                    {{--class="iconfont icon-open mr-1"></i>Get--}}
                                    {{--deal</a>--}}
                                    <a href="#" class="btn btn-primary product-promotion-modal" data-toggle="modal"
                                       data-product-id="{{ $product['id'] }}"><i
                                                class="iconfont icon-open mr-1"></i>{{ __('Get promotion') }}</a>
                                    {{--<a href="#" class="btn btn-primary product-out" data-out="{{ route('product.out', ['productId' => $product['id']]) }}"><i class="iconfont icon-open mr-1"></i>{{ __('Go to buy') }}</a>--}}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif

</ul>