<?php
require_once 'openai.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['chat_id'])) $_SESSION['chat_id'] = bin2hex(random_bytes(16));
$sessionId = $_SESSION['chat_id'];

$mysqli = new mysqli("localhost", "root", "", "doan_banbanh");
if ($mysqli->connect_error) die("DB lỗi");

// Lấy lịch sử
$stmt = $mysqli->prepare("SELECT messages FROM chat_sessions WHERE session_id = ?");
$stmt->bind_param("s", $sessionId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    $messages = [['role'=>'system','content'=>'Bạn là trợ lý siêu dễ thương của Canvas Bánh Ngọt, nói giọng miền Nam, hay dùng emoji, rất nhiệt tình tư vấn khách đặt bánh nhé!']];
    $json = json_encode($messages, JSON_UNESCAPED_UNICODE);
    $ins = $mysqli->prepare("INSERT INTO chat_sessions (session_id, messages) VALUES (?, ?)");
    $ins->bind_param("ss", $sessionId, $json);
    $ins->execute();
} else {
    $messages = json_decode($res->fetch_assoc()['messages'], true);
}
$stmt->close();

// XỬ LÝ TIN NHẮN MỚI – ĐÃ SỬA ĐÚNG 100% CHO TECTALIC
if (!empty($_POST['message'])) {
    $userMsg = trim($_POST['message']);
    $messages[] = ['role' => 'user', 'content' => $userMsg];

    try {
        $client = getOpenAIClient();

        // CÚ PHÁP CHÍNH XÁC NHẤT CHO TECTALIC 1.x
        $response = $client->chatCompletions()->create([
            'model'       => 'gpt-4o-mini',
            'messages'    => $messages,
            'temperature' => 0.8,
            'max_tokens'  => 800
        ]);

        // ← ĐÂY LÀ DÒNG QUAN TRỌNG NHẤT – SỬA ĐÚNG RỒI!
        $reply = $response->choices[0]->message->content ?? "Mình đang hơi mệt, bạn nói lại giúp mình nha!";

    } catch (Exception $e) {
        error_log("OpenAI lỗi: " . $e->getMessage());
        $reply = "Oops, mạng hơi chập chờn, bạn gửi lại giúp mình nha!";
    }

    // Lưu lại lịch sử
    $messages[] = ['role' => 'assistant', 'content' => $reply];
    $json = $mysqli->real_escape_string(json_encode($messages, JSON_UNESCAPED_UNICODE));
    $mysqli->query("UPDATE chat_sessions SET messages='$json', last_active=NOW() WHERE session_id='$sessionId'");

    echo $reply;
    exit;
}
?>

<!-- PHẦN GIAO DIỆN CHAT (GIỮ NGUYÊN, ĐẸP HOÀN HẢO) -->
<div id="canvas-chat-widget" class="fixed bottom-6 right-6 z-[9999999999] font-sans select-none">
    <button id="chat-toggle" class="bg-gradient-to-br from-pink-500 to-purple-600 text-white rounded-full shadow-2xl w-16 h-16 flex items-center justify-center hover:scale-110 transition-all duration-300">
        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
    </button>

    <div id="chat-box" class="hidden absolute bottom-20 right-0 w-80 max-w-[92vw] bg-white rounded-2xl shadow-2xl overflow-hidden border border-pink-200 flex flex-col" style="height:480px;">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white p-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/30 rounded-full flex items-center justify-center text-lg">Cake</div>
                <div><div class="font-bold text-lg">Canvas Bánh Ngọt</div><div class="text-xs opacity-90">Đang online • Trả lời ngay</div></div>
            </div>
            <button id="chat-close" class="text-2xl hover:bg-white/20 w-9 h-9 rounded-full">×</button>
        </div>
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4"></div>
        <form id="chat-form" class="p-3 border-t bg-gray-50">
            <div class="flex gap-2">
                <input type="text" id="chat-input" placeholder="Nhắn tin cho Canvas nè..." class="flex-1 px-4 py-3 rounded-full border focus:outline-none focus:border-pink-500 text-sm">
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:shadow-xl transition">Send</button>
            </div>
        </form>
    </div>
</div>

<style>
#chat-messages > div { animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from {opacity:0; transform:translateY(10px)} to {opacity:1; transform:none} }
.user-msg { background: linear-gradient(to right,#f472b6,#c084fc); color:white; border-radius:18px 18px 5px 18px; padding:11px 16px; max-width:78%; align-self:flex-end; box-shadow:0 5px 20px rgba(244,114,182,.4); }
.bot-msg  { background:white; color:#333; border-radius:18px 18px 18px 5px; padding:11px 16px; max-width:78%; border:1.5px solid #f0abfc; box-shadow:0 5px 20px rgba(240,171,252,.35); }
.typing-indicator { display:flex; gap:6px; padding:12px 16px; background:white; border-radius:18px 18px 18px 5px; max-width:100px; border:1.5px solid #f0abfc; box-shadow:0 5px 20px rgba(240,171,252,.35); }
.typing-indicator span { width:9px; height:9px; background:#ccc; border-radius:50%; animation:typing 1.4s infinite; }
.typing-indicator span:nth-child(2) { animation-delay:0.2s; }
.typing-indicator span:nth-child(3) { animation-delay:0.4s; }
@keyframes typing { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
</style>

<script>
document.getElementById('chat-toggle').onclick = () => document.getElementById('chat-box').classList.toggle('hidden');
document.getElementById('chat-close').onclick = () => document.getElementById('chat-box').classList.add('hidden');

function add(msg, user = false) {
    const d = document.createElement('div');
    d.className = user ? 'user-msg' : 'bot-msg';
    d.innerHTML = msg.replace(/\n/g,'<br>');
    document.getElementById('chat-messages').appendChild(d);
    d.scrollIntoView({behavior:'smooth', block:'nearest'});
    return d;
}

function showTyping() {
    const old = document.getElementById('typing');
    if (old) old.remove();
    const t = document.createElement('div');
    t.id = 'typing';
    t.className = 'typing-indicator';
    t.innerHTML = '<span></span><span></span><span></span>';
    document.getElementById('chat-messages').appendChild(t);
    t.scrollIntoView({behavior:'smooth', block:'nearest'});
}

function hideTyping() {
    const t = document.getElementById('typing');
    if (t) t.remove();
}

document.getElementById('chat-form').onsubmit = function(e) {
    e.preventDefault();
    const msg = document.getElementById('chat-input').value.trim();
    if (!msg) return;
    add(msg, true);
    document.getElementById('chat-input').value = '';
    showTyping();

    fetch('chat-widget.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'message='+encodeURIComponent(msg)})
    .then(r => r.text())
    .then(text => {
        hideTyping();
        add(text.trim() || 'Mình không nghe rõ, bạn nói lại giúp mình nha!');
    })
    .catch(() => { hideTyping(); add('Mạng hơi yếu, bạn gửi lại nha!'); });
};

setTimeout(() => {
    if (!document.querySelector('#chat-messages > div:not(#typing)')) {
        add('Chào bạn nè! Canvas Bánh Ngọt đây ạ. Hôm nay muốn đặt bánh gì mình tư vấn liền luôn nha!');
    }
}, 1200);
</script>