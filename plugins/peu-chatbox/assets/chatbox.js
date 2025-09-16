(function(){
  if (!window.PEU_CB) return;

  const o = window.PEU_CB;

  // Fab
  const fab = document.createElement('div');
  fab.id = 'peu-cb-fab';
  fab.style.background = o.primary;
  fab.style.color = o.text;
  fab.style.setProperty('--peu-offset-x', o.offset_px+'px');
  fab.style.bottom = o.offset_vh + 'vh';
  if (o.position === 'left') fab.classList.add('left');

  fab.innerHTML = `
    <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 5a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3H9.83l-3.59 3.59A1 1 0 0 1 5 20v-3H5a3 3 0 0 1-3-3V5z"/></svg>
    <span class="badge">${o.badge>0?o.badge:''}</span>
  `;
  document.body.appendChild(fab);

  // Panel
  const panel = document.createElement('div');
  panel.id = 'peu-cb-panel';
  panel.style.background = o.bubble_bg;
  panel.style.color = o.bubble_text;
  panel.style.setProperty('--peu-primary', o.primary);
  panel.style.setProperty('--peu-text', o.text);
  panel.style.bottom = `calc(${o.offset_vh}vh + 68px)`;
  if (o.position === 'left') panel.classList.add('left');

  const logoHtml = o.logo ? `<img src="${o.logo}" alt="logo">` : '';
  panel.innerHTML = `
    <div id="peu-cb-head">${logoHtml}<div>${escapeHtml(o.title)}</div><button id="peu-cb-close" aria-label="Đóng">×</button></div>
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

  function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;', "'":'&#039;'}[m])); }

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

  function greet(){
    const m = msgBot(escapeHtml(o.greeting));
    renderQuickFlows(m);
  }

  function renderQuickFlows(container){
    if (!o.flows || !o.flows.length) return;
    const wrap = document.createElement('div');
    wrap.className = 'peu-quick';
    o.flows.forEach((f, idx)=>{
      const b = document.createElement('button');
      b.textContent = f.label;
      b.addEventListener('click', ()=> handleFlow(f));
      wrap.appendChild(b);
    });
    container.appendChild(wrap);
  }

  function handleFlow(flow){
    // hiển thị câu hỏi
    const m = msgBot(escapeHtml(flow.reply || ''));
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
          let text = (flow.then && flow.then[key]) ? flow.then[key] : 'Cảm ơn bạn!';
          text = text.replace(/\{\{test_drive_url\}\}/g, o.vars.test_drive_url);
          msgBot(escapeHtml(text));
        });
        wrap.appendChild(b);
      });
      m.appendChild(wrap);
    }
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

  // gửi tin nhắn tự do + webhook
  function sendMsg(){
    const val = input.value.trim();
    if (!val) return;
    msgYou(val);
    input.value='';

    // Forward webhook (nếu cấu hình)
    if (o.webhook && o.webhook.url){
      const form = new FormData();
      form.append('action','peu_cb_forward');
      form.append('nonce', o.rest.nonce);
      form.append('message', val);
      form.append('meta', JSON.stringify({path: location.pathname}));
      fetch(o.rest.ajax, {method:'POST', body: form});
    }

    // Bot phản hồi mặc định khi không thuộc flow
    setTimeout(()=> msgBot('Cảm ơn bạn! Nhân viên sẽ sớm liên hệ.'), 400);
  }

  sendBtn.addEventListener('click', sendMsg);
  input.addEventListener('keydown', e=>{ if (e.key === 'Enter') sendMsg(); });

  // tiện ích
  // apply left/right offsets
  if (o.position === 'left') {
    fab.classList.add('left'); panel.classList.add('left');
  }
})();
