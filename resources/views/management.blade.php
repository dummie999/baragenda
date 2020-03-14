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
                        <form class="form-horizontal col-md-12 col-xs-12" action="" method='POST'>
                            {{ csrf_field() }}
                            <div class="form-group">
                                <table class="table table-responsive management">
                                    <thead>
                                        <tr>
                                            <th>Geactiveerd</th>
                                            <th>Algemeen</th>
                                            <th>Commissie</th>
                                            <th>Naam</th>
                                            <th>Omschrijving</th>
                                            <th>Default start-/eindtijd</th>
                                            <th>Update</th>
                                            <th>Create</th>
                                            <th>Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shifttypes as $s)
                                        <tr>
                                        <td >
                                            <input type="hidden" name="id[{{$s->id}}]" value=" {{$s->id}} ">
                                            <input name="enabled[{{$s->id}}]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $s->enabled == 1 ? 'checked' : '' }} value="1" >
                                        </td>
                                        <td>
                                            <input name="common[{{$s->id}}]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $s->common == 1 ? 'checked' : '' }} value="1" >
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="committee_id[{{$s->id}}]" class="form-control" >
                                                        @isset($s->committee_id) @else <option></option>@endisset
                                                        @foreach($committees as $c)
                                                        <option @isset($s->committee->name) @if($c->name==$s->committee->name) selected  @endif @endisset value="{{ $c->id }}" > {{$c->name}}  </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input name="title[{{$s->id}}]" type="text" class="form-control" value="{{$s->title ?? ''}}">
                                            </td>
                                            <td>
                                                <textarea name="description[{{$s->id}}]" class="form-control" rows="1" >{{$s->description}}</textarea>
                                            </td>
											<td>
												 
												
														<div class="col-sm-8">
															<input type="text" class="form-control datetimepicker-input" name="default_datetime[{{$s->id}}]" id="datetimepicker5a_{{$s->id}}" data-toggle="datetimepicker" data-target="#datetimepicker5a_{{$s->id}}"/>
														</div>
														<div class="col-sm-8">
															<input type="text" class="form-control datetimepicker-input" name="default_datetime_end[{{$s->id}}]" id="datetimepicker5b_{{$s->id}}" data-toggle="datetimepicker" data-target="#datetimepicker5b_{{$s->id}}"/>
														</div>
														<script type="text/javascript">
															$(function () {
																$('#datetimepicker5a_{{$s->id}}').datetimepicker({
																	defaultDate: moment({ hour: 14, minute: 00 }), format: 'HH:mm'																	
																	});
																$('#datetimepicker5b_{{$s->id}}').datetimepicker({
																	defaultDate: moment({ hour: 21, minute: 00 }), format: 'HH:mm'
																});							
															});
														</script>
												                                          
											</td>                                            
                                            <td>{{$s->updated_at}} <br>({{$s->user->info->name}})</td>
                                            <td>{{$s->created_at}}</td>
                                        <td>

                                        <a href="{{ route('management.delRow', ['shifttype' => $s->id]) }}" class="btn btn-danger">Remove</a>

                                        </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Aanpassen
                                    </button>
                                </div>

                                </form>
                                <form id="newRow-form" action="{{ route('management.newRow', ['shifttypes' => $shifttypes]) }}" method="POST">
                                    {{ csrf_field() }}
                                    <button class="btn btn-primary" type="submit" >Extra rij</button>
                                </form>

                            </div>
                        </div>
					<div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
