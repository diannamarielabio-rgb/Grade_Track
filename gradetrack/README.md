# GradeTrack – Role-Based Laravel Final Project

## Role Flow

```
Register (Teacher / Student)
        ↓
  Status: PENDING
        ↓
  Admin logs in → Users & Approval → Approve / Reject
        ↓
  Status: APPROVED → User can now log in
```

### Role Permissions
| Feature                  | Admin | Teacher | Student |
|--------------------------|:-----:|:-------:|:-------:|
| Approve / Reject users   | ✅    | ❌      | ❌      |
| Create users manually    | ✅    | ❌      | ❌      |
| View all grades          | ✅    | ❌      | ❌      |
| Add / Edit / Delete grades | ❌  | ✅      | ❌      |
| View own grades          | ❌    | ❌      | ✅      |
| Dashboard with charts    | ✅    | ✅      | ✅      |
| Edit own profile         | ✅    | ✅      | ✅      |

---

## Setup Instructions

### 1. Create a fresh Laravel project and copy files in
```bash
composer create-project laravel/laravel gradetrack
cd gradetrack
# Copy the provided app/, database/, resources/, routes/ folders into this project
```

### 2. Install dependencies
```bash
composer install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=gradetrack
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Register the middleware in bootstrap/app.php
Add to the `withMiddleware` callback in `bootstrap/app.php`:
```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Create the first admin account (via seeder or tinker)
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@school.edu.ph',
    'password' => bcrypt('password'),
    'role'     => 'admin',
    'status'   => 'approved',
]);
```

### 7. Storage link (for profile pictures)
```bash
php artisan storage:link
```

### 8. Serve
```bash
php artisan serve
```

---

## File Structure

```
app/Http/
  Controllers/
    Auth/AuthController.php       ← Login, Register, Logout (role-based redirect)
    AdminController.php           ← Dashboard, User CRUD, Approve/Reject, View all grades
    TeacherController.php         ← Dashboard, Grade CRUD (own records only)
    StudentController.php         ← Dashboard, View own grades (read-only)
    ProfileController.php         ← Shared profile for all roles
  Middleware/
    RoleMiddleware.php             ← Checks role + approved status

app/Models/
  User.php                        ← role, status, initials accessor, helpers
  Grade.php                       ← letter_grade, status, letter_color accessors

database/migrations/
  create_users_table.php          ← role (admin/teacher/student), status (pending/approved/rejected)
  create_grades_table.php         ← student_id, teacher_id, subject, score, semester, school_year

resources/views/
  layouts/  app.blade.php         ← Role-aware sidebar
            auth.blade.php
  auth/     login.blade.php
            register.blade.php    ← Role selection, shows pending-approval notice
  admin/    dashboard.blade.php   ← Stats + charts + pending approvals widget
            users.blade.php       ← Full CRUD + Approve/Reject buttons
            edit-user.blade.php
            grades.blade.php      ← Read-only view of all grades
  teacher/  dashboard.blade.php
            grades.blade.php      ← Add/Edit/Delete grades, pick student from dropdown
            edit-grade.blade.php
  student/  dashboard.blade.php   ← Personal grade stats + chart
            grades.blade.php      ← Filterable read-only grade table
  profile/  show.blade.php
            edit.blade.php        ← Edit info + photo upload

routes/web.php                    ← Grouped by role with middleware('role:...')
```
