@extends('base')

@section('styles')
@parent

@endsection

@section('scripts')
@parent
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <form id="createform" name="createEvent" action="" method="post">
                    {{ csrf_field() }}
                    <div class="card-header">
                        <h4 class="">Nieuw event</h4>
                    </div>
                    <div class="card-block" style="">
                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <br>
                        <div class="container ">
                            <div class="row">
                                <div class="col-8">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Titel</span>
                                        </div>
                                    <input type="text" class="form-control" name="eventNew[summary]" value={{$event['summary'] ?? ""}} required></input>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <input type="hidden" name="eventNew[agenda]" value="0"></input>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="eventNew[agenda]"
                                                value="1" @if($event['calendarNo']==1) checked @endif>Toevoegen aan publieke agenda
                                        </label>
                                    </div>
                                    <br />
                                    <!----  grey out dienst
                                        <a>Baragenda</a><input type="radio" name="eventNew[agenda]" value="private" checked></input> <br />
                                        <a>Beide</a><input type="radio" name="eventNew[agenda]" value="both"></input> <br /> grey out dienst  --->

                                    <!----  grey out beide/openbaar --->
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-6">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width:65px">Vanaf</span>
                                                </div>
                                                <input id="inputDateStart" class="form-control" type="date"
                                                    name="eventNew[start][date]" value={{$event['start']['carbon']->translatedFormat("Y-m-d") ?? $today}} required></input>
                                                <input id="inputTimeStart" class="form-control" type="time"
                                                    name="eventNew[start][time]" value={{$event['start']['carbon']->translatedFormat("h:i:s") ?? $nowHour}} required></input>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width:65px">Tot</span>
                                                </div>
                                                <input id="inputDateEnd" class="form-control" type="date"
                                                    name="eventNew[end][date]" value={{$event['end']['carbon']->translatedFormat("Y-m-d") ?? $today}} required></input>
                                                <input id="inputTimeEnd" class="form-control" type="time"
                                                    name="eventNew[end][time]" value={{$event['end']['carbon']->translatedFormat("h:i:s") ?? $nowHour2}} required></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-3 ">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="hidden" name="eventNew[allDay]" value=0></input>
                                                        <input type="checkbox" name="eventNew[allDay]" value=1></input>
                                                    </div>
                                                </div>

                                                <span class="input-group-text"style="min-width:105px" >Gehele dag</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <input type="hidden" name="eventNew[recurring][active]" value=0 /></input>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="checkbox" value=1></input>
                                            </div>
                                        </div>
                                        <input class="input-group-text btn btn-info" type="button" id="inputRecurrenceButton"
                                            data-toggle="modal" data-target="#inputRecurrenceModal"
                                            value="Herhalen" style="min-width:105px"></input>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#event">Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " data-toggle="tab" href="#location">Locatie</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#time">Vrije ruimtes & tijden</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#notifications">Notificaties</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#guests">Gasten</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="row  pane_details-time" style="min-height:400px;">
                                <div class="col-8">
                                    <div class="tab-content" style="height:initial">
                                        <div class="row">&nbsp;</div>
                                        <div class="tab-pane container active" id="event">
                                            <div class="row" style="">
                                                <div class="col-12">
                                                    <div class="form-group" style=" height:200px; ">
                                                        <label for="description">Omschrijving</label>
                                                        <textarea class="form-control" name="eventNew[description]"
                                                    id="description" style="width:100%; height:100%" required>{{$event['description'] ?? ""}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane container" id="location">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Locatie:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="eventNew[location]"
                                                            placeholder="Geef exacte locatie op"
                                                            value={{"Studentenvereniging V.S.L. Catena, Kolfmakersteeg 8, 2311 VG Leiden, Netherlands"}} required></input>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row" style="">
                                                <div class="col-6">
                                                    <div class="form-group" style="height:100%">
                                                        <label for="room_select">Ruimtes:</label>
                                                        <select id="room_select" class="form-control"
                                                            name="eventNew[rooms][]" multiple form="createform"
                                                            style="height:200px">
                                                            @foreach($resources as $r)
                                                            <option data-toggle="tooltip"
                                                                title="Capaciteit: {{$r['capacity']}}" value={{$r['email']}}  @if(in_array($r['email'], array_values($event['attendees']['resource']))) selected @endif>
                                                                {{$r['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane container " id="time">Onder ontwikkeling..</div>
                                        <div class="tab-pane container " id="notifications">

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Notificatie</span>
                                                        </div>
                                                        <select class="btn btn-outline-secondary"
                                                            name="eventNew[reminder][type]">
                                                            <option value="" selected>Geen</option>
                                                            <option value="popup">Notificatie</option>
                                                            <option value="email">Email</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Herinnering</span>
                                                        </div>
                                                        <input class="form-control" type="number"
                                                            name="eventNew[reminder][no]"></input>
                                                        <select class="btn btn-outline-secondary"
                                                            name="eventNew[reminder][period]">
                                                            <option value="1">Minuut</option>
                                                            <option value="60">Uur</option>
                                                            <option value="1440" selected>Dag</option>
                                                            <option value="10080">Week</option>
                                                        </select>
                                                    </div>
                                                    <br />
                                                </div>
                                            </div>


                                        </div>
                                        <div class="tab-pane container " id="guests">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Gasten:</span>
                                                        </div>
                                                        <input type="text" class="form-control"
                                                            name="eventNew[guests][]"></input>
                                                    </div>

                                                    <div class="row">

                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="hidden"
                                                                    name="eventNew[option][guestsCanModify]"
                                                                    value=0 /></input>
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="eventNew[option][guestsCanModify]" value=1>Aan
                                                                te passen door gasten.
                                                            </label>
                                                        </div>

                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="hidden"
                                                                    name="eventNew[option][guestsCanInviteOthers]"
                                                                    value=0 /></input>
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="eventNew[option][guestsCanInviteOthers]"
                                                                    value=1 >Gasten toestaan anderen uit te
                                                                nodigen.
                                                            </label>
                                                        </div>

                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="hidden"
                                                                    name="eventNew[option][guestsCanSeeOtherGuests]"
                                                                    value=0 /></input>
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="eventNew[option][guestsCanSeeOtherGuests]"
                                                                    value=1 >Gastenlijst zichtbaar
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-warning" name="submit" value="Toevoegen"></input>
                            </div>

                            <div class="col-md-2 offset-md-8">
                                <a href={{ route('agenda') }}>
                                    <button type="button" class="btn btn-secondary">Sluiten</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
