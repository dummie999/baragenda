@extends('base')

@section('content')
    <div class="container">

		<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
			<div class="container">
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						@admin <li class="nav-item"><a class="nav-link" href="{{ route('shifts.admin')}}">Diensten</a></li>@endadmin
						@superadmin<li class="nav-item"><a class="nav-link" href=" {{route('management.settings') }} ">Commissie</a></li>@endsuperadmin
					</ul>
				</div>
			</div>
		</nav>
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        @if(!isset($page) || $page == 0)
                            Diensten in de komende week
                        @else
                            Diensten tussen {{ reset($shifts)['carbon']->translatedFormat('l d F') }} en {{ end($shifts)['carbon']->translatedFormat('l d F') }}
                        @endif
						<hr>

						<a href="{{
                        $page == 1 ?
                            route('shifts.admin') :
                            route('shifts.admin.page', ['page' => (isset($page) ? $page-1 : -1)])
                    }}" class="pull-left btn">Previous</a>

                        <a href="{{
                        $page == -1 ?
                            route('shifts.admin') :
                            route('shifts.admin.page', ['page' => (isset($page) ? $page+1 : 1)])
                    }}" class="pull-right btn">Next</a>
                        <br class="clearfix"/>
					</div>
                    <div class="card-block">
                        @if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
                        @endif
						<br>
						<div class="container">
							<form action="" method='POST'>
								{{ csrf_field() }}
								<div class="row">
									<div class="col-sm-12">
										<div class='row' style="display:block; margin: 0 0 0 -5px"
											@foreach($shifttypes as $j => $type)
												<div class="col-xs-1" style="padding:5px;">
													<input id="shift_type{{$j}}" name="input_shifttype[{{$type->id}}][]" type="checkbox"   @if($type->common==true) checked @endif }} data-toggle="toggle" data-on="{{$type->title}}" data-off="{{$type->title}}"  data-onstyle="primary">
												</div>
									
											@endforeach
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div style="padding:10px 5px 10px 0;">
											<input id="excludeWeekend" name="excludeWeekend[]" type="checkbox" data-toggle="toggle" data-on="Weekdays" data-off="Full Week"  data-onstyle="primary" checked>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<div class="input-group date" id="datetimepicker4a" data-target-input="nearest">
												<input name="dt1" type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4a"/>
												<div class="input-group-append" data-target="#datetimepicker4a" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="fa fa-calendar"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<div class="input-group date" id="datetimepicker4b" data-target-input="nearest">
												<input name="dt2" type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4b"/>
												<div class="input-group-append" data-target="#datetimepicker4b" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="fa fa-calendar"></i></div>
												</div>
											</div>
										</div>
									</div>
									<script type="text/javascript">
										$(function () {
											$('#datetimepicker4a').datetimepicker({
												format : 'YYYY-MM-DD',
												allowInputToggle: true,
												defaultDate : moment(),
												minDate: moment(new Date()).add(-1,'days'),
											});
											$('#datetimepicker4b').datetimepicker({
												format : 'YYYY-MM-DD',
												allowInputToggle: true,
												defaultDate : moment(new Date()).add(1,'days'),
												
											});
											$("#datetimepicker4a").on("change.datetimepicker", function (e) {
												$('#datetimepicker4b').datetimepicker('minDate', e.date);
											});
											$("#datetimepicker4b").on("change.datetimepicker", function (e) {
												$('#datetimepicker4a').datetimepicker('maxDate', e.date);
											});
										});									
									</script>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-success" name="">Aanmaken</button>
									</div>
								</div>
								<Br>
							</form>
						</div>
						
						<form action="" method='POST'>
							{{ csrf_field() }}
						<table class="table table-responsive diensten">
							<thead>
								<tr>
									<th>Datum</th>
								@foreach($shifttypes as $i => $type)
									<th>{{$type->title}}</th>
								@endforeach
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($shifts as $i => $shift)
                               <tr>
                               <td><a href="{{ route('shifts.date', ['date' => $i]) }}">{{ $shift['carbon']->translatedFormat('l d F') }}</a></td>
								@foreach($shifttypes as $j => $type)

									<td>
										@isset($shift[$type->title]) 
											@if( $shift[$type->title]==True)
											<button type="submit" id="del_shift_type{{$i}}_{{$type['id']}}" class="link-button" name="del_shift_type[{{$i}}]" value="{{$type['id']}}">✔</button>
											@endif
											@if( $shift[$type->title]==False)
											<button type="submit" id="res_shift_type{{$i}}_{{$type['id']}}" class="link-button" name="res_shift_type[{{$i}}]" value="{{$type['id']}}">⎌</button>
											@endif
										@endisset
									</td>

						@endforeach
									<td>

                                    </td>
								</tr>
						@endforeach
							</tbody>
						</table>
					</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
