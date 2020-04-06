@extends('include.layout')

@section('title', 'Wantgains category')

@section('slider')
    @if ($topMerchants)
        <h1>Top {{ $category }} stores</h1>
        <section class="flip-container">
        @foreach($topMerchants as $merchant)

            <section class="boxItem">
                <a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}"></a>
                <a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}"></a>
                <a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}"></a>
                <a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}"></a>
                <section class="itemContentBox">
                    <img src="{{ $merchant['merchant_logo'] }}" alt="">
                    <section class="itemContent">
                        <span><a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                    </section>
                    <section class="itemContent">
                        <span><a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                    </section>
                    <section class="itemContent">
                        <span><a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                    </section>
                    <section class="itemContent">
                        <span><a href="{{ route('merchant.detail', ['slug' => $merchant['merchant_slug'], 'merchant_id' => $merchant['merchant_id']]) }}" class="btn btn-warning product-out"><i class="iconfont icon-open mr-1"></i>{{ __('View') }}</a></span>
                    </section>
                </section>
                {{ $merchant['merchant_name'] }}

            </section>
        @endforeach
        </section>

    @endif
@endsection