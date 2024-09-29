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
</head>
<body>
<div class="container">
    <h1 class="mt-5"><?php echo $heading; ?></h1>
    <form id="loginForm" class="mt-4">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>
<script>
    $(document).ready(function(){
        $('#loginForm').on('submit', function(e){
            e.preventDefault();

            const formData = {
                email: $('#email').val(),
                password: $('#password').val(),
                csrfToken: $('meta[name="csrf-token"]').attr('content')
            };

            axios.post('/login', formData)
                .then(response => {
                    console.log(response);
                    if (response.status === 200) {
                        if (response.data.role === 'manager') {
                            window.location.href = '/listUsers';
                        } else if (response.data.role === 'employee') {
                            window.location.href = '/listLeaves';
                        }
                    }
                })
                .catch(error => {
                    if (error) {
                        alert('Error: ' + error.response.data.message);
                        window.location.href = '/register';
                    } else {
                        alert('An unexpected error occurred.');
                    }
                });
        });
    });
</script>
</body>
</html>

