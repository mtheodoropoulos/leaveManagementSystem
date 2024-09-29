<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="<?php echo $csrfToken; ?>">
    <title>Edit User</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4"><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></h1>

    <form id="editUserForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
            <label for="employeeCode">Employee Code</label>
            <input type="text" class="form-control" id="employeeCode" name="employeeCode" value="<?php echo htmlspecialchars($user->employeeCode, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script>
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(this);
        formData.append('csrfToken', csrfToken);

        axios.post(`/updateUser/<?php echo $user->id; ?>`, formData)
            .then(function(response) {
                if (response.status === 200) {
                    alert('User updated successfully!');
                    window.location.href = '/users'; // Redirect to user list page after success
                }
            })
            .catch(function(error) {
                console.error('Error updating user: ', error);
                alert('Failed to update user.');
            });
    });
</script>
</body>
</html>
