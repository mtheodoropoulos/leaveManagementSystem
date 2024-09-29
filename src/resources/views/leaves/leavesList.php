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
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
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

    <button id="createLeaveButton" class="btn btn-success mb-3">Create Leave Request</button>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Date Requested</th>
            <th>Date Approved</th>
            <th>Date From</th>
            <th>Date To</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="leaveTableBody">
        <?php if (!empty($leaves)): ?>
            <?php foreach ($leaves as $leave): ?>
                <tr>
                    <td><?php echo htmlspecialchars($leave->id, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($leave->date_requested, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($leave->date_approved ?? "", ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($leave->date_from, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($leave->date_to, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($leave->status, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($leave->reason, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm editLeaveButton" data-id="<?php echo htmlspecialchars($leave->id, ENT_QUOTES, 'UTF-8'); ?>">Edit</button>
                        <button class="btn btn-danger btn-sm deleteLeaveButton" data-id="<?php echo htmlspecialchars($leave->id, ENT_QUOTES, 'UTF-8'); ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No leave requests found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    document.getElementById('createLeaveButton').addEventListener('click', function() {
        window.location.href = '/showCreateLeave';
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

    document.querySelectorAll('.editLeaveButton').forEach(button => {
        button.addEventListener('click', function() {
            const leaveId = this.getAttribute('data-id');
            window.location.href = `/editLeave/${leaveId}`;
        });
    });

    document.querySelectorAll('.deleteLeaveButton').forEach(button => {
        button.addEventListener('click', function() {
            const leaveId = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formData = {
                leaveId: leaveId,
                csrfToken: csrfToken
            };

            axios.delete(`/deleteLeave/${leaveId}`, {
                data: { csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            })
                .then(response => {
                    window.location.reload();
                    alert('Leave deleted successfully!');
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
