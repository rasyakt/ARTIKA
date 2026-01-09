# 🔌 ARTIKA POS - API Documentation

Complete API routes documentation untuk ARTIKA POS system dengan request/response examples.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Authentication](#authentication)
- [Admin Routes](#admin-routes)
- [POS/Cashier Routes](#poscashier-routes)
- [Warehouse Routes](#warehouse-routes)
- [Response Format](#response-format)
- [Error Handling](#error-handling)

---

## Overview

ARTIKA POS menggunakan **web routes** (session-based authentication) dengan role-based middleware untuk authorization.

**Base URL:** `http://localhost:8000` (development)

**Authentication:** Laravel session cookies

---

## Authentication

### Login

**Endpoint:** `POST /login`

**Description:** Authenticate user dengan username atau NIS

**Request:**
```http
POST /login HTTP/1.1
Content-Type: application/x-www-form-urlencoded

login=admin&password=password
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| login | string | Yes | Username atau NIS (numeric untuk NIS) |
| password | string | Yes | User password |

**Response (Success):**
```http
HTTP/1.1 302 Found
Location: /admin/dashboard  (atau /pos, /warehouse/dashboard based on role)
Set-Cookie: laravel_session=...
```

**Response (Error):**
```http
HTTP/1.1 302 Found
Location: /login
```
With error message: "Invalid credentials"

**Example cURL:**
```bash
curl -X POST http://localhost:8000/login \
  -d "login=admin&password=password" \
  -c cookies.txt
```

---

### Logout

**Endpoint:** `POST /logout`

**Authentication:** Required

**Request:**
```http
POST /logout HTTP/1.1
Cookie: laravel_session=...
X-CSRF-TOKEN: {token}
```

**Response:**
```http
HTTP/1.1 302 Found
Location: /login
```

---

## Admin Routes

**Base Path:** `/admin`

**Middleware:** `auth`, `role:admin`

### Dashboard

#### Get Admin Dashboard

**Endpoint:** `GET /admin/dashboard`

**Authentication:** Required (Admin only)

**Response:** HTML view dengan statistik sales, products, dll.

---

### Product Management

#### List All Products

**Endpoint:** `GET /admin/products`

**Authentication:** Required (Admin only)

**Response:** HTML view dengan tabel products

---

#### Create Product Form

**Endpoint:** `GET /admin/products/create`

**Authentication:** Required (Admin only)

**Response:** HTML form untuk create product

---

#### Store New Product

**Endpoint:** `POST /admin/products`

**Authentication:** Required (Admin only)

**Request:**
```http
POST /admin/products HTTP/1.1
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}

barcode=899999999999
&name=New Product
&category_id=1
&price=15000
&cost_price=12000
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| barcode | string | Yes | Unique product barcode |
| name | string | Yes | Product name |
| category_id | integer | Yes | Category ID |
| price | decimal | Yes | Selling price |
| cost_price | decimal | Yes | Cost price |

**Validation Rules:**
```php
'barcode' => 'required|unique:products',
'name' => 'required|string|max:255',
'category_id' => 'required|exists:categories,id',
'price' => 'required|numeric|min:0',
'cost_price' => 'required|numeric|min:0',
```

**Response (Success):**
```http
HTTP/1.1 302 Found
Location: /admin/products
```
With success message: "Product created successfully"

---

#### Edit Product Form

**Endpoint:** `GET /admin/products/{id}/edit`

**Authentication:** Required (Admin only)

**URL Parameters:**
- `id` - Product ID

**Response:** HTML form dengan product data

---

#### Update Product

**Endpoint:** `PUT /admin/products/{id}`

**Authentication:** Required (Admin only)

**Request:**
```http
PUT /admin/products/1 HTTP/1.1
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}

barcode=899999999999
&name=Updated Product
&category_id=1
&price=18000
&cost_price=14000
```

**Response (Success):**
```http
HTTP/1.1 302 Found
Location: /admin/products
```

---

#### Delete Product

**Endpoint:** `DELETE /admin/products/{id}`

**Authentication:** Required (Admin only)

**Request:**
```http
DELETE /admin/products/1 HTTP/1.1
X-CSRF-TOKEN: {token}
```

**Response (Success):**
```http
HTTP/1.1 302 Found
Location: /admin/products
```

---

### Category Management

#### List Categories

**Endpoint:** `GET /admin/categories`

**Authentication:** Required (Admin only)

**Response:** HTML view dengan categories table

---

#### Store Category

**Endpoint:** `POST /admin/categories`

**Authentication:** Required (Admin only)

**Request:**
```http
POST /admin/categories HTTP/1.1
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}

name=Electronics&slug=electronics
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Category name |
| slug | string | Yes | URL-friendly slug (unique) |

**Response (Success):**
```http
HTTP/1.1 302 Found
Location: /admin/categories
```

---

#### Update Category

**Endpoint:** `PUT /admin/categories/{id}`

**Authentication:** Required (Admin only)

**Request:**
```http
PUT /admin/categories/1 HTTP/1.1
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}

name=Updated Category&slug=updated-category
```

---

#### Delete Category

**Endpoint:** `DELETE /admin/categories/{id}`

**Authentication:** Required (Admin only)

**Request:**
```http
DELETE /admin/categories/1 HTTP/1.1
X-CSRF-TOKEN: {token}
```

---

### User Management

#### List Users

**Endpoint:** `GET /admin/users`

**Authentication:** Required (Admin only)

**Response:** HTML view dengan users table

---

#### Store User

**Endpoint:** `POST /admin/users`

**Authentication:** Required (Admin only)

**Request:**
```http
POST /admin/users HTTP/1.1
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}

name=John Doe
&username=johndoe
&nis=67890
&password=password123
&role_id=2
&branch_id=1
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | User full name |
| username | string | Yes | Unique username |
| nis | string | No | NIS (for cashiers/students) |
| password | string | Yes | User password (min 8 chars) |
| role_id | integer | Yes | Role ID (1=Admin, 2=Cashier, 3=Warehouse) |
| branch_id | integer | Yes | Branch ID |

---

#### Update User

**Endpoint:** `PUT /admin/users/{id}`

**Authentication:** Required (Admin only)

---

#### Delete User

**Endpoint:** `DELETE /admin/users/{id}`

**Authentication:** Required (Admin only)

---

### Customer Management

#### List Customers

**Endpoint:** `GET /admin/customers`

**Authentication:** Required (Admin only)

---

#### Store Customer

**Endpoint:** `POST /admin/customers`

**Authentication:** Required (Admin only)

**Request:**
```http
POST /admin/customers HTTP/1.1
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}

name=Jane Doe
&phone=081234567890
&email=jane@example.com
&address=Jl. Example No. 123
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Customer name |
| phone | string | Yes | Phone number (unique) |
| email | string | No | Email address |
| address | text | No | Customer address |

---

#### Update Customer

**Endpoint:** `PUT /admin/customers/{id}`

**Authentication:** Required (Admin only)

---

#### Delete Customer

**Endpoint:** `DELETE /admin/customers/{id}`

**Authentication:** Required (Admin only)

---

### Reports

#### View Reports

**Endpoint:** `GET /admin/reports`

**Authentication:** Required (Admin only)

**Response:** HTML view dengan sales reports, analytics, etc.

---

## POS/Cashier Routes

**Base Path:** `/pos`

**Middleware:** `auth`, `role:cashier`

### POS Interface

#### Get POS Page

**Endpoint:** `GET /pos`

**Authentication:** Required (Cashier only)

**Response:** HTML POS interface dengan product grid, cart, scanner

**Features:**
- Product search
- Barcode scanner
- Shopping cart
- Payment methods
- Keyboard shortcuts

---

#### Scanner Page

**Endpoint:** `GET /pos/scanner`

**Authentication:** Required (Cashier only)

**Response:** HTML camera scanner interface

---

### Transactions

#### Create Transaction (Checkout)

**Endpoint:** `POST /pos/checkout`

**Authentication:** Required (Cashier only)

**Request:**
```http
POST /pos/checkout HTTP/1.1
Content-Type: application/json
X-CSRF-TOKEN: {token}

{
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 15000
    },
    {
      "product_id": 2,
      "quantity": 1,
      "price": 18000
    }
  ],
  "payment_method": "cash",
  "cash_amount": 50000,
  "customer_id": null,
  "discount": 0,
  "tax": 0,
  "note": ""
}
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| items | array | Yes | Array of cart items |
| items[].product_id | integer | Yes | Product ID |
| items[].quantity | integer | Yes | Quantity |
| items[].price | decimal | Yes | Price per unit |
| payment_method | string | Yes | Payment method (cash, qris, debit, credit, ewallet) |
| cash_amount | decimal | Conditional | Required if payment_method is cash |
| customer_id | integer | No | Customer ID (optional) |
| discount | decimal | No | Discount amount |
| tax | decimal | No | Tax amount |
| note | string | No | Transaction note |

**Response (Success):**
```json
{
  "success": true,
  "transaction_id": 123,
  "invoice_no": "INV-20260109-0001",
  "total_amount": 48000,
  "change": 2000,
  "receipt_url": "/pos/receipt/123"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Insufficient stock for product: Product Name"
}
```

**Business Logic:**
1. Validate all items have sufficient stock
2. Begin database transaction
3. Create `transactions` record dengan auto-generated invoice_no
4. Create `transaction_items` records untuk setiap item
5. Update `stocks` (decrease quantity)
6. Create `stock_movements` records untuk audit trail
7. Create `journals` entries untuk accounting
8. Commit transaction
9. Return success response

---

### Hold Transactions

#### Save/Hold Transaction

**Endpoint:** `POST /pos/hold`

**Authentication:** Required (Cashier only)

**Request:**
```json
{
  "items": [...],
  "note": "Customer will return in 10 minutes"
}
```

**Response (Success):**
```json
{
  "success": true,
  "held_transaction_id": 5,
  "message": "Transaction saved successfully"
}
```

---

#### Get Held Transactions

**Endpoint:** `GET /pos/held`

**Authentication:** Required (Cashier only)

**Response:**
```json
{
  "held_transactions": [
    {
      "id": 5,
      "subtotal": 48000,
      "note": "Customer will return",
      "created_at": "2026-01-09 10:30:00"
    }
  ]
}
```

---

#### Resume Held Transaction

**Endpoint:** `GET /pos/held/{id}/resume`

**Authentication:** Required (Cashier only)

**URL Parameters:**
- `id` - Held transaction ID

**Response:** Redirect ke POS dengan cart loaded

---

#### Delete Held Transaction

**Endpoint:** `DELETE /pos/held/{id}`

**Authentication:** Required (Cashier only)

**Request:**
```http
DELETE /pos/held/5 HTTP/1.1
X-CSRF-TOKEN: {token}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Held transaction deleted"
}
```

---

### Receipt

#### Print Receipt

**Endpoint:** `GET /pos/receipt/{id}`

**Authentication:** Required (Cashier only)

**URL Parameters:**
- `id` - Transaction ID

**Response:** HTML receipt view (print-friendly)

---

## Warehouse Routes

**Base Path:** `/warehouse`

**Middleware:** `auth`, `role:warehouse`

### Dashboard

#### Get Warehouse Dashboard

**Endpoint:** `GET /warehouse/dashboard`

**Authentication:** Required (Warehouse only)

**Response:** HTML view dengan stock overview, alerts, movements

---

### Stock Management

#### Stock Management Page

**Endpoint:** `GET /warehouse/stock`

**Authentication:** Required (Warehouse only)

**Response:** HTML view dengan stock list untuk all products

**Features:**
- View current stock levels
- Adjust stock
- Search products
- Filter by branch

---

#### Low Stock Alerts

**Endpoint:** `GET /warehouse/low-stock`

**Authentication:** Required (Warehouse only)

**Response:** HTML view dengan products where `quantity <= min_stock`

---

#### Stock Movements

**Endpoint:** `GET /warehouse/stock-movements`

**Authentication:** Required (Warehouse only)

**Response:** HTML view dengan stock movement history

**Features:**
- Filter by date range
- Filter by product
- Filter by movement type (in/out/adjustment/transfer)

---

#### Adjust Stock

**Endpoint:** `POST /warehouse/stock/adjust`

**Authentication:** Required (Warehouse only)

**Request:**
```http
POST /warehouse/stock/adjust HTTP/1.1
Content-Type: application/json
X-CSRF-TOKEN: {token}

{
  "product_id": 1,
  "branch_id": 1,
  "type": "adjustment",
  "quantity": 50,
  "notes": "Stock correction after physical count"
}
```

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| product_id | integer | Yes | Product ID |
| branch_id | integer | Yes | Branch ID |
| type | enum | Yes | in, out, adjustment, transfer |
| quantity | integer | Yes | Quantity (positive or negative) |
| reference_no | string | No | Reference number (PO, etc) |
| notes | text | No | Adjustment notes |

**Response (Success):**
```json
{
  "success": true,
  "message": "Stock adjusted successfully",
  "new_quantity": 150
}
```

**Business Logic:**
1. Validate product exists
2. Get current stock for branch
3. Calculate new quantity
4. Update `stocks` table
5. Create `stock_movements` record untuk audit trail
6. Return new quantity

---

## Response Format

### Success Response

**HTTP Status:** 200 OK (atau 302 Found untuk redirects)

**JSON Response:**
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... }
}
```

**HTML Response:** Rendered Blade view

---

### Error Response

**HTTP Status:** 4xx atau 5xx

**Validation Error (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "barcode": ["The barcode has already been taken."],
    "price": ["The price must be at least 0."]
  }
}
```

**Unauthorized (403):**
```json
{
  "message": "Unauthorized Access"
}
```

**Not Found (404):**
```json
{
  "message": "Resource not found"
}
```

---

## Error Handling

### Common HTTP Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 302 | Found | Redirect (after successful form submission) |
| 401 | Unauthorized | User not authenticated |
| 403 | Forbidden | User doesn't have required role |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error |

### CSRF Protection

All POST/PUT/DELETE requests require CSRF token:

**In Blade:**
```blade
@csrf
```

**In JavaScript:**
```javascript
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

---

## Rate Limiting

Laravel default rate limiting: **60 requests per minute** per IP/user

---

## Related Documentation

- [ARCHITECTURE.md](file:///c:/laragon/www/ARTIKA/ARCHITECTURE.md) - System architecture
- [DATABASE.md](file:///c:/laragon/www/ARTIKA/DATABASE.md) - Database schema
- [USER_GUIDE_ADMIN.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_ADMIN.md) - Admin user guide
- [USER_GUIDE_CASHIER.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_CASHIER.md) - Cashier user guide
- [USER_GUIDE_WAREHOUSE.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_WAREHOUSE.md) - Warehouse user guide

---

**Last Updated:** 2026-01-09  
**API Version:** 2.0
