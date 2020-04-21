<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script type="text/javascript">
    $(function () {
        //默认先写死一个cookieid
        var cookieId = '34iou3nm9mnmyyyYo';
        $(window).on('resize', function () {
            let ww = $(this).width();
            if (ww < 1200) {
                $('#wg-tool').css({'width': 'auto'});
            } else {
                let w = ww * 0.8 * 0.25;
                $('#wg-tool').css({'width': w});
            }
            $('#wg-tool').addClass('show');
        }).resize();
        $(window).on('scroll', function () {
            let ww = $(this).width();
            if ($(this).scrollTop() > 50) {
                $('.js-totop').addClass('show');
            } else {
                $('.js-totop').removeClass('show');
            }
        }).scroll();
        $('.js-nav-toggle').on('click', function () {
            if ($('body').hasClass('off-left')) {
                $('body').removeClass('off-left');
            } else {
                $('body').addClass('off-left');
            }
        });
        $('.js-tool-toggle').on('click', function () {
            if ($('body').hasClass('off-right')) {
                $('body').removeClass('off-right');
            } else {
                $('body').addClass('off-right');
            }
        });
        $('.js-totop').on('click', function () {
            window.scrollTo(0, 0);
        });
        $('#form-subscribe').on('submit', function () {
            const $notepad = $(this).find('.notepad'),
                $input = $(this).find('input');
            let val = $input.val(),
                $label = $('<a href="javascript:;" class="words js-words">' + val + '</span>');
            if (val) {
                $notepad.append($label);
                $input.val('');
            }
            //提交之后保存
            return false;
        });
        $('#form-subscribe').on('click', '.js-words', function () {
            $(this).remove();
        });
        $('.btn-subscribe').on('click', function () {
            var words = ["adidas shoes", "Avaris 2"];
            $.ajax({
                type: 'POST',
                url: '{{ route('api.subscribe') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "mail": $('input[type="email"]').val(),
                    "keywords": words,
                    "track_id": cookieId
                },
                success: function (data) {
                }
            });
            $('#subscribe-modal').modal('hide')
        });
        $('.search-form').on('submit', function () {
            var q = $('input[name="q"]').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('api.user-track') }}',
                data: {"_token": "{{ csrf_token() }}", "source": "search", "value": q, "track_id": cookieId},
                success: function (data) {
                }
            });
        });
        $('.product-out').on('click', function () {
            // window.open($(this).attr('data-out'))
        });
        $('.out').on('click', function () {
            // window.open(window.location.href)
        });
        $('.product-promotion-modal').on('click', function () {
            var productId = $(this).attr('data-product-id');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '{{ route('api.product-detail') }}',
                data: {"_token": "{{ csrf_token() }}", "product_id": productId},
                success: function (data) {
                    // window.open(data.out);
                    $('#product-promotion-modal').html(data.html);
                    $('#product-promotion-modal').modal('show')
                }
            });
        })
    })
</script>