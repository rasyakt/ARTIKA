# 📦 ARTIKA POS - Warehouse User Guide

Panduan lengkap untuk Staff Gudang mengelola inventory menggunakan sistem ARTIKA POS.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Login](#login)
- [Dashboard](#dashboard)
- [Stock Management](#stock-management)
- [Low Stock Alerts](#low-stock-alerts)
- [Stock Movements](#stock-movements)
- [Stock Adjustment](#stock-adjustment)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## Overview

Sebagai **Staff Gudang/Warehouse**, tugas utama Anda adalah:

- Monitor stock levels untuk semua products
- Handle stock in/out (receiving & shipping)
- Perform stock adjustments
- Alert when stock low
- Track stock movements
- Maintain inventory accuracy

---

## Login

1. Buka aplikasi ARTIKA POS
2. Masukkan **Username:** `gudang`
3. Masukkan **Password:** (default: `password`)
4. Klik **Login**

Setelah login, Anda akan diarahkan ke **Warehouse Dashboard**.

---

## Dashboard

### Overview Widgets

Warehouse dashboard menampilkan:

1. **Total Products** - Jumlah total SKU
2. **Low Stock Items** - Products di bawah minimum stock
3. **Total Stock Value** - Nilai inventory (cost price × quantity)
4. **Recent Movements** - Stock movements terbaru

### Quick Actions

- **Stock Management** - View all product stocks
- **Low Stock Alerts** - Products yang perlu restock
- **Stock Movements** - History pergerakan stock
- **Adjust Stock** - Manual stock adjustment

---

## Stock Management

### View Stock Levels

**Path:** Warehouse → Stock Management

**Table Columns:**

- **Product Name**
- **Barcode**
- **Category**
- **Current Stock**
- **Minimum Stock**
- **Status** (Normal / Low Stock / Out of Stock)
- **Actions** (Adjust Stock)

**Features:**

- Search by product name atau barcode
- Filter by:
    - Category
    - Stock status (All / Low Stock / Out of Stock)
- Sort by stock quantity, product name, etc

### Stock Status Indicators

| Status           | Condition            | Indicator |
| ---------------- | -------------------- | --------- |
| **Normal**       | Quantity > Min Stock | 🟢 Green  |
| **Low Stock**    | Quantity ≤ Min Stock | 🟡 Yellow |
| **Out of Stock** | Quantity = 0         | 🔴 Red    |

---

## Low Stock Alerts

### View Low Stock Products

**Path:** Warehouse → Low Stock Alerts

Menampilkan products dengan `quantity <= min_stock`

**Table Shows:**

- Product details
- Current quantity
- Minimum stock threshold
- Recommended reorder quantity
- Supplier info (future feature)

### Actions for Low Stock

1. **Request Purchase Order**
    - Generate PO untuk supplier
    - Specify quantity to reorder

2. **Adjust Minimum Stock**
    - Jika min_stock terlalu tinggi, adjust threshold

### Setting Minimum Stock

**Recommended Calculation:**

```
Min Stock = (Average Daily Sales × Lead Time Days) + Safety Stock

Example:
- Average sales: 10 units/day
- Lead time: 7 days
- Safety stock: 20 units
Min Stock = (10 × 7) + 20 = 90 units
```

---

## Stock Movements

### View Stock Movement History

**Path:** Warehouse → Stock Movements

**Movement Types:**

| Type           | Description             | Quantity Impact  |
| -------------- | ----------------------- | ---------------- |
| **IN**         | Stock masuk (receiving) | +Positive        |
| **OUT**        | Stock keluar (manual)   | -Negative        |
| **ADJUSTMENT** | Manual correction       | +/-              |
| **ADJUSTMENT** | Adjust stock quantity   | +/-              |
| **SALE**       | Sold via POS            | -Negative (auto) |

**Table Columns:**

- Date/Time
- Product
- Type
- Quantity Changed
- Quantity Before
- Quantity After
- User (who made the change)
- Reference No
- Notes

**Filters:**

- Date range
- Product
- Movement type
- User

**Use Cases:**

- Track product flow
- Audit stock changes
- Investigate discrepancies
- Generate stock reports

---

## Stock Adjustment

### When to Adjust Stock

**Common Scenarios:**

1. **Physical Count Different**
    - System: 100 units
    - Physical: 95 units
    - Adjustment: -5 (shrinkage)

2. **Receiving Goods**
    - New stock arrived dari supplier
    - Adjustment: +50 (stock in)

3. **Damaged/Expired Products**
    - Remove damaged items
    - Adjustment: -10 (wastage)

4. **Return to Supplier**
    - Return defective items
    - Adjustment: -25 (return out)

5. **Found Missing Stock**
    - Found extra stock during audit
    - Adjustment: +3 (found)

### How to Adjust Stock

**Steps:**

1. Go to **Warehouse → Stock Management**

2. Find the product yang perlu adjust

3. Klik **Adjust Stock** button

4. Fill in adjustment form:
    - **Type:** Select movement type
        - `IN` - Receiving new stock
        - `OUT` - Remove stock
        - `ADJUSTMENT` - General correction
    - **Quantity:**
        - For IN/TRANSFER IN: Enter positive number (e.g., `50`)
        - For OUT/ADJUSTMENT: Enter negative or positive
    - **Reference No:** (Optional)
        - PO number
        - Transfer document no
        - Audit reference
    - **Notes:** (Required for ADJUSTMENT)
        - Reason for adjustment
        - Example: "Physical count correction - found extra 5 units"

5. Verify the new quantity displayed

6. Klik **Submit**

**Result:**

- Stock updated in `stocks` table
- Movement recorded in `stock_movements` table
- Timestamp dan user logged untuk audit trail

### Stock Adjustment Best Practices

✅ **DO:**

- Always add detailed notes explaining adjustment
- Perform physical count before adjustment
- Double check quantity before submit
- Use proper movement type
- Reference supporting documents (PO, transfer doc)
- Get supervisor approval for large adjustments

❌ **DON'T:**

- Adjust without physical verification
- Leave notes empty
- Adjust multiple times for same issue
- Guess quantities
- Adjust stock to cover theft (report properly)

---

## Receiving Stock (Stock IN)

### Receiving Process

**When:** New stock arrives dari supplier

**Steps:**

1. **Verify Delivery**
    - Check PO number
    - Count physical items
    - Inspect for damage

2. **Record in System**
    - Go to Stock Management
    - Find each product received
    - Click **Adjust Stock**
    - Type: `IN`
    - Quantity: Amount received (positive number)
    - Reference No: PO number
    - Notes: "Received from [Supplier Name], PO-12345"

3. **Update for Each Product**
    - Repeat untuk semua items di PO

4. **Physical Storage**
    - Place items in proper location
    - Update bin location (if tracked)

**Tips:**

- Process receipts immediately (same day)
- Double count valuable items
- Quarantine damaged items (don't add to stock)
- Keep packing slips untuk reference

---

## Shipping Stock (Stock OUT)

### Manual Stock OUT

**When:** Stock keluar bukan via POS sale (e.g., transfer, return)

**Steps:**

1. Verify authorization (transfer request, return doc)

2. Go to Stock Management

3. Select product

4. Click **Adjust Stock**

5. Fill in:
    - Type: `OUT` atau `TRANSFER`
    - Quantity: Negative number (e.g., `-30`)
    - Reference: Transfer doc no
    - Notes: "Transfer to Branch Cabang 1, Transfer-001"

6. Submit

7. Pack and ship items

**Notes:**

- Stock OUT dari POS sales tercatat otomatis
- Manual OUT hanya untuk non-sales movements

---

## Physical Stock Count (Inventory Audit)

### Performing Stock Count

**Frequency:** Monthly atau quarterly

**Steps:**

1. **Preparation**
    - Schedule count (off-hours recommended)
    - Freeze transactions during count
    - Print current stock report dari system

2. **Count Process**
    - Count each product physically
    - Record on count sheet
    - Use 2-person verification untuk accuracy

3. **Compare with System**
    - Compare physical count vs system
    - Note discrepancies

4. **Adjust Discrepancies**
    - Untuk setiap product dengan mismatch:
        - Click Adjust Stock
        - Type: `ADJUSTMENT`
        - Quantity: Difference (positive or negative)
        - Notes: "Physical count [date] - System: 100, Physical: 95, Variance: -5"
5. **Report**
    - Generate variance report
    - Investigate large discrepancies
    - Submit to supervisor

**Accuracy Target:** ≥ 95% (most products match)

---

## Stock Reports

### Available Reports

1. **Stock Level Report**
    - Current stock per product
    - Grouped by category
    - Stock value calculation

2. **Stock Movement Report**
    - All movements in date range
    - Grouped by type
    - User activity log

3. **Variance Report**
    - Discrepancies from physical count
    - Suspicious patterns
    - Shrinkage analysis

4. **Reorder Report**
    - Products below minimum stock
    - Recommended reorder quantities
    - Supplier information

**Export Options:** PDF, Excel

---

## Best Practices

### Inventory Accuracy

✅ **DO:**

- Perform regular physical counts
- Adjust stock immediately when discrepancies found
- Use detailed notes for all adjustments
- Implement cycle counting (count subset daily)
- Train staff on proper procedures
- Use barcode scanners for accuracy

❌ **DON'T:**

- Delay recording receipts/shipments
- Adjust without physical verification
- Skip notes/documentation
- Allow unauthorized adjustments
- Ignore recurring discrepancies

### Organization

✅ **DO:**

- Organize warehouse by category
- Use bin locations
- FIFO (First In, First Out) rotation
- Label shelves clearly
- Keep aisles clear
- Secure high-value items

### Safety

✅ **DO:**

- Wear safety equipment
- Follow lifting procedures
- Keep fire exits clear
- Report hazards immediately
- Maintain clean workspace

---

## Troubleshooting

### Problem: Stock Count Mismatch

**Investigation Steps:**

1. **Count Again**
    - Verify physical count is correct
    - Check multiple locations (backroom, display)

2. **Check Recent Movements**
    - Review stock_movements for product
    - Look for unrecorded sales atau adjustments

3. **Verify POS Sales**
    - Check if POS transactions recorded properly
    - Look for failed transactions

4. **Common Causes:**
    - Theft/shrinkage
    - Unrecorded damage/wastage
    - Data entry errors
    - System glitches (rare)

**Solution:**

- Adjust to physical count
- Document reason
- Implement controls to prevent recurrence

### Problem: Cannot Adjust Stock

**Error:** "Insufficient permissions"

**Solution:**

- Check your user role
- Only Warehouse role atau Admin can adjust
- Contact admin for access

### Problem: Negative Stock

**Error:** "Cannot reduce stock below zero"

**Explanation:**

- System prevents negative stock (stock can't be < 0)

**Solution:**

- Verify quantity being removed
- Check current stock first
- If system shows 5, can't remove 10

### Problem: Missing Product in Stock List

**Check:**

1. Product exists in product master?
2. Stock record created for your branch?

**Solution:**

- Contact admin to create product
- Or admin assigns stock to your branch

---

## Key Performance Indicators (KPIs)

### Warehouse KPIs to Monitor

1. **Inventory Accuracy**
    - Target: ≥ 98%
    - Formula: (Correct Counts / Total Counts) × 100%

2. **Stock Out Rate**
    - Target: < 2%
    - Formula: (Days Out of Stock / Total Days) × 100%

3. **Inventory Turnover**
    - Target: Depends on industry (e.g., 8-12× per year)
    - Formula: Cost of Goods Sold / Average Inventory

4. **Order Cycle Time**
    - Target: < 3 days (order to receipt)
    - Measure: PO date to receiving date

5. **Shrinkage Rate**
    - Target: < 1%
    - Formula: (Book Inventory - Physical Inventory) / Book Inventory

---

## Need Help?

### Support

- **Stock Discrepancies:** Report to Supervisor
- **System Issues:** Contact IT Support
- **Process Questions:** Training Department
- **Emergency:** Use emergency contact

### Documentation

- [DATABASE.md](file:///c:/laragon/www/ARTIKA/DATABASE.md) - Stock tables reference
- [USER_GUIDE_ADMIN.md](file:///c:/laragon/www/ARTIKA/docs/USER_GUIDE_ADMIN.md) - Admin guide

---

**Keep Inventory Accurate! 📦**

**Version:** 2.5  
**Last Updated:** 2026-01-23
