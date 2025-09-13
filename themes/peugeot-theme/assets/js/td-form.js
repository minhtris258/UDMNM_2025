(function($){
  // Accordion
  function setupAccordion(){
    var mobile = window.matchMedia('(max-width: 1024px)').matches;
    $('.td-acc').each(function(i){
      if(mobile){ $(this).toggleClass('td-open', i===0); }
      else{ $(this).addClass('td-open'); }
    });
  }
  $(document).on('click', '.td-acc-head', function(){
    if (window.matchMedia('(max-width: 1024px)').matches){
      $(this).closest('.td-acc').toggleClass('td-open');
    }
  });
  $(window).on('resize', setupAccordion);

  // UI helpers
  function showError($field, msg){
    $field.addClass('pg-error');
    if(!$field.find('.pg-error-text').length){
      $field.find('.acf-input').append('<div class="pg-error-text"></div>');
    }
    $field.find('.pg-error-text').text(msg || 'Vui lòng điền thông tin bắt buộc.');
  }
  function clearError($field){
    $field.removeClass('pg-error').find('.pg-error-text').remove();
  }

  // Client check (ngay lập tức)
  function clientValidate($field){
    if(!$field.hasClass('-required')) return true;
    var $inp = $field.find('select:visible, input[type="text"]:visible, input[type="email"]:visible, textarea:visible').first();
    var v = ($inp.val()||'').toString().trim();
    if(v==='' || v==='0' || v==='---'){ showError($field,'Trường này bắt buộc.'); return false; }
    clearError($field); return true;
  }

  // Server check (AJAX)
  function serverValidate($field){
    var key = $field.data('key');
    var $inp = $field.find('select:visible, input[type="text"]:visible, input[type="email"]:visible, textarea:visible').first();
    var v = ($inp.val()||'').toString().trim();

    return $.post(TD_AJAX.url, { action:'td_validate_field', nonce:TD_AJAX.nonce, context: TD_AJAX.context, key:key, value:v })
      .then(function(res){
        if(res && res.success && res.data){
          if(res.data.ok){ clearError($field); return true; }
          showError($field, res.data.message || 'Không hợp lệ'); return false;
        }
        return true;
      });
  }

  function debounce(fn, wait){ var t; return function(){ clearTimeout(t); var a=arguments, c=this; t=setTimeout(function(){ fn.apply(c,a); }, wait||250); }; }

  acf.add_action('ready append', function(){
    setupAccordion();
    var $form = $('#test-drive-form');

    // required native
    $form.find('.acf-field.-required').each(function(){
      $(this).find('input, textarea, select').attr('required','required');
    });

    // Realtime: nhập/blur -> client + AJAX
    $form.on('input blur change', 'input, textarea, select', debounce(function(){
      var $field = $(this).closest('.acf-field');
      if (!clientValidate($field)) return;   // trống -> báo lỗi ngay, KHÔNG cần gọi server
      serverValidate($field);                // có giá trị -> check định dạng/dup (email/phone)
    }, 200));

    // Submit: chặn nếu còn lỗi client
    $form.on('submit', function(e){
      var ok = true;
      $form.find('.acf-field.-required').each(function(){ if(!clientValidate($(this))) ok = false; });
      if(!ok){
        e.preventDefault();
        var $first = $form.find('.pg-error').first();
        if($first.length) $('html,body').animate({scrollTop:$first.offset().top-120}, 400);
      }
    });
  });
})(jQuery);
