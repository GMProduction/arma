@extends('admin.base')

@section('title')
    Laporan
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
                <h5>Laporan</h5>

            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                    <th>
                        #
                    </th>
                    <th>
                        Nama Roti
                    </th>
                    <th>
                        Minggu (Penjualan)
                    </th>
                    <th>
                        Minggu (Prediksi)
                    </th>

                    <th>
                        Prediksi
                    </th>

                    <th>
                        Kesalahan
                    </th>
                    <th>
                        Masuk
                    </th>

                    <th>
                       Penjualan
                    </th>



                </thead>

                @foreach($data as $v)
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $v->barang->nama }}
                        </td>
                        <td>
                            {{ $v->minggu }}
                        </td>
                        <td>
                            {{ $v->prediksi == null ? '-' : $v->prediksi->minggu  }}
                        </td>
                        <td>
                            {{ $v->prediksi == null ? '-' : $v->prediksi->prediksi  }}
                        </td>
                        <td>
                            {{ $v->prediksi == null ? '-' : $v->prediksi->kesalahan  }}
                        </td>
                        <td>
                            {{ $v->prediksi == null ? '-' : $v->prediksi->masuk  }}
                        </td>
                        <td>
                            {{ $v->qty  }}
                        </td>

                    </tr>
                @endforeach


            </table>

        </div>


        </div>



    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {

        })


    </script>

@endsection
