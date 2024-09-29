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
    <title>Create Leave Request</title>
</head>
<body>
<div class="container mt-5">
    <h1><?php echo $heading; ?></h1>
    <form id="createLeaveForm">
        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="date" class="form-control" id="date_from" name="date_from" required>
        </div>

        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="date" class="form-control" id="date_to" name="date_to" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason for Leave</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter reason for leave" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Leave Request</button>
    </form>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $(document).ready(function() {
        $('#createLeaveForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                date_from: $('#date_from').val(),
                date_to: $('#date_to').val(),
                reason: $('#reason').val(),
                csrfToken: csrfToken
            };

            axios.post('/createLeave', formData)
                .then(response => {
                    alert('Leave request submitted successfully!');
                    window.location.href = '/listLeaves';
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.message) {
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
