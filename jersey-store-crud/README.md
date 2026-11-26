# Jersey Store - E-Commerce Website

Jersey Store is a PHP and MySQL based e-commerce website for buying football jerseys online. It provides a customer storefront, user authentication, shopping cart, checkout, orders, reviews, and an admin dashboard.

## Features
**Customer:** registration & login, browse by category, product details, cart (add/update/remove), checkout, Cash on Delivery, eSewa online payment, order history, product reviews, profile menu with username.

**Admin:** dashboard, add/edit/delete products (with image upload), view customer orders and update status, view/delete reviews. Admin uses the **same login page** as customers.

## Categories
Club, National Team, Retro, Training, Goalkeeper

## Technologies Used
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend:** PHP 8.x, MySQL (PDO)
- **Tools:** XAMPP (Apache + MySQL), VS Code

## Project Structure
```
jersey-store-crud/
├── admin/            index, products, product_add/edit/delete, orders, reviews, logout
├── assets/           css/main.css, js/main.js
├── database/         schema.sql
├── includes/         db.php, auth.php, header.php, footer.php, product_card.php
├── payment/          config.php, esewa_initiate/success/failure.php
├── uploads/products/ product images
└── index, products, product, register, login, logout, cart, cart_update,
    add_to_cart, checkout, orders, review_add (.php)
```

## Installation (XAMPP)
1. Copy the `jersey-store-crud` folder into `C:\xampp\htdocs\`
2. Start **Apache** and **MySQL** in the XAMPP Control Panel
3. Open http://localhost/phpmyadmin and **import** `database/schema.sql` (it creates the `jersey_store_crud` database, tables, default admin and 12 sample jerseys)
4. Check `includes/db.php` (defaults: host `localhost`, user `root`, empty password)
5. Open http://localhost/jersey-store-crud/

If the folder name or port differs, update `BASE_URL` in `includes/db.php` and the success/failure URLs in `payment/config.php`.

## Admin Login
Use the normal login page: **admin / admin** -> redirected to the Admin Dashboard. Change this before any real deployment.

## eSewa Payment (test environment)
Config: `payment/config.php` (test product code `EPAYTEST`, public test secret key).
Flow: Checkout -> eSewa -> payment -> return -> signature + status verification -> order marked paid.
PHP **cURL** must be enabled (`extension=curl` in php.ini). Replace test credentials/URLs for production and never commit real secret keys.

## Testing checklist
Register -> login -> browse -> add to cart -> update cart -> checkout (COD and eSewa) -> order history -> logout -> login as admin -> product CRUD -> orders/reviews.

## Notes
Academic/development project. Use HTTPS in production. Sample product images are generated SVG placeholders - upload your own through Admin -> Products.

## Author
BScCSIT academic e-commerce project built with PHP, MySQL, Bootstrap, HTML, CSS and JavaScript.
