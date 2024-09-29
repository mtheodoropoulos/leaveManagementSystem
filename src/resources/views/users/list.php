
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content=<?php echo $csrfToken; ?>>
    <style>
        .logout-btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="logout-btn-container">
        <button id="logoutButton" class="btn btn-danger">Logout</button>
    </div>
    <h1 class="mt-5"><?php echo "Hello " . htmlspecialchars($loggedInUserName, ENT_QUOTES, 'UTF-8'); ?></h1>
    <h3 class="mt-5"><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></h3>

    <button id="createUserButton" class="btn btn-success mb-3">Create User</button>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Employee Code</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="userTableBody">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->id, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->employeeCode, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->created_at, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm editButton" data-id="<?php echo htmlspecialchars($user->id, ENT_QUOTES, 'UTF-8'); ?>">Edit</button>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    document.getElementById('createUserButton').addEventListener('click', function() {
        window.location.href = '/showCreateUser';
    });

    document.getElementById('logoutButton').addEventListener('click', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('csrfToken', csrfToken);

        axios.post('/logout', formData)
            .then(function(response) {
                if(response.status === 200){
                    window.location.href = '/login';
                }
            })
            .catch(function(error) {
                console.error("Logout failed: ", error);
                alert("Failed to logout.");
            });
    });

    document.querySelectorAll('.editButton').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            window.location.href = `/editUser/${userId}`;
        });
    });
</script>
</body>
</html>
