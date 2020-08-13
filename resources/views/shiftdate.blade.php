@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                       	<a class="btn" href="./{{$prev}}">Previous</a>
                        |<div class="btn" style="width:20%">{{$today}}</div>|
                        <a class="btn" href="./{{$next}}">Next</a>
                    </div>
                    <div class="card-block">
                        @if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
                        @endif
						<br>
						<form action="" method='POST'>
							{{ csrf_field() }}
						<table class="table responsive-table diensten">
							<thead>
								<tr>
									<th>Dienst</th>
									<th>Titel</th>
									<th>Tijdstip</th>
									<th>Leden</th>
								</tr>
							</thead>
							<tbody>

						@foreach($shift as $i => $s)
								<tr>
							   <td>{{ $s->shifttype->title }}</td>
							   <td>{{ $s->title }}</td>
							   <td>{{ Carbon\Carbon::parse($s->datetime)->format('H:i') }}</td>
							   <td>
                           @foreach( $s->shiftuser as $j=>$u )
							   {{ $u->info->name   }}
							   {{-- This needs fix comparing date vs today --}}
							   @if ( Carbon\Carbon::parse($s->datetime)->ne($today)) <button type="submit" id="del_shift_user{{$s->id}}_{{$u->id}}" class="link-button" name="del_shift_user[{{$s->id}}]" value="{{$u->id}}">⚡</button>@endif
                           @if ((count($s->shiftuser)-1) > $j)
                                   <br>
                           @endif
                           @endforeach
                               </td>
							   <td></td>
								</tr>
						@endforeach

							</tbody>
						</table>
					</form>
                    </div>
					<div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
