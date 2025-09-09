(function ($) {
  const $form = $('.agency-filter-form'); // nhớ class này trong template
  const $results = $('#agency-results');  // vùng kết quả
  let typingTimer;
  const DEBOUNCE = 300;

  function params() {
    return {
      action: 'filter_agencies',
      nonce: AGENCY_AJAX.nonce,
      keyword: $form.find('[name="keyword"]').val() || '',
      mien: $form.find('[name="mien"]').val() || '',
      tinhthanh: $form.find('[name="tinhthanh"]').val() || '',
      paged: $form.data('page') || 1,
    };
  }

  function pushState(paged) {
    // Cập nhật URL để share được trạng thái lọc
    const url = new URL(window.location.href);
    const p = params();
    url.searchParams.set('keyword', p.keyword);
    if (p.mien) url.searchParams.set('mien', p.mien); else url.searchParams.delete('mien');
    if (p.tinhthanh) url.searchParams.set('tinhthanh', p.tinhthanh); else url.searchParams.delete('tinhthanh');
    if (paged && paged > 1) url.searchParams.set('paged', paged); else url.searchParams.delete('paged');
    window.history.replaceState({}, '', url.toString());
  }

  function fetchResults(paged = 1) {
    $form.data('page', paged);
    const data = params();
    data.paged = paged;

    $results.addClass('is-loading');

    $.post(AGENCY_AJAX.ajax_url, data)
      .done(function (res) {
        if (res && res.success) {
          $results.html(res.data.html);
          pushState(paged);
          attachPaginationHandlers();
        } else {
          $results.html('<p>Lỗi tải dữ liệu.</p>');
        }
      })
      .fail(function () {
        $results.html('<p>Không thể kết nối máy chủ.</p>');
      })
      .always(function () {
        $results.removeClass('is-loading');
      });
  }

  function attachPaginationHandlers() {
    // Bắt click vào các link phân trang (đều nằm trong #agency-results)
    $results.find('.agency-pagination a').on('click', function (e) {
      e.preventDefault();
      // Tìm ?paged=n trong href
      const href = $(this).attr('href') || '';
      const url = new URL(href, window.location.origin);
      const paged = parseInt(url.searchParams.get('paged') || '1', 10);
      fetchResults(paged);
    });
  }

  // Sự kiện form
  $form.on('submit', function (e) {
    e.preventDefault();
    fetchResults(1);
  });

  $form.on('change', 'select', function () {
    fetchResults(1);
  });

  $form.on('input', 'input[name="keyword"]', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () {
      fetchResults(1);
    }, DEBOUNCE);
  });

  // Lần đầu vào trang: load theo URL hiện tại (nếu có query) hoặc hiển thị sẵn server-side
  // Nếu muốn luôn load AJAX ngay khi vào trang:
  // fetchResults(parseInt(new URL(window.location.href).searchParams.get('paged') || '1', 10));

  // Sau mỗi lần AJAX xong, pagination handlers được gắn lại
  attachPaginationHandlers();

})(jQuery);
