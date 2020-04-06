<div class="modal fade" id="search-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-transparent border-0">
            <form class="mb-4 search-form" action="{{ route('search') }}">
                <div class="search-group d-flex">
                    <i class="iconfont icon-search"></i>
                    <input type="search" name="q" placeholder="Search brands, products etc.">
                </div>
            </form>
            <div class="recent">
                <h6>Recent search</h6>
                <div class="notepad">
                    <a href="javascript:;" class="words">234hi</a>
                </div>
            </div>
        </div>
    </div>
</div>