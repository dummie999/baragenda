@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        @if(!isset($page) || $page == 0)
                            Diensten in de komende 2 weken
                        @else
                            Diensten tussen {{ reset($shifts)['carbon']->format('l d F') }} en {{ end($shifts)['carbon']->format('l d F') }}
                        @endif
                        <a href="{{route('shifts.page', ['page' => 0])}}" class="pull-right btn">Deze week</a>
						<hr>

						<a href="{{
                        $page == 1 ?
                            route('shifts') :
                            route('shifts.page', ['page' => (isset($page) ? $page-1 : -1)])
                    }}" class="pull-left btn">Previous</a>
                        <div class="btn center" style="width:75%">{{ $weeknos ?? ''  }} - {{ $weeknos+1 ?? ''  }}</div>
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
						<table class="table diensten">
						{{--<table class="table table-responsive diensten">--}}
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
						@foreach($shifts as $j => $shift)
                               <tr>
                               <td>@if (count($shift)>1)<a href="{{ route('shifts.date', ['date' => $j]) }}">@endif {{ $shift['carbon']->format('l d F') }}</a></td>
                                    @foreach($shifttypes as $i => $type)
                                        <td>
                                            
                                            @if (array_key_exists($type->title,$shift))
                                                <i>{{ $shift[$type->title]->title ?? '' }}</i>
                                                @foreach( $shift[$type->title]->shiftuser as $k =>$u )
                                                    {{$u->info->name}}
                                                    @if ((count($shift[$type->title]->shiftuser)-1) > $k)
                                                        <br>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
						            @endforeach
                                <td>
                                    {{--Still need fix for html hack to enlist for the past--}}
                                @if (count($shift)>1 && $shift['carbon']->gte($today)) <button id="showEnlistment" type="button" data-date={{$j}} onClick="showEnlistment(this)" class="btn btn-primary">
                                  Aanmelden
                                </button>
                                @endif
								@isset($shift)
                                    <form  action="{{ route('shifts.enlist') }}" method='POST' >
                                            {{ csrf_field() }}
                                    <div id="E_{{$j}}" name="E_{{$j}}" style="display:none;">
                                    <select name="shiftDate[{{$j}}]" id="S_{{$j}}" class="form-control">
                                        @foreach($shift as $s)
                                            @isset($s->shifttype)
                                                <option name="O_{{$s->shift_type_id}}" value="{{$s->shift_type_id}}">{{$s->shifttype->title}}</value>
                                            @endisset
                                        @endforeach
                                    </select>
                                    <input type="submit" class="btn btn-warning form-control" value="Verzenden">
                                    </div>
                                    </form>
								@endisset
                                    </td>
								</tr>
							@if(array_keys($shifts)[6]==$i)
								<tr>
									<td>&nbsp;</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							@endif
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
                        <br class="clearfix"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
