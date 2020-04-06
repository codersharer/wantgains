<div class="col-xl-3 p-0 bg-light">
    <aside id="wg-sidebar">
        <div class="sidebar-box categories">
            @if ($merchantInfo)
                    <div class="img"><img src="{{ $merchantInfo['logo'] }}" width="200px" height="164px"></div>
                    <div class="content">
{{--                        <a href="" class="h5">{{ $merchantInfo['name'] }}</a>--}}
                        <br />
                        <div class="footer">
                            <div class="float-left">
                                <a href="{{ route('out', ['domain' => $merchantInfo['domain']]) }}" class="btn btn-primary out"><i
                                            class="iconfont icon-opzen mr-1"></i>GO TO {{ $merchantInfo['domain'] }}</a>
                            </div>
                        </div>
                    </div>
                <br />
                <br />
            @endif
            <h3 class="sidebar-heading">Categories</h3>
            <ul>
                @foreach($categories as $cate)
                    <li><a href="{{ route('category', ['category' => $cate->category]) }}">{{ $cate->category }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>