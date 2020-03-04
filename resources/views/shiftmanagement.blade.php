@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        @if(!isset($page) || $page == 0)
                            Diensten in de komende week
                        @else
                            Diensten tussen {{ reset($shifts)['carbon']->format('l d F') }} en {{ end($shifts)['carbon']->format('l d F') }}
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
						ANGULAR? AJAX?
						<form action="" method='POST'>
						 {{ csrf_field() }}
						<div style="display:flex;">
						@foreach($shifttypes as $j => $type)
						<div style="padding:5px;">
							<input id="shift_type{{$j}}" name="input_shifttype[{{$type->id}}][]" type="checkbox"   @isset($shifttypes) checked @endisset }} data-toggle="toggle" data-on="{{$type->title}}" data-off="{{$type->title}}"  data-onstyle="primary">
						</div>
						
						@endforeach
						</div>
						<div style="padding:5px;">
							<input id="excludeWeekend" name="excludeWeekend[]" type="checkbox" data-toggle="toggle" data-on="Weekdays" data-off="Full Week"  data-onstyle="primary" checked>
						</div>
						<div class="container">
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
										});
										$('#datetimepicker4b').datetimepicker({
											format : 'YYYY-MM-DD',
                                            allowInputToggle: true,
                                            defaultDate : moment(),
										});
									});
								</script>
							</div>
						</div>

						<input type="submit" value="Aanmaken">
						</form>

						{{-- <form action="" type='POST'>
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
                               <td><a href="{{ route('shifts.date', ['date' => $i]) }}">{{ $shift['carbon']->format('l d F') }}</a></td>
						@foreach($shifttypes as $j => $type)

									<td>
									<input id="shift_id{{$i}}{{$j}}" name="[{{$i}}][{{$j}}][]" type="checkbox" data-toggle="toggle" data-on="" data-off=""  data-onstyle="primary"   @isset($shift[$i][$j]) checked @endisset }}  >
									</td>

						@endforeach
									<td>

                                    </td>
								</tr>
						@endforeach
							</tbody>
						</table>
                    </div>
					<div class="card-footer">
                        <a href="{{
                        $page == 1 ?
                            route('shifts') :
                            route('shifts.page', ['page' => (isset($page) ? $page-1 : -1)])
                    }}" class="pull-left btn">Previous</a>

                        <a href="{{
                        $page == -1 ?
                            route('shifts') :
                            route('shifts.page', ['page' => (isset($page) ? $page+1 : 1)])
                    }}" class="pull-right btn">Next</a>
					<input type="submit" class="btn btn-primary float-right" value="Toepassen">
                        <br class="clearfix"/>


						</form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
