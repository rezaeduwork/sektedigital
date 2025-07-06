# WhatsApp Template Parsing System Documentation

This document provides an overview of the template parsing system implemented for the WhatsApp bot.

## Overview

The template parsing system allows for dynamic rendering of message templates with placeholders that can:

1. Call methods from parser classes (e.g., `{Product.all}`)
2. Use context-specific data from customer sessions (e.g., `{input.query}`)
3. Chain methods with parameters (e.g., `{Product.input.query}`)

## Usage

Templates can be defined in the database and used in bot responses. When a message is sent, the template parser processes all placeholders before sending the message.

### Basic Syntax

Templates use the following syntax patterns:

- `{ClassName.methodName}` - Calls a simple method with no parameters (e.g., `{Product.all}`)
- `{ClassName.methodName.parameter}` - Calls a method with a parameter (e.g., `{Product.find.123}`)
- `{ClassName.input.paramName}` - Calls the input method with the parameter name to access in session context (e.g., `{Product.input.query}`)
- `{input.parameterName}` - Directly accesses session context data (e.g., `{input.query}`)

### Command Parameters and Context

When a user runs a command like `search product_name`, the system:

1. Extracts the parameters based on the command definition
2. Stores these parameters in the session's context_data (e.g., `query` = "product_name")
3. The template parser can then use these values in different ways:
   - `{input.query}` directly displays the value ("product_name")
   - `{Product.search}` uses the context data to perform a search automatically
   - `{Product.input.query}` calls the input method on Product and passes "query" as the parameter name, telling it to get the value from `context_data['query']`
   - `{Product.input.name}` would similarly use `context_data['name']`

This approach allows templates to dynamically access different context parameters.

The following parser classes are available:

1. **Product** - For product-related information
2. **Cart** - For customer cart information
3. **Customer** - For customer data
4. **Order** - For order-related information
5. **Settings** - For store settings
6. **Formatter** - For text formatting utilities
7. **Template** - For rendering other templates

### Examples

Here are some example templates:

```
Hello {Customer.name}, welcome to {{store_name}}!

Here are our products:
{Product.all}

Your cart currently has {Cart.count} items with a total of {Cart.total}.

You searched for: {input.query}
Results: {Product.input.query}

Your last order status: {Order.status}

Contact us:
{Settings.contact}
```

## Parser Classes Documentation

### Product Parser

Methods:
- `all()` - Lists all active products for the store
- `search()` - Searches products using query from session context
- `input($data)` - Gets data from context based on the parameter name in $data['param']
- `find($productId)` - Gets details for a specific product

Example:
```
{Product.all}               // Lists all products
{Product.input.query}       // Gets value from context_data['query'] (may be search results or just the text)
{input.query}               // Directly outputs the query text
{Product.search}            // Searches products (using context_data['query'])
{Product.find.123}          // Gets details for product #123
{Product.input.name}        // Gets value from context_data['name']
```

#### Example: Search Command

For a command like:
```
search iPhone
```

1. The system stores `query` = "iPhone" in context_data
2. The template `🔍 *Search Results for '{input.query}'*\n\n{Product.search}` processes as:
   - `{input.query}` → "iPhone" (direct context access)
   - `{Product.search}` → Calls Product::search() which uses context_data['query']

The difference between `{Product.input.query}` and `{Product.search}` is:
- `{Product.input.query}` is more versatile and just retrieves the value from context data, possibly applying formatting
- `{Product.search}` does a specific search operation with the context data

### Cart Parser

Methods:
- `current()` - Shows the customer's current cart contents
- `count()` - Returns the number of items in cart
- `total()` - Returns the total value of the cart

Example:
```
{Cart.current}
{Cart.count}
{Cart.total}
```

### Customer Parser

Methods:
- `name()` - Returns the customer's name
- `phone()` - Returns the customer's phone number
- `input($data)` - Gets specific customer information from context data based on parameter name

Example:
```
{Customer.name}
{Customer.phone}
{Customer.input.email}      // Gets the email from context_data['email']
{Customer.input.address}    // Gets the address from context_data['address']
```

### Order Parser

Methods:
- `status()` - Gets the status of the customer's most recent order
- `history()` - Lists the customer's recent orders
- `detail($data)` - Gets details for a specific order

Example:
```
{Order.status}
{Order.history}
{Order.detail.123}
```

### Settings Parser

Methods:
- `name()` - Returns the store name
- `address()` - Returns the store address
- `contact()` - Returns formatted contact information
- `payment()` - Lists available payment methods
- `shipping()` - Lists available shipping methods
- `custom($data)` - Gets a custom store setting

Example:
```
{Settings.name}
{Settings.contact}
{Settings.payment}
```

### Formatter Parser

Methods:
- `currency($amount)` - Formats a number as currency
- `date($date, $format)` - Formats a date
- `upper($text)` - Converts text to uppercase
- `lower($text)` - Converts text to lowercase
- `title($text)` - Formats text in title case

Example:
```
{Formatter.currency.15000}
{Formatter.date.2023-05-22}
{Formatter.upper.hello}
```

### Template Parser

Methods:
- `render($data)` - Renders a template by its code
- `list()` - Lists available templates
- `welcome()` - Gets the welcome message template

Example:
```
{Template.render.product_list}
{Template.welcome}
```

## Context Data Access

You can access session context data directly using the `input` keyword:

```
You searched for: {input.query}
```

## Extending the System

To add new parsers:

1. Create a new class in the `App\Services\Whatsapp\MessageParser` namespace
2. Implement methods that return strings
3. Register the class in the `WhatsappBotService` constructor
4. Use your new parser in templates with `{YourClass.method}`

## Best Practices

1. Keep parser methods focused on single responsibilities
2. Return properly formatted strings from parser methods
3. Handle errors gracefully within parser methods
4. Use WhatsApp-compatible formatting (e.g., *bold*, _italic_)
5. Keep templates simple and readable
6. Test templates with different scenarios to ensure they display correctly

## Error Handling

The parser will attempt to handle errors gracefully:

- If a parser class doesn't exist: `[Parser 'ClassName' not found]`
- If a method doesn't exist: `[Method 'methodName' not found]`
- If an error occurs during processing: `[Error processing data: message]`
- If session context data is missing: `[No data for 'key']`

## Testing Templates

Use the `MessageParserTest` class to test template parsing functionality.
