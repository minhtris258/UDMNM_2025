(function ($) {
  const $form = $('.agency-filter, .agency-filter-form').first();
  const $results = $('#agency-results');
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
          $('.agency-count').text('Có ' + res.data.found_posts + ' Đại lý / Chi Nhánh / Workshop');
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
    $results.find('.agency-pagination a').on('click', function (e) {
      e.preventDefault();
      const href = $(this).attr('href') || '';
      const url = new URL(href, window.location.origin);
      const paged = parseInt(url.searchParams.get('paged') || '1', 10);
      fetchResults(paged);
    });
  }

  // Sự kiện form
  $form.on('submit', function (e) { e.preventDefault(); fetchResults(1); });
  $form.on('change', 'select', function () { fetchResults(1); });
  $form.on('input', 'input[name="keyword"]', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () { fetchResults(1); }, DEBOUNCE);
  });

  attachPaginationHandlers();
})(jQuery);
