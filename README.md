Corporate Purchase & Supply Chain System (Multi-Warehouse)

A Laravel REST API implementing a real-world ERP-style purchase workflow:

Purchase Requisition (PR) -> Purchase Order (PO) -> Goods Receive Note (GRN)
with multi-warehouse inventory, partial receiving, and stock ledger auditing.

# Workflow Overview
Warehouse -> Purchase Requisition (PR) -> (Approve) -> Purchase Order (PO) -> (Approve) -> Goods Receive Note (GRN – Partial) -> Stock + Stock Ledger Update

# Key Features

Multi-warehouse stock management

Approval-based PR → PO workflow

Partial GRN with over-receive protection

Transaction-safe inventory update

Stock ledger for audit trail

API-only (UI not required)

# Tech Stack

Laravel 10 / 11

MySQL / PostgreSQL

REST API

FormRequest validation

DB Transactions + lockForUpdate()

Postman for testing

# Core Modules

Master Data: Warehouse, Supplier, Product

PR: Internal demand (warehouse-wise)

PO: Supplier commitment (from approved PR)

GRN: Physical receiving (partial allowed)

Stock: Warehouse-wise balance

Stock Ledger: Inventory history

# Main API Endpoints
POST /api/v1/pr
POST /api/v1/pr/{id}/approve
POST /api/v1/po
POST /api/v1/po/{id}/approve
POST /api/v1/grn
GET  /api/v1/po/{id}/receiving-summary
GET  /api/v1/stocks

# Data Integrity

Approved PR required for PO

Approved PO required for GRN

Over-receive blocked

Stock updated only during GRN

All inventory changes wrapped in DB transaction

# Error Handling

422 -> Validation error

404 -> Resource not found

409 -> Business conflict (invalid state / over-receive)

# Testing

Postman collection included

Standard API response format

#Setup Instructions
git clone <repo-url>
cd cpsc-backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve

#Copy Right
Sharmin Akter
