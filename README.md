# Rantari API

Repository ini berisi codebase API untuk Rantari. Perkembangan sementara:

[V] Autentikasi
[V] Product Controller
[V] Order Controller - 50% (kurang fetching, add cart, dan buy)
[X] Chat
[X] Logistics Tracking

Cara mencoba API:
1. Clone repository dengan mendownload repo atau cloning: <code>git clone https://github.com/StanleyJo-37/rantari-api.git</code>
2. cd rantari-api
3. code .
4. php artisan optimize
5. php artisan serve

Routes yang sudah tersedia:
1. POST       api/auth/login -> login user
2. POST       api/auth/register -> registrasi user baru
3. GET|HEAD   api/orders -> get all orders (sementara hanya bisa untuk buyer)
4. GET|HEAD   api/products -> get all products dengan filter dan pagination
5. POST       api/products -> create products
6. GET|HEAD   api/products/{id} -> get product detail
7. PATCH      api/products/{product_id} -> update product
8. DELETE     api/products/{product_id} -> delete product
