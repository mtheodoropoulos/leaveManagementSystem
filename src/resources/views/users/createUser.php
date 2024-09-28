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
    <title>Create User</title>
</head>
<body>
<div class="container mt-5">
    <h1><?php echo $heading; ?></h1>

    <form id="createUserForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label for="employeeCode">Employee Code</label>
            <input type="number" class="form-control" id="employeeCode" name="employeeCode" placeholder="Enter employee code" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $(document).ready(function() {
        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                employeeCode: $('#employeeCode').val(),
                csrfToken: csrfToken
            };

            axios.post('/createUser', formData)
                .then(response => {
                    alert('Create User successfully!');
                    window.location.href = '/login';
                })
                .catch(error => {
                    if (error) {
                        alert('Error: ' + error.response.data.message);
                    } else {
                        alert('An unexpected error occurred.');
                    }
                });
        });
    });
</script>
</body>
</html>
