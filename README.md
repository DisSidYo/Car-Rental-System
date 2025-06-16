# 🚗 Car Rental Website

A full-featured, dynamic web application for a car rental service, simulating a real-world online car booking experience. Built using **JavaScript (with AJAX and jQuery), HTML, CSS**, and **JSON for data persistence**, and deployed on **AWS Elastic Beanstalk**.

## 📦 Project Overview

This project enables users to:
- Browse and filter rental cars by **type** and **brand**
- Search using keywords with **real-time suggestions**
- View **detailed information** about available vehicles
- Make, confirm, or cancel rental **reservations**
- Automatically save form inputs across sessions
- Perform all interactions dynamically using **AJAX**, without full page reloads

## 🚀 Features

### 🔍 Car Browsing and Search
- **Search Box**: Supports real-time suggestions as users type.
- **Filters**: Type (e.g., SUV, Sedan) and brand (e.g., BMW, Ford).
- **AJAX-powered search** with dynamic result updates.

### 🏷️ Car Grid View
- Responsive grid layout showing car image, type, brand, model, year, mileage, fuel type, price/day, and availability.
- Mouseover highlights and click functionality to initiate a reservation.
- Unique car identification via **VIN** (hidden from users).

### 📅 Reservation System
- Users can reserve one car at a time.
- Pre-fills form using **localStorage/sessionStorage** if user leaves page.
- Validates required info: name, phone, email, driver’s license.
- Calculates and displays rental cost (price × days).
- AJAX submission of rental data to backend.

### ✅ Order Confirmation Flow
- Submitting a reservation moves it to **pending** status.
- Confirmation link updates the order status to **confirmed**.
- Car becomes **unavailable** after confirmation.

## 🗃️ Data Persistence

- Car data and reservation data stored in **JSON** files.
- Reservation state retained using **localStorage/sessionStorage**.
- JSON used to simulate a backend database without SQL.

## 💡 Front-End Tech Stack

| Technology     | Purpose                                |
|----------------|----------------------------------------|
| HTML & CSS     | Website structure and styling          |
| JavaScript     | Core interaction & dynamic behavior    |
| AJAX + jQuery  | Asynchronous requests & UI updates     |
| JSON           | Data storage and exchange              |

## ☁️ Deployment

Deployed and tested on **AWS Elastic Beanstalk**  
🔗 [Live Demo URL] *(replace with your actual URL)*

## 🎨 Visual and UX Highlights
- Modern fonts and clean layout
- Appropriate use of colors for clarity and accessibility
- All pages maintain consistent design language
- Real-time feedback and smooth transitions
