# SPED Merchant Payout System – Backend Assignment

## Project Overview

This project is a **backend implementation of a Merchant Payout System** for SPED's **Own-Delivery model**. The purpose is to demonstrate:

- Understanding of **real-world business logic**  
- Clean **backend architecture**  
- Accurate **financial calculations**  
- Implementing **locking mechanisms** and **data integrity**  
- Handling **refunds, penalties, and adjustments**  

### How the System Works

1. **Customer Orders:** Customers place orders at restaurants using SPED. Orders include VAT, delivery, service, packaging fees, and restaurant-funded discounts.  
2. **SPED Receives Payments:** All customer payments are collected in SPED’s account.  
3. **Merchant Payouts:** SPED calculates payouts **per period** (1-15, 16-end) for **delivered orders only**. Paid periods are **locked**; refunds for paid periods are applied to the **next open period**.  
4. **Adjustments:** Admins can apply refunds, penalties, or compensation, either linked to specific orders or generally. Duplicate adjustments are prevented.  
5. **Final Transfer:** After calculations (gross sales, VAT, discounts, admin commission, adjustments), the **final transfer amount** is sent to the merchant.

---

## Best Practices

- Repository pattern is ideal for clean architecture, but for simplicity, this project uses **service and controller structure**.  
- **Constants** are used for statuses instead of raw strings.  
- **Validation** prevents foreign key and data integrity issues.  
- Duplicate adjustment prevention ensures **accounting integrity**.  
- Reflects **real SPED backend payout logic**.

---

## Database Tables

- **merchants:** Stores restaurant/merchant information.  
- **orders:** Stores order data, fees, discounts, and status.  
- **merchant_payout_periods:** Tracks payout periods and their status (`open` / `paid`).  
- **merchant_payout_items:** Detailed breakdown of orders per payout period (optional).  
- **adjustments:** Stores refunds, penalties, or compensations.  

Foreign key constraints ensure **data integrity**. `adjustments.order_id` is nullable for general adjustments.

---

## API Endpoints

### Routes (API v1)

```php
Route::prefix('v1')->group(function () {
    // Merchant Payout Summary
    Route::get('/merchants/{merchantId}/payout-periods/{periodId}', [PayoutController::class, 'summary']);

    // Lock Payout
    Route::post('/merchants/{merchantId}/payout-periods/{periodId}/lock', [PayoutController::class, 'lockPeriod']);

    // Apply Adjustment
    Route::post('/merchants/{merchantId}/payout-periods/{periodId}/adjustment', [PayoutController::class, 'applyAdjustment']);
});



## API Breakdown

- Merchant Payout Summary:
    GET /api/v1/merchants/1/payout-periods/1

- Lock Payout Period:
    POST /api/v1/merchants/1/payout-periods/1/lock

- Apply Adjustment:
    POST /api/v1/merchants/1/payout-periods/1/adjustment
    Content-Type: application/json
    Body (raw JSON):

    {
        "order_id": 5,
        "type": "refund",
        "amount": -10,
        "vat_amount": 1.4,
        "reason": "Customer complaint"
    }

## How to Run

- composer install/update
- cp .env.example .env
- Change DB Name
- php artisan migrate --seed
= php artisan serve
- Run On postman / Swager or any others platform 
- SQL file in Root directory DB folder 
