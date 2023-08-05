<div class="modal fade modal-flex" id="modal-feature" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">
                    Tambah Transaksi Wakaf
                </h4>
            </div>
            <div class="modal-body">
                <form id="form-feature" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="wakaf">Program Wakaf</label>
                                <select name="wakaf" id="wakaf" style="width: 100%" class="form-control wakaf">
                                    <option value="" disabled>Pilih Program Wakaf</option>
                                    @foreach ($dataWakaf as $item)
                                        <option value="{{ $item->uuid }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="signature">Atas Nama</label>
                                <input type="text" name="signature" id="signature" required
                                    class="form-control form-control-solid" placeholder="cth: Hamba Allah">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="amount">Donasi</label>
                                <input type="text" name="amount" id="amount" required
                                    class="form-control form-control-solid" placeholder="cth: Rp 1.200.000"
                                    oninput="formatRupiah(this)">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control form-control-solid">
                                    <option value="" disabled selected>Pilih</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="cancel">Dibatalkan</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="success">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <input type="hidden" name="hidden_user_uuid" id="hidden_user_uuid">
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
