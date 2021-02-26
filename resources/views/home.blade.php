@extends('base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Agenda</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @guest
                        Je bezoekt deze pagina als gast:
                        
                        

                        
                                        
                                            <form method="POST" action="{{ route('login') }}">
                                                {{ csrf_field() }}
                                                @if(!empty($msg))
                                                    <div class="alert alert-danger">
                                                        {{ $msg }}
                                                    </div>
                                                @endif
                                                <div class="alert alert-info text-center">Je accountnaam is de eerste letter van je voornaam plus je achternaam voluit geschreven.</div>
                                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                                <div class="form-group row">
                                                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        
                                                        @error('username')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row">
                                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row">
                                                    <div class="col-md-6 offset-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        
                                                            <label class="form-check-label" for="remember">
                                                                {{ __('Remember Me') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ __('Login') }}
                                                        </button>
                        
                                                    </div>
                                                </div>
                                            </form>
                                        
                     
                        
                        <br>
                        <iframe src="https://calendar.google.com/calendar/embed?wkst=2&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FAmsterdam&amp;src=NGNtaTgxcHFrMXFucWtoY3VndWF2aDh0N2dAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;color=%23616161&amp;showTz=0&amp;showCalendars=0&amp;showDate=1&amp;showNav=1&amp;showPrint=0&amp;showTabs=0&amp;showTitle=0&amp;hl=nl" style="border:solid 1px #777" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
                    @else
                        Hoi {{$user->info->name}}!
                        @if (count($shifts) > 0)
                       
                            <br><br>
                            <p>Dit zijn je aankomende diensten:</p>
                            <table class="table diensten">
                                <thead>
                                        <tr>
                                            <th>Datum</th>
                                            <th>Dienst</th>
                                            <th>Commissie</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                @foreach($shifts as $i => $s)
                                    <tr>
                                        <td>
                                            @if (count($shifts)>0)<a href="{{ route('shifts.date', ['date' =>$s['date'] ]) }}">@endif {{$s['carbon']->translatedFormat('l d F H:i')}}</a>
                                        </td>
                                        <td>{{$s['data'] ->shifttype->title}}</td>
                                        <td>{{$s['data'] ->shifttype->committee->name}}</td>
                                @endforeach
                                    </tbody>
                            </table>
                        @endif 
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
