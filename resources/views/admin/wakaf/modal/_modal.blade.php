<div class="modal fade modal-flex" id="modal-feature" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">
                    Tambah Wakaf
                </h4>

            </div>
            <div class="modal-body">
                <form id="form-feature" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="title">Judul Wakaf</label>
                                <input type="text" name="title" id="title" required
                                    class="form-control form-control-solid" placeholder="cth: Membangun Menara Masjid">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="target">Target</label>
                                <input type="text" name="target" id="target" required
                                    class="form-control form-control-solid" placeholder="cth: Rp 1.200.000"
                                    oninput="formatRupiah(this)">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="thumbnail">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail"
                                    class="form-control form-control-solid">
                                <span id="thumbnail-description"></span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control form-control-solid" name="description" id="description"></textarea>
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
