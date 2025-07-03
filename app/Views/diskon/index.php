<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Diskon</h2>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDiskonModal">
        Tambah Data
    </button>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if(isset($validation) && $validation->hasError('tanggal')): ?>
        <div class="alert alert-danger"><?= $validation->getError('tanggal') ?></div>
    <?php endif; ?>

    <table class="table table-bordered" id="diskonTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Nominal (Rp)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($diskonList as $diskon): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $diskon['tanggal'] ?></td>
                <td><?= number_format($diskon['nominal'], 0, ',', '.') ?></td>
                <td>
                    <button class="btn btn-success btn-sm btn-edit" 
                        data-id="<?= $diskon['id'] ?>" 
                        data-tanggal="<?= $diskon['tanggal'] ?>" 
                        data-nominal="<?= $diskon['nominal'] ?>"
                        data-bs-toggle="modal" data-bs-target="#editDiskonModal">
                        Ubah
                    </button>
                    <a href="/diskon/delete/<?= $diskon['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Diskon -->
<div class="modal fade" id="addDiskonModal" tabindex="-1" aria-labelledby="addDiskonModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/diskon/store" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDiskonModalLabel">Tambah Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input type="date" class="form-control <?= (isset($validation) && $validation->hasError('tanggal')) ? 'is-invalid' : '' ?>" id="tanggal" name="tanggal" value="<?= old('tanggal') ?>" required>
              <?php if(isset($validation) && $validation->hasError('tanggal')): ?>
                <div class="invalid-feedback">
                    <?= $validation->getError('tanggal') ?>
                </div>
              <?php endif; ?>
          </div>
          <div class="mb-3">
              <label for="nominal" class="form-label">Nominal (Rp)</label>
              <input type="number" class="form-control" id="nominal" name="nominal" value="<?= old('nominal') ?>" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Diskon -->
<div class="modal fade" id="editDiskonModal" tabindex="-1" aria-labelledby="editDiskonModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editDiskonForm" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDiskonModalLabel">Edit Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="edit-id" name="id">
          <div class="mb-3">
              <label for="edit-tanggal" class="form-label">Tanggal</label>
              <input type="date" class="form-control" id="edit-tanggal" name="tanggal" readonly>
          </div>
          <div class="mb-3">
              <label for="edit-nominal" class="form-label">Nominal (Rp)</label>
              <input type="number" class="form-control" id="edit-nominal" name="nominal" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit');
    const editForm = document.getElementById('editDiskonForm');
    const editId = document.getElementById('edit-id');
    const editTanggal = document.getElementById('edit-tanggal');
    const editNominal = document.getElementById('edit-nominal');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            editId.value = button.getAttribute('data-id');
            editTanggal.value = button.getAttribute('data-tanggal');
            editNominal.value = button.getAttribute('data-nominal');
            editForm.action = '/diskon/update/' + editId.value;
        });
    });
});
</script>
<?= $this->endSection() ?>
