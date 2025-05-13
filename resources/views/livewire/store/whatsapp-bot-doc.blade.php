<section class="container">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-lg sm:text-2xl font-bold">WhatsApp Bot Documentation</h1>
  </div>

  <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
    <h2 class="text-xl font-semibold mb-4">Introduction</h2>
    <p class="mb-3">
      Our WhatsApp Bot allows your store to interact with customers through WhatsApp automatically.
      The bot can help customers browse products, manage their carts, and process orders, providing a seamless shopping experience.
    </p>
    <p>
      This guide will help you understand how to use and configure your WhatsApp Bot for optimal performance.
    </p>
  </div>

  <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
    <h2 class="text-xl font-semibold mb-4">Getting Started</h2>
    <div class="mb-4">
      <h3 class="font-semibold text-lg mb-2">Prerequisites</h3>
      <ul class="list-disc list-inside space-y-2">
        <li>An active WhatsApp connection for your store</li>
        <li>Products added to your store inventory</li>
      </ul>
    </div>

    <div>
      <h3 class="font-semibold text-lg mb-2">Setting Up</h3>
      <ol class="list-decimal list-inside space-y-2">
        <li>Connect your WhatsApp account via the WhatsApp Integration page</li>
        <li>Configure your bot commands in the Bot Commands section</li>
        <li>Customize your welcome message and product listings</li>
      </ol>
    </div>
  </div>

  <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
    <h2 class="text-xl font-semibold mb-4">Available Features</h2>

    <div class="mb-4">
      <h3 class="font-semibold text-lg mb-2">1. Welcome Message</h3>
      <p class="mb-2">When a customer first interacts with your store, they'll receive a welcome message explaining what your bot can do.</p>
      <div class="bg-gray-50 p-3 rounded border text-sm mb-2">
        <p>👋 <strong>Welcome to [Your Store]!</strong> 👋</p>
        <p>I'm your shopping assistant bot. Here's what I can help you with:</p>
        <p>🔍 <strong>Browse Products</strong>: Just send me any search term to see matching products</p>
        <p>📋 <strong>/products</strong>: View all available products</p>
        <p>ℹ️ <strong>/help</strong>: See all available commands</p>
        <p>Let me know what you're looking for today!</p>
      </div>
    </div>

    <div class="mb-4">
      <h3 class="font-semibold text-lg mb-2">2. Product Listing</h3>
      <p class="mb-2">Customers can browse products in your inventory in several ways:</p>
      <ul class="list-disc list-inside space-y-2">
        <li><strong>Direct search</strong>: By typing a product name or description</li>
        <li><strong>Command</strong>: By using the /products command</li>
        <li><strong>Categories</strong>: By browsing through product categories</li>
      </ul>
      <div class="bg-gray-50 p-3 rounded border text-sm">
        <p>🛍️ <strong>Products (Page 1)</strong> 🛍️</p>
        <p>1. Product Name</p>
        <p>💰 Price: Rp 100,000</p>
        <p>✨ Product highlight text</p>
        <p>Type: <strong>/view 1</strong> for details</p>
        <p>...</p>
        <p>To see more products, type: <strong>/products 2</strong></p>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
    <h2 class="text-xl font-semibold mb-4">Commands Reference</h2>

    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
          <tr>
            <th scope="col" class="px-6 py-3">Command</th>
            <th scope="col" class="px-6 py-3">Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/help</td>
            <td class="px-6 py-4">Shows a list of all available commands</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/products [page]</td>
            <td class="px-6 py-4">Lists all products, optionally with page number</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/view [number]</td>
            <td class="px-6 py-4">View details of a specific product by its number</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/cart</td>
            <td class="px-6 py-4">View your shopping cart contents</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/add [number] [quantity]</td>
            <td class="px-6 py-4">Add a product to your cart with optional quantity</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/remove [number]</td>
            <td class="px-6 py-4">Remove a product from your cart</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/checkout</td>
            <td class="px-6 py-4">Start the checkout process</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/cancel</td>
            <td class="px-6 py-4">Cancel the current action</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/about</td>
            <td class="px-6 py-4">Information about the store</td>
          </tr>
          <tr class="border-b">
            <td class="px-6 py-4 font-medium">/contact</td>
            <td class="px-6 py-4">Get store contact information</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
    <h2 class="text-xl font-semibold mb-4">Best Practices</h2>

    <div class="space-y-3">
      <div>
        <h3 class="font-semibold mb-1">1. Keep Commands Simple</h3>
        <p>Use short, intuitive commands that customers can easily remember.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">2. Provide Clear Instructions</h3>
        <p>Always include clear instructions in your responses so customers know what to do next.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">3. Regularly Update Product Information</h3>
        <p>Keep your product catalog up-to-date to ensure the bot provides accurate information.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">4. Customize Your Welcome Message</h3>
        <p>Make a good first impression with a personalized welcome message that reflects your brand.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">5. Monitor Bot Performance</h3>
        <p>Regularly check the bot's performance and customer interactions to identify areas for improvement.</p>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-lg p-6 shadow-sm">
    <h2 class="text-xl font-semibold mb-4">Troubleshooting</h2>

    <div class="space-y-4">
      <div>
        <h3 class="font-semibold mb-1">Bot not responding</h3>
        <p>Check if your WhatsApp connection is active. Try disconnecting and reconnecting your WhatsApp.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">Products not showing up</h3>
        <p>Ensure you have active products in your store inventory and they are set to "active" status.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">Custom commands not working</h3>
        <p>Check that your command syntax is correct and the command is marked as "active" in the Bot Commands section.</p>
      </div>

      <div>
        <h3 class="font-semibold mb-1">Still need help?</h3>
        <p>Contact our support team for assistance with your WhatsApp Bot setup.</p>
      </div>
    </div>
  </div>
</section>
