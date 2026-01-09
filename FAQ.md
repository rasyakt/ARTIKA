# ❓ ARTIKA POS - Frequently Asked Questions (FAQ)

Common questions and answers about ARTIKA POS system.

---

## General Questions

### What is ARTIKA POS?

ARTIKA POS is a complete Point of Sale system built with Laravel 12, designed for retail stores with features like barcode scanning, multi-branch support, inventory management, and role-based access control.

### Who should use ARTIKA POS?

- Small to medium retail stores
- Convenience stores
- Grocery shops
- Any business that needs POS functionality

### Is ARTIKA POS free?

Yes, ARTIKA POS is open-source under MIT License. You can use, modify, and distribute it freely.

### What are the system requirements?

- PHP 8.2+
- MySQL 5.7+
- Node.js 18+
- Web browser (Chrome, Firefox, Edge recommended)

See [INSTALLATION.md](INSTALLATION.md) for details.

---

## Installation & Setup

### How do I install ARTIKA POS?

See complete installation guide in [INSTALLATION.md](INSTALLATION.md).

Quick steps:
1. Clone repository
2. Run `composer install` and `npm install`
3. Configure `.env` file
4. Run `php artisan migrate:fresh --seed`
5. Run `php artisan serve` and `npm run dev`

### What are the default login credentials?

| Role | Username | NIS | Password |
|------|----------|-----|----------|
| Admin | `admin` | - | `password` |
| Cashier | `kasir1` | `12345` | `password` |
| Warehouse | `gudang` | - | `password` |

> **IMPORTANT:** Change these passwords immediately after installation!

### How do I reset admin password?

```bash
php artisan tinker

>>> $admin = User::where('username', 'admin')->first()
>>> $admin->password = bcrypt('new_password')
>>> $admin->save()
```

### Can I install on shared hosting?

Not recommended. ARTIKA requires:
- Command line access
- Composer
- Node.js
- Root access for configuration

Use VPS or dedicated server for production.

---

## Features

### Does it support barcode scanning?

Yes! Two methods:
1. **USB Barcode Scanner** - Plug & play, works immediately
2. **Camera Scanner** - Uses device camera with html5-qrcode library

See [USER_GUIDE_CASHIER.md](docs/USER_GUIDE_CASHIER.md) for usage.

### Can I use it without a barcode scanner?

Yes. You can:
- Search products by name
- Manual barcode entry
- Click product cards to add to cart

### Does it support multiple branches?

Yes. Features:
- Branch-specific stock tracking
- Transfer stock between branches
- Branch-level reports

### What payment methods are supported?

Default payment methods:
- Cash
- QRIS
- Debit Card
- Credit Card
- E-Wallet

Admin can add/remove payment methods.

### Can customers have loyalty points?

Yes! Customer database includes:
- Loyalty points tracking
- Member since date
- Purchase history (via transactions)

### Does it print receipts?

Yes. After checkout:
- Receipt preview shown
- Click "Print" to print via browser
- Supports thermal printers

### Can I hold/park transactions?

Yes. Press `F4` or click "Hold" to save current transaction for later. Resume from "Held Transactions" list.

---

## Product Management

### How do I add products?

**Admin panel:**
1. Login as Admin
2. Go to Products → Add New
3. Fill in: Barcode, Name, Category, Price, Cost Price
4. Save

### Can products have variations (size, color)?

Not currently supported in v2.0. Future feature.

### How do I update product prices?

1. Admin → Products
2. Click ellipsis (⋮) on product
3. Edit → Update price
4. Save

Price changes don't affect existing transactions.

### Can I import products from CSV/Excel?

Not built-in currently. You can:
1. Create seeder from CSV
2. Or use Laravel Excel package (custom development)

---

## Inventory Management

### How does stock tracking work?

- Stock tracked per **branch**
- Auto-decreased on POS sale
- Warehouse can manually adjust
- All movements logged in `stock_movements`

### What happens when stock is zero?

- Product shows "Out of Stock" in POS
- Cannot add to cart
- System prevents negative stock
- Low stock alert shown to warehouse

### How do I transfer stock between branches?

Warehouse staff:
1. Source branch: Adjust Stock → Type: Transfer → Negative quantity
2. Destination branch: Adjust Stock → Type: Transfer → Positive quantity
3. Use same reference number

### Can I see stock movement history?

Yes. Warehouse → Stock Movements

Filters:
- Date range
- Product
- Movement type
- User who made change

---

## User Management

### How do I add a new cashier?

Admin panel:
1. Users → Add New
2. Fill name, username, NIS (optional), password
3. Role: **Cashier**
4. Assign branch
5. Save

Cashier can now login with username or NIS.

### What's the difference between Username and NIS?

- **Username:** Alphanumeric login (e.g., `kasir1`)
- **NIS:** Numeric ID for students/cashiers (e.g., `12345`)

Cashiers can login with either.

### Can a user have multiple roles?

No. Each user has one role:
- Admin
- Cashier
- Warehouse

### How do I disable a user account?

Currently: Delete user from Admin → Users

Future: Deactivate feature (keeps data, prevents login)

---

## Transactions

### Can I void/cancel a transaction?

Not directly in POS. Transactions are:
- Pending (during checkout)
- Completed (after checkout)
- Canceled (admin only, rare)

For returns, use Return feature.

### How do I process returns/refunds?

Return feature available but UI pending. Current workaround:
1. Create negative adjustment in stock
2. Manual cash refund
3. Note in system

Future: Full return UI.

### Where can I view transaction history?

Admin → Reports → Transactions (future)

Currently: Check database `transactions` table.

### Can I print receipt after transaction completed?

Yes. If receipt ID known:
- Go to `/pos/receipt/{transaction_id}`
- Or from transaction history (when UI ready)

### What if payment fails mid-transaction?

- Transaction not saved until payment confirmed
- If error occurs, cart preserved
- Can retry or cancel

---

## Reports

### What reports are available?

Current (v2.0):
- Sales dashboard widget
- Stock levels
- Low stock alerts

Future:
- Detailed sales reports
- Financial reports
- Inventory reports
- User performance reports

### Can I export reports to Excel/PDF?

Future feature. Current workaround:
- Query database directly
- Export from phpMyAdmin/MySQL Workbench

### How do I see best-selling products?

SQL query (future: built-in report):
```sql
SELECT p.name, SUM(ti.quantity) as total_sold
FROM transaction_items ti
JOIN products p ON ti.product_id = p.id
GROUP BY p.id
ORDER BY total_sold DESC
LIMIT 10;
```

---

## Troubleshooting

### Scanner not working

**USB Scanner:**
1. Check USB connection
2. Test in notepad - scan barcode, does it type numbers?
3. If yes, but not working in POS: refresh page
4. Still broken: check browser console for errors

**Camera Scanner:**
1. Browser must support getUserMedia (Chrome, Firefox)
2. HTTPS required (or localhost)
3. Grant camera permission
4. Good lighting needed
5. Check browser console for errors

### "Stock not sufficient" error

Product out of stock. Options:
1. Remove item from cart
2. Reduce quantity
3. Ask warehouse to restock

### Login not working

**Check:**
- Correct username/NIS?
- Correct password? (case-sensitive)
- User account active?
- Role assigned?

**Reset password:**
```bash
php artisan tinker
>>> $user = User::where('username', 'kasir1')->first()
>>> $user->password = bcrypt('newpassword')
>>> $user->save()
```

### Page not loading / 500 error

1. Check storage permissions:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

2. Check logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

### Database connection error

**Check `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artika
DB_USERNAME=root
DB_PASSWORD=
```

**Test connection:**
```bash
mysql -u root -p artika
```

### Assets not loading (CSS/JS)

**Development:**
```bash
npm run dev
```
Keep this running.

**Production:**
```bash
npm run build
```

---

## Performance

### System is slow

**Check:**
1. Too many products? Use pagination
2. Database needs optimization:
   ```sql
   OPTIMIZE TABLE products, stocks, transactions;
   ```

3. Enable caching:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. Use Redis for cache/sessions (production)

### Database growing too large

**Cleanup old data:**
- Archive old transactions (>1 year)
- Delete old logs
- Vacuum database

**Increase storage** if needed.

---

## Security

### Is it secure?

Yes, if configured properly:
- ✅ CSRF protection (default)
- ✅ SQL injection prevention (Eloquent)
- ✅ XSS protection (Blade escaping)
- ✅ Password hashing (bcrypt)
- ✅ Role-based access control

**YOU must:**
- Use HTTPS in production
- Strong passwords
- Keep system updated
- Regular backups

### Should I change default passwords?

**ABSOLUTELY YES!** Default passwords are public knowledge.

Change immediately after installation.

### Can I use HTTP instead of HTTPS?

**Development:** OK
**Production:** NO! HTTPS required for:
- Security
- Camera scanner (browser requirement)
- Payment processing
- Customer trust

---

## Customization

### Can I customize the design/theme?

Yes! ARTIKA uses standard CSS/SCSS.

- Edit `resources/css/app.css`
- Change colors in CSS variables
- Rebuild: `npm run build`

### Can I add custom features?

Yes! ARTIKA is open-source.

1. Fork repository
2. Add features following [CONTRIBUTING.md](CONTRIBUTING.md)
3. Create pull request (optional, to contribute back)

### Does it support multiple languages?

Not currently. Default: English + Indonesian (mixed).

Future: Laravel localization for full multi-language.

### Can I white-label it?

Yes! MIT License allows:
- Change branding
- Rename application
- Remove credits (though attribution appreciated)

---

## Support

### Where can I get help?

1. **Documentation:** Start with README.md
2. **User Guides:** Role-specific guides in `docs/`
3. **FAQ:** This file
4. **GitHub Issues:** For bugs/features
5. **Community:** GitHub Discussions

### Is there commercial support?

Currently: Community support only via GitHub.

Future: Paid support may be available.

### Can I hire someone to customize it?

Yes! Being open-source, any Laravel developer can customize ARTIKA for you.

---

## Roadmap

### What's planned for future versions?

See [CHANGELOG.md](CHANGELOG.md) for version history.

**Planned features:**
- Return/refund UI
- Shift management
- Complete reporting suite
- Customer loyalty program
- Online ordering integration
- Multi-language support
- Mobile app
- API for third-party integrations

### Can I request features?

Yes! Create feature request on GitHub Issues.

Or better: Contribute the feature yourself!

---

## Still Have Questions?

- 📖 Check [Documentation](README.md)
- 💬 Ask on [GitHub Discussions](https://github.com/yourusername/artika-pos/discussions)
- 🐛 Report bugs on [GitHub Issues](https://github.com/yourusername/artika-pos/issues)

---

**Last Updated:** 2026-01-09  
**Version:** 2.0
