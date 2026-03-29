<?php
session_start();
require_once 'openai.php';

// Tạo session_id duy nhất cho mỗi người chat
if (empty($_SESSION['session_id'])) {
    $_SESSION['session_id'] = bin2hex(random_bytes(16));
}
$sessionId = $_SESSION['session_id'];

// Kết nối database (sửa nếu bạn dùng tên khác)
$mysqli = new mysqli("localhost", "root", "", "doan_banbanh");
if ($mysqli->connect_error) die("Lỗi kết nối DB");

// Lấy lịch sử chat
$result = $mysqli->query("SELECT messages FROM chat_sessions WHERE session_id = '$sessionId'");
if ($result->num_rows == 0) {
    $messages = [['role' => 'system', 'content' => 'Bạn là trợ lý chuyên về cây trái, nông nghiệp, nói tiếng Việt thân thiện và hài hước.']];
    $mysqli->query("INSERT INTO chat_sessions (session_id, messages) VALUES ('$sessionId', '" . json_encode($messages, JSON_UNESCAPED_UNICODE) . "')");
} else {
    $row = $result->fetch_assoc();
    $messages = json_decode($row['messages'], true);
}

// Xử lý tin nhắn mới
if ($_POST['message'] ?? false) {
    $userMessage = trim($_POST['message']);
    if ($userMessage === '') die();

    $messages[] = ['role' => 'user', 'content' => $userMessage];

    // Gọi OpenAI (streaming từng chữ)
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');

    $client = getOpenAIClient();
    $stream = $client->chat()->createStreamed([
        'model' => 'gpt-4o-mini',  // rẻ và cực mạnh
        'messages' => $messages,
        'temperature' => 0.8,
    ]);

    $reply = '';
    foreach ($stream as $response) {
        $delta = $response->choices[0]->delta->content ?? '';
        if ($delta !== null) {
            echo "data: " . json_encode(['delta' => $delta], JSON_UNESCAPED_UNICODE) . "\n\n";
            $reply .= $delta;
            @ob_flush();
            flush();
        }
    }

    // Lưu lại lịch sử
    $messages[] = ['role' => 'assistant', 'content' => $reply];
    $json = $mysqli->real_escape_string(json_encode($messages, JSON_UNESCAPED_UNICODE));
    $mysqli->query("UPDATE chat_sessions SET messages = '$json', last_active = NOW() WHERE session_id = '$sessionId'");

    echo "data: [DONE]\n\n";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Trái Cây - DUAN_TRAICAY1</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">
<div class="max-w-4xl mx-auto p-5">
    <h1 class="text-4xl font-bold text-center my-8 text-green-700">ChatBot Trái Cây Thông Minh</h1>
    
    <div id="chatbox" class="bg-white rounded-xl shadow-2xl h-96 overflow-y-auto p-6 mb-4"></div>
    
    <form id="form" class="flex gap-3">
        <input type="text" id="msg" autocomplete="off" required placeholder="Hỏi mình về cây trái, phân bón, sâu bệnh..."
               class="flex-1 border-2 border-green-300 rounded-full px-6 py-4 text-lg focus:outline-none focus:border-green-600">
        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-10 py-4 rounded-full font-bold hover:from-green-600 hover:to-blue-700 transition">
            Gửi
        </button>
    </form>
</div>

<script>
let es;
document.getElementById('form').onsubmit = function(e) {
    e.preventDefault();
    let msg = document.getElementById('msg').value.trim();
    if (!msg) return;

    addMessage('Bạn', msg, 'bg-blue-600 text-white text-right');
    
    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message=' + encodeURIComponent(msg)
    });

    const aiDiv = addMessage('Bot', 'đang suy nghĩ...', 'bg-gray-200');
    if (es) es.close();
    es = new EventSource('');
    
    es.onmessage = function(e) {
        if (e.data === '[DONE]') {
            es.close();
            document.getElementById('chatbox').scrollTop = 99999;
            return;
        }
        const data = JSON.parse(e.data);
        aiDiv.innerHTML = aiDiv.innerHTML.replace('đang suy nghĩ...', '') + data.delta;
    };
    
    document.getElementById('msg').value = '';
};

function addMessage(sender, text, bg) {
    const div = document.createElement('div');
    div.className = `mb-4 ${sender === 'Bạn' ? 'text-right' : ''}`;
    div.innerHTML = `<div class="inline-block max-w-3xl px-6 py-4 rounded-3xl ${bg || 'bg-gray-200'} text-lg">
        <strong>${sender}:</strong> <span>${text}</span>
    </div>`;
    document.getElementById('chatbox').appendChild(div);
    document.getElementById('chatbox').scrollTop = 99999;
    return div.querySelector('span');
}
</script>
</body>
</html>