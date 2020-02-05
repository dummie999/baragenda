@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Instellingen aanpassen</div>

                    <div class="panel-body">
                        <form class="form-horizontal col-md-6 col-xs-12" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="available">Open voor diensten</label>
                              <input id="available" class="" name="available" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary" checked>

                            <div class="form-group">
                                <label for="extra_info">Extra informatie</label>
                                <input id="extra_info" class="form-control" name="extra_info" value="{{ old('extra_info', Auth::user()->extra_info) }}">
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Aanpassen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
