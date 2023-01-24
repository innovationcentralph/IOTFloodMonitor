function reloadData(){
    $.ajax({
        cache:false,
        url: 'resources/data/getMonitors.php',
        type: 'GET',
        async: false,
        data: {userID: userID},
        dataType: 'JSON',
        success: function(msg) {
             const locations = msg;
             console.log(msg)
             locations.forEach((loc)=>{
                var stat = false;
                
                console.log(msg[3].currentEpoch,loc.timeStart)
                if (loc.Rented == 1){
                    stat = true;
                    var timeTravelled = ( msg[3].currentEpoch - loc.timeStart)/60;
                    $("#time-" + loc.devID).html(timeTravelled.toFixed(2) + "minutes");

                    var fuelConsumed = loc.multiplier * (( msg[3].currentEpoch - loc.timeStart)/60) /10;
                    $("#fuel-" + loc.devID).html(fuelConsumed.toFixed(2) + "L");
                    
                    var distanceTraveled = loc.multiplier * (( msg[3].currentEpoch - loc.timeStart)/60);
                    $("#km-" + loc.devID).html(distanceTraveled.toFixed(2) + "km");
                }
                else{
                    $("#time-" + loc.devID).html("N/A");
                    $("#fuel-" + loc.devID).html("N/A");
                    $("#km-" + loc.devID).html("N/A");
                   
                }
                // console.log("vehicle" +loc.devID, "stat: " + loc.Rented)
                $(".childckbx[name='"+loc.devID+"']").prop('checked', stat);
                // console.log("vehicleafter" +loc.devID, "stat: " + loc.Rented)
            
             })
        } ,
        error: function(req, err){
        }		
    }).always(function(){
        // console.log("status before anything else", $('#vehicle1').is(':checked'),$('#vehicle1'))
         
        $('.ckbx').click(function(){
            var newVal = false
            var val = 0;
            var name = this.name;
            var id = "#" +this.id;
           if (!$(id).is(':checked')){
                    newVal = false;
                    val = 0;
            }
            else{
                    newVal = true;
                    val = 1;
            }
            
            $.ajax({
            cache:false,
            url: 'resources/data/updateMonitor.php',
            type: 'POST',
            data: {devID: name, Rented: val, userID: userID},
            dataType: 'JSON',
            success: function(msg) {
                if(!$(".childckbx[name='"+msg[0].devID+"']").prop('checked')){
                    var maxRentTime = 30;
                    var distance = msg[0].multiplier * msg[1].timeEnd;
                    var excessDistance = 0;
                    if (distance - msg[0].maxDistance > 0){
                        //maxDistance is set to 50 for 30min drive
                        excessDistance = distance - msg[0].maxDistance; 
                    } 
                    //time travelled * manila rental rate (cost/min) + 5pesos * distance travelled excess of 5km 
                    var excess = 0;
                    var excessCharge = 0;
                    var rawCost = (msg[0].ratepermin * (msg[1].timeEnd)) + (5*excessDistance);
                   
                    if (msg[1].timeEnd.toFixed(1) > maxRentTime){
                        var rawCost = (msg[0].ratepermin * 30) + (5*excessDistance);
                         excess = msg[1].timeEnd.toFixed(1) - maxRentTime;
                         excessCharge = (msg[0].ratepermin * excess) + (5*excessDistance);
                    }
                    
                    var totalPayment = rawCost + excessCharge;
                    var element =  modalElement("Name", msg[0].Name);
                    element += modalElement("Time Rented", msg[1].timeEnd.toFixed(1) + "minutes");
                    element += modalElement("Excess Time", excess.toFixed(1) + "minutes");
                    element += modalElement("Base Charge", "P" + rawCost.toFixed(2));
                    element += modalElement("Excess Charge", "P"+ excessCharge.toFixed(2));
                    element += modalElement("Total Payment", "P"+ totalPayment.toFixed(2));
                    generateModal("COMPUTATION", element, true, "modal", "90%", {bg:"#03394F", color:"white"})
                 
                    }
                    else{
                        reloadData();
                    }
                 
              
            } ,
            error: function(req, err){
            }		
        });
            
           
            
            
        });
    });
        

   

    };
