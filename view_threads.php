<?php
// Start the session
session_start();

// Fetch the logged-in user's ID (assuming user session is managed)
$userId = $_SESSION['user_id']; // Or however you get the logged-in user's ID

require_once('../../../../controller/threadcontroller.php');
$threadController = new ThreadC();

// Fetch notifications for the user
$sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
$db = new config();
$conn = $db->getConnexion();
$query = $conn->prepare($sql);
$query->execute(['user_id' => $userId]);
$notifications = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch threads
$threads = $threadController->afficherThreads('public'); // Fetch public threads

// Fetch most popular locations
$popularLocations = $threadController->getMostPopularLocations();

// Handle Like/Dislike
if (isset($_GET['action']) && isset($_GET['thread_id'])) {
    $action = $_GET['action'];
    $threadId = $_GET['thread_id'];
    if ($action == 'like') {
        $threadController->likeThread($userId, $threadId);
    } elseif ($action == 'dislike') {
        $threadController->dislikeThread($userId, $threadId);
    }
    header('Location: view_threads.php');  // Redirect to refresh the page after action
    exit();
}

// Handle search functionality
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $threads = $threadController->rechercherThreads($searchQuery); // Search threads based on the query
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Experiences Forum</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    /* Custom Styles (kept as is) */
    .notification-container {
      background-color: #f8d7da;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .notification-header {
      font-weight: bold;
    }
    .notification-message {
      margin-top: 5px;
    }
    .notification-read {
      color: green;
      cursor: pointer;
    }

    .btn-search {
      background-color: #d9534f;
      color: white;
      padding: 12px 30px;
      font-size: 1.4rem;
      font-weight: bold;
      border: none;
      border-radius: 50px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .btn-search:hover {
      background-color: #c9302c;
      transform: scale(1.05);
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-search:focus {
      outline: none;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
    }

    .search-btn-container {
      text-align: center;
      margin-top: 20px;
    }

    .search-bar {
      display: none;
      margin-top: 20px;
      text-align: center;
    }

    .search-bar input {
      padding: 10px;
      width: 300px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .navbar {
      background-color: #d9534f;
      color: white;
      padding: 15px;
    }

    .navbar a {
      color: white;
      font-weight: bold;
      text-decoration: none;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .content-wrapper {
      background-color: #fdf7f7;
      padding: 20px;
    }

    .most-popular-locations {
      background-color: #d9534f;
      color: white;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .most-popular-locations h3 {
      margin-bottom: 15px;
    }

    .most-popular-locations ul {
      list-style-type: none;
      padding-left: 0;
    }

    .most-popular-locations li {
      font-size: 1.1rem;
      margin-bottom: 8px;
    }

    .footer {
      background-color: #d9534f;
      color: white;
      padding: 10px;
      text-align: center;
    }

    .footer span {
      color: white;
    }

    .thread-item {
      margin-bottom: 20px;
      background-color: #ffe6e6;
      padding: 15px;
      border-radius: 8px;
      border: 1px solid #d9534f;
    }

    .thread-item h4 {
      font-size: 1.5rem;
      font-weight: bold;
      color: #d9534f;
      margin-bottom: 10px;
    }

    .thread-item p {
      font-size: 1rem;
      margin-bottom: 5px;
    }

    .thread-item .location {
      color: #6c757d;
      font-style: italic;
    }

    .like-dislike-buttons {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-top: 10px;
    }

    .like-btn, .dislike-btn {
      background-color: #d9534f;
      color: white;
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      margin: 0 5px;
    }

    .like-btn:hover, .dislike-btn:hover {
      background-color: #c9302c;
    }

    .like-btn:focus, .dislike-btn:focus {
      outline: none;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
    }

    .like-count, .dislike-count {
      margin-left: 10px;
      font-size: 1.2rem;
      color: #d9534f;
    }

    #sortBy {
      background-color: #d9534f;
      color: white;
      padding: 10px 20px;
      font-size: 1rem;
      font-weight: bold;
      border: none;
      border-radius: 50px;
      transition: background-color 0.3s ease, transform 0.3s ease;
      cursor: pointer;
    }

    #sortBy:hover {
      background-color: #c9302c;
      transform: scale(1.05);
    }

    #sortBy:focus {
      outline: none;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
    }

    .sort-container {
      text-align: center;
      margin-top: 30px;
    }

    .thread-item .created-at {
      font-size: 0.9rem;
      color: #6c757d;
      font-style: italic;
    }

    /* Reply Form Styles */
    .reply-form {
      display: none;
      margin-top: 10px;
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .reply-btn {
      background-color: #d9534f;
      color: white;
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .reply-btn:hover {
      background-color: #c9302c;
    }

    .delete-reply-btn {
      background-color: #f44336;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-reply-btn:hover {
      background-color: #d32f2f;
    }

  </style>
</head>
<body>
  <div class="container-scroller">
    <header class="navbar">
      <a href="index.php">Back to Home Page</a>
    </header>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Most Popular Locations Section -->
          <div class="most-popular-locations">
            <h3>Most Popular Locations</h3>
            <ul>
              <?php foreach ($popularLocations as $location): ?>
                <li><strong><?= htmlspecialchars($location['location']) ?></strong>: <?= $location['thread_count'] ?> threads</li>
              <?php endforeach; ?>
            </ul>
          </div>

          <h2 class="text-center" style="color: #d9534f;">Travel Experiences Shared by Users</h2>
          <p class="text-center">Browse through amazing travel stories and experiences shared by others!</p>

          <!-- Sorting Dropdown -->
          <div class="sort-container">
            <label for="sortBy">Sort By:</label>
            <select id="sortBy" onchange="sortThreads()">
              <option value="DESC">Newest First</option>
              <option value="ASC">Oldest First</option>
            </select>
          </div>

          <!-- Button to show search bar -->
          <div class="search-btn-container">
            <button class="btn btn-search" id="searchButton">Search Threads</button>
          </div>

          <!-- Search Bar (hidden initially) -->
          <div class="search-bar" id="searchBar">
            <input type="text" id="searchInput" placeholder="Search for threads..." oninput="searchThreads()">
          </div>

          <!-- Display the threads here -->
          <div id="thread-container" class="mt-4">
            <?php
            if (!empty($threads)) {
              foreach ($threads as $thread) {
                $createdAt = date('F j, Y, g:i a', strtotime($thread['created_at'])); // Format the created_at field
                // Fetch like/dislike stats
                $likeDislikeStats = $threadController->getThreadLikesDislikes($thread['id']);
                echo '<div class="thread-item" data-name="' . htmlspecialchars($thread['name']) . '" data-location="' . htmlspecialchars($thread['location']) . '" data-comment="' . htmlspecialchars($thread['comment']) . '" data-created-at="' . $thread['created_at'] . '">';
                echo '<h4>' . htmlspecialchars($thread['name']) . '</h4>';
                echo '<p class="location"><strong>Location:</strong> ' . htmlspecialchars($thread['location']) . '</p>';
                echo '<p class="comment">' . htmlspecialchars($thread['comment']) . '</p>';
                echo '<p class="created-at"><strong>Posted on:</strong> ' . $createdAt . '</p>';
                
                // Like/Dislike Buttons and Counts
                echo '<div class="like-dislike-buttons">';
                echo '<a href="view_threads.php?action=like&thread_id=' . $thread['id'] . '" class="like-btn">Like</a>';
                echo '<span class="like-count">' . $likeDislikeStats['likes'] . '</span>';
                echo '<a href="view_threads.php?action=dislike&thread_id=' . $thread['id'] . '" class="dislike-btn">Dislike</a>';
                echo '<span class="dislike-count">' . $likeDislikeStats['dislikes'] . '</span>';
                echo '</div>';

                // Reply Button and Form
                echo '<button class="reply-btn" onclick="toggleReplyForm(' . $thread['id'] . ')">Reply</button>';
                echo '<div class="reply-form" id="reply-form-' . $thread['id'] . '">';
                echo '<form class="replyForm" data-thread-id="' . $thread['id'] . '">'; // Use data-thread-id for AJAX
                echo '<textarea name="reply_text" required></textarea>';
                echo '<input type="hidden" name="thread_id" value="' . $thread['id'] . '">';
                echo '<button type="submit">Submit Reply</button>';
                echo '</form>';
                echo '</div>'; // End of reply form


                // Display replies for this thread
                $replies = $threadController->getReplies($thread['id']);
                if (!empty($replies)) {
                    echo '<div class="replies">';
                    foreach ($replies as $reply) {
                        echo '<div class="reply-item" data-reply-id="' . $reply['reply_id'] . '">';
                        echo '<p>' . htmlspecialchars($reply['reply_text']) . '</p>';
                        echo '<p><small>' . $reply['created_at'] . '</small></p>';
                        // Display delete button for each reply
                        echo '<button class="delete-reply-btn" onclick="deleteReply(' . $reply['reply_id'] . ')">Delete</button>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>No replies yet. Be the first to reply!</p>';
                }

                echo '</div>'; // End of thread-item
              }
            } else {
              echo '<p style="color: #d9534f;">No public threads available. Be the first to share your travel experience!</p>';
            }
            ?>
          </div>
        </div>

        <footer class="footer">
          <span>Travel Forum. All rights reserved © <?= date('Y') ?>.</span>
        </footer>
      </div>
    </div>
  </div>

  <script>
    // Existing functions for search, reply form toggling, etc.
    document.getElementById("searchButton").addEventListener("click", function() {
      const searchBar = document.getElementById("searchBar");
      searchBar.style.display = searchBar.style.display === "none" || searchBar.style.display === "" ? "block" : "none";
    });

    function searchThreads() {
      const query = document.getElementById('searchInput').value.toLowerCase();
      const threads = document.querySelectorAll('.thread-item');

      threads.forEach(thread => {
        const name = thread.getAttribute('data-name').toLowerCase();
        const location = thread.getAttribute('data-location').toLowerCase();
        const comment = thread.getAttribute('data-comment').toLowerCase();

        if (name.includes(query) || location.includes(query) || comment.includes(query)) {
          thread.style.display = 'block';
        } else {
          thread.style.display = 'none';
        }
      });
    }

    function toggleReplyForm(threadId) {
      const form = document.getElementById('reply-form-' + threadId);
      form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    }

    // Function to delete reply
    function deleteReply(replyId) {
      const confirmation = confirm("Are you sure you want to delete this reply?");
      if (confirmation) {
        fetch('delete_reply.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ reply_id: replyId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Remove the reply from the UI after successful deletion
            const replyItem = document.querySelector(`.reply-item[data-reply-id="${replyId}"]`);
            replyItem.remove();
          } else {
            alert('Failed to delete the reply. Please try again.');
          }
        });
      }
    }

    function sortThreads() {
      const sortOrder = document.getElementById('sortBy').value;
      const threadsContainer = document.getElementById('thread-container');
      const threads = Array.from(threadsContainer.getElementsByClassName('thread-item'));

      threads.sort((a, b) => {
        const dateA = new Date(a.getAttribute('data-created-at'));
        const dateB = new Date(b.getAttribute('data-created-at'));

        return sortOrder === 'ASC' ? dateA - dateB : dateB - dateA;
      });

      threads.forEach(thread => {
        threadsContainer.appendChild(thread);
      });
    }
  </script>
</body>
</html>
