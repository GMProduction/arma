@extends('admin.base')

@section('title')
    Data Penjualan
@endsection

@section('content')

    @if (\Illuminate\Support\Facades\Session::has('success'))
        <script>
            swal("Berhasil!", "Berhasil Menambah data!", "success");
        </script>
    @endif

    <section class="m-2">

        <div class="table-container">


            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Data Prediksi</h5>
                <button type="button ms-auto" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalprediksi">Hitung Prediksi Minggu ini <i class='bx bxs-chevron-right'></i>
                </button>
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                <th>
                    #
                </th>
                <th>
                    Minggu Ke
                </th>
                <th>
                    Nama Roti
                </th>
                <th>
                    Hasil Prediksi
                </th>

                <th>
                    Tingkat Kesalahan
                </th>


                <th>
                    Roti di input
                </th>

                <th>
                    Action
                </th>

                </thead>

                @foreach($prediksi as $p)
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            Minggu ke {{ $p->minggu }}
                        </td>
                        <td>
                            {{ $p->barang->nama }}
                        </td>
                        <td>
                            {{ $p->prediksi }}
                        </td>
                        <td>
                            {{ $p->kesalahan }} %
                        </td>
                        <td>
                            {{ $p->masuk }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-id="{{$p->id}}" data-nama="{{$p->barang->nama}}" data-prediksi="{{$p->prediksi}}" data-barang="{{$p->barang->id}}"
                                    data-bs-target="#tambahbarang">Masukan
                            </button>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-qty="{{$p->masuk}}" data-id="{{$p->id}}" data-nama="{{$p->barang->nama}}" data-prediksi="{{$p->prediksi}}" data-barang="{{$p->barang->id}}"
                                    data-bs-target="#editbarang">Ubah
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('{{$p->id}}') ">hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div>


            <!-- Modal Prediksi-->
            <div class="modal fade" id="modalprediksi" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Hitung Prediksi minggu ke - <span
                                    id="mingguprediksi">0</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <input type="hidden" id="barang_predict_id" value="0">
                                <div class="mb-3">
                                    <label for="barang" class="form-label">Roti</label>
                                    <div class="d-flex">
                                        <select id="daftar_barang" class="form-select"
                                                aria-label="Default select example" name="barang">
                                            <option selected>Pilih Roti</option>
                                            @foreach($data as $v)
                                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="mb-4"></div>
                                <a href="#" id="process-predict" class="btn btn-primary">Hitung</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Input-->
            <div class="modal fade" id="tambahbarang" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah <span id="namaBarang"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <input type="hidden" name="id_barang" id="id_barang">
                                <input type="hidden" name="id_predict" id="id_predict">
                                <div class="mb-3">
                                    <label for="prediksi" class="form-label">Hasil Prediksi</label>
                                    <input type="number" name="prediksi" id="prediksi" class="form-control" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" name="qty" id="qty" class="form-control" value="0">
                                </div>

                                <div class="mb-4"></div>
                                <a href="#" id="btn-predict" class="btn btn-primary">Simpan</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="editbarang" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah <span id="edit_namaBarang"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <input type="hidden" name="edit_id_barang" id="edit_id_barang">
                                <input type="hidden" name="id_predict" id="edit_id_predict">
                                <div class="mb-3">
                                    <label for="edit_prediksi" class="form-label">Hasil Prediksi</label>
                                    <input type="number" name="edit_prediksi" id="edit_prediksi" class="form-control" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_qty" class="form-label">Qty</label>
                                    <input type="number" name="edit_qty" id="edit_qty" class="form-control">
                                </div>

                                <div class="mb-4"></div>
                                <a href="#" id="btn-edit-predict" class="btn btn-primary">Simpan</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#daftar_barang').on('change', async function () {
                let idBarang = this.value;
                try {
                    let response = await $.get("/admin/barangmasuk/minggu?id=" + idBarang);
                    let mingguKe = 1;
                    $('#barang_predict_id').val(0);
                    if (response['data'] !== null) {
                        mingguKe = response['data']['minggu'] + 1;
                        $('#barang_predict_id').val(idBarang)
                    }
                    $('#mingguprediksi').html(mingguKe);
                } catch (e) {
                    console.log(e)
                }
            });

            $('#process-predict').on('click', async function (e) {
                e.preventDefault();
                try {
                    let idBarang = $('#barang_predict_id').val();
                    let response = await $.post('/admin/barangmasuk/prediksi', {
                        _token: '{{ csrf_token() }}',
                        id: idBarang
                    });
                    if (response['code'] === 200) {
                        alert("Berhasil Menyimpan Data Prediksi");
                        window.location.reload();
                    } else {
                        alert(response['msg'])
                    }
                } catch (e) {
                    alert("Gagal Menyimpan Data Prediksi")
                }
            });

            $('#tambahbarang').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                let id = button.data('id');
                let nama = button.data('nama');
                let prediksi = button.data('prediksi');

                let barang = button.data('barang');
                var modal = $(this);
                modal.find('#id_predict').val(id);
                modal.find('#prediksi').val(prediksi);
                modal.find('#id_barang').val(barang);
                modal.find('#namaBarang').html(nama);
            });

            $('#editbarang').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                let id = button.data('id');
                let nama = button.data('nama');
                let prediksi = button.data('prediksi');
                let barang = button.data('barang');
                let qty = button.data('qty');
                var modal = $(this);
                modal.find('#edit_id_predict').val(id);
                modal.find('#edit_qty').val(qty);
                modal.find('#edit_prediksi').val(prediksi);
                modal.find('#edit_id_barang').val(barang);
                modal.find('#edit_namaBarang').html(nama);
            });

            $('#btn-predict').on('click', async function (e) {
                e.preventDefault();
                let idBrang = $('#id_barang').val();
                let qty = $('#qty').val();
                let idPredict = $('#id_predict').val();
                try{
                    let response = await $.post('/admin/barangmasuk/tambah',{
                        _token: '{{ csrf_token() }}',
                        id_barang: idBrang,
                        id_predict: idPredict,
                        qty: qty
                    });
                    alert("Berhasil Menambahkan Stok Barang");
                    window.location.reload();
                }catch(e){
                    alert("Gagal Menambahkan Stok Barang");
                    window.location.reload();
                }
            })
            $('#btn-edit-predict').on('click', async function (e) {
                e.preventDefault();
                let idBrang = $('#edit_id_barang').val();
                let qty = $('#edit_qty').val();
                let idPredict = $('#edit_id_predict').val();
                try{
                    let response = await $.post('/admin/barangmasuk/edit',{
                        _token: '{{ csrf_token() }}',
                        id_barang: idBrang,
                        id_predict: idPredict,
                        qty: qty
                    });
                    alert("Berhasil Merubah Stok Barang");
                    window.location.reload();
                }catch(e){
                    alert("Gagal Merubah Stok Barang");
                    window.location.reload();
                }
            })
        });

        function hapus(id) {
            swal({
                title: "Menghapus data?",
                text: "Apa kamu yakin, ingin menghapus data ?!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(async (willDelete) => {
                    if (willDelete) {
                        try {
                            let data = {
                                '_token': '{{ csrf_token() }}'
                            };
                            await $.post('/admin/barangmasuk/hapus/'+id, data);
                            swal(
                                "Berhasil!", "Berhasi Menghapus data!", "success"
                            );
                            window.location.reload()
                        }catch (e) {
                            swal("Gagal!", "Gagal Menghapus data!", "error");
                        }
                    } else {
                        swal("Data belum terhapus");
                    }
                });
        }
    </script>

@endsection
