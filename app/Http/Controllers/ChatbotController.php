<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_AI_API_KEY');
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    }

    /**
     * Xử lý tin nhắn từ chatbot
     */
    public function chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            $userMessage = $request->input('message');
            
            // Lấy lịch sử hội thoại từ session (giữ 5 tin nhắn gần nhất)
            $chatHistory = Session::get('chat_history', []);
            
            // Tạo context chi tiết cho chatbot về website bán nước
            $systemPrompt = "Bạn là trợ lý AI chuyên nghiệp cho website bán nước uống Tanh Water Store - cửa hàng nước uống hàng đầu Việt Nam.

THÔNG TIN SẢN PHẨM CHI TIẾT:

🥤 NƯỚC NGỌT CÓ GA:
- Coca Cola 330ml (12.000đ): Nước ngọt truyền thống, có caffeine
- Pepsi 330ml (12.000đ): Hương vị cola độc đáo
- Sprite 330ml (11.000đ): Chanh tươi mát
- 7Up 330ml (11.000đ): Chanh không caffeine
- Fanta Cam 330ml (10.000đ): Hương cam tự nhiên

🧊 NƯỚC KHOÁNG & TINH KHIẾT:
- Aquafina 500ml (8.000đ): Nước tinh khiết cao cấp
- Lavie 500ml (7.000đ): Nước khoáng tự nhiên VN
- Vinh Hao 500ml (6.000đ): Nước khoáng thiên nhiên
- Evian 500ml (25.000đ): Nước khoáng Alps cao cấp từ Pháp
- Perrier 330ml (30.000đ): Nước khoáng có ga tự nhiên từ Pháp

🍊 NƯỚC TRÁI CÂY:
- Tropicana Cam 1L (35.000đ): 100% nước cam ép tươi
- Minute Maid Cam 330ml (15.000đ): Nước cam có thịt
- TH True Juice Cam 1L (45.000đ): Nước cam ép tươi không đường
- Real Grape 500ml (25.000đ): Nước nho đỏ nguyên chất từ Hàn Quốc

🏃 NƯỚC THỂ THAO:
- Pocari Sweat 500ml (20.000đ): Bù ion điện giải từ Nhật
- Gatorade Cam 500ml (22.000đ): Nước thể thao hương cam từ Mỹ
- Powerade Xanh 500ml (20.000đ): Nước thể thao Coca Cola
- H2O Sport 500ml (15.000đ): Nước thể thao Việt Nam

☕ TRÀ & CÀ PHÊ:
- Trà Xanh C2 455ml (12.000đ): Trà xanh không đường
- Lipton Trà Đào 330ml (13.000đ): Trà đào ngọt mát
- Nescafe Cà Phê Sữa 180ml (15.000đ): Cà phê sữa đậm đà

DỊCH VỤ & CHÍNH SÁCH:
✅ Giao hàng: 1-3 ngày làm việc
✅ Thanh toán: Thẻ tín dụng, chuyển khoản, COD, MoMo
✅ Miễn phí ship đơn từ 200.000đ
✅ Đổi trả trong 7 ngày nếu lỗi sản phẩm
✅ Tư vấn dinh dưỡng miễn phí
✅ Chương trình khuyến mãi thường xuyên

Hãy tư vấn chi tiết, nhiệt tình và chuyên nghiệp. Luôn đề xuất sản phẩm phù hợp với nhu cầu khách hàng. Trả lời bằng tiếng Việt.";
            
            // Xây dựng ngữ cảnh với lịch sử hội thoại
            $contextMessage = $systemPrompt;
            
            if (!empty($chatHistory)) {
                $contextMessage .= "\n\nLịch sử hội thoại gần đây:";
                foreach ($chatHistory as $item) {
                    $contextMessage .= "\nKhách: " . $item['user'] . "\nBot: " . $item['bot'];
                }
            }
            
            $fullMessage = $contextMessage . "\n\nKhách hàng hỏi: " . $userMessage;

            // Gọi Google AI API với timeout và retry
            $response = Http::timeout(30)
                ->retry(2, 1000)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $fullMessage
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1000,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $botReply = $data['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Lưu vào lịch sử hội thoại (giữ tối đa 5 cặp hội thoại)
                    $chatHistory[] = [
                        'user' => $userMessage,
                        'bot' => $botReply,
                        'timestamp' => now()
                    ];
                    
                    // Giữ chỉ 5 hội thoại gần nhất
                    if (count($chatHistory) > 5) {
                        $chatHistory = array_slice($chatHistory, -5);
                    }
                    
                    Session::put('chat_history', $chatHistory);
                    
                    return response()->json([
                        'success' => true,
                        'message' => $botReply
                    ]);
                } else {
                    throw new \Exception('Không nhận được phản hồi từ AI');
                }
            } else {
                throw new \Exception('Lỗi kết nối API: ' . $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, tôi đang gặp sự cố. Vui lòng thử lại sau hoặc liên hệ hỗ trợ.'
            ], 500);
        }
    }

    /**
     * Lấy câu hỏi thường gặp
     */
    public function getFAQ()
    {
        $faqs = [
            [
                'question' => 'Cách đặt hàng',
                'answer' => 'Bạn có thể duyệt sản phẩm, thêm vào giỏ hàng và thanh toán trực tuyến. Rất đơn giản!'
            ],
            [
                'question' => 'Thanh toán',
                'answer' => 'Chúng tôi hỗ trợ thanh toán qua thẻ tín dụng, chuyển khoản, MoMo và COD.'
            ],
            [
                'question' => 'Giao hàng',
                'answer' => 'Thời gian giao hàng từ 1-3 ngày làm việc. Miễn phí ship đơn từ 200.000đ!'
            ],
            [
                'question' => 'Giờ làm việc',
                'answer' => 'Chúng tôi phục vụ 24/7 online. Hotline: 1900-xxxx (8h-22h hàng ngày).'
            ],
            [
                'question' => 'Sản phẩm bán chạy',
                'answer' => 'Top sản phẩm: Aquafina, Coca Cola, Pocari Sweat, Tropicana Cam, Lavie.'
            ],
            [
                'question' => 'Khuyến mãi',
                'answer' => 'Giảm 10% đơn đầu tiên, mua 2 tặng 1 cuối tuần, tích điểm đổi quà!'
            ],
            [
                'question' => 'Tư vấn sản phẩm',
                'answer' => 'Cần tư vấn chọn nước phù hợp? Hãy chat với tôi hoặc gọi hotline nhé!'
            ],
            [
                'question' => 'Đổi trả',
                'answer' => 'Đổi trả miễn phí trong 7 ngày nếu sản phẩm lỗi hoặc không đúng mô tả.'
            ]
        ];

        return response()->json([
            'success' => true,
            'faqs' => $faqs
        ]);
    }
}