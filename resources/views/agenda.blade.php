@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
					</div>
					
					<div class="card-block" >
						<div style="display: flex;box-sizing:border-box">
							<div class="" style="display: flex;	flex: none;	flex-direction: column;		width: 256px;	overflow: hidden;	position: relative;">
								Left
							</div>
							<div  style=" flex: 1 1 auto;   overflow: hidden;position: relative;">
								<div class="" style=" display:flex;flex:none">
									Top 
									</div>
								<div style=" flex: 1 1 60%;">
									<div style=" overflow:hidden; align-items: stretch;display:flex;flex:1 1 auto;">
										<div style="height:auto; overflow-y:hidden;flex:none;display:flex;align-items:flex-start;min-width:40px;">
											left small bar
										</div>
										<div class="" style=" overflow-x:auto; overflow-y:scroll; display:flex; align-items:flex-start">
											<div class="p-2 bg-info flex-fill">Flex item 1</div>
											<div class="p-2 bg-info flex-fill">Flex item 2</div>
											<div class="p-2 bg-info flex-fill">Flex item 3</div>
											<div class="p-2 bg-info flex-fill">Flex item 4</div>
											<div class="p-2 bg-info flex-fill">Flex item 5</div>
											<div class="p-2 bg-info flex-fill">Flex item 6</div>
											<div class="p-2 bg-info flex-fill">Flex item 7</div>
										</div>
									</div>
								</div>
							</div>
						</div>
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
						@foreach($date['events'] as $j => $event)
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
