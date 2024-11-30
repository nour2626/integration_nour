<?php
session_start();

// Check if the user is authenticated for the back-office
if (!isset($_SESSION['back_office_user'])) {
    header('Location: reauthenticate.php');
    exit();
}

// Rest of your _navbar.php code
?>

<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="http://localhost/Projectweb25/View/Back-office/bindex.php">
        <img src="../assets/images/logo.svg" alt="logo" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="../../bindex.php">
        <img src="../assets/images/logo-mini.svg" alt="logo" />
      </a>
    </div>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-nav">
      <li class="nav-item fw-semibold d-none d-lg-block ms-0">
        <h1 class="welcome-text">Good Morning, <span class="text-black fw-bold">
          <?php echo $_SESSION['back_office_user']['userName']; ?>
        </span></h1>
        <h3 class="welcome-sub-text">Your performance summary this week </h3>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown d-none d-lg-block user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle" src="<?php echo $_SESSION['back_office_user']['photo']; ?>" alt="Profile image">
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <img class="img-md rounded-circle" width="30" height="30" src="<?php echo $_SESSION['back_office_user']['photo']; ?>" alt="Profile image">
            <p class="mb-1 mt-3 fw-semibold">
              <?php echo $_SESSION['back_office_user']['userName']; ?>
            </p>
            <p class="fw-light text-muted mb-0">
              <?php echo $_SESSION['back_office_user']['email']; ?>
            </p>
          </div>
          <a class="dropdown-item" href="../Front-office/profile.php"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile <span class="badge badge-pill badge-danger">1</span></a>
          <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages</a>
          <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
          <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
          <a class="dropdown-item" href="http://localhost/Projectweb25/View/Back-office/blogout.php">
            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out
          </a>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>