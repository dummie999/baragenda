@extends('base')

@section('styles')
    @parent

@endsection

@section('scripts')
    @parent
@endsection

@section('content')
    <div class="container" >
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        <h4 class="">Nieuw event</h4>
                    </div>
                    <div class="card-block">
                        @if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
                        @endif
                        <br>

                                            <div class="container " >
                                            <form  id="createform" name="createEvent" action="" method="post">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Titel</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="eventNew[summary]"></input>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="hidden" name="eventNew[agenda]" value="private"></input>
                                                        <div class="form-check">
                                                          <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" name="eventNew[agenda]" value="public">Toevoegen aan publieke agenda
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
                                                        <input id="inputDateStart" class="form-control" type="date" name="eventNew[start][date]" value={{$today}} required></input>
                                                        <input id="inputTimeStart" class="form-control" type="time" name="eventNew[start][time]" value={{$nowHour}} required></input>
                                                        <a>tot</a>
                                                        <input id="inputDateEnd" class="form-control" type="date" name="eventNew[end][date]" value={{$today}} required></input>
                                                        <input id="inputTimeEnd" class="form-control" type="time" name="eventNew[end][time]" value={{$nowHour}} required></input>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                <div class="input-group mb-3 ">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="hidden" name="eventNew[allDay]" value=false></input>
                                                                <input type="checkbox" name="eventNew[allDay]" value=true></input>
                                                            </div>
                                                        </div>
                                                        
                                                    <span class="input-group-text"  >Gehele dag</span>
                                                    
                                                </div>	
                                                </div>
                                            </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                    <input type="hidden" name="eventNew[recurring][active]" value=false /></input>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <input type="checkbox"></input>
                                                                </div>
                                                            </div>
                                                            <input class= "input-group-text btn" type="button" id="inputRecurrenceButton" data-toggle="modal" data-target="#inputRecurrenceModal" value="Herhalen"></input>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                <ul class="nav nav-tabs">
                                                  <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#event">Details</a>
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
                                                <div class="row  pane_details-time">
                                                    <div class="col-8">
                                                    
                                                    
                                                    <div class="tab-content" style="min-height:300px">
                                                    <div class="tab-pane container active" id="event">
                                                    
                                                        <div class="row">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Locatie:</span>
                                                                </div>
                                                                <input type="text" class="form-control" name="eventNew[location]" placeholder="Geef exacte locatie op" value="Studentenvereniging V.S.L. Catena, Kolfmakersteeg 8, 2311 VG Leiden, Netherlands"></input>
                                                            </div>
                                                        </div>
                                                        <div class="row">	                                                            
                                                            <div class="form-group">
                                                                <label for="room_select">Ruimtes:</label>
                                                                <select id="room_select" class="form-control" name="eventNew[rooms][]" multiple form="createform">
                                                                    @foreach($resources as $r)
                                                                        <option data-toggle="tooltip" title="Capaciteit:{{$r['capacity']}}" value={{$r['email']}}>{{$r['name']}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        
                                                         

                                                     
                                                       
                                                            <a>Omschrijving</a>
                                                            <textarea name="eventNew[description]" rows="5" cols="40" ></textarea>		
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="tab-pane container " id="time"> test </div>
                                                    <div class="tab-pane container " id="notifications"> 
                                                        
                                                        <div class="row">
                                                            
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Notificatie</span>
                                                                    </div>
                                                                <select class="btn btn-outline-secondary" name="eventNew[reminder][type]">
                                                                    <option value="" selected>Geen</option>
                                                                    <option value="popup">Notificatie</option>
                                                                    <option value="email">Email</option>
                                                                </select>							
                                                                <input class="form-control" type="number" name="eventNew[reminder][no]"></input>		
                                                                <select class="btn btn-outline-secondary" name="eventNew[reminder][period]">
                                                                    <option value="1">min</option>
                                                                    <option value="60">hour</option>
                                                                    <option value="1440" selected>days</option>
                                                                    <option value="10080">week</option>
                                                                </select>
                                                            </div>
                                                            <br />				
                                                        </div>
                                                    
                                                    
                                                    </div>
                                                    <div class="tab-pane container " id="guests"> 
                                                        <div class="col-12" >
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Gasten:</span>
                                                                </div>
                                                                <input type="text" class="form-control" name="eventNew[guests][]"></input>
                                                            </div>					
                                                            
                                                            <div class="row">
                                                            
                                                                <div class="form-check">
                                                                  <label class="form-check-label">
                                                                    <input type="hidden" name="eventNew[option][guestsCanModify]" value=false /></input>
                                                                    <input type="checkbox" class="form-check-input" name="eventNew[option][guestsCanModify]" value=true>Aan te passen door gasten.
                                                                  </label>
                                                                </div>				
                                                                
                                                                <div class="form-check">
                                                                  <label class="form-check-label">
                                                                    <input type="hidden" name="eventNew[option][guestsCanInviteOthers]" value=false /></input>
                                                                    <input type="checkbox" class="form-check-input" name="eventNew[option][guestsCanInviteOthers]" value=true checked>Gasten toestaan anderen uit te nodigen.
                                                                  </label>
                                                                </div>		
                                                                
                                                                <div class="form-check">
                                                                  <label class="form-check-label">
                                                                    <input type="hidden" name="eventNew[option][guestsCanSeeOtherGuests]" value=false /></input>
                                                                    <input type="checkbox" class="form-check-input" name="eventNew[option][guestsCanSeeOtherGuests]" value=true checked>Gastenlijst zichtbaar
                                                                  </label>
                                                                </div>	
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    </div>
                                          
                                            
                                                    

                                                </div>
                                                
                                            					
                                        </div>
                                        <div class="modal-footer">
                                            <div>
                                                <input type="submit" class="btn btn-warning" name="submit" value="Toevoegen"></input>
                                            </div>
                                        </form>
                                        <button  type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                

                    </div>
					<div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

