<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/profile.css">
    <style>
        .form-section {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 10px 40px var(--shadow);
        }

        .form-section h2 {
            font-size: 1.25rem;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--ocean-blue);
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .form-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--ocean-blue);
            color: white;
        }

        .btn-primary:hover {
            background: var(--ocean-deep);
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #d1fae5;
            border-color: #10b981;
            color: #059669;
        }

        .alert-error {
            background: #fee2e2;
            border-color: #f87171;
            color: #dc2626;
        }

        .form-actions {
            margin-top: 1rem;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile" class="active">Profile</a>
                <a href="<?= $baseUrl ?>auth/logout">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div id="alert-success" class="alert alert-success"></div>
        <div id="alert-error" class="alert alert-error"></div>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">👤</div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($user['name'] ?? 'Admin') ?></h1>
                    <p class="profile-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <span class="badge badge-admin">Administrator</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-section">
                <h2>Edit Profile</h2>
                <form id="editProfileForm">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="editProfileBtn">Save Changes</button>
                    </div>
                </form>
            </div>

            <div class="form-section">
                <h2>Change Password</h2>
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="profile-details">
            <div class="detail-section">
                <h2>Account Information</h2>
                <div class="detail-row">
                    <span class="detail-label">User ID</span>
                    <span class="detail-value">#<?= $user['id'] ?? '' ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Member Since</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Status</span>
                    <span class="detail-value"><?= ucfirst($user['status'] ?? 'Active') ?></span>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editProfileForm = document.getElementById('editProfileForm');
            const changePasswordForm = document.getElementById('changePasswordForm');
            const editProfileBtn = document.getElementById('editProfileBtn');
            const changePasswordBtn = document.getElementById('changePasswordBtn');
            const alertSuccess = document.getElementById('alert-success');
            const alertError = document.getElementById('alert-error');

            function showAlert(type, message) {
                const alert = type === 'success' ? alertSuccess : alertError;
                alert.textContent = message;
                alert.classList.add('show');
                
                setTimeout(() => {
                    alert.classList.remove('show');
                }, 5000);
            }

            function hideAlerts() {
                alertSuccess.classList.remove('show');
                alertError.classList.remove('show');
            }

            editProfileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                hideAlerts();
                
                const formData = new FormData(editProfileForm);
                formData.append('action', 'updateProfile');
                formData.append('ajax', '1');
                
                editProfileBtn.innerHTML = '<span class="spinner"></span>Saving...';
                editProfileBtn.classList.add('loading');
                
                fetch('<?= $baseUrl ?>profile', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log('Response:', text);
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showAlert('success', data.message);
                        } else {
                            showAlert('error', data.message);
                        }
                    } catch (e) {
                        showAlert('error', 'Server error: ' + text);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    editProfileBtn.innerHTML = 'Save Changes';
                    editProfileBtn.classList.remove('loading');
                });
            });

            changePasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                hideAlerts();
                
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                if (newPassword !== confirmPassword) {
                    showAlert('error', 'New passwords do not match.');
                    return;
                }
                
                if (newPassword.length < 6) {
                    showAlert('error', 'New password must be at least 6 characters.');
                    return;
                }
                
                const formData = new FormData(changePasswordForm);
                formData.append('action', 'changePassword');
                formData.append('ajax', '1');
                
                changePasswordBtn.innerHTML = '<span class="spinner"></span>Changing...';
                changePasswordBtn.classList.add('loading');
                
                fetch('<?= $baseUrl ?>profile', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log('Response:', text);
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showAlert('success', data.message);
                            changePasswordForm.reset();
                        } else {
                            showAlert('error', data.message);
                        }
                    } catch (e) {
                        showAlert('error', 'Server error: ' + text);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    changePasswordBtn.innerHTML = 'Change Password';
                    changePasswordBtn.classList.remove('loading');
                });
            });
        });
    </script>
</body>

</html>