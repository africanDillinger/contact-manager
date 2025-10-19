<?php include 'db.php'; ?>
<?php include 'includes/auth.php'; ?>
<?php include 'includes/navbar.php'; ?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h3 class="mb-0">📇 Contact Manager</h3>
      <a href="add.php" class="btn btn-light btn-sm">➕ Add Contact</a>
    </div>

    <div class="card-body">
      <!-- Search Bar -->
      <form class="row g-3 mb-4" method="GET">
        <div class="col-md-9">
          <input type="text" name="search" class="form-control" placeholder="Search name or email"
                 value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-primary w-50">Search</button>
          <a href="index.php" class="btn btn-secondary w-50">Reset</a>
        </div>
      </form>

      <!-- Contact Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $where = ($search !== '') ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%'" : '';

            $limit = 5;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;

            $count_result = $conn->query("SELECT COUNT(*) AS total FROM contacts $where");
            $total_rows = $count_result->fetch_assoc()['total'];
            $total_pages = ceil($total_rows / $limit);

            $result = $conn->query("SELECT * FROM contacts $where ORDER BY id DESC LIMIT $limit OFFSET $offset");

            if ($result->num_rows > 0):
              while($row = $result->fetch_assoc()):
            ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this contact?')">Delete</a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5" class="text-center text-muted">No contacts found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav>
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
<script>
Swal.fire({
  title: '✅ Contact Updated!',
  text: 'The contact has been successfully updated.',
  icon: 'success',
  timer: 2000,
  showConfirmButton: false
});
</script>
<?php endif; ?>

</body>
</html>
