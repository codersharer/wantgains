<ul class="pagination mt-5">
    <li class="page-item">
        @if ($isFirstPage)

            <a class="page-link" href=""><i class="iconfont icon-prev"></i></a>
        @else
            <a class="page-link" href="{{ $route . "?page={$prevPage}" }}"><i
                        class="iconfont icon-prev"></i></a>

        @endif
    </li>

    <li class="page-item">
        @if ($isFirstPage)
            <a class="page-link" href="">{{ __('Prev') }}</a>
        @else
            <a class="page-link" href="{{ $route . "page={$prevPage}" }}">{{ __('Prev') }}</a>
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
           href="{{ $route .  "?page={$nextPage}" }}">{{ __('Next') }}</a></li>
    @endif
    <li class="page-item">
        @if ($isLastPage)
            <a class="page-link" href=""><i class="iconfont icon-next"></i></a>
        @else
            <a class="page-link" href="{{ $route . "?page={$nextPage}" }}"><i
                        class="iconfont icon-next"></i></a>
        @endif
    </li>
</ul>