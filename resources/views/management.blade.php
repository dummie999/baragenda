@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-block">
                        @if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
                        @endif
						<br>
						<table class="table responsive-table diensten">
							<thead>
								<tr>
									<th>Instelling</th>
									<th>Waarde</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>


								<tr>
							   <td></td>
							   <td></td>
							   <td></td>
							   <td>


                               </td>
							   <td></td>
								</tr>

							</tbody>
						</table>
                    </div>
					<div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
