$(document).ready(function() {
  $('#loginForm').on('submit', function(e) {
    e.preventDefault();

    const email = $('#email').val().trim();
    const password = $('#password').val();

    if (!email || !password) {
      showAlert('Please fill all required fields', 'error');
      return;
    }

    const payload = {
      email: email,
      password: password
    };

    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: JSON.stringify(payload),
      contentType: 'application/json',
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          localStorage.setItem('user', JSON.stringify({
            username: res.user.username,
            email: res.user.email,
            token: res.token
          }));
          showAlert(res.message, 'success');
          setTimeout(() => window.location.href = 'profile.html', 1200);
        } else {
          showAlert(res.message || 'Login failed', 'error');
        }
      },
      error: function(xhr, status, err) {
        showAlert('Server error: ' + (xhr.responseText || status), 'error');
      }
    });
  });

  $('#registerLink').on('click', function(e) {
    e.preventDefault();
    window.location.href = 'register.html';
  });

  function showAlert(msg, type) {
    const a = $('#alert');
    a.removeClass().addClass(type === 'success' ? 'success' : 'error').text(msg).show();
    setTimeout(() => a.fadeOut(), 3500);
  }
});
