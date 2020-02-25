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
                            route('shifts') :
                            route('shifts.page', ['page' => (isset($page) ? $page-1 : -1)])
                    }}" class="pull-left btn">Previous</a>

                        <a href="{{
                        $page == -1 ?
                            route('shifts') :
                            route('shifts.page', ['page' => (isset($page) ? $page+1 : 1)])
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
						
						<form action="" type='POST'>
						@foreach($shifttypes as $j => $type)
									<input id="shift_id{{$i}}{{$j}}" name="[{{$i}}][{{$j}}][]" type="checkbox" data-toggle="toggle" data-on="" data-off=""  data-onstyle="primary"   @isset($shift[$i][$j]) checked @endisset }}  >
						@endforeach						
						<input id="customDate1" type="text">

						<div class="form-group">
							<div class='input-group date' id='datetimepicker3_1'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-time"></span>
								</span>
							</div>
						</div>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker3_2'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-time"></span>
								</span>
							</div>
						</div>					 
					<script type="text/javascript">
						$(function () {
							$('#datetimepicker3_1').datetimepicker({
								format: 'LT'
							});
							$('#datetimepicker3_2').datetimepicker({
								format: 'LT'
							});
						});
					</script>

						<input type="submit" value="Aanmaken">
						</form>
						
						<form action="" type='POST'>
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
						
						
						</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
