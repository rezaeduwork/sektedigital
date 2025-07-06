<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Deposit Terakhir</h3>

    <div class="overflow-hidden">
        @if(count($recentPayments) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($recentPayments as $payment)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                @if($payment->status === 'settlement')
                                    <span class="inline-flex items-center bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mr-2">
                                        <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Berhasil
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mr-2">
                                        <svg class="mr-1 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full mr-2">
                                        <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                @endif
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount) }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ url('payment/'.$payment->token.'/detail') }}" class="text-sm text-primary hover:underline">
                            Detail
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-gray-500 text-center py-4">Belum ada riwayat deposit.</p>
        @endif
    </div>
</div>
