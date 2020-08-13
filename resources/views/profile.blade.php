@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">Profielinstellingen</div>

                    <div class="card-block">
                        <form class="form-horizontal col-md-6 col-xs-12" method="POST">
                            {{ csrf_field() }}
							 <div class="form-group">

							<b>Wie ben ik?</b> <br>
							Naam: {{ $info->name ?? '' }} <br>
							Lidnummer: {{ $info->lidnummer ?? '' }} <br>
							Relatienummer: {{ $info->relatienummer ?? '' }} <br>
							Sinds: {{ $info->created_at ?? '' }} <br>
							Laatste wijziging: {{ $info->updated_at ?? '' }} <br>
							<br>
							<b>Voorkeuren</b><br>
                                <label for="available">Open voor diensten</label>
                              <input id="available" name="available[]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $info->available == 1 ? 'checked' : '' }}  >
                              
                              <label for="autofill_name">Naam automatisch invullen</label>
                              <input id="autofill_name" name="autofill_name[]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $info->autofill_name == 1 ? 'checked' : '' }}  >
                            <div class="form-group">
                                <label for="extra_info">Extra informatie</label>
                                <input id="extra_info" class="form-control" name="extra_info" value="{{ $info->extra_info }}">
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
