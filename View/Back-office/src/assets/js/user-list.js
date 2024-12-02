let currentPage = 1;
const limit = 10;
let sortField = 'userName';
let sortOrder = 'asc';

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        loadUsers();
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    currentPage++;
    loadUsers();
});

document.querySelectorAll('.sort').forEach(header => {
    header.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        sortField = this.getAttribute('data-sort');
        sortOrder = this.getAttribute('data-order') === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-order', sortOrder);
        loadUsers();
    });
});

function loadUsers() {
    fetch(`get-users.php?page=${currentPage}&limit=${limit}&sort=${sortField}&order=${sortOrder}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#userTable tbody');
            tbody.innerHTML = '';
            data.users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
          <td class="py-1"><img src="../../uploads/${user.photo}" alt="image" /></td>
          <td>${user.userName}</td>
          <td>${user.email}</td>
          <td>${user.age}</td>
          <td>${user.created_at}</td>
          <td>${user.updated_at}</td>
          <td>
            <a href="#" class="btn btn-primary edit-user" data-id="${user.id}" data-username="${user.userName}" data-email="${user.email}" data-age="${user.age}">Edit</a>
            <button class="btn btn-danger delete-user" data-id="${user.id}">Delete</button>
          </td>
        `;
                tbody.appendChild(row);
            });

            // Add event listeners for delete buttons
            document.querySelectorAll('.delete-user').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const id = this.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this user?')) {
                        fetch('delete-user.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: id })
                        })
                            .then(response => response.json())  // Ensure you parse the response as JSON
                            .then(data => {
                                console.log(data);  // Log the response for debugging
                                if (data.success) {
                                    event.target.closest('tr').remove(); // Remove the row dynamically
                                } else {
                                    alert('Error deleting user: ' + (data.message || 'Unknown error')); // Display any message from the server
                                }
                            })
                            .catch(error => {
                                console.error(error);  // Log any errors in the fetch process
                                alert('Error deleting user: ' + error.message);
                            });

                    }
                });
            });

            // Add event listeners for edit buttons
            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-username');
                    const userEmail = this.getAttribute('data-email');
                    const userAge = this.getAttribute('data-age');

                    document.getElementById('editUserId').value = id;
                    document.getElementById('editUserName').value = userName;
                    document.getElementById('editUserEmail').value = userEmail;
                    document.getElementById('editUserAge').value = userAge;

                    $('#editUserModal').modal('show');
                });
            });
        });
}

loadUsers();

document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#userTable tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td:not(.py-1)');
        const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchValue));
        row.style.display = match ? '' : 'none';
    });
});

document.getElementById('editUserForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const id = document.getElementById('editUserId').value;
    const userName = document.getElementById('editUserName').value;
    const userEmail = document.getElementById('editUserEmail').value;
    const userAge = document.getElementById('editUserAge').value;
    const userPhoto = document.getElementById('editUserPhoto').files[0];

    const formData = new FormData();
    formData.append('userName', userName);
    formData.append('email', userEmail);
    formData.append('age', userAge);
    if (userPhoto) {
        formData.append('photo', userPhoto);
    }

    fetch('update-user.php?id=' + id, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User updated successfully');
                loadUsers(); // Reload users to reflect changes
            } else {
                alert('Error updating user: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
});