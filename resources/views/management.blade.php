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
                        <form class="form-horizontal col-md-12 col-xs-12" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <table class="table responsive-table management">
                                    <thead>
                                        <tr>
                                            <th>Algemeen</th>
                                            <th>Commissie</th>
                                            <th>Naam</th>
                                            <th>Omschrijving</th>
                                            <th>Update</th>
                                            <th>Create</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shifttypes as $s)
                                        <tr>
                                            <td >
                                            <input type="hidden" name="id[{{$s->id}}]" value=" {{$s->id}} ">
                                                <input name="common[{{$s->id}}]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $s->common == 1 ? 'checked' : '' }} value="1" >
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="committee_id[{{$s->id}}]" class="form-control" >
                                                        @foreach($committees as $c)
                                                        <option @if($c->name==$s->committee->name) selected  @endif value="{{ $c->id }}" > {{$c->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input name="title[{{$s->id}}]" type="text" class="form-control" value="{{$s->title}}">
                                            </td>
                                            <td>
                                                <textarea name="description[{{$s->id}}]" class="form-control" rows="1" >{{$s->description}}</textarea>
                                            </td>
                                            <td>{{$s->updated_at}} <br>({{$s->user->info->name}})</td>
                                            <td>{{$s->created_at}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Aanpassen
                                    </button>
                                    <form method="POST">
                                        <button class="btn btn-primary" type="submit" href="{{ route('new_row') }}">Extra rij</button>
                                    </form>
                                </div>
                            </form>
                        </div>
					<div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
