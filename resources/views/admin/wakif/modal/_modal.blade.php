<div class="modal fade modal-flex" id="modal-feature" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">
                    Tambah Wakif
                </h4>

            </div>
            <div class="modal-body">
                <form id="form-feature" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required
                                    class="form-control form-control-solid">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required
                                    class="form-control form-control-solid">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="phone">No Handphone</label>
                                <input type="number" name="phone" id="phone" required
                                    class="form-control form-control-solid">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea class="form-control form-control-solid" name="address" id="address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <input type="hidden" id="action" val="add">
                        <input type="submit" class="btn btn-success" value="Simpan" id="btn">
                        <button type="button" class="btn btn-danger" id="cancelModal"
                            data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
