<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content=<?php echo $csrfToken; ?>>
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php echo $heading; ?></h1>
    <form id="registerForm" class="mt-4">

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
<script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            const formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                csrfToken: $('meta[name="csrf-token"]').attr('content')
            };

            axios.post('/register', formData)
                .then(response => {
                    alert('Registration successful!');
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
