# Brancy Template Testing Guide

## Quick Start

### 1. Start the Development Server

```bash
php artisan serve
```

Visit: http://127.0.0.1:8000

### 2. Test User Journey

#### A. Browse Products
1. **Homepage**: http://127.0.0.1:8000
   - View hero slider
   - See product categories
   - Browse featured products

2. **Shop Page**: http://127.0.0.1:8000/shop
   - Test search functionality
   - Filter by collection/category
   - Sort products (newest, name, price)
   - Test pagination

3. **Product Detail**: Click any product
   - View product images
   - Select variants (if available)
   - Change quantity
   - Click "Add to Cart"
   - Verify cart count updates in header

#### B. Shopping Cart
1. **View Cart**: http://127.0.0.1:8000/cart
   - Verify products are listed
   - Test quantity update (+/- buttons)
   - Test remove item
   - Check cart totals calculation
   - Test "Clear Cart" button

#### C. Checkout Process (Requires Products in Cart)
1. **Checkout Form**: http://127.0.0.1:8000/checkout
   - Fill shipping address
   - Toggle "Same as shipping" for billing
   - Add order notes (optional)
   - Click "Proceed to Payment"

2. **Payment Page**: http://127.0.0.1:8000/checkout/payment
   - Review order summary
   - Enter Stripe test card:
     - **Card Number**: 4242 4242 4242 4242
     - **Expiry**: Any future date (e.g., 12/25)
     - **CVC**: Any 3 digits (e.g., 123)
   - Click "Pay Now"

3. **Success Page**:
   - Verify order confirmation displays
   - Check order number
   - View order details

#### D. User Account (Requires Registration)
1. **Register**: http://127.0.0.1:8000/register
   - Create an account
   - Verify email (if enabled)

2. **Login**: http://127.0.0.1:8000/login
   - Login with credentials

3. **Dashboard**: http://127.0.0.1:8000/account/dashboard
   - View account overview
   - See recent orders

4. **Order History**: http://127.0.0.1:8000/account/orders
   - View all orders
   - Click order to see details

5. **Profile**: http://127.0.0.1:8000/account/profile
   - Update personal information

#### E. Static Pages
1. **About**: http://127.0.0.1:8000/about
   - View company info
   - See features and stats

2. **Contact**: http://127.0.0.1:8000/contact
   - Fill contact form
   - Submit and verify success message
   - Check email was sent (if mail configured)

3. **FAQ**: http://127.0.0.1:8000/faq
   - Test accordion functionality
   - Read through FAQs

4. **Blog**: http://127.0.0.1:8000/blog
   - Browse blog posts (when content added)

## Testing Checklist

### ✅ Homepage
- [ ] Hero slider animates
- [ ] Product categories display
- [ ] Featured products load from database
- [ ] "Buy Now" buttons navigate to shop
- [ ] Newsletter form submits

### ✅ Product Browsing
- [ ] All products display with images
- [ ] Search returns relevant results
- [ ] Category filter works
- [ ] Sort options work correctly
- [ ] Pagination navigates properly
- [ ] "No products" state shows when applicable

### ✅ Product Details
- [ ] Product image displays
- [ ] Variant selection updates price
- [ ] Quantity selector works
- [ ] Add to cart shows success
- [ ] Cart count in header updates
- [ ] Related products display

### ✅ Shopping Cart
- [ ] Cart displays all added items
- [ ] Quantity update works (AJAX)
- [ ] Remove item works (AJAX)
- [ ] Cart totals calculate correctly
- [ ] Empty cart state shows
- [ ] "Continue Shopping" returns to shop
- [ ] "Clear Cart" removes all items

### ✅ Checkout
- [ ] Form validation works
- [ ] Required fields marked
- [ ] Billing address toggle works
- [ ] Can't checkout with empty cart
- [ ] Progresses to payment page

### ✅ Payment
- [ ] Stripe form loads
- [ ] Test card processes successfully
- [ ] Loading state shows during processing
- [ ] Redirects to success page
- [ ] Order created in database
- [ ] Cart cleared after successful payment

### ✅ Authentication
- [ ] Registration creates account
- [ ] Login authenticates user
- [ ] Logout works
- [ ] Password reset emails sent
- [ ] Protected routes redirect to login
- [ ] Header shows user icon when logged in

### ✅ Account Pages
- [ ] Dashboard loads
- [ ] Order history displays
- [ ] Order details show correctly
- [ ] Profile update works
- [ ] Can only view own orders

### ✅ Responsive Design
- [ ] Mobile menu works
- [ ] Layout adapts to mobile
- [ ] Cart works on mobile
- [ ] Checkout works on mobile
- [ ] Touch interactions work

### ✅ AJAX Features
- [ ] Add to cart without page reload
- [ ] Cart count updates in header
- [ ] Quantity changes update totals
- [ ] Remove item updates UI
- [ ] Loading states show

## Stripe Test Cards

### Successful Payment
```
Card: 4242 4242 4242 4242
Expiry: 12/25
CVC: 123
```

### Failed Payment (insufficient funds)
```
Card: 4000 0000 0000 9995
Expiry: 12/25
CVC: 123
```

### Requires Authentication
```
Card: 4000 0025 0000 3155
Expiry: 12/25
CVC: 123
```

## Database Checks

### Verify Products
```bash
php artisan tinker
```
```php
\Lunar\Models\Product::count() // Should show 9
\Lunar\Models\Product::first()->name // Show first product name
```

### Verify Collections
```php
\Lunar\Models\Collection::count()
\Lunar\Models\Collection::first()->name
```

### Check Orders After Purchase
```php
\Lunar\Models\Order::count()
\Lunar\Models\Order::latest()->first() // Latest order
```

### View Cart
```php
\Lunar\Facades\CartSession::current() // Current cart
```

## Common Issues & Solutions

### Issue: "Products not showing"
**Solution**: Ensure products are published
```bash
php artisan tinker
\Lunar\Models\Product::where('status', '!=', 'published')->update(['status' => 'published']);
```

### Issue: "Images not loading"
**Solution**: Check storage link exists
```bash
php artisan storage:link
```

### Issue: "Stripe payment fails"
**Solution**:
1. Check `.env` has correct Stripe keys
2. Clear config: `php artisan config:cache`
3. Use test card: 4242 4242 4242 4242

### Issue: "Cart empty after login"
**Solution**: This is expected - guest carts don't automatically merge. Set merge policy in `config/lunar/cart.php`:
```php
'auth_policy' => 'merge',
```

### Issue: "Email not sending from contact form"
**Solution**: Configure mail in `.env`:
```env
MAIL_MAILER=log  # For testing - emails saved to storage/logs
# Or configure real SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

### Issue: "Session expired during checkout"
**Solution**: Increase session lifetime in `config/session.php` or `.env`:
```env
SESSION_LIFETIME=120
```

## Performance Testing

### Page Load Times
```bash
# Test homepage
curl -o /dev/null -s -w 'Total: %{time_total}s\n' http://127.0.0.1:8000

# Test shop page
curl -o /dev/null -s -w 'Total: %{time_total}s\n' http://127.0.0.1:8000/shop
```

### Database Queries
Enable query logging in `app/Providers/AppServiceProvider.php`:
```php
\DB::listen(function($query) {
    \Log::info($query->sql, $query->bindings);
});
```

## Next Steps After Testing

1. **Populate Real Data**:
   - Add real products via Filament admin
   - Upload product images
   - Create collections/categories
   - Add blog posts

2. **Configure Email**:
   - Set up SMTP for contact form
   - Configure order confirmation emails

3. **SEO Optimization**:
   - Add meta descriptions to pages
   - Create XML sitemap
   - Add structured data

4. **Production Setup**:
   - Configure production database
   - Set up SSL certificate
   - Enable caching
   - Optimize images

5. **Deploy**:
   - Push to production server
   - Run migrations
   - Seed production data
   - Test payment with live Stripe keys

---

**Happy Testing! 🚀**
