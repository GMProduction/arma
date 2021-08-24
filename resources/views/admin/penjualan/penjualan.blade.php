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
                <h5>Data Penjualan</h5>
                <button type="button ms-auto" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#tambahbarang">Masukan Data Penjualan</button>
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
                        Qty
                    </th>

                    <th>
                        Total Penjualan
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
                            {{ $v->minggu }}
                        </td>
                        <td>
                            {{ $v->barang->nama }}
                        </td>
                        <td>
                            {{ $v->qty }}
                        </td>
                        <td>
                            {{ $v->total }}
                        </td>

                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-minggu="{{$v->minggu}}" data-qty="{{$v->qty}}" data-id="{{$v->id}}" data-harga="{{$v->harga}}" data-total="{{$v->total}}"
                                    data-bs-target="#editpenjualan">Ubah</button>
{{--                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('id', 'nama') ">hapus</button>--}}
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
                            <h5 class="modal-title" id="exampleModalLabel">Data Penjualan minggu ke -</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>


                                <div class="mb-3">
                                    <label for="barang" class="form-label">Roti</label>
                                    <div class="d-flex">
                                        <select id="barang-option" class="form-select" aria-label="Default select example" name="barang">
                                            <option selected>Pilih Roti</option>
                                            @foreach($barang as $v)
                                                <option value="{{ $v->id }}" data-harga="{{$v->harga}}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="mb-3">
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" class="form-control" id="qty" value="0">
                                </div>

                                <div class="mb-3">
                                    <label for="total" class="form-label">Total Pendapatan</label>
                                    <input type="text" class="form-control" readonly id="total" value="0">
                                </div>

                                <div class="mb-4"></div>
                                <a href="#" id="btn-save" class="btn btn-primary">Simpan</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="editpenjualan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Data Penjualan minggu ke <span id="edit_minggu">0</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <input type="hidden" id="edit_id_penjualan" value="">
                                <input type="hidden" id="edit_harga" value="=0">
                                <div class="mb-3">
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" class="form-control" id="edit_qty" value="0">
                                </div>
                                <div class="mb-3">
                                    <label for="total" class="form-label">Total Pendapatan</label>
                                    <input type="text" class="form-control" readonly id="edit_total" value="0">
                                </div>

                                <div class="mb-4"></div>
                                <a href="#" id="btn-edit" class="btn btn-primary">Simpan</a>
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
        function getHarga(harga, qty){
            console.log(harga, qty)
            let vHarga = 0;
            let vQty = 0;
            if(harga !== undefined){
                if(harga !== null || qty !== "" ){
                    vHarga = harga;
                    vQty = qty;
                }
            }

            return vHarga * vQty;
        }

        $(document).ready(function() {

            $('#barang-option').on('change', function () {
                let harga = this.options[this.selectedIndex].getAttribute("data-harga");
                $('#total').val(getHarga(harga, $('#qty').val()));

            });

            $('#qty').on('keyup', function () {
                let harga = $('#barang-option').find(':selected').data('harga');
                $('#total').val(getHarga(harga, this.value));
            });
            $('#qty').on('change', function () {
                let harga = $('#barang-option').find(':selected').data('harga');
                $('#total').val(getHarga(harga, this.value));
            });

            $('#editpenjualan').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                let id = button.data('id');
                let harga = button.data('harga');
                let qty = button.data('qty');
                let total = button.data('total');
                let minggu = button.data('minggu');
                var modal = $(this);
                modal.find('#edit_id_penjualan').val(id);
                modal.find('#edit_qty').val(qty);
                modal.find('#edit_harga').val(harga);
                modal.find('#edit_total').val(total);
                modal.find('#edit_minggu').html(minggu);
            });

            $('#edit_qty').on('keyup', function () {
                let harga = $('#edit_harga').val();
                $('#edit_total').val(getHarga(harga, this.value));
            });
            $('#edit_qty').on('change', function () {
                let harga = $('#edit_harga').val();
                $('#edit_total').val(getHarga(harga, this.value));
            });

            $('#btn-save').on('click', async function (e) {
                e.preventDefault();
                let barang = $('#barang-option').val();
                let qty =  $('#qty').val();
                try{
                    await $.post('/admin/penjualan/tambah', {
                        _token: '{{ csrf_token() }}',
                        id: barang,
                        qty: qty
                    })
                    window.location.reload();
                }catch (e) {
                    alert("Gagal Menambahkan Penjualan")
                }
            });
            $('#btn-edit').on('click', async function (e) {
                e.preventDefault();
                let id = $('#edit_id_penjualan').val();
                let qty =  $('#edit_qty').val();
                try{
                    await $.post('/admin/penjualan/edit', {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        qty: qty
                    })
                    window.location.reload();
                }catch (e) {
                    alert("Gagal Mengganti Penjualan")
                }
            });
        });

        function hapus(id, name) {
            swal({
                    title: "Menghapus data?",
                    text: "Apa kamu yakin, ingin menghapus data ?!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Berhasil Menghapus data!", {
                            icon: "success",
                        });
                    } else {
                        swal("Data belum terhapus");
                    }
                });
        }
    </script>

@endsection
