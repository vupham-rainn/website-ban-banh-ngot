<script>
const API_KEY = "AIzaSyB2k7Gu_zkcXn9esOZMxp-iiEMRw-C-bd4";  // ← DÁN API KEY Ở ĐÂY

// Thông tin tiệm bánh – cho AI hiểu cửa hàng của bạn
const bakeryData = `
Tiệm Bánh Ngọt Ngào.
Địa chỉ: 123 Nguyễn Trãi, Q.5, TP.HCM.
Giờ mở cửa: 8h - 21h mỗi ngày.
Chuyên bánh kem, mousse, tiramisu.
Có giao hàng nội thành từ 20.000đ.
Hotline: 0912 345 678.
`;

/* --- Mở chatbox --- */
document.getElementById("chat-toggle").onclick = () => {
    document.getElementById("chatbox").classList.remove("hidden");
};

/* --- Đóng chatbox --- */
document.getElementById("close-chat").onclick = () => {
    document.getElementById("chatbox").classList.add("hidden");
};

/* --- Gửi tin nhắn --- */
async function sendMessage() {
    let input = document.getElementById("userInput");
    let msg = input.value.trim();
    if (!msg) return;

    addMessage("Bạn", msg);
    input.value = "";

    let reply = await callGemini(msg);
    addMessage("Tiệm bánh", reply);
}

/* --- In tin nhắn ra màn hình --- */
function addMessage(sender, text) {
    let box = document.getElementById("chat-messages");
    box.innerHTML += `<p><strong>${sender}:</strong> ${text}</p>`;
    box.scrollTop = box.scrollHeight;
}

/* --- Gọi API Gemini --- */
async function callGemini(userMessage) {
    const res = await fetch(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" + API_KEY,
        {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                contents: [
                    { role: "user", parts: [{ text: 
                        "Bạn là chatbot tư vấn tiệm bánh. Đây là dữ liệu cửa hàng:\n" 
                        + bakeryData + 
                        "\nĐây là câu hỏi của khách: " + userMessage 
                    }] }
                ]
            })
        }
    );

    const data = await res.json();
    return data.candidates?.[0]?.content?.parts?.[0]?.text || "Xin lỗi, tôi không hiểu câu hỏi!";
}
</script>
