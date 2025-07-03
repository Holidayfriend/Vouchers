# 🎟️ Vouchers Software Documentation

## 📖 Overview
**Vouchers** is a user-friendly software solution for hotels to create, manage, and integrate customizable vouchers for guests.  
The platform empowers super admins to oversee hotels, while hotel administrators can generate branded vouchers, manage transactions, apply discounts, and embed vouchers seamlessly on their websites. Guests benefit from an intuitive experience, receiving QR codes for voucher redemption.

---

## 📦 Features and Modules

### 1. 👨‍💼 Super Admin Management
- **Add Hotels**: Super admins can register new hotels by providing details like:
  - Hotel name
  - Location
  - Contact information
- **Manage Hotels**: Edit, update, or deactivate hotel accounts for centralized control.

---

### 2. 🎨 Voucher Creation and Customization
- **Add Voucher**: Create vouchers with:
  - Title, description, price, and validity period.
- **Customization Options**:
  - Color: Match the voucher's appearance to hotel branding.
  - Font Family: Align fonts with hotel typography.
- **Categories**: Organize vouchers into categories for easy filtering (e.g., Room Stay, Spa, Dining).

---

### 3. 💸 Promo Codes and Discounts
- **Promo Codes**: Create discounts for guests during checkout.
  - Example: `SUMMER25` for a 25% discount.
- **Discount Application**: Automatically calculate and apply valid discounts.

---

### 4. 💳 Payment and Transaction Details
- **Payment Integration**:
  - PayPal for secure purchases.
- **Voucher Types**:
  - **Internal Voucher**: Includes PayPal integration.
  - **Simple Voucher**: No payment integration; used internally or for promotions.
- **Transaction Details**:
  - Each purchase generates a record with:
    - Transaction ID, amount, date, promo code used, and discount applied.
  - Accessible via the admin dashboard.

---

### 5. 📱 QR Code Generation
- Unique QR codes are generated for every purchased voucher.
- Delivered via email for redemption at the hotel.

---

### 6. 📊 Analytics and Reporting
- **Google Analytics Tracking**: Monitor voucher performance and user interactions.
- **Revenue and Transaction Reports**: View detailed insights on:
  - Voucher sales, transaction details, revenue, and promo code usage.
- **Filtering**:
  - Filter by categories, date, or other criteria.

---

### 7. 🌐 Website Integration
- **Iframe Embed**: Generate iframe codes for hotel administrators to display vouchers on their websites.
- Ensures a seamless guest experience for browsing and purchasing.

---

### 8. 📧 Email Notifications
- Automated emails are sent to purchasers with:
  - Voucher details (title, price, discount applied).
  - Unique QR code for redemption.
  - Instructions for using the voucher at the hotel.

---

## 🚀 How to Get Started

- 🛠️ [Developer Setup Guide](docs/dev-setup.md)

---

## 📘 Documentation

- 📖 [User Guide](docs/user-setup.md) – for hoteliers
- 🛠️ [Developer Guide](docs/dev-setup.md) – for tech teams
- 🔍 [FAQ](docs/faq.md)

---

## 🤝 Contribute

We welcome contributions – whether it’s a bug report, a feature request or a pull request.

- 📥 [How to contribute](CONTRIBUTING.md)
- 🧩 Good first issues will be marked as such
- 🔄 Feature suggestions are welcome via GitHub Issues

---

## 📃 License

This project is licensed under the [GNU AGPLv3 License](license).  
That means: You can use, modify and share it freely – as long as your modified version stays open as well.

---

## 🔗 Additional Notes
- Ensure Google Analytics and PayPal are configured correctly for full functionality.
- Regularly review transaction and revenue reports to optimize voucher offerings.

---

Made with ❤️ by [Weihrerhof Hotel](https://www.weihrerhof.com)  
More at [holidayfriend.solutions](https://holidayfriend.solutions)
Contact us at support@holidayfriend.solutions

