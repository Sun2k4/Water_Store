<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Water Store') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/color-system.css') }}" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .footer-sticky {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <i class="fas fa-tint text-highlight"></i> 
                <span class="text-headline">{{ config('app.name', 'Water Store') }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i> Giỏ hàng
                                <span class="cart-count" id="cart-count">{{ app('App\Http\Controllers\CartController')->getCartCount() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('wishlist.index') }}">
                                <i class="fas fa-heart"></i> Yêu thích
                                <span class="wishlist-count" id="wishlist-count">{{ app('App\Http\Controllers\WishlistController')->getWishlistCount() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">
                                <i class="fas fa-receipt"></i> Lịch sử đơn hàng
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-secondary text-light py-4 footer-sticky">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 {{ config('app.name', 'Water Store') }}. All rights reserved.</p>
            <p class="mb-0 mt-2">
                <i class="fas fa-tint text-highlight"></i> 
                <span class="text-tertiary">Nước sạch cho cuộc sống khỏe mạnh</span>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    @yield('scripts')

    <!-- Chatbot JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatbotToggle = document.getElementById('chatbot-toggle');
            const chatbotWindow = document.getElementById('chatbot-window');
            const chatbotClose = document.getElementById('chatbot-close');
            const chatbotInput = document.getElementById('chatbot-input');
            const chatbotSend = document.getElementById('chatbot-send');
            const chatbotMessages = document.getElementById('chatbot-messages');
            const faqButtons = document.querySelectorAll('.faq-btn');

            // Toggle chatbot window
            chatbotToggle.addEventListener('click', function() {
                chatbotWindow.style.display = chatbotWindow.style.display === 'none' ? 'flex' : 'none';
            });

            // Close chatbot window
            chatbotClose.addEventListener('click', function() {
                chatbotWindow.style.display = 'none';
            });

            // Send message on Enter key
            chatbotInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Send message on button click
            chatbotSend.addEventListener('click', sendMessage);

            // FAQ button clicks
            faqButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const question = this.getAttribute('data-question');
                    chatbotInput.value = question;
                    sendMessage();
                });
            });

            function sendMessage() {
                const message = chatbotInput.value.trim();
                if (!message) return;

                // Add user message to chat
                addMessage(message, 'user');
                chatbotInput.value = '';

                // Show typing indicator
                showTypingIndicator();

                // Send to API
                fetch('{{ route("chatbot.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideTypingIndicator();
                    
                    if (data.success) {
                        addMessage(data.message, 'bot');
                    } else {
                        addMessage('Xin lỗi, tôi đang gặp sự cố. Vui lòng thử lại sau.', 'bot');
                    }
                })
                .catch(error => {
                    hideTypingIndicator();
                    console.error('Chatbot Error:', error);
                    addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
                });
            }

            function addMessage(content, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${type}-message`;
                
                const contentDiv = document.createElement('div');
                contentDiv.className = 'message-content';
                contentDiv.textContent = content;
                
                messageDiv.appendChild(contentDiv);
                chatbotMessages.appendChild(messageDiv);
                
                // Scroll to bottom
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }

            function showTypingIndicator() {
                const typingDiv = document.createElement('div');
                typingDiv.className = 'message bot-message';
                typingDiv.id = 'typing-indicator';
                
                const typingContent = document.createElement('div');
                typingContent.className = 'typing-indicator';
                typingContent.innerHTML = '<span></span><span></span><span></span>';
                
                typingDiv.appendChild(typingContent);
                chatbotMessages.appendChild(typingDiv);
                
                // Scroll to bottom
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }

            function hideTypingIndicator() {
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
            }
        });
    </script>

    <!-- Chatbot Widget -->
    <div id="chatbot-widget" class="chatbot-widget">
        <!-- Chatbot Toggle Button -->
        <div id="chatbot-toggle" class="chatbot-toggle">
            <i class="fas fa-comments"></i>
        </div>

        <!-- Chatbot Window -->
        <div id="chatbot-window" class="chatbot-window" style="display: none;">
            <div class="chatbot-header">
                <h6 class="mb-0">
                    <i class="fas fa-robot me-2"></i>
                    Trợ lý AI - Tanh Water Store
                </h6>
                <button id="chatbot-close" class="btn-close btn-close-white" aria-label="Close"></button>
            </div>
            
            <div class="chatbot-body">
                <div id="chatbot-messages" class="chatbot-messages">
                    <div class="message bot-message">
                        <div class="message-content">
                            Xin chào! Tôi là trợ lý AI của Tanh Water Store. Tôi có thể giúp bạn tìm hiểu về sản phẩm, đặt hàng và các dịch vụ khác. Bạn cần hỗ trợ gì?
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Quick Actions -->
                <div id="chatbot-faq" class="chatbot-faq">
                    <small class="text-muted">Câu hỏi thường gặp:</small>
                    <div class="faq-buttons mt-2">
                        <button class="btn btn-outline-primary btn-sm faq-btn" data-question="Làm thế nào để đặt hàng?">
                            Cách đặt hàng
                        </button>
                        <button class="btn btn-outline-primary btn-sm faq-btn" data-question="Các phương thức thanh toán nào được hỗ trợ?">
                            Thanh toán
                        </button>
                        <button class="btn btn-outline-primary btn-sm faq-btn" data-question="Thời gian giao hàng là bao lâu?">
                            Giao hàng
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="chatbot-footer">
                <div class="input-group">
                    <input type="text" id="chatbot-input" class="form-control" placeholder="Nhập tin nhắn..." maxlength="1000">
                    <button id="chatbot-send" class="btn btn-primary" type="button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot Styles -->
    <style>
        .chatbot-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }

        .chatbot-toggle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }

        .chatbot-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .chatbot-toggle i {
            color: white;
            font-size: 24px;
        }

        .chatbot-window {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            max-height: 300px;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
        }

        .bot-message .message-content {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 15px 15px 15px 5px;
            padding: 10px 15px;
            max-width: 80%;
        }

        .user-message {
            justify-content: flex-end;
        }

        .user-message .message-content {
            background: #007bff;
            color: white;
            border-radius: 15px 15px 5px 15px;
            padding: 10px 15px;
            max-width: 80%;
        }

        .chatbot-faq {
            padding: 10px 15px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .faq-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .faq-btn {
            font-size: 11px;
            padding: 4px 8px;
        }

        .chatbot-footer {
            padding: 15px;
            border-top: 1px solid #e9ecef;
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 15px 15px 15px 5px;
            max-width: 80px;
        }

        .typing-indicator span {
            height: 8px;
            width: 8px;
            background: #007bff;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .chatbot-window {
                width: 300px;
                height: 450px;
            }
        }
    </style>
</body>
</html>