# VividCart

An **eCommerce platform** built using **Laravel** and **Blade** templating engine. It includes essential eCommerce features such as **authentication**, **authorization**, **cart management**, **wishlists**, **product filtering**, and **reviews**. The project is designed to provide a seamless shopping experience for users and includes **custom helpers**, **toaster messages**, and **AJAX** for enhanced functionality and user interaction.

---

## Features

### **1. Modular Architecture**
- **Separation of Concerns**: The project is organized into modules (e.g., User, Product, Cart, Wishlist) for better maintainability and scalability.
- **Reusable Components**: Each module is self-contained, making it easy to reuse or extend functionality.

### **2. User Authentication & Authorization**
- **User Registration & Login**: Secure user authentication system.
- **Password Reset**: Users can reset their password via email.

### **3. Product Management**
- **Product Listings**: Display products with details like name, description, price, and images.
- **Product Filtering & Sorting**: Filter products by category, price range, latest, etc.
- **Product Reviews**: Users can leave reviews and ratings for products.

### **4. Cart Management**
- **Add/Remove Products**: Users can add or remove products from their cart.
- **Order History**: Users can view their past orders.

### **5. Wishlist**
- **Save Products**: Users can save products to their wishlist for future purchase.
- **Manage Wishlist**: Add or remove products from the wishlist.

### **6. Search Functionality**
- **Global Search**: Search for products by title.

### **7. Toaster Messages**
- **User Feedback**: Display success, error, and info messages using toaster notifications.

### **8. AJAX Integration**
- **Dynamic Updates**: Use AJAX for seamless interactions like adding/removing products from the cart or wishlist without page reloads.

---

## Technologies Used

### **Backend**
- **Framework**: [Laravel](https://laravel.com/)
- **Database**: [MySQL](https://www.mysql.com/)
- **Authentication**: Laravel's built-in authentication system
- **Payment**: [Zarinpal](https://www.zarinpal.com)

### **Frontend**
- **Templating Engine**: [Blade](https://laravel.com/docs/blade)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **AJAX**: For dynamic and seamless user interactions.

### **Testing**
- **PHPUnit**: Comprehensive tests for application functionality.

---

## License
VividCart is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.
