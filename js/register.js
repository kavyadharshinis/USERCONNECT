$(document).ready(function () {
  function showAlert(msg, type) {
    const a = $('#alert');
    a.removeClass().addClass(type === 'success' ? 'success' : 'error').text(msg).show();
    setTimeout(() => a.fadeOut(), 3500);
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function isStrongPassword(password) {
    // At least 8 characters, uppercase, lowercase, digit, special char
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
  }

  $('#registerForm').on('submit', function (e) {
    e.preventDefault();

    const username = $('#name').val().trim();
    const email = $('#email').val().trim();
    const password = $('#password').val();

    if (!username || !email || !password) {
      showAlert('Please fill all required fields', 'error');
      return;
    }

    if (!isValidEmail(email)) {
      showAlert('Please enter a valid email address', 'error');
      return;
    }

    if (!isStrongPassword(password)) {
      showAlert(
        'Password must be at least 8 characters, include uppercase, lowercase, number, and special character.',
        'error'
      );
      return;
    }

    const payload = {
      username: username,
      email: email,
      password: password
    };

    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: JSON.stringify(payload),
      contentType: 'application/json',
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          showAlert(res.message || 'Registration successful!', 'success');
          $('#registerForm')[0].reset();
          setTimeout(() => {
            window.location.href = 'login.html';
          }, 1200);
        } else {
          showAlert(res.message || 'Registration failed', 'error');
        }
      },
      error: function (xhr, status, err) {
        showAlert('Server error: ' + (xhr.responseText || status), 'error');
      }
    });
  });
});
