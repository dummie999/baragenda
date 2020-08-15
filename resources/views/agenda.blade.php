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
									<th>Titel</th>
								</tr>
							</thead>
							<tbody>

						@foreach($eventsPrivate as $i => $event)
								<tr>
                               <td>{{ $event->googleEvent->start->dateTime }}</td>
							   <td>{{ $event->googleEvent->summary  }}</td>
								</tr>
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
