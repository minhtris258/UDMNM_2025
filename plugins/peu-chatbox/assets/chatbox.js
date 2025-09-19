(function(){
  if (!window.PEU_CB) return;

  const o = window.PEU_CB || {};
  const I18N = o.i18n || { title: 'Chat', greeting: 'Hello', default: 'Thanks!' };

  // ============ Helpers ============
  function escapeHtml(s){
    return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;', "'":'&#039;'}[m]));
  }
  function replacePlaceholders(text){
    if (!text) return '';
    return text
      .replace(/\{\{contact_url\}\}/g, (o.vars && o.vars.contact_url) ? o.vars.contact_url : '#')
      .replace(/\{\{test_drive_url\}\}/g, (o.vars && o.vars.test_drive_url) ? o.vars.test_drive_url : '#');
  }
  // Render text an toàn + tự link URL
  function renderText(raw){
    const withVars = replacePlaceholders(raw || '');
    let html = escapeHtml(withVars)
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/\n/g, '<br>');
    // auto-link http/https
    html = html.replace(/(https?:\/\/[^\s<]+)/g, '<a href="$1" target="_blank" rel="noopener nofollow">$1</a>');
    return html;
  }

  // ============ FAB ============
  const fab = document.createElement('div');
  fab.id = 'peu-cb-fab';
  fab.style.background = o.primary;
  fab.style.color = o.text;
  fab.style.setProperty('--peu-offset-x', (o.offset_px || 0) + 'px');
  fab.style.bottom = (o.offset_vh || 0) + 'vh';
  if (o.position === 'left') fab.classList.add('left');

  fab.innerHTML = `
    <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 5a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3H9.83l-3.59 3.59A1 1 0 0 1 5 20v-3H5a3 3 0 0 1-3-3V5z"/></svg>
    <span class="badge">${o.badge>0?o.badge:''}</span>
  `;
  document.body.appendChild(fab);

  // ============ Panel ============
  const panel = document.createElement('div');
  panel.id = 'peu-cb-panel';
  panel.style.background = o.bubble_bg;
  panel.style.color = o.bubble_text;
  panel.style.setProperty('--peu-primary', o.primary);
  panel.style.setProperty('--peu-text', o.text);
  panel.style.bottom = `calc(${o.offset_vh || 0}vh + 68px)`;
  if (o.position === 'left') panel.classList.add('left');

  const logoHtml = o.logo ? `<img src="${o.logo}" alt="logo">` : '';
  const headerTitle = escapeHtml(I18N.title || o.title || 'Chat');
  panel.innerHTML = `
    <div id="peu-cb-head">${logoHtml}<div>${headerTitle}</div><button id="peu-cb-close" aria-label="Đóng">×</button></div>
    <div id="peu-cb-body"></div>
    <div id="peu-cb-input">
      <input type="text" placeholder="Nhập tin nhắn...">
      <button type="button" style="background:${o.primary};color:${o.text}">Gửi</button>
    </div>
  `;
  document.body.appendChild(panel);

  const body = panel.querySelector('#peu-cb-body');
  const input = panel.querySelector('#peu-cb-input input');
  const sendBtn = panel.querySelector('#peu-cb-input button');
  const badge = fab.querySelector('.badge');

  let unseen = o.badge || 0;
  let opened = false;

  function scrollEnd(){ body.scrollTop = body.scrollHeight; }

  function msgBot(html){
    const el = document.createElement('div');
    el.className = 'peu-msg peu-bot';
    el.innerHTML = html;
    body.appendChild(el);
    if (!opened){ unseen++; badge.textContent = unseen; }
    scrollEnd();
    return el;
  }
  function msgYou(text){
    const el = document.createElement('div');
    el.className = 'peu-msg peu-you';
    el.textContent = text;
    body.appendChild(el);
    scrollEnd();
  }

  function renderQuickFlows(container){
    if (!o.flows || !o.flows.length) return;
    const wrap = document.createElement('div');
    wrap.className = 'peu-quick';
    o.flows.forEach((f)=>{
      const b = document.createElement('button');
      b.textContent = f.label;
      b.addEventListener('click', ()=> handleFlow(f));
      wrap.appendChild(b);
    });
    container.appendChild(wrap);
  }

  function handleFlow(flow){
    // hiển thị câu hỏi (auto-link)
    const m = msgBot(renderText(flow.reply || ''));
    if (Array.isArray(flow.choices)){
      const wrap = document.createElement('div');
      wrap.className = 'peu-quick';
      flow.choices.forEach(c=>{
        const b = document.createElement('button');
        b.textContent = c.label;
        b.addEventListener('click', ()=>{
          msgYou(c.label);
          wrap.remove();
          const key = c.value;
          let text = (flow.then && flow.then[key]) ? flow.then[key] : (I18N.default || 'Cảm ơn bạn!');
          msgBot(renderText(text)); // auto-link
        });
        wrap.appendChild(b);
      });
      m.appendChild(wrap);
    }
  }

  function greet(){
    const greetTextRaw = I18N.greeting || o.greeting || '';
    const m = msgBot(renderText(greetTextRaw)); // auto-link
    renderQuickFlows(m);
  }

  function openPanel(){
    opened = true;
    panel.classList.add('open');
    unseen = 0; badge.textContent = '';
    if (!body.dataset.greet){ greet(); body.dataset.greet = '1'; }
    input.focus();
  }
  function closePanel(){ opened = false; panel.classList.remove('open'); }

  fab.addEventListener('click', openPanel);
  panel.querySelector('#peu-cb-close').addEventListener('click', closePanel);

  // ============ Intent (FAQ) ============
  function intentReply(val){
    if (!o.faq) return null;
    const msg = (val || '').toLowerCase();
    const keys = Object.keys(o.faq).sort((a,b)=>b.length - a.length); // ưu tiên khóa dài
    for (const k of keys){
      if (msg.includes(k.toLowerCase())){
        return o.faq[k]; // trả về raw text, renderText sẽ xử lý
      }
    }
    return null;
  }

  // ============ Send ============
  function sendMsg(){
    const val = input.value.trim();
    if (!val) return;
    msgYou(val);
    input.value='';

    // 1) thử trả lời theo FAQ
    const hit = intentReply(val);
    if (hit){
      setTimeout(()=> msgBot(renderText(hit)), 250);
    } else {
      // 2) không khớp → mặc định + link liên hệ
      const fallback = (I18N.default || 'Cảm ơn bạn! Nhân viên sẽ sớm liên hệ.')
                     + '\n' + 'Bạn có thể gửi yêu cầu tại: {{contact_url}}';
      setTimeout(()=> msgBot(renderText(fallback)), 400);
    }

    // 3) Forward webhook (nếu cấu hình)
    if (o.webhook && o.webhook.url){
      const form = new FormData();
      form.append('action','peu_cb_forward');
      form.append('nonce', (o.rest && o.rest.nonce) ? o.rest.nonce : '');
      form.append('message', val);
      form.append('meta', JSON.stringify({path: location.pathname, lang: o.lang || 'vi'}));
      fetch(o.rest.ajax, {method:'POST', body: form});
    }
  }

  sendBtn.addEventListener('click', sendMsg);
  input.addEventListener('keydown', e=>{ if (e.key === 'Enter') sendMsg(); });

  // apply left/right offsets
  if (o.position === 'left') {
    fab.classList.add('left');
    panel.classList.add('left');
  }
})();
