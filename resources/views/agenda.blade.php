@section('scripts')
	<script src="{{ asset('js/agenda.js') }}" defer></script>
@section('styles')
	<link href="{{ asset('css/agenda.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@extends('base')

@section('content')
    <div class="container" style="min-width: 1280px;">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
					</div>
					<div class="card-block" >


						<div class="border-div ag-header">
							<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
								<div class="container">
									<div class="collapse navbar-collapse" id="navbarSupportedContent">
										<ul class="navbar-nav mr-auto">
											{{--<li class="nav-item"><a class="nav-link" href="#">Vandaag</a></li>--}}
										</ul>
									</div>
								</div>
							</nav>
						</div>
						<div class="border-div ag-topleft">
							<a href={{ route('agenda.edit')}}>
								<div class="ag-create-container">
									<button class="ag-create-button"  >
										<span class="ag-create-span"></span>
										<div class="ag-create-shape"></div>
										<span class="ag-create-shape2">
											<div class="ag-create-shape3">
												<svg width="36" height="36" viewBox="0 0 36 36">
													<path fill="#34A853" d="M16 16v14h4V20z"></path>
													<path fill="#4285F4" d="M30 16H20l-4 4h14z"></path>
													<path fill="#FBBC05" d="M6 16v4h10l4-4z"></path>
													<path fill="#EA4335" d="M20 16V6h-4v14z"></path>
													<path fill="none" d="M0 0h36v36H0z"></path>
												</svg>
											</div>
											<div class="ag-create-text"  >Nieuw</div>
										</span>
									</button>
								</div>
							</a>

						</div>

						<div class="border-div ag-full" style="">
							<div class="border-div ag-left" style="">
								<div class="cal_left">
									<div class="row">
										<div class="col-12">
											<div style="overflow:hidden; padding-left:15px; padding-right:15px; ">
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
															<div id="datetimepicker13" style="border: 1px solid #0000000d"></div>
														</div>
													</div>
												</div>
												<script type="text/javascript">
												var data
													$(function () {
														$('#datetimepicker13').datetimepicker({
															locale:'nl',
															buttons: {showToday: true},
															calendarWeeks: true,
															inline: true,
															format: 'L'
														});
													});
													$( document ).ready(function() {

														$("#datetimepicker13").on("change.datetimepicker", function (e) {
															iso=moment(e.date._d).toISOString();
															console.log(iso)
															try {
																$.post("{{ route('agenda.getdate') }}", {
																	date: iso,
																	"_token": "{{ csrf_token() }}",
																})
																.then(function(response){
																	data=response.data;
																	parseAgenda(data)
																})
															}
															catch (err){
																console.log("error "+err)
															}
														});
													})




												</script>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="border-div ag-right" style=" ">
								<div class="border-div ag-right-main" style= "">
									<div class="border-div ag-right-main-data" style="">
										<div class="border-div data-grid" style="">
											<div class="border-div data-grid-top" style=""">
												<div class="border-div grid-top-filler" style="">

												</div>
												<div class="border-div grid-top-days" style="">
													<div class="border-div top-days-list " style="">
														@foreach(array_values($events) as $i => $date)
															<div class="vertDayColumn border-div ">
																<div class="vertDayColumn-data border-div ">
																	<div id="dayname_{{$i}}" class="vertDayColumn-dayname border-div ">
																		{{$date['carbon']->translatedFormat('D')}}
																	</div>
																	<div id="dayno_{{$i}}" class="vertDayColumn-dayno border-div ">
																		{{$date['carbon']->translatedFormat('d')}}
																	</div>
																</div>
															</div>
														@endforeach
														<div class="p-2 vertDayColumn border-div "></div>
													</div>
													<div  class="grid-top-allday border-div" >
														<div  class="top-allday-data border-div" >
															<div  class="allday-data-list  border-div" >
																@php
																	$loopvar = 0;
																@endphp
																@foreach($events as $i => $date)
																	@foreach($date['events'] as $j => $event)
																		@if($event['shape']['size']>=1)
																			<div style="
																					width:{{ $event['shape']['size_day']*100}}%;
																					top: {{$loopvar}}em;
																					left: {{ ($event['shape']['pos_day'])*100 }}%;"
																				class="allday-data-item border-div" >
																				<div class="allday-data-item-button">
																					<span class="allday-data-item-span">
																						{{$event['summary']}}
																					</span>
																				</div>
																			</div>
																			@php
																				$loopvar ++;
																			@endphp
																		@endif
																	@endforeach
																@endforeach
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="border-div data-grid-bottom" style=" ">
												<div class="border-div grid-bottom-tabs" onload="scrollTo(0.5)" style="">
													<div class="border-div bottom-tabs-time" style="" >
														<div class="border-div tabs-time-list " style="" >
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	00:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	01:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	02:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	03:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	04:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	05:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	06:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	07:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	08:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	09:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	10:00
																</div>
															</div>
															<div class="vertTimeItem vertTimeItemCompact">
																<div class="vertTimeItemFont">
																	11:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	12:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	13:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	14:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	15:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	16:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	17:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	18:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	19:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	20:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	21:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	22:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">
																	23:00
																</div>
															</div>
															<div class="vertTimeItem">
																<div class="vertTimeItemFont">

																</div>
															</div>
														</div>
													</div>
													<div class="border-div bottom-tabs-content" style="">
														<div class="border-div tabs-content-events" style="">
															@foreach(array_values($events) as $i => $date)
																<div id="grid_{{$i}}" class="border-div bottom-tabs-gridcell" style="position:relative">
																	@foreach($date['events'] as $j => $event)
																		@if($event['shape']['size']<1)
																			<div onclick="eventModal({{array_keys($events)[$i]}},{{$j}},data)" class="{{ $event['calendar']==1 ? "event-button" : "event-button2"}}"
																			style="z-index: {{$j+15}};top:
																				@if($event['shape']['pos']<=0.5) {{20/720*24*$event['shape']['pos'] *100}}% {{--20=time;720=total--}}
																				@else {{((40/720)*(24*($event['shape']['pos']-0.5))+(0.5*24*(20/720))) * 100}}% {{--20&40=time;720=total--}}
																				@endif; height:{{$event['shape']['size']*720}}px;">
																				<div class="event-button-data">
																					<div class="event-button-title">
																						{{ $event['summary']   }}
																					</div>
																					<div class="event-button-time">
																						{{ $event['start']['carbon']->translatedFormat('H:i') }}-{{ $event['end']['carbon']->translatedFormat('H:i') }}
																					</div>
																				</div>
																			</div>
																		@endif
																	@endforeach
																</div>
															@endforeach
														</div>
													</div>
														<!-- Modal -->
														<div id="myModal" class="modal fade" role="dialog">
															<div class="modal-dialog">

															<!-- Modal content-->
															<div class="modal-content">
																<div class="modal-header">
																</div>
																<div class="modal-body">
																<div class="row">
																</div>
																<div class="row">
																	<div class="col-1">

																	</div>
																	<div class="col-11">
																		<h3 id="md_summary"></h3>
																	</div>
																</div>
																<div class="row">
																	<div class="col-1">
																	</div>
																	<div class="col-11">
																		<h3 id="md_time"></h3>
																	</div>
																</div>
																<div class="row">
																	<div class="col-1">
																		<div id="test"></div>
																	</div>
																	<div class="col-11">
																		<h3 id="md_summary"></h3>
																	</div>
																</div>
																</div>
																<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																</div>
															</div>

															</div>
														</div>


												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>



						{{-- <table class="table responsive-table agenda">
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
						@if(count($date['events'])<1) <tr><td>{{$date['carbon']->translatedFormat('l d F')}}</td><td></td><td></td><td></td></tr> @endif
						@foreach($date['events'] as $j => $event)
								<tr>
								<td>@if($j==0){{ $event['start']['carbon']->translatedFormat('l d F')  }}@endif</td>
									<td>{{ $event['start']['carbon']->translatedFormat('H:i') }}-{{ $event['end']['carbon']->translatedFormat('H:i') }} </td>
									<td>{{ $event['calendar'] }}</td>
									<td>{{ $event['summary']  }}</td>
								</tr>
						@endforeach
					@endforeach

						</tbody>
					</table> --}}
					</div>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection
