<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    @include('include.head')
</head>
<body class="home-page">
<div id="wg-app">
    <a href="javascript:;" id="wg-nav-toggle" class="btn btn-light js-nav-toggle"><i
                class="iconfont icon-toggle"></i></a>
    @include('include.sidebar')
    @include('include.top_tool')
    <div id="wg-main">
        <div class="container">

            <div class="row">

                <div class="col-xl-9 py-5 px-md-4">
                    @yield('slider')
                    <ul id="wg-list">
                        @include('include.product')
                    </ul>
                    @include('include.paginator')
                </div>
                @include('include.category')
            </div>
        </div>
    </div>
</div>
<div id="wg-overlay"></div>

<!-- Modal -->
@include('include.modal_search')
@include('include.modal_subscribe')
@include('include.modal_promotion')
@include('include.foot')
</body>
</html>