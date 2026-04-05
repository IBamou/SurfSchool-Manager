# Repository Pattern

## What is a Repository?

A **Repository** is an intermediary layer between the Service/Controller and the Database. It handles all data access operations.

## Structure

```
Controller/Service
       ↓
  Repository
       ↓
    Model (Database Connection)
```

## Example

### Without Repository
```php
// In Service
public function getUser($email) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}
```

### With Repository
```php
// UserRepository.php
class UserRepository {
    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}

// AuthService.php
class AuthService {
    public function __construct(private UserRepository $userRepo) {}
    
    public function login($email) {
        $user = $this->userRepo->findByEmail($email);
    }
}
```

## When to Use

| App Size | Use Repository? |
|----------|----------------|
| Small (< 10 pages) | No |
| Medium (10-50 pages) | Optional |
| Large (50+ pages) | Yes |
| Team project | Yes |

## Benefits

1. **Single Source of Truth** - Queries in one place
2. **Easy Testing** - Mock repositories for unit tests
3. **Code Reuse** - Same query in multiple services
4. **Clean Architecture** - Separation of concerns

## When NOT to Use

- Very small projects
- Learning phase
- One-time scripts

## Files to Create Later

```php
app/Repositories/
├── UserRepository.php    // User data access
├── StudentRepository.php // Student data access
├── SessionRepository.php // Session data access
└── CoachRepository.php   // Coach data access
```

## Learn More

- Repository Pattern (Martin Fowler)
- Clean Architecture (Robert C. Martin)
- Dependency Injection
