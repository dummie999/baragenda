@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-block">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <br>
                        <table class="table responsive-table diensten">
                            <thead>
                                <tr>
                                    <th>Naam</th>
                                    <th>Omschrijving</th>
                                    <th>Commissie</th>
                                    <th>Color</th>
                                    <th>Zichtbaarheid</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($skills as $skill)
                                <tr>
                                    <td>{{$skill->name }}</td>
                                    <td>{{$skill->description }}</td>
                                    <td>{{$skill->committee_id }}</td>
                                    <td> <font color="#{{$skill->color}}" >{{$skill->color }}</font></td>
                                    <td>{{$skill->visibility }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                        <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
