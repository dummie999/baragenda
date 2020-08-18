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
						<table class="table table-responsive diensten">
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
                               <td>@if (count($shift)>1)<a href="{{ route('shifts.date', ['date' => $j]) }}">@endif {{ $shift['carbon']->locale('nl_NL')->format('l d F') }}</a></td>
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
                                @if (count($shift)>1 && $shift['carbon']->gte($today)) <button id="showEnlistment"  type="button" data-date={{$j}} onClick="showEnlistment(this)" class="btn btn-primary enlistbutton">
                                  Aanmelden
                                </button>
                                @endif
                                @isset($shift)
                               
                                    {{-- https://www.w3schools.com/php/php_ajax_livesearch.asp --}}
                                    {{-- https://www.w3schools.com/howto/howto_js_autocomplete.asp --}}
                                    
                                
                                    <form autocomplete="off" action="{{ route('shifts.enlist') }}" method='POST' >
											{{ csrf_field() }}

									<div id="E_{{$j}}" class="enlistform" name="E_{{$j}}" style="display:none;">
										@admin <div class="autocomplete enlistautocomplete" >
										<input id="ac_{{$j}}" type="text" name="shiftUser[{{$j}}]" placeholder="Naam" value=@if($user->info->autofill_name==1)"{{$user->info->name}}"@endif>
										</div>@endadmin
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
    <script>

function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function(e) {
		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) { return false;}
		currentFocus = -1;
		/*create a DIV element that will contain the items (values):*/
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		/*append the DIV element as a child of the autocomplete container:*/
		this.parentNode.appendChild(a);
		/*for each item in the array...*/
		for (i = 0; i < arr.length; i++) {
		  /*check if the item starts with the same letters as the text field value:*/
		  if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			/*create a DIV element for each matching element:*/
			b = document.createElement("DIV");
			/*make the matching letters bold:*/
			b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
			b.innerHTML += arr[i].substr(val.length);
			/*insert a input field that will hold the current array item's value:*/
			b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
			/*execute a function when someone clicks on the item value (DIV element):*/
				b.addEventListener("click", function(e) {
				/*insert the value for the autocomplete text field:*/
				inp.value = this.getElementsByTagName("input")[0].value;
				/*close the list of autocompleted values,
				(or any other open lists of autocompleted values:*/
				closeAllLists();
			});
			a.appendChild(b);
		  }
		}
	});
	/*execute a function presses a key on the keyboard:*/
	inp.addEventListener("keydown", function(e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
		  /*If the arrow DOWN key is pressed,
		  increase the currentFocus variable:*/
		  currentFocus++;
		  /*and and make the current item more visible:*/
		  addActive(x);
		} else if (e.keyCode == 38) { //up
		  /*If the arrow UP key is pressed,
		  decrease the currentFocus variable:*/
		  currentFocus--;
		  /*and and make the current item more visible:*/
		  addActive(x);
		} else if (e.keyCode == 13) {
		  /*If the ENTER key is pressed, prevent the form from being submitted,*/
		  e.preventDefault();
		  if (currentFocus > -1) {
			/*and simulate a click on the "active" item:*/
			if (x) x[currentFocus].click();
		  }
		}
	});
	function addActive(x) {
	  /*a function to classify an item as "active":*/
	  if (!x) return false;
	  /*start by removing the "active" class on all items:*/
	  removeActive(x);
	  if (currentFocus >= x.length) currentFocus = 0;
	  if (currentFocus < 0) currentFocus = (x.length - 1);
	  /*add class "autocomplete-active":*/
	  x[currentFocus].classList.add("autocomplete-active");
	}
	function removeActive(x) {
	  /*a function to remove the "active" class from all autocomplete items:*/
	  for (var i = 0; i < x.length; i++) {
		x[i].classList.remove("autocomplete-active");
	  }
	}
	function closeAllLists(elmnt) {
	  /*close all autocomplete lists in the document,
	  except the one passed as an argument:*/
	  var x = document.getElementsByClassName("autocomplete-items");
	  for (var i = 0; i < x.length; i++) {
		if (elmnt != x[i] && elmnt != inp) {
		x[i].parentNode.removeChild(x[i]);
	  }
	}
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
	  closeAllLists(e.target);
  });
  }
  


        var users = ["Demo Commissie Testman","Lid testvrouw","Netcie Superadmin"];

      
    </script>
@endsection
