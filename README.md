# Discount Demo (SOLID Principles)

This is a demonstration project showcasing a clean, extensible discount system architecture based on **SOLID principles**.
It was built to accompany the article ["When E-Commerce Turns into a Nightmare: Why I Rebuilt Discounts the SOLID Way"](https://medium.com/@oleg_64514/when-e-commerce-turns-into-a-nightmare-why-i-rebuilt-discounts-the-solid-way-6a2476739e5c).

> ⚡ Note: This is a **demo**, not a production-ready package. However, the structure is suitable for integrating into real applications.

---

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/kwidoo/discount-demo.git
    cd discount-demo
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. (Optional) If using Laravel:

    - Copy `.env.example` to `.env`
    - Run:
        ```bash
        php artisan key:generate
        ```

4. (Optional) Run migrations if needed:
    ```bash
    php artisan migrate
    ```

---

## Running the Demo

You can run a local development server:

```bash
php artisan serve
```

By default, the demo API will be available at:

```
http://localhost:8000/api/discounts/calculate
```

Use tools like **Postman** or **cURL** to test discount calculations.

---

## Features

-   Percentage-based and fixed-amount discounts
-   Conditions (authenticated users, promo codes, cart total, product bundles)
-   Strategy pattern for multiple discounts
-   Chain of Responsibility for conditions
-   Value Objects for strong domain modeling (`Money`, `Percentage`)

---

## License

MIT License
