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
                    <br>
                    <iframe src="https://calendar.google.com/calendar/embed?wkst=2&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FAmsterdam&amp;src=NGNtaTgxcHFrMXFucWtoY3VndWF2aDh0N2dAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;color=%23616161&amp;showTz=0&amp;showCalendars=0&amp;showDate=1&amp;showNav=1&amp;showPrint=0&amp;showTabs=0&amp;showTitle=0&amp;hl=nl" style="border:solid 1px #777" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
                    @else
                    Hoi lid!
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
