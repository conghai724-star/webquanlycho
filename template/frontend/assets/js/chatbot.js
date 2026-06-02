
(function () {
  var CHAT_FLOW = {
    start: {
      message: 'Xin chào! 👋 Mình là trợ lý hướng dẫn Vieclam. Bạn chọn một gợi ý bên dưới để được chỉ dẫn nhanh nhé.',
      suggestions: [
        { id: 'job_find', label: 'Tôi muốn tìm việc làm', icon: 'ti-briefcase' },
        { id: 'employer', label: 'Tôi là nhà tuyển dụng', icon: 'ti-building' },
        { id: 'account', label: 'Hỗ trợ tài khoản', icon: 'ti-user' },
        { id: 'contact', label: 'Liên hệ tư vấn', icon: 'ti-headset' }
      ]
    },
    job_find: {
      answer: '📌 Cách tìm việc:\n1. Nhập vị trí hoặc công ty.\n2. Chọn khu vực.\n3. Lọc theo ngành nghề, mức lương.\n4. Xem chi tiết và ứng tuyển.',
      suggestions: [
        { id: 'job_cv', label: 'Tạo CV online', icon: 'ti-file-cv' },
        { id: 'start', label: '← Menu chính', icon: 'ti-home', back: true }
      ]
    },
    job_cv: {
      answer: '📄 Tạo CV online: chọn mẫu CV, điền thông tin, lưu và dùng để ứng tuyển nhanh.',
      suggestions: [
        { id: 'job_find', label: 'Tìm việc phù hợp', icon: 'ti-briefcase' },
        { id: 'start', label: '← Menu chính', icon: 'ti-home', back: true }
      ]
    },
    employer: {
      answer: '🏢 Nhà tuyển dụng có thể đăng tin, quản lý hồ sơ ứng viên và liên hệ ứng viên phù hợp.',
      suggestions: [
        { id: 'contact', label: 'Liên hệ tư vấn', icon: 'ti-headset' },
        { id: 'start', label: '← Menu chính', icon: 'ti-home', back: true }
      ]
    },
    account: {
      answer: '🔐 Bạn có thể đăng nhập bằng email hoặc Google. Nếu quên mật khẩu, hãy dùng chức năng đặt lại mật khẩu.',
      suggestions: [
        { id: 'contact', label: 'Cần hỗ trợ thêm', icon: 'ti-headset' },
        { id: 'start', label: '← Menu chính', icon: 'ti-home', back: true }
      ]
    },
    contact: {
      answer: '📞 Hotline ứng viên: (028) 7109 2424 / (024) 7309 2424.\nHotline nhà tuyển dụng: (028) 7108 2424 / (024) 7308 2424.',
      suggestions: [
        { id: 'start', label: '← Menu chính', icon: 'ti-home', back: true }
      ]
    }
  };

  var chatPanel = document.getElementById('chatPanel');
  var chatToggleBtn = document.getElementById('chatToggleBtn');
  var chatToggleIcon = document.getElementById('chatToggleIcon');
  var chatPanelClose = document.getElementById('chatPanelClose');
  var chatPanelBody = document.getElementById('chatPanelBody');
  var chatMessages = document.getElementById('chatMessages');
  var chatSuggestions = document.getElementById('chatSuggestions');
  if (!chatPanel || !chatToggleBtn || !chatMessages || !chatSuggestions) return;

  function scrollChat(){ if(chatPanelBody) chatPanelBody.scrollTop = chatPanelBody.scrollHeight; }
  function appendMessage(text,type){
    var msg=document.createElement('div');
    msg.className='chat-msg '+(type||'bot');
    msg.textContent=text;
    chatMessages.appendChild(msg);
    scrollChat();
  }
  function renderSuggestions(list){
    chatSuggestions.innerHTML='';
    list.forEach(function(item){
      var btn=document.createElement('button');
      btn.type='button';
      btn.className='chat-suggestion-btn'+(item.back?' is-back':'');
      btn.innerHTML='<i class="ti '+(item.icon||'ti-chevron-right')+'"></i><span>'+item.label+'</span>';
      btn.onclick=function(){ handleChoice(item.id,item.label); };
      chatSuggestions.appendChild(btn);
    });
  }
  function showNode(id,userLabel){
    var node=CHAT_FLOW[id];
    if(!node) return;
    if(userLabel) appendMessage(userLabel,'user');
    setTimeout(function(){
      appendMessage(id==='start' && !userLabel ? node.message : node.answer || node.message,'bot');
      renderSuggestions(node.suggestions || []);
    }, userLabel ? 250 : 0);
  }
  function handleChoice(id,label){
    if(id==='start'){
      chatMessages.innerHTML='';
      showNode('start');
      return;
    }
    showNode(id,label);
  }
  function setChatOpen(open){
    chatPanel.classList.toggle('open',open);
    chatToggleBtn.classList.toggle('open',open);
    chatToggleBtn.setAttribute('aria-expanded',open?'true':'false');
    chatPanel.setAttribute('aria-hidden',open?'false':'true');
    if(chatToggleIcon) chatToggleIcon.className=open?'ti ti-x':'ti ti-message-circle';
    if(open){chatMessages.innerHTML='';showNode('start');}
  }
  chatToggleBtn.addEventListener('click',function(){setChatOpen(!chatPanel.classList.contains('open'));});
  if(chatPanelClose) chatPanelClose.addEventListener('click',function(){setChatOpen(false);});
})();
