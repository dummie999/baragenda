// A $( document ).ready() block.
//$( document ).ready(function() {
//    console.log( "ready!" );
//});



function eventModal(date,id){
    console.log(data)
    console.log(date)
    console.log(id)
    console.log(data[date])
    event=data[date]['events'][id]
    $('#md_summary').text(event.summary);
    $('#md_time').text(id);
    $('#md_description').text(event.description);

    $("#myModal").modal()
}


function parseAgenda(data){
    moment.locale('nl');
    $("div.event-button").remove();
    $("div.event-button2").remove();
    $("div.allday-data-item").remove();
    var ade=0
    Object.values(data).forEach(function(value, index){

        no = moment(value.carbon).format('DD');
        name = moment(value.carbon).format('dd');
        $("#dayno_"+index).text(no);
        $("#dayname_"+index).text(name+".");
        
        
        
        value.events.forEach(function (event,id) {
            
            if (event.shape.size<1){
                if(event.shape.pos<=0.5){
                    pos=20/720*24*event.shape.pos*100
                } 
                else {
                    pos=((40/720)*(24*(event.shape.pos-0.5))+(0.5*24*(20/720)))*100
                }
                key = Object.keys(data)[index]
                //console.log(id,event)
                $('#grid_'+index).append(
                    $('<div/>', {
                        'class': event.calendarNo==0 ? "event-button2" : "event-button",
                        'css':{
                            "z-index": "15",
                            "top": pos+"%",
                            "height": event.shape.size*720+"px",
                            "click":  function eventModal(key, id) }
                        
                
                    }).append(
                        $('<div/>', {'class': 'event-button-data'})
                        .append(
                            $('<div/>', {
                                'class': 'event-button-title',
                                text: event.summary
                            })
                        )
                        .append(
                            $('<div/>', {
                                'class': 'event-button-time',
                                text: moment(event.start.carbon).format("HH:mm")+"-"+moment(event.end.carbon).format("HH:mm")
                            })
                            
                        )
                    ) 
                )
            }   
            else {
                //console.log(event.summary)
                //multiday events
                $('.allday-data-list').append(
                    $('<div/>', {
                        'class': 'allday-data-item',
                        'css':{
                            "left":event.shape.pos_day*100+"%",
                            "top":ade+"em",
                            "width":event.shape.size_day*100+"%"
                        }
                    })
                    .append(
                        $('<div/>', {
                            'class': 'allday-data-item-button'
                        })
                        .append(
                            $('<div/>', {
                                'class': 'allday-data-item-span',
                                text: event.summary
                            })
                        )
                    )
                )
                ade = ade +1                
            }
        })
    })
}  
    

