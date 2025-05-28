<div class="modal fade" id="visitModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="visitForm">
      @csrf
      <input type="hidden" id="visit_id" name="visit_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Doctor Visit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="doctor_name" id="doctor_name" class="form-control mb-2" placeholder="Doctor Name" required>
          <input type="text" name="visit_description" id="visit_description" class="form-control mb-2" placeholder="Visit Description" required>
          <input type="number" name="visit_charges" id="visit_charges" class="form-control mb-2" placeholder="Visit Charges" required>
          <select name="status" id="status" class="form-control">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-success">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
