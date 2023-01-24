

$( document ).ready(function() {
    var pathname = window.location.pathname;
    const urlParams = new URLSearchParams(window.location.search);
    pathname = pathname.substring(pathname.lastIndexOf("/") + 1, pathname.lastIndexOf("php") - 1);
    $("#" + pathname).addClass("active");

    $('form input[required]').on('input', '', function () {
        if($(this).hasClass('fieldAlert')){
            removeInputAlert(this)
        }
    });
           
    $('form select[required]').on('change', '', function () {
        if($(this).hasClass('fieldAlert')){
            removeInputAlert(this)
        }
    });
});


function toggleInfo(state){
    console.log("toggling info", state)
    if (state == 'show'){
        
        $('#info-content').removeClass('hide');
    }
    else if (state == 'hide'){
    // else if (state == 'hide' && navClicked == false){
        $('#info-content').addClass('hide');
    }
}

function removeInputAlert(element){
    
    var alertID = "#alert-" + element.id;
    
    $(this).removeClass('fieldAlert');
    $(alertID).addClass('hide');
    $(alertID).css('display', 'none');

}

function showModal(modalID, datatable = null){
    var modal = document.getElementById(modalID);
    modal.style.display = "block";
    $( ".container" ).addClass( "blur" );
    if (datatable != null){
            datatable.ajax.reload();
    }
    if (modalID == "addUserModal"){
       resetForm('addUserForm');
       
    }
    if (modalID == "listofStudentsModal"){

        $('#select_all').prop("checked", false);
        $('#batchDelete').addClass('disabled');
    }
    
  
}

function showSwal(swalID, index = null, id = null, tableView = null){
 
    if (swalID == "addUser"){
        Swal.fire({
            html: 'Successfully registered <b>'+index + ' </b>with user ID <b>'+id+'</b>!',
            icon: 'success',
            confirmButtonText: 'OKAY'
        }).then((result) => {
            window.location.href = 'index.php';
          })
          
    }
    else if (swalID == "deleteStudent"){
        var htmlContent = 'Are you sure you want to delete student <b>' + index + '</b>?';
        if (index.length > 1){
            htmlContent = 'Are you sure you want to delete selected students?'
        }
        Swal.fire({
            html: htmlContent,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YES'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(index + " : " + id)
                
                $.ajax({
                    cache:false,
                    url: 'resources/data/deleteUsers.php',
                    type: 'POST',
                    data: {"id" : id},
                    success: function(msg) {
                        // console.log(msg);
                        if (msg.indexOf("OK!") != -1){
                            
                        Swal.fire({
                            html: 'User <b>'+index + '</b> successfully deleted!',
                            icon: 'success',
                            confirmButtonText: 'OKAY'
                        })
                            $('#tblstudents').DataTable().ajax.reload();
                            $('#select_all').prop("checked", false);
                            $('#batchDelete').addClass('disabled');
                        }
                        else{
                            console.log(msg);
                            Swal.fire({
                                title: "Ops! Something went wrong.",
                                text: msg,
                                icon: "error"
                              });
                        } 
                    },
                    error: function(req, err){
                        console.log(err);
                    } 	
                });
            }
        })
    }
    else if (swalID == "deleteAttendance"){
        var htmlContent = 'Are you sure you want to delete  <b>this record</b>?';
        if (id.length > 1){
             htmlContent = 'Are you sure you want to delete  <b>' + index + ' records</b>?';
        }
        Swal.fire({
            html: htmlContent,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YES'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(index + " : " + id)
                $.ajax({
                    cache:false,
                    url: 'resources/data/deleteAttendance.php',
                    type: 'POST',
                    data: {"id" : id},
                    success: function(msg) {
                        console.log(msg);
                        if (msg.indexOf("OK!") != -1){
                            
                        Swal.fire({
                            html: '<b>'+index + ' records</b> successfully deleted!',
                            icon: 'success',
                            confirmButtonText: 'OKAY'
                        })
                            $('#tblattendance').DataTable().ajax.reload();
                            $('#tblattendance #select_all').prop("checked", false);
                            $('#batchDelete').addClass('disabled');
                            $('#batchDeleteAttendance').addClass('disabled');
                        }
                        else{
                            console.log(msg);
                            Swal.fire({
                                title: "Ops! Something went wrong.",
                                text: msg,
                                icon: "error"
                              });
                        } 
                    },
                    error: function(req, err){
                        console.log(err);
                    } 	
                });
            }
        })
    }
    
  
}

function closeModal(modalID){
    var modal = document.getElementById(modalID);
    modal.style.display = "none";
    // $('#custodianForm')[0].reset();
    $( ".container" ).removeClass( "blur" );
    if(modalID == "addUserModal"){
        resetForm('addUserForm');
        
        $('#tblstudents #select_all').prop("checked", false);
        $('#batchDelete').addClass('disabled');
    }
    if (modalID == "studentAttendanceModal"){
        
        $('#tblattendance #select_all').prop("checked", false);
        $('#batchDeleteAttendance').addClass('disabled');
    }
}
// FORM SUBMISSIONS

function validateSingleForm(formUpdate = "default"){
    var alertMsg = "";
    formID = "#" + formUpdate;
    
    $(formID + " select").each(function() {
        // var select = $(this);
        var alertID = "#alert-" + this.id;
        elementID = "#"+this.id
        if($(this).prop('required')){
            if (this.value == null || this.value == ""){
                var fieldName = this.id.split(/(?=[A-Z])/);
                fieldName = fieldName.join(" ");
                fieldName = fieldName.toLowerCase();
                alertMsg += "Required "+fieldName +" field.<br>";
                $(this).addClass('fieldAlert');
                
                $(alertID).removeClass('hide');
                $(alertID).css('display','block');
                $(alertID).html('Please occupy input ' + fieldName);
            }
            else{
                $(this.id).removeClass('fieldAlert');
                $(alertID).addClass('hide');
                $(alertID).css('display','none');
            }
        }

    });
    
    $(formID + " input").each(function() {
        // var select = $(this);
        var alertID = "#alert-" + this.id;
        elementID = "#"+this.id
        if($(this).prop('required')){
            if (this.value == null || this.value == ""){
                var fieldName = this.id.split(/(?=[A-Z])/);
                fieldName = fieldName.join(" ");
                fieldName = fieldName.toLowerCase();
                alertMsg += "Required "+fieldName +" field.<br>";
                $(this).addClass('fieldAlert');
                
                $(alertID).removeClass('hide');
                $(alertID).css('display','block');
                $(alertID).html('Please occupy input ' + fieldName);
            }
            else{
                $(this).removeClass('fieldAlert');
                $(alertID).addClass('hide');
                $(alertID).css('display','none');
            }
        }

    });

    if (alertMsg == ""){
        submitForm(formID);
    }
    else{
        Swal.fire({
            title: "Failed to submit form.",
            html:  "Please make sure to fill in all required fields!",
            icon: "error"
            })
    }
}

function submitForm(formUpdate){
    var dataArray = $(formUpdate).serializeArray();
    dataObj = [];
    
    $(dataArray).each(function(i, field){
      dataObj[field.name] = field.value;
    });
    
    // console.log(dataArray);
    // // dataObj["category"]
   
    // dataArray.push({name: "activity", value : action});
    // console.log("Sending dataarray");
    // console.log(dataArray);
    
    if (formUpdate == "#addUserForm"){
        console.log(dataArray)
        $.ajax({
            cache:false,
            async: false,
            url: 'resources/data/register.php',
            type: 'POST',
            data: dataArray,
            
            dataType: "JSON",
            success: function(msg) {
                console.log(dataArray)
                console.log(msg);
                if (msg.response == "success"){
                    Swal.fire({
                        title: "SUCCESS!",
                        text: msg.data,
                        icon: "success"
                      }).then((result)=>{
                        if (result){
                            window.location.href = "index.php";
                        }
                      });
                }
                else{
                    Swal.fire({
                        title: "Oops! Something went wrong.",
                        html:  msg.data,
                        icon: "error"
                      })
                }

            },
            error: function(req, err){
                console.log(err);
            }	
        });
    }
    
    else  if (formUpdate == "#addProgramForm"){
        console.log(dataArray)
        $.ajax({
            cache:false,
            async: false,
            url: 'resources/data/addProgram.php',
            type: 'GET',
            data: dataArray,
            success: function(msg) {
                // console.log(dataArray)
                // console.log(msg);
                if (msg.indexOf("OK!") != -1){
                    showSwal('addProgram', $('#name').val())
                }
                else{
                    // console.log(msg);
                    Swal.fire({
                        title: "Ops! Something went wrong.",
                        text: msg,
                        icon: "error"
                      });
                } 
            },
            error: function(req, err){
                console.log(err);
            }	
        });
    }
    
    else  if (formUpdate == "#exportDateForm"){
        console.log(dataArray)
        var params = "";
        for(var i = 0; i < dataArray.length; i++){
            params += dataArray[i].name + "=" + dataArray[i].value +"&";
        }
        params += "device=" + selectedID;
        console.log(params)
        window.location.href = "resources/data/exportlogs.php?" + params;
    }
    else  if (formUpdate == "#exportUserAttendanceForm"){
        
        console.log(dataArray)
        var params = "";
        for(var i = 0; i < dataArray.length; i++){
            params += dataArray[i].name + "=" + dataArray[i].value +"&";
        }
        console.log(params)
        window.location.href = "resources/data/exportattendance.php?" + params;
    }
   
}

function openFile(fileName){
    $('#lectureViewer').attr('src', 'pdfViewer.html?file='+fileName+'#toolbar=0')
    $('#fileDesc').html("FILE VIEWER - " + fileName)
    showModal('iframeViewerModal')
}

/*************************** MODAL GENERATOR  ***************************/
/****   create a modal with 
 *      title = string
 *      element({type: span, class: string, content: string})
 *      closeBtn: boolean default false
 *      id: string default modal
 *      width: px or % default 50%
 * ****/
function generateModal(title="", element = "", closeBtn = false, id = "modal", width="50%", header = "",customClass = ""){
    const modal = document.createElement('div');
    modal.classList.add('modal');
    modal.id = 'modal';
    modal.style.width = width;
    $('.container').addClass('blur')
    // modal.hidden = true;

    modal.innerHTML = 
    '<div class="modal-header" style="background:'+header.bg+';color:'+header.color+'">  <span class="close">&times;</span>'
    +    '<h2>'+title+'</h2></div>'
    
    +'<div class="modalContent'+customClass+' wrap-x">'
     +  element
 +'   </div>';

document.body.appendChild(modal);
    
 modal.querySelector('.close').addEventListener('click', () => { 
    console.log("closing modal");
    console.log(modal);
    $('.container').removeClass('blur')
    modal.remove() 
   
    reloadData();
});
}