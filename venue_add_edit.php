<div class="col-md-9 p-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4><?= !empty($venue) ? 'Edit Venue' : 'Add New Venue' ?></h4>
      <form action="<?= base_url('DozenDreams/' . (!empty($venue) ? 'updateVenue/' . $venue->id : 'saveVenue')); ?>" method="post">
        <div class="mb-3">
          <label class="form-label">Venue Name</label>
          <input type="text" name="name" class="form-control" required value="<?= !empty($venue) ? $venue->name : '' ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" required value="<?= !empty($venue) ? $venue->location : '' ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Capacity</label>
          <input type="number" name="capacity" class="form-control" required value="<?= !empty($venue) ? $venue->capacity : '' ?>">
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-success"><?= !empty($venue) ? 'Update' : 'Add' ?></button>
          <a href="<?= base_url('DozenDreams/venueList'); ?>" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
