// Chờ cho DOM được tải hoàn toàn
document.addEventListener("DOMContentLoaded", function() {

    // Lấy form đăng nhập và đăng ký
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");

    // --- XỬ LÝ FORM ĐĂNG NHẬP ---
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            // Ngăn form gửi đi theo cách truyền thống
            event.preventDefault();
            
            // Xóa các thông báo lỗi cũ
            clearErrors();

            // Lấy giá trị từ các trường
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            let isValid = true;

            // Kiểm tra email
            if (email.trim() === "") {
                showError("email-error", "Vui lòng nhập email của bạn.");
                isValid = false;
            } else if (!isValidEmail(email)) {
                 showError("email-error", "Định dạng email không hợp lệ.");
                isValid = false;
            }

            // Kiểm tra mật khẩu
            if (password.trim() === "") {
                showError("password-error", "Vui lòng nhập mật khẩu của bạn.");
                isValid = false;
            }

            // Nếu tất cả đều hợp lệ
            if (isValid) {
                // *** Gửi dữ liệu lên server (AJAX/Fetch) ở đây ***
                console.log("Đăng nhập hợp lệ:", { email, password });
                alert("Đăng nhập thành công! (Đây là thông báo giả lập)");
                // Ví dụ: window.location.href = "dashboard.html";
            }
        });
    }

    // --- XỬ LÝ FORM ĐĂNG KÝ ---
    if (registerForm) {
        registerForm.addEventListener("submit", function(event) {
            // Ngăn form gửi đi
            event.preventDefault();
            
            // Xóa lỗi cũ
            clearErrors();

            // Lấy giá trị
            const fullname = document.getElementById("fullname").value;
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;

            let isValid = true;

            // Kiểm tra họ tên
            if (fullname.trim() === "") {
                showError("fullname-error", "Vui lòng nhập họ và tên.");
                isValid = false;
            }

            // Kiểm tra email
            if (email.trim() === "") {
                showError("email-error", "Vui lòng nhập email.");
                isValid = false;
            } else if (!isValidEmail(email)) {
                 showError("email-error", "Định dạng email không hợp lệ.");
                isValid = false;
            }

            // Kiểm tra mật khẩu
            if (password.trim() === "") {
                showError("password-error", "Vui lòng nhập mật khẩu.");
                isValid = false;
            } else if (password.length < 6) {
                showError("password-error", "Mật khẩu phải có ít nhất 6 ký tự.");
                isValid = false;
            }

            // Kiểm tra xác nhận mật khẩu
            if (confirmPassword.trim() === "") {
                showError("confirm-password-error", "Vui lòng xác nhận mật khẩu.");
                isValid = false;
            } else if (password !== confirmPassword) {
                showError("confirm-password-error", "Mật khẩu xác nhận không khớp.");
                isValid = false;
            }

            // Nếu tất cả đều hợp lệ
            if (isValid) {
                // *** Gửi dữ liệu đăng ký lên server (AJAX/Fetch) ở đây ***
                console.log("Đăng ký hợp lệ:", { fullname, email, password });
                alert("Đăng ký thành công! (Đây là thông báo giả lập)");
                // Ví dụ: chuyển hướng sang trang đăng nhập
                // window.location.href = "login.html";
            }
        });
    }

    // --- CÁC HÀM HỖ TRỢ ---

    // Hàm hiển thị lỗi
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
        }
    }

    // Hàm xóa tất cả các lỗi
    function clearErrors() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(function(span) {
            span.textContent = "";
        });
    }

    // Hàm kiểm tra định dạng email (Regex cơ bản)
    function isValidEmail(email) {
        const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return regex.test(email);
    }
});