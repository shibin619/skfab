<div class="col-md-9 p-4">
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Venue List</h4>
        <div>
          <a href="<?= base_url('DozenDreams/addVenue'); ?>" class="btn btn-primary btn-sm">Add New Venue</a>
          <a href="<?= base_url('DozenDreams/exportVenues'); ?>" class="btn btn-success btn-sm">Export Venues</a>
        </div>
      </div>

      <form action="<?= base_url('DozenDreams/importVenues'); ?>" method="post" enctype="multipart/form-data" class="mb-3 d-flex gap-2">
        <input type="file" name="file" class="form-control" required>
        <button type="submit" class="btn btn-info btn-sm">Import Excel</button>
      </form>

      <table id="venueTable" class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
          <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($venues)): ?>
            <?php foreach ($venues as $venue): ?>
              <tr>
                <td><?= $venue->name ?></td>
                <td><?= $venue->location ?></td>
                <td><?= $venue->capacity ?></td>
                <td>
                  <a href="<?= base_url('DozenDreams/editVenue/'.$venue->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="<?= base_url('DozenDreams/deleteVenue/'.$venue->id); ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">No venues found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  // Optional: Initialize DataTables
  document.addEventListener("DOMContentLoaded", function () {
    let table = new DataTable('#venueTable');
  });
</script>
