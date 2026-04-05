<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($student['name'] ?? 'Student') ?> - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>app/Views/css/students.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>dashboard" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>lessons">Lessons</a>
                <a href="<?= $baseUrl ?>sessions">Sessions</a>
                <a href="<?= $baseUrl ?>students" class="active">Students</a>
                <a href="<?= $baseUrl ?>coaches">Coaches</a>
                <a href="<?= $baseUrl ?>profile">Profile</a>
                <form action="<?= $baseUrl ?>auth/logout" method="POST">
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Student Info Card -->
        <div class="student-detail-card">
            <div class="student-header">
                <div class="student-avatar">🏄</div>
                <div class="student-info">
                    <h1><?= htmlspecialchars($student['name'] ?? 'Unknown') ?></h1>
                    <p class="student-email"><?= htmlspecialchars($student['email'] ?? '-') ?></p>
                    <span class="badge badge-<?= strtolower($student['level'] ?? 'beginner') ?>">
                        <?= htmlspecialchars($student['level'] ?? 'Beginner') ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Edit/Back Buttons -->
        <div class="detail-buttons" style="justify-content: center;">
            <a href="<?= $baseUrl ?>students/edit/<?= $student['id'] ?>" class="btn btn-secondary">Edit</a>
            <a href="<?= $baseUrl ?>students" class="btn btn-outline">← Back to Students</a>
        </div>

        <!-- Enrolled Sessions -->
        <div class="enrolled-section">
            <h2>Enrolled Sessions (<?= count($assignments ?? []) ?>)</h2>
            
            <?php if (!empty($assignments)): ?>
                <table class="sessions-table">
                    <thead>
                        <tr>
                            <th>Session</th>
                            <th>Date & Time</th>
                            <th>Coach</th>
                            <th>Location</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $assignment): ?>
                            <tr id="row-<?= $assignment['id'] ?>">
                                <td>
                                    <div class="session-title"><?= htmlspecialchars($assignment['lesson_title'] ?? 'Session') ?></div>
                                    <div class="session-datetime">
                                        <span class="badge badge-<?= strtolower($assignment['lesson_level'] ?? 'beginner') ?> badge-sm">
                                            <?= htmlspecialchars($assignment['lesson_level'] ?? '') ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <?= date('M d, Y', strtotime($assignment['datetime'] ?? 'now')) ?>
                                    <br>
                                    <small class="text-gray">
                                        <?= date('H:i', strtotime($assignment['datetime'] ?? 'now')) ?> 
                                        (<?= $assignment['duration'] ?? 60 ?>min)
                                    </small>
                                </td>
                                <td><?= htmlspecialchars($assignment['coach_name'] ?? 'TBD') ?></td>
                                <td><?= htmlspecialchars($assignment['location'] ?? 'TBD') ?></td>
                                <td>
                                <div class="payment-control">
                                    <select id="payment-<?= $assignment['id'] ?>" data-original="<?= htmlspecialchars($assignment['payment_status'] ?? 'pending') ?>">
                                        <option value="pending" <?= ($assignment['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="paid" <?= ($assignment['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                    </select>
                                    <button type="button" class="payment-btn" id="pay-btn-<?= $assignment['id'] ?>" onclick="updatePayment(<?= $assignment['id'] ?>)" disabled>Save</button>
                                </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-sessions">
                    <p>No sessions enrolled yet.</p>
                    <a href="<?= $baseUrl ?>sessions" class="btn btn-primary mt-1">Browse Sessions</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const baseUrl = '<?= $baseUrl ?>';
        
        function showToast(message, type = 'success') {
            const existing = document.querySelector('.toast');
            if (existing) existing.remove();
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => toast.remove(), 3000);
        }

        document.querySelectorAll('select[id^="payment-"]').forEach(select => {
            select.addEventListener('change', function() {
                const assignmentId = this.id.replace('payment-', '');
                const btn = document.getElementById(`pay-btn-${assignmentId}`);
                const original = this.dataset.original;
                btn.disabled = (this.value === original);
            });
        });

        function updatePayment(assignmentId) {
            const select = document.getElementById(`payment-${assignmentId}`);
            const btn = select.nextElementSibling;
            const status = select.value;
            
            btn.classList.add('loading');
            btn.textContent = 'Saving...';
            
            const formData = new FormData();
            formData.append('payment_status', status);
            
            fetch(`${baseUrl}/students/payment/${assignmentId}/<?= $student['id'] ?>`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Payment status updated!', 'success');
                    updateBadge(assignmentId, status);
                } else {
                    showToast('Failed to update payment', 'error');
                }
            })
            .catch(error => {
                showToast('Error: ' + error.message, 'error');
            })
            .finally(() => {
                btn.classList.remove('loading');
                btn.textContent = 'Save';
            });
        }

        function updateBadge(assignmentId, status) {
            const select = document.getElementById(`payment-${assignmentId}`);
            const row = document.getElementById(`row-${assignmentId}`);
            const badge = row.querySelector('.session-datetime .badge');
            
            badge.className = `badge badge-${status}`;
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }
    </script>
</body>

</html>
