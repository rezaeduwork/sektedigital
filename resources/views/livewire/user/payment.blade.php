<div class="container mx-auto p-4">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detail Pembayaran</h1>
                <a href="{{url('user/wallet')}}" class="text-primary hover:underline" wire:navigate>Kembali ke Wallet</a>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Payment Status -->
            <div class="mb-6">
                @if($paymentStatus === 'settlement' || $paymentStatus === 'paid')
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                        <p class="font-bold">Pembayaran Berhasil!</p>
                        <p>Saldo telah ditambahkan ke akun Anda.</p>
                    </div>
                @elseif(in_array($paymentStatus, ['expired', 'failed', 'cancelled']))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                        <p class="font-bold">Pembayaran {{ ucfirst($paymentStatus) }}!</p>
                        <p>Silahkan coba lagi dengan metode pembayaran yang berbeda.</p>
                    </div>
                @else
                    <div x-data="{ pollingEnabled: @js($polling) }" x-init="
                        if (pollingEnabled) {
                            setInterval(() => {
                                if (pollingEnabled) {
                                    @this.checkPaymentStatus();
                                }
                            }, 15000);
                        }
                    ">
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                            <p class="font-bold">Menunggu Pembayaran</p>
                            <p>Silahkan selesaikan pembayaran Anda sebelum batas waktu berakhir.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Payment Information -->
            <div class="border rounded-lg overflow-hidden mb-6">
                <div class="p-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold text-gray-700">Informasi Pembayaran</h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID Pembayaran</p>
                            <p class="font-medium">{{ $payment->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium">
                                @if($paymentStatus === 'settlement' || $paymentStatus === 'paid')
                                    <span class="text-green-600">Berhasil</span>
                                @elseif($paymentStatus === 'pending')
                                    <span class="text-yellow-600">Menunggu Pembayaran</span>
                                @else
                                    <span class="text-red-600">{{ ucfirst($paymentStatus) }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Deposit</p>
                            <p class="font-medium">Rp {{ number_format($paymentAmount) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Metode Pembayaran</p>
                            <p class="font-medium">{{ $paymentMethod ?? '-' }}</p>
                        </div>
                        @if($paymentGateway)
                            <div>
                                <p class="text-sm text-gray-600">Gateway</p>
                                <p class="font-medium">{{ ucfirst($paymentGateway) }}</p>
                            </div>
                        @endif
                        @if($paymentExpiry && $paymentStatus === 'pending')
                            <div>
                                <p class="text-sm text-gray-600">Batas Waktu Pembayaran</p>
                                <p class="font-medium" x-data="countdown({{ $paymentExpiry }})" x-init="startCountdown()" x-text="formattedTimeLeft"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Waktu Akhir</p>
                                <p class="font-medium">{{ date('d M Y H:i', $paymentExpiry) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Action -->
            @if($paymentStatus === 'pending')
                @php
                    // Fetch live payment detail from gateway
                    $paymentDetail = $paymentData;
                @endphp

                <!-- QR Code Section -->
                @if($paymentDetail && isset($paymentDetail['transaction_data']))
                    @php
                        $data = $paymentDetail['transaction_data'];
                        $qrUrl = null;
                        $payCode = null;
                        $payUrl = null;

                        // Extract QR, payment code, and pay URL based on gateway
                        switch($paymentGateway) {
                            case 'tripay':
                                if (in_array($paymentMethod, ['QRIS', 'QRIS2'])) {
                                    $qrUrl = $data['data']['qr_url'] ?? null;
                                }
                                $payCode = $data['data']['pay_code'] ?? null;
                                $payUrl = $data['data']['checkout_url'] ?? null;
                                break;
                            case 'xendit':
                                $qrUrl = $data['qr_string'] ?? null;
                                $payCode = $data['account_number'] ?? $data['payment_code'] ?? null;
                                $payUrl = $data['invoice_url'] ?? $data['ewallet_url'] ?? null;
                                break;
                            case 'paymenku':
                                $qrUrl = $data['data']['qr_url'] ?? null;
                                $payCode = $data['data']['payment_code'] ?? $data['data']['account_number'] ?? null;
                                $payUrl = $data['data']['pay_url'] ?? null;
                                break;
                            case 'sakurupiah':
                                // SakuRupiah response: qr (QRIS string), payment_no (VA number), checkout_url
                                $qrUrl = $data['qr'] ?? null;
                                $qrUrl = 'https://sakurupiah.id/assets/img/sakurupiah-sanbox-sampleQR.png';
                                $payCode = $data['payment_no'] ?? null;
                                $payUrl = $data['checkout_url'] ?? null;
                                break;
                        }
                    @endphp

                    @if($qrUrl)
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-medium mb-2">Scan QR Code</h3>
                            <div class="mx-auto max-w-xs">
                                <img src="{{ $qrUrl }}" alt="QR Code" class="mx-auto w-full">
                            </div>
                        </div>
                    @endif

                    @if($payCode)
                        <div class="mb-6">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <h3 class="text-lg font-medium mb-2">Kode Pembayaran</h3>
                                <div class="bg-white border rounded-md p-3 text-xl font-bold">
                                    {{ $payCode }}
                                </div>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $payCode }}'); alert('Kode pembayaran berhasil disalin!');"
                                    class="mt-2 text-primary text-sm hover:underline"
                                >
                                    📋 Salin Kode
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($payUrl && !$qrUrl && !$payCode)
                        <div class="text-center mb-6">
                            <a href="{{ $payUrl }}" target="_blank" class="inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-primary/90 transition">
                                Bayar Sekarang
                            </a>
                            <p class="text-sm text-gray-500 mt-2">Anda akan diarahkan ke halaman pembayaran</p>
                        </div>
                    @endif
                @endif
                <div class="mb-6">
                    <div class="text-center">
                        <button wire:click="checkPaymentStatus" type="button" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition">
                            <span wire:loading.remove wire:target="checkPaymentStatus">Cek Status Pembayaran</span>
                            <span wire:loading wire:target="checkPaymentStatus" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Payment Instructions -->
                @if(!empty($paymentInstructions))
                    <div class="border rounded-lg overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b">
                            <h2 class="text-lg font-semibold text-gray-700">Cara Pembayaran</h2>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                @foreach($paymentInstructions as $instruction)
                                    @if(isset($instruction['title']) && isset($instruction['steps']))
                                        <div class="mb-4">
                                            <h3 class="font-medium mb-2">{{ $instruction['title'] }}</h3>
                                            <ol class="list-decimal list-inside space-y-1 text-sm pl-2">
                                                @foreach($instruction['steps'] as $step)
                                                    <li class="ml-2">{!! $step !!}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @elseif($paymentStatus === 'settlement' || $paymentStatus === 'paid')
                <div class="flex justify-center">
                    <a href="{{url('user/wallet')}}" wire:navigate class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition">
                        Kembali ke Wallet
                    </a>
                </div>
            @else
                <div class="flex justify-center">
                    <a href="{{url('user/wallet')}}" wire:navigate class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition mr-4">
                        Kembali ke Wallet
                    </a>
                    <button
                        wire:click="$dispatch('openDeposit')"
                        class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition"
                        onclick="window.depositOpen = true"
                    >
                        Deposit Baru
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Countdown JS -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (expiry) => ({
                expiry: expiry * 1000, // Convert to milliseconds
                timeLeft: '',
                formattedTimeLeft: '',

                startCountdown() {
                    this.calculateTimeLeft();
                    setInterval(() => this.calculateTimeLeft(), 1000);
                },

                calculateTimeLeft() {
                    const now = new Date().getTime();
                    const distance = this.expiry - now;

                    if (distance <= 0) {
                        this.timeLeft = 'Waktu Habis';
                        this.formattedTimeLeft = 'Waktu Habis';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let formatted = '';
                    if (days > 0) formatted += `${days}h `;
                    formatted += `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                    this.timeLeft = formatted;
                    this.formattedTimeLeft = formatted;
                }
            }));
        });
    </script>
</div>
