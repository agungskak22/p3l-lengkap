<html>
<head>
    <title>{{ $transaction->id_transaction }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
	<style>
    .container {
		width: 600px;
		margin: 0 auto;
		padding: 5px 10px;
	}

    .text-xs-center {
        text-align: center;
    }

	.header-section .image {
		float: left;
        height: 125px;
	}

	.header-section .title {
		text-align: center;
		line-height: 20px;
	}

	.description-section h3 {
		text-align: center;
	}

	.description-section .date-section {
		float: right;
	}

	.description-section .date-section tbody tr {
		text-align: left;
	}

    .general-section {
        width: 100%;
    }

    .general-section .box {
        width: 100%;
        text-align: left;
    }

	.table-section table {
		width: 100%;
		text-align: left;
		border: 1px solid black;
		border-collapse: collapse;
	}

	.table-section table thead tr th {
		border: 1px solid black;
	}

	.table-section table tbody tr td {
		border: 1px solid black;
	}

    .sparepart-details {
        width: 100%;
        text-align: left;
        
    }

    .sparepart-details tbody tr {
        width: 100%;
    }

    .service-details {
        width: 100%;
        text-align: left;
    }

    .service-details tbody tr {
        width: 100%;
    }
    .sub-title{
        font-weight: bold !important;
    }
    .border_bottom_upper{
        border-bottom:1pt solid black;
        border-top:1pt solid black;
        
    }
    .col-1{
        width: 20%;
    }
    .col-2{
        width: 30%;
    }
    .col-3{
        width: 25%;
    }
    .col-4{
        width: 20%;
    }
    .col-5{
        width: 10%;
    }
   
	</style>
</head>
<body>
	<div class="container">
		<div class="header-section">
			<div class="image">
				<img src="https://s3.us-east-2.amazonaws.com/pilatix-api-clubs/AtmaAutoLogo.png" alt="">
			</div>
			<div class="title">
				<div><h1>ATMA AUTO</h1></div>
				<div>MOTORCYCLE SPAREPARTS AND SERVICES</div>
				<div>
					Jl. Babarsari No.43 Yogyakarta 552181 <br>
					Telp. (0274) 487711 <br>
					http://www.atmaauto.com
				</div>
			</div>
		</div>

        <hr>
        <div class="text-xs-center sub-title">
            SURAT PERINTAH KERJA   
        </div>
        <hr>
        
		<div class="description-section">
			<div class="date-section">
                <div>{{ date('d-m-Y H:i', strtotime($transaction->transaction_date)) }}</div>
			</div>
        </div>
        
        <div>
            <!-- <h3>SS-240119-141</h3> -->
            <h3>{{ $transaction->id_transaction }}</h3>

        </div>

        <div class="general-section">
            <table class="box">
                <tbody>
                    <tr>
                        <th>Cust</th>
                        <td>{{ $transaction->customer_name }}</td>
                        <th>CS</th>
                        @foreach ($employee as $item)
                            @if ($item->id_role == 2 && $item->id_employee == $detail->id_employee)
                                <td>{{ $item->name }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $transaction->customers->customer_phone_number }}</td>

                        <th>Montir</th>
                        <td>
                        @foreach ($employee as $item)
                            @if ($item->id_role == 4 && $item->id_employee == $detail->id_employee)
                                <td>{{ $item->name }}</td>
                            @endif
                        @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Motor</th>
                        <td>
                            {{ 
                                $transaction->detail_services()->motorcycles->motorcycle_types->motorcycle_brands>motorcycle_brand_name . ' ' .
                                $transaction->detail_services()->motorcycles->motorcycle_types->motorcycle_type_name . ' ' .
                                $transaction->detail_services()->motorcycles->license_number
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>                    
        <hr>
        <div class="text-xs-center sub-title">
            SPAREPART   
        </div>
        <hr>
        <br>                       
        <div>
            <table class="sparepart-details border_bottom_upper">
                <thead>
                    <tr>
                        <th class="col-1">Kode</th>
                        <th class="col-2">Nama</th>
                        <th class="col-3">Merk</th>
                        <th class="col-4">Rak</th>
                        <th class="col-5">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->detail_spareparts as $item)
                        <tr>
                            <td>{{ $item->id_sparepart }}</td>
                            <td>{{ $item->spareparts->sparepart_name }}</td>
                            <td>{{ $item->spareparts->sparepart_brand_name }}</td>
                            <td>{{ $item->spareparts->placement }}</td>
                            <td style="text-align:right;">{{ $item->transaction_total + $item->transaction_discount}}</td>
                        </tr>
                    @endforeach
              
                </tbody>
            </table>
        </div>
        <div>
            <table class="sparepart-details border_bottom_upper">
                <thead>
                    <tr>
                        <th class="col-1"></th>
                        <th class="col-2"></th>
                        <th class="col-3"></th>
                        <th class="col-4"></th>
                        <th class="col-5"></th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;">{{$transaction->detail_spareparts->sum('detail_sparepart_amount')}}</td> 
                    </tr>
              
                </tbody>
            </table>
        </div>

        <hr>
        <div class="text-xs-center sub-title">
            SERVICE
        </div>
        <hr>
        <br>                    
        <div>
            <table class="service-details border_bottom_upper">
                <thead>
                    <tr>
                        <th class="col-1">Kode</th>
                        <th class="col-2">Nama</th>
                        <th class="col-3"></th>
                        <th class="col-4"></th>
                        <th class="col-5">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->detail_transaction_service as $item)
                        <tr>
                            <td>{{ $item->id_service }}</td>
                            <td>{{ $item->service->name_service }}</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right;">{{ $item->amount_transaction_service }}</td>
                        </tr>
                    @endforeach
           
                </tbody>
            </table>
        </div>
        <div>
            <table class="service-details border_bottom_upper">
                <thead>
                    <tr>
                        <th class="col-1"></th>
                        <th class="col-2"></th>
                        <th class="col-3"></th>
                        <th class="col-4"></th>
                        <th class="col-5"></th>
    
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;">{{$transaction->detail_transaction_service->sum('amount_transaction_service')}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
	</div>
</body>
</html>