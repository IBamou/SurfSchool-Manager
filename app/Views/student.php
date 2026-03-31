<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($student['name'] ?? 'Student') ?> - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/students.css">
    <style>
        .student-detail-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 10px 40px var(--shadow);
        }

        .student-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .student-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--ocean-blue), var(--ocean-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .student-info h1 {
            font-size: 1.75rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .student-email {
            color: var(--text-gray);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .enrolled-section {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 10px 40px var(--shadow);
        }

        .enrolled-section h2 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .sessions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sessions-table th,
        .sessions-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .sessions-table th {
            font-weight: 600;
            color: var(--text-gray);
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .session-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        .session-datetime {
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .payment-control {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .payment-control select {
            padding: 0.5rem;
            font-size: 0.85rem;
            min-width: 120px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: var(--white);
            cursor: pointer;
        }

        .payment-control select:focus {
            outline: none;
            border-color: var(--ocean-light);
        }

        .payment-btn {
            padding: 0.5rem 1rem;
            background: var(--ocean-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-btn:hover {
            background: var(--ocean-deep);
        }

        .payment-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .admin-actions {
            margin: 2rem 0;
            display: flex;
            gap: 1rem;
        }

        .no-sessions {
            text-align: center;
            padding: 2rem;
            color: var(--text-gray);
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }

        .toast.success {
            background: #059669;
        }

        .toast.error {
            background: #dc2626;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .student-header {
                flex-direction: column;
                text-align: center;
            }

            .sessions-table {
                overflow-x: auto;
            }

            .payment-control {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/home">Home</a>
                <a href="<?= $baseUrl ?>/lessons">Lessons</a>
                <a href="<?= $baseUrl ?>/sessions">Sessions</a>
                <a href="<?= $baseUrl ?>/students" class="active">Students</a>
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
                                        <select id="payment-<?= $assignment['id'] ?>">
                                            <option value="pending" <?= ($assignment['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="paid" <?= ($assignment['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="refunded" <?= ($assignment['payment_status'] ?? '') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                                        </select>
                                        <button type="button" class="payment-btn" onclick="updatePayment(<?= $assignment['id'] ?>)">Save</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-sessions">
                    <p>No sessions enrolled yet.</p>
                    <a href="<?= $baseUrl ?>/sessions" class="btn btn-primary mt-1">Browse Sessions</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Admin Actions -->
        <div class="admin-actions">
            <a href="<?= $baseUrl ?>/students/edit/<?= $student['id'] ?>" class="btn btn-secondary">Edit Level</a>
            <a href="<?= $baseUrl ?>/students" class="btn btn-outline">← Back to Students</a>
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

        function updatePayment(assignmentId) {
            const select = document.getElementById(`payment-${assignmentId}`);
            const btn = select.nextElementSibling;
            const status = select.value;
            
            btn.classList.add('loading');
            btn.textContent = 'Saving...';
            
            const formData = new FormData();
            formData.append('payment_status', status);
            
            fetch(`${baseUrl}/students/${assignmentId}/payment/<?= $student['id'] ?>`, {
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
