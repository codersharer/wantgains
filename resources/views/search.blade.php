<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wantgains</title>
    @include('include.head')
</head>
<body class="result-page">
<div id="wg-app">
    <a href="javascript:;" id="wg-nav-toggle" class="btn btn-light js-nav-toggle"><i
                class="iconfont icon-toggle"></i></a>
    @include('include.sidebar')
    <div id="wg-tool">
        <a href="#subscribe-modal" class="btn btn-light" data-toggle="modal"><i class="iconfont icon-subscribe"></i></a>
        <a href="javascript:;" class="btn btn-light btn-category js-tool-toggle"><i class="iconfont icon-category"></i></a>
        <a href="javascript:;" class="btn btn-light fade btn-totop js-totop"><i class="iconfont icon-top"></i></a>
    </div>
    <div id="wg-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 py-5 px-md-4">
                    <form class="search-form">
                        <div class="search-group d-flex border-bottom border-dark">
                            <i class="iconfont icon-search"></i>
                            <input type="search" name="q" placeholder="Search brands, products etc."
                                   value="{{ $q }}">
                        </div>
                    </form>
                    @if ($merchants)
                        <h1>Similar {{ $q }} stores</h1>
                        <section class="flip-container">
                            @foreach($merchants as $merchant)

                                <section class="boxItem">
                                    <a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}"></a>
                                    <a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}"></a>
                                    <a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}"></a>
                                    <a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}"></a>
                                    <section class="itemContentBox">
                                        <img src="/upload/merchant/logo/{{ $merchant['slug'] }}.png" alt="">
                                        <section class="itemContent">
                                            <span><a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                                        </section>
                                        <section class="itemContent">
                                            <span><a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                                        </section>
                                        <section class="itemContent">
                                            <span><a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                                        </section>
                                        <section class="itemContent">
                                            <span><a href="{{ route('merchant.detail', ['slug' => $merchant['slug'], 'merchant_id' => $merchant['id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                                        </section>
                                    </section>
                                    {{ $merchant['name'] }}

                                </section>
                            @endforeach
                        </section>

                    @endif
                    @include('include.product')
                    <ul class="pagination mt-5">
                        <li class="page-item">
                            @if ($isFirstPage)

                                <a class="page-link" href=""><i class="iconfont icon-prev"></i></a>
                            @else
                                <a class="page-link" href="{{ $route . "&page={$prevPage}" }}"><i
                                            class="iconfont icon-prev"></i></a>

                            @endif
                        </li>

                        <li class="page-item">
                            @if ($isFirstPage)
                                <a class="page-link" href="">{{ __('Prev') }}</a>
                            @else
                                <a class="page-link" href="{{ $route . "&page={$prevPage}" }}">{{ __('Prev') }}</a>
                            @endif
                        </li>
                        {{--<li class="page-item active" aria-current="page">--}}
                        {{--<span class="page-link">--}}
                        {{--{{ $paginator->total() }}--}}
                        {{--</span>--}}
                        {{--</li>--}}
                        &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <li class="page-item">
                            @if ($isLastPage)
                                <a class="page-link" href="">{{ __('Next') }}</a></li>
                        @else
                            <a class="page-link"
                               href="{{ $route .  "&page={$nextPage}" }}">{{ __('Next') }}</a></li>
                        @endif
                        <li class="page-item">
                            @if ($isLastPage)
                                <a class="page-link" href=""><i class="iconfont icon-next"></i></a>
                            @else
                                <a class="page-link" href="{{ $route . "&page={$nextPage}" }}"><i
                                            class="iconfont icon-next"></i></a>
                            @endif
                        </li>
                    </ul>
                </div>
{{--                @include('include.category')--}}
            </div>
        </div>
    </div>
</div>
<div id="wg-overlay"></div>

<!-- Modal -->
@include('include.modal_subscribe')
@include('include.foot')
</body>
</html>