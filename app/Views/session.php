<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($session['lesson_title'] ?? 'Session') ?> - <?= $siteName ?? 'SurfManager' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/surf-theme.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/session.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/app/Views/css/form.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <a href="<?= $baseUrl ?>" class="logo">Surf<span>Manager</span></a>
            <nav>
                <a href="<?= $baseUrl ?>/home">Home</a>
                <a href="<?= $baseUrl ?>/lessons">Lessons</a>
                <a href="<?= $baseUrl ?>/sessions" class="active">Sessions</a>
                <a href="<?= $baseUrl ?>/students">Students</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Session Info Card -->
        <div class="session-detail-card">
            <div class="session-header">
                <div class="session-badges">
                    <span class="badge badge-<?= strtolower($session['lesson_level'] ?? 'beginner') ?>">
                        <?= htmlspecialchars($session['lesson_level'] ?? 'Beginner') ?>
                    </span>
                    <span class="badge badge-<?= strtolower($session['status'] ?? 'available') ?>">
                        <?= htmlspecialchars(ucfirst($session['status'] ?? 'Available')) ?>
                    </span>
                </div>
                <h1><?= htmlspecialchars($session['lesson_title'] ?? 'Surf Session') ?></h1>
                <?php if (!empty($session['lesson_description'])): ?>
                    <p class="session-desc"><?= htmlspecialchars($session['lesson_description']) ?></p>
                <?php endif; ?>
            </div>

            <div class="session-details-grid">
                <div class="detail-item">
                    <div class="detail-icon">📅</div>
                    <div class="detail-content">
                        <div class="detail-label">Date & Time</div>
                        <div class="detail-value"><?= date('l, F d, Y', strtotime($session['datetime'])) ?></div>
                        <div class="detail-sub"><?= date('H:i', strtotime($session['datetime'])) ?> (<?= $session['duration'] ?? 60 ?> minutes)</div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">👤</div>
                    <div class="detail-content">
                        <div class="detail-label">Instructor</div>
                        <div class="detail-value"><?= htmlspecialchars($session['coach_name'] ?? 'TBD') ?></div>
                        <div class="detail-sub"><?= htmlspecialchars($session['coach_speciality'] ?? '') ?></div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">📍</div>
                    <div class="detail-content">
                        <div class="detail-label">Location</div>
                        <div class="detail-value"><?= htmlspecialchars($session['location'] ?? 'TBD') ?></div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">👥</div>
                    <div class="detail-content">
                        <div class="detail-label">Availability</div>
                        <div class="detail-value"><?= $session['spots_available'] ?? '?' ?> / <?= $session['max_spots'] ?? '?' ?> spots left</div>
                        <div class="detail-sub"><?= ($session['max_spots'] ?? 0) - ($session['spots_available'] ?? 0) ?> booked</div>
                    </div>
                </div>

                <?php if (!empty($session['requirements'])): ?>
                <div class="detail-item full-width">
                    <div class="detail-icon">📋</div>
                    <div class="detail-content">
                        <div class="detail-label">Requirements</div>
                        <div class="detail-value"><?= htmlspecialchars($session['requirements']) ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="session-price-section">
                <div class="session-price">
                    $<?= number_format($session['price'] ?? 0, 2) ?>
                    <small>per person</small>
                </div>
                <?php if (($session['status'] ?? '') === 'available' && $session['spots_available'] > 0): ?>
                    <a href="<?= $baseUrl ?>/sessions/book/<?= $session['id'] ?>" class="btn btn-primary">Book This Session</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Not Available</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Enrolled Students Section -->
        <div class="enrolled-section">
            <h2>Enrolled Students (<?= count($assignments ?? []) ?>)</h2>
            
            <?php if (!empty($assignments)): ?>
                <div class="assignments-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $assignment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($assignment['student_name'] ?? 'Student #' . $assignment['student_id']) ?></td>
                                    <td><?= htmlspecialchars($assignment['student_email'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge badge-<?= strtolower($assignment['payment_status'] ?? 'pending') ?>">
                                            <?= htmlspecialchars(ucfirst($assignment['payment_status'] ?? 'Pending')) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="<?= $baseUrl ?>/sessions/cancel/<?= $assignment['id'] ?>/<?= $session['id'] ?>" method="POST" class="inline-form">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this student?')">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-students">No students enrolled yet.</p>
            <?php endif; ?>
        </div>

        <!-- Admin Actions -->
        <div class="admin-actions">
            <a href="<?= $baseUrl ?>/sessions/edit/<?= $session['id'] ?>" class="btn btn-secondary">Edit Session</a>
            <form action="<?= $baseUrl ?>/sessions/delete/<?= $session['id'] ?>" method="POST" class="inline-form" onsubmit="return confirm('Delete this session?')">
                <button type="submit" class="btn btn-danger">Delete Session</button>
            </form>
        </div>
    </main>
</body>

</html>
