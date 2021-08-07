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
                        Nama Barang
                    </th>
                    <th>
                        Minggu Ke 
                    </th>

                    <th>
                        Hasil Prediksi
                    </th>

                    <th>
                        Barang Masuk
                    </th>

                    <th>
                       Penjualan
                    </th>



                </thead>

                <tr>
                    <td>
                        1
                    </td>
                    <td>
                        Cheese Cake
                    </td>
                    <td>
                       15
                    </td>
                    <td>
                        21
                    </td>
                    <td>
                        21
                    </td>
                    <td>
                      21
                    </td>
                
                    </td>
            
                </tr>

            </table>

        </div>


        <div>



    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {

        })

  
    </script>

@endsection
