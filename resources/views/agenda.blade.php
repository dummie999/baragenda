@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
					</div>
					
					<div class="card-block">
                        <table class="table responsive-table agenda">
							<thead>
								<tr>
									<th>Datum</th>
									<th>Tijd</th>
									<th>Agenda</th>
									<th>Titel</th>
								</tr>
							</thead>
							<tbody>

						@foreach($events as $i => $date)
							@foreach($date as $j => $event)
									<tr>
								<td>{{ $event['start']['carbon']->translatedFormat('l d F')  }}</td>
								<td>{{ $event['start']['carbon']->isstartofday() ? '' : $event['start']['carbon']->translatedFormat('H:i') }}</td>
								<td>{{ $event['calendar'] }}</td>
								<td>{{ $event['summary']  }}</td>
									</tr>
							@endforeach
						@endforeach

							</tbody>
						</table>
					</div>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection
