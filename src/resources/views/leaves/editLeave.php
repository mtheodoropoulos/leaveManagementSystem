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
    <title>Edit Leave Request</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4"><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></h1>

    <form id="editLeaveForm">
        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo htmlspecialchars($leave->date_from, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo htmlspecialchars($leave->date_to, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
            <label for="reason">Reason for Leave</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required><?php echo htmlspecialchars($leave->reason, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script>
    document.getElementById('editLeaveForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(this);
        formData.append('csrfToken', csrfToken);

        axios.post(`/updateLeave/<?php echo $leave->id; ?>`, formData)
            .then(function(response) {
                if (response.status === 200) {
                    alert('Leave request updated successfully!');
                    window.location.href = '/listLeaves';
                }
            })
            .catch(function(error) {
                console.error('Error updating leave request: ', error);
                alert('Failed to update leave request.');
            });
    });
</script>
</body>
</html>
