<section class="container">
  {{-- <h1 class="text-lg sm:text-2xl font-bold mb-5">Whatsapp</h1> --}}

  @if (session()->has('error'))
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
      {{ session('error') }}
    </div>
  @endif

  @if($connectionError)
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg flex justify-between items-center">
      <div>      // Listen for status updates to properly manage intervals
      Livewire.on('statusUpdated', (status) => {
        console.log('WhatsApp status updated to:', status);

        if (status !== 'connecting') {
          // If we're not in connecting state, stop all timers
          console.log('Status is not connecting, clearing all timers');
          resetAllTimers();
        } else if (status === 'connecting') {
          // If we're connecting, make sure we have a status check running
          if (!statusCheckIntervalId) {
            console.log('Status is connecting but no interval is active, starting status checks');
            setTimeout(checkWhatsappStatus, 2000);
          } else {
            console.log('Status is connecting and interval is already active');
          }
        }
      });

      // Also listen for QR code generation events to clear connection timeout
      window.addEventListener('qrCodeGenerated', function() {
        console.log('QR code generated event received in window listener');
        if (connectionTimeoutId) {
          console.log('Clearing connection timeout due to QR code generation');
          clearTimeout(connectionTimeoutId);
          connectionTimeoutId = null;
        }
      });ng class="font-medium">Connection Error:</strong> {{ $connectionError }}
      </div>
      <button type="button" wire:click="clearConnectionError" class="text-red-700 hover:text-red-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  @endif

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-lg sm:text-2xl font-bold">WhatsApp Integration</h1>

    @if($status == 'connected')
      <div class="flex space-x-2">
        <a href="{{ url('store/whatsapp/bot/commands') }}" class="inline-block text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:ring-violet-300 font-medium rounded-lg text-sm px-5 py-2.5">
          Bot Commands
        </a>
        <a href="{{ url('store/whatsapp/bot/documentation') }}" class="inline-block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
          Bot Documentation
        </a>
      </div>
    @endif
  </div>

  <div class="p-6 mb-6 space-y-4 bg-white rounded border">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-xl font-semibold mb-2">WhatsApp Status</h2>
        @if($status == 'connected')
          <div class="flex items-center">
            <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
            <p class="text-green-600 font-medium">Connected</p>
            @if($phoneNumber)
              <span class="ml-3 text-gray-600">{{ $phoneNumber }}</span>
            @endif
          </div>
        @elseif($status == 'connecting' && !$qrCode)
          <div class="flex items-center">
            <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
            <p class="text-yellow-600 font-medium">Connecting...</p>
          </div>
        @else
          <div class="flex items-center">
            <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
            <p class="text-red-600 font-medium">Disconnected</p>
          </div>
        @endif
      </div>

      @if ($status !== 'connected' && !$qrCode)
      <div>
        @if($status !== 'connected')
          <button wire:click="connectWhatsapp" type="button" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:ring-violet-300 font-semibold rounded-lg text-sm px-5 py-2.5 me-2" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="connectWhatsapp">Connect WhatsApp</span>
            <span wire:loading wire:target="connectWhatsapp">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Connecting...
            </span>
          </button>
        @else
          <button wire:click="disconnectWhatsapp" type="button" class="text-red-700 bg-red-100 hover:bg-red-200 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="disconnectWhatsapp">Disconnect</span>
            <span wire:loading wire:target="disconnectWhatsapp">Disconnecting...</span>
          </button>
        @endif
      </div>
      @endif
    </div>

    @if($status == 'connecting' && $qrCode)
      <div class="mt-6">
        <div class="mb-3 flex justify-between items-center">
          <h3 class="font-medium text-gray-700">Scan this QR code with your WhatsApp</h3>

          <button wire:click="refreshQrCode" type="button" class="text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-xs px-3 py-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh QR
          </button>
        </div>

        <div class="flex justify-center p-4 bg-gray-100 rounded-lg" wire:ignore>
          <div class="inline-block p-4 bg-white rounded">
            <div id="qrcode" class="mx-auto qrcode-container"></div>
          </div>
        </div>

        <style>
          .qrcode-container {
            min-width: 256px;
            min-height: 256px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            padding: 8px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
          }
          .qrcode-container canvas {
            display: block;
            max-width: 100%;
            height: auto !important;
          }
          #qr-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
          }
          #qr-wrapper canvas {
            border: 4px solid white;
          }
        </style>

        <div class="mt-3 text-sm text-gray-600 text-center">
          <p>Open WhatsApp on your phone</p>
          <p>Tap Menu or Settings > Linked Devices > Link a Device</p>
          <p>Point your phone to this screen to capture the QR code</p>
        </div>
      </div>
    @endif

    @if($status == 'connected')
      <div class="mt-6 p-4 bg-green-50 border border-green-100 rounded-lg">
        <h3 class="text-green-800 font-medium mb-2">WhatsApp is connected successfully!</h3>
        <p class="text-green-700">Your store can now use WhatsApp for customer communications.</p>
      </div>
    @endif
  </div>

  <div class="p-6 mb-6 space-y-4 bg-white rounded border">
    {{-- Commands List --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Command</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Parameters</th>
                        <th scope="col" class="px-6 py-3 text-center">Order</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commands as $cmd)
                        <tr class="border-b">
                            <td class="px-6 py-4">
                                <span class="font-medium">/{{ $cmd->command }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $cmd->description }}
                            </td>
                            <td class="px-6 py-4">
                                @if (is_array($cmd->parameters) && count($cmd->parameters) > 0)
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                        {{ implode(', ', $cmd->parameters) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $cmd->display_order }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No commands found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3">
            {{ $commands->links() }}
        </div>
    </div>
  </div>

  <!-- JQuery and QR Code libraries from CDN -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
  <script>
    // Setup loading indicator
    window.addEventListener('load', function() {
      const qrcodeContainer = document.getElementById('qrcode');
      if (qrcodeContainer) {
        qrcodeContainer.innerHTML = '<div class="text-center text-gray-500">QR Code loading...</div>';
      }

      // Check if jQuery and QR code plugin are loaded
      if (typeof jQuery === 'undefined') {
        console.error('jQuery not loaded!');
        showLibraryError();
      } else {
        console.log('jQuery loaded successfully');

        // Check if jQuery QR code plugin is loaded
        if (!jQuery.fn.qrcode) {
          console.error('jQuery QR code plugin not loaded!');
          showLibraryError();
        } else {
          console.log('jQuery QR code plugin loaded successfully');

          // If QR code is already available, generate it
          if (@this.qrCode) {
            generateQrCode(@this.qrCode);
          }
        }
      }
    });

    function showLibraryError() {
      const qrcodeContainer = document.getElementById('qrcode');
      if (qrcodeContainer) {
        qrcodeContainer.innerHTML =
          '<div class="p-4 text-red-600 text-center">Failed to load QR code library. Please refresh the page.</div>';
      }
    }

    function generateQrCode(data) {
      if (!data) return;

      try {
        console.log('Generating QR code with jQuery.qrcode');
        const qrcodeContainer = document.getElementById('qrcode');

        if (qrcodeContainer) {
          // Clear previous QR code
          qrcodeContainer.innerHTML = '';

          // Create a new container for the QR code to avoid any issues
          const qrWrapper = document.createElement('div');
          qrWrapper.id = 'qr-wrapper';
          qrcodeContainer.appendChild(qrWrapper);

          // Try to use jQuery qrcode plugin
          try {
            $(qrWrapper).qrcode({
              text: data,
              width: 256,
              height: 256,
              background: "#ffffff",
              foreground: "#000000"
            });

            console.log('QR code generated successfully with jQuery.qrcode');

            // Reset connection timeout when QR is generated
            if (typeof connectionTimeoutId !== 'undefined' && connectionTimeoutId) {
              console.log('Clearing connection timeout on QR code generation');
              clearTimeout(connectionTimeoutId);
              connectionTimeoutId = null;
            }

            // Dispatch event to signal successful QR generation
            // window.dispatchEvent(new CustomEvent('qrCodeGenerated'));

            // Also notify the Livewire component
            // Livewire.find(@this.id).dispatch('qrCodeGenerated');
            // Also add a text version as ultimate fallback (hidden by default)
            // const textFallback = document.createElement('div');
            // textFallback.className = 'text-center mt-2 text-xs text-gray-500';
            // textFallback.style.display = 'none';
            // textFallback.innerHTML = 'If QR code is not visible: <button class="underline text-blue-600" onclick="alert(\'WhatsApp code: ' +
            //   data.replace(/'/g, "\\'") + '\')">Show Code</button>';
            // qrcodeContainer.appendChild(textFallback);

          } catch (innerError) {
            console.error('jQuery.qrcode failed:', innerError);

            // Create a simple version as fallback
            const fallbackDiv = document.createElement('div');
            fallbackDiv.className = 'text-center p-4';
            fallbackDiv.innerHTML = `
              <p class="mb-2 text-red-600">QR code display failed</p>
              <p class="mb-2">Please try refreshing the page or use the code below:</p>
              <textarea readonly class="w-full p-2 border border-gray-300 rounded text-sm" rows="2">${data}</textarea>
              <p class="mt-2 text-xs text-gray-600">Use this code with WhatsApp</p>
            `;
            qrWrapper.appendChild(fallbackDiv);
          }
        }
      } catch (error) {
        console.error('Error generating QR code:', error);
        const qrcodeContainer = document.getElementById('qrcode');
        if (qrcodeContainer) {
          qrcodeContainer.innerHTML =
            '<div class="p-4 text-red-600 text-center">Failed to generate QR code: ' + error.message + '</div>';
        }
      }
    }

    // Function to safely generate the QR code
    function safeGenerateQrCode(qrData) {
      if (!qrData) return;
      console.log('Safe generate QR code called with data');

      // Clear any existing timeout since we have a QR code
      if (typeof connectionTimeoutId !== 'undefined' && connectionTimeoutId) {
        console.log('Clearing connection timeout because we have a QR code');
        clearTimeout(connectionTimeoutId);
        connectionTimeoutId = null;
      }

      // Make sure we are in connecting state and listening for updates
      if (@this.status !== 'connecting') {
        // If we're not in connecting state, we should stop the interval
        if (typeof statusCheckIntervalId !== 'undefined' && statusCheckIntervalId) {
          console.log('Stopping status checks because status is not connecting');
          clearTimeout(statusCheckIntervalId);
          statusCheckIntervalId = null;
        }
      }

      if (typeof jQuery === 'undefined' || !jQuery.fn.qrcode) {
        console.error('jQuery or QR code plugin not available');

        // Try again after a short delay
        setTimeout(() => {
          if (typeof jQuery !== 'undefined' && jQuery.fn.qrcode) {
            generateQrCode(qrData);
          } else {
            showLibraryError();
          }
        }, 1000);
        return;
      }

      // Libraries are loaded, generate the QR code
      generateQrCode(qrData);
    }

    @if($qrCode)
    // We need to use setTimeout to ensure jQuery and the qrcode plugin are loaded
    setTimeout(() => {
      const qrData = @js($qrCode);
      safeGenerateQrCode(qrData);
    }, 800); // Increased timeout to ensure libraries are loaded
    @endif

    // Initialize variables at global scope to avoid issues with variable scope
    let statusCheckIntervalId = null;
    let connectionTimeoutId = null;

    document.addEventListener('livewire:init', () => {
      // Log that we're initializing Livewire with our interval variables
      console.log('Initializing WhatsApp connection manager, timer IDs reset');

      function checkWhatsappStatus() {
        console.log('Checking WhatsApp status - current status:', @this.status);
        @this.loadWhatsappData();

        // If we have a QR code, make sure it's displayed
        if (@this.qrCode) {
          console.log('QR code found in status check, ensuring it is displayed');
          safeGenerateQrCode(@this.qrCode);
        } else {
          console.log('No QR code available in status check');
        }

        // Clear previous interval if it exists
        if (statusCheckIntervalId) {
          clearTimeout(statusCheckIntervalId);
          statusCheckIntervalId = null;
        }

        // Check every 5 seconds if we're in connecting state
        if (@this.status === 'connecting') {
          console.log('Status is connecting, setting up next check in 5 seconds');
          // Using setInterval would be better for recurring checks, but we need to
          // manage state between checks, so we use setTimeout and reset it each time
          statusCheckIntervalId = setTimeout(checkWhatsappStatus, 5000);
        } else {
          // Status is not connecting, so we stop checking
          console.log('Status is ' + @this.status + ', stopping status checks');
          // No need to set a new interval
          statusCheckIntervalId = null;
        }
      }

      // Function to clear all timers and start fresh
      function resetAllTimers() {
        console.log('Resetting all timers');

        // Clear connection timeout if it exists
        if (connectionTimeoutId) {
          console.log('→ Clearing connection timeout ID:', connectionTimeoutId);
          clearTimeout(connectionTimeoutId);
          connectionTimeoutId = null;
        } else {
          console.log('→ No connection timeout to clear');
        }

        // Clear status check interval if it exists
        if (statusCheckIntervalId) {
          console.log('→ Clearing status check interval ID:', statusCheckIntervalId);
          clearTimeout(statusCheckIntervalId);
          statusCheckIntervalId = null;
        } else {
          console.log('→ No status check interval to clear');
        }

        console.log('All timers reset complete');
      }

      Livewire.on('initQrScanner', () => {
        // Reset any existing timers first
        resetAllTimers();

        // Start the status check after a short delay
        setTimeout(checkWhatsappStatus, 3000);

        // Set a timeout to check for failed connection
        connectionTimeoutId = setTimeout(() => {
          if (Livewire.find(@this.id).status === 'connecting' && !Livewire.find(@this.id).qrCode) {
            console.log('Connection timeout - no QR code received');
            Livewire.find(@this.id).dispatch('connectionTimeout');
            resetAllTimers(); // Also reset timers when timeout occurs
          }
        }, 30000); // 30 seconds timeout
      });

      Livewire.on('refreshQrCode', () => {
        console.log('QR code refresh requested');

        // Reset all timers when we refresh the QR code
        resetAllTimers();

        // Generate QR code when refreshed
        if (@this.qrCode) {
          const qrData = @js($qrCode);
          if (qrData) {
            console.log('Refreshing QR code with new data');
            safeGenerateQrCode(qrData);

            // Start a new status check for the refreshed QR code
            setTimeout(checkWhatsappStatus, 2000);
          }
        }
      });

      // Listen for status updates to properly manage intervals
      Livewire.on('statusUpdated', (status) => {
        console.log('WhatsApp status updated to:', status);

        if (status !== 'connecting') {
          // If we're not in connecting state anymore, stop all timers
          resetAllTimers();
        } else if (status === 'connecting') {
          // If we're connecting, make sure we have a status check running
          if (!statusCheckIntervalId) {
            setTimeout(checkWhatsappStatus, 2000);
          }
        }
      });
    });
  </script>
</section>
