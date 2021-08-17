@extends('admin.base')

@section('title')
    Master Barang
@endsection

@section('content')

    @if (\Illuminate\Support\Facades\Session::has('success'))
        <script>
            swal("Berhasil!", "Berhasil Menambah data!", "success");
        </script>
    @endif
    @if (\Illuminate\Support\Facades\Session::has('failed'))
        <script>
            swal("Gagal!", "Gagal Menambah data!", "error");
        </script>
    @endif

    <section class="m-2">


        <div class="table-container">


            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Data Barang</h5>
                <button type="button ms-auto" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#tambahbarang">Tambah Data</button>
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                    <th>
                        #
                    </th>
                    <th>
                        nama Barang
                    </th>
                    <th>
                        Qty
                    </th>
                    <th>
                        harga
                    </th>

                    <th>
                        Satuan
                    </th>

                    <th>
                        Action
                    </th>

                </thead>

                @foreach($data as $v)
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $v->nama }}
                        </td>
                        <td>
                            {{ $v->qty }}
                        </td>
                        <td>
                            {{ $v->harga }}
                        </td>
                        <td>
                            {{ $v->satuan }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-id="{{$v->id}}" data-qty="{{$v->qty}}" data-nama="{{$v->nama}}" data-harga="{{$v->harga}}" data-satuan="{{$v->satuan}}"
                                    data-bs-target="#editbarang">Ubah</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('{{$v->id}}') ">hapus</button>
                        </td>
                    </tr>
                @endforeach


            </table>

        </div>


        <div>


            <!-- Modal Tambah-->
            <div class="modal fade" id="tambahbarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Master Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/admin/barang/tambah" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="namabarang" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="namabarang" name="nama">
                                </div>


                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga">
                                </div>

                                <div class="mb-3">
                                    <label for="satuan" class="form-label">Satuan</label>
                                    <input type="text" class="form-control" id="satuan" name="satuan">
                                </div>

                                <div class="mb-4"></div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="editbarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Master Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/admin/barang/edit" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="edit_id">
                                <div class="mb-3">
                                    <label for="namabarang" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama">
                                </div>


                                <div class="mb-3">
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" class="form-control" id="edit_qty" name="qty">
                                </div>

                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="edit_harga" name="harga">
                                </div>

                                <div class="mb-3">
                                    <label for="satuan" class="form-label">Satuan</label>
                                    <input type="text" class="form-control" id="edit_satuan" name="satuan">
                                </div>

                                <div class="mb-4"></div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
        $(document).ready(function() {
            $('#editbarang').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                let id = button.data('id');
                let nama = button.data('nama');
                let harga = button.data('harga');
                let satuan = button.data('satuan');
                let qty = button.data('qty');
                var modal = $(this);
                modal.find('#edit_id').val(id);
                modal.find('#edit_nama').val(nama);
                modal.find('#edit_harga').val(harga);
                modal.find('#edit_satuan').val(satuan);
                modal.find('#edit_qty').val(qty);
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
                .then(async (result) => {
                    if (result) {
                        try {
                            let data = {
                                '_token': '{{ csrf_token() }}'
                            };
                            await $.post('/admin/barang/hapus/'+id, data);
                            swal(
                                "Berhasil!", "Berhasi Menghapus data!", "success"
                            );
                            window.location.reload()
                        }catch (e) {
                            swal("Gagal!", "Gagal Menghapus data!", "error");
                        }
                    }
                });
        }
    </script>

@endsection
