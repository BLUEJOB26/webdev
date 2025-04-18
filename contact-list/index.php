<?php
require 'functions.php';

// Handle delete
if (isset($_GET['delete'])) {
    deleteContact($_GET['delete']);
    header('Location: index.php');
    exit;
}

// Handle search
$contacts = isset($_GET['search']) ? searchContacts($_GET['search']) : getContacts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .search-add {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-box {
            flex: 1;
            margin-right: 10px;
        }
        .add-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-btn:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #2196F3;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        textarea,
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
            }
            td:before {
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "Name"; }
            td:nth-of-type(2):before { content: "Phone"; }
            td:nth-of-type(3):before { content: "Email"; }
            td:nth-of-type(4):before { content: "Actions"; }
            .action-btns {
                display: flex;
                justify-content: space-around;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact List</h1>
        
        <div class="search-add">
            <div class="search-box">
                <form method="GET" action="index.php">
                    <input type="text" name="search" placeholder="Search contacts..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Search</button>
                    <?php if (isset($_GET['search'])): ?>
                        <a href="index.php">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
            <button class="add-btn" onclick="openModal()">Add New Contact</button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No contacts found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td class="action-btns">
                                <button class="action-btn edit-btn" onclick="openModal(<?php echo $contact['id']; ?>)">Edit</button>
                                <button class="action-btn delete-btn" onclick="if(confirm('Are you sure?')) window.location.href='index.php?delete=<?php echo $contact['id']; ?>'">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Add/Edit Modal -->
    <div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add New Contact</h2>
            <form id="contactForm" action="save_contact.php" method="POST">
                <input type="hidden" id="contactId" name="id" value="">
                <div class="form-group">
                    <label for="name">Name*</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number*</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address*</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="birthday">Birthday</label>
                    <input type="date" id="birthday" name="birthday">
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="submit-btn">Save Contact</button>
            </form>
        </div>
    </div>
    
    <script>
        function openModal(id = null) {
            const modal = document.getElementById('contactModal');
            const form = document.getElementById('contactForm');
            const title = document.getElementById('modalTitle');
            
            if (id) {
                // Fetch contact data and populate form
                fetch(`get_contact.php?id=${id}`)
                    .then(response => response.json())
                    .then(contact => {
                        document.getElementById('contactId').value = contact.id;
                        document.getElementById('name').value = contact.name;
                        document.getElementById('phone').value = contact.phone;
                        document.getElementById('email').value = contact.email;
                        document.getElementById('address').value = contact.address || '';
                        document.getElementById('birthday').value = contact.birthday || '';
                        document.getElementById('notes').value = contact.notes || '';
                        title.textContent = 'Edit Contact';
                        modal.style.display = 'block';
                    });
            } else {
                // Reset form for new contact
                form.reset();
                document.getElementById('contactId').value = '';
                title.textContent = 'Add New Contact';
                modal.style.display = 'block';
            }
        }
        
        function closeModal() {
            document.getElementById('contactModal').style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('contactModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>