<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - <?= $siteName ?? 'SurfManager' ?></title>
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
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Students</h1>
            <p>Manage all registered surf students</p>
        </div>
    </section>

    <main class="container">
        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?= $totalStudents ?? 0 ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">🌊</div>
                <div class="stat-number"><?= $beginnerCount ?? 0 ?></div>
                <div class="stat-label">Beginners</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">🏄</div>
                <div class="stat-number"><?= $intermediateCount ?? 0 ?></div>
                <div class="stat-label">Intermediate</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">⭐</div>
                <div class="stat-number"><?= $advancedCount ?? 0 ?></div>
                <div class="stat-label">Advanced</div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-input">
                <input type="text" id="searchInput" placeholder="Search students by name or email..." onkeyup="filterStudents()">
            </div>
            <div class="filters-row">
                <select id="levelFilter" onchange="filterStudents()">
                    <option value="">All Levels</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
        </div>

        <!-- Students Table -->
        <?php if (!empty($students)): ?>
            <div class="students-table">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <?php foreach ($students as $student): ?>
                            <tr class="student-row" data-level="<?= htmlspecialchars($student['level'] ?? '') ?>" data-id="<?= $student['id'] ?>">
                                <td>
                                    <div class="student-name"><?= htmlspecialchars($student['name'] ?? 'Unknown') ?></div>
                                </td>
                                <td>
                                    <div class="student-email"><?= htmlspecialchars($student['email'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <div class="inline-edit">
                                        <select id="level-<?= $student['id'] ?>" data-original="<?= htmlspecialchars($student['level'] ?? 'Beginner') ?>">
                                            <option value="Beginner" <?= ($student['level'] ?? '') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                                            <option value="Intermediate" <?= ($student['level'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                            <option value="Advanced" <?= ($student['level'] ?? '') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                                            <option value="Expert" <?= ($student['level'] ?? '') === 'Expert' ? 'selected' : '' ?>>Expert</option>
                                        </select>
                                        <button class="save-btn" id="btn-<?= $student['id'] ?>" onclick="updateLevel(<?= $student['id'] ?>)" disabled>Save</button>
                                    </div>
                                </td>
                                <td>
                                    <?= date('M d, Y', strtotime($student['created_at'] ?? 'now')) ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= $baseUrl ?>students/<?= $student['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h2>No Students Found</h2>
                <p>No students registered yet.</p>
            </div>
        <?php endif; ?>
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

        document.querySelectorAll('select[id^="level-"]').forEach(select => {
            select.addEventListener('change', function() {
                const studentId = this.id.replace('level-', '');
                const btn = document.getElementById(`btn-${studentId}`);
                const original = this.dataset.original;
                btn.disabled = (this.value === original);
            });
        });

        function filterStudents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const levelFilter = document.getElementById('levelFilter').value;
            const rows = document.querySelectorAll('.student-row');

            rows.forEach(row => {
                const name = row.querySelector('.student-name').textContent.toLowerCase();
                const email = row.querySelector('.student-email').textContent.toLowerCase();
                const level = row.dataset.level;

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesLevel = levelFilter === '' || level === levelFilter;

                if (matchesSearch && matchesLevel) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function updateLevel(studentId) {
            const select = document.getElementById(`level-${studentId}`);
            const btn = select.nextElementSibling;
            const level = select.value;
            const row = document.querySelector(`tr[data-id="${studentId}"]`);
            
            btn.classList.add('loading');
            btn.textContent = '...';
            
            const formData = new FormData();
            formData.append('level', level);
            
            fetch(`${baseUrl}/students/level/${studentId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Level updated!', 'success');
                    row.dataset.level = level;
                    filterStudents();
                } else {
                    showToast('Failed to update level', 'error');
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
    </script>
</body>

</html>
