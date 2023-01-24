
<div id="fileUploadModal" class="modal">
    <div class="modal-content">
        <!-- <div class="modal-header">
            <span class="close"  onclick="closeModal('fileUploadModal')">&times;</span>
        </div> -->
        <div class="modal-body">
            <h2>REGISTER USER FOR MONITORING</h2>
            <form id="fileUploadForm">
                    <div class="textOnInput">
                        <input type="text" name="Name"/>
                        <label for="Name" class="topLabel">Name</label>
                    </div>
                    <div class="textOnInput">
                        <label for="inputText" class="topLabel">Device ID</label>
                        <select class="form-control" id="deviceList" name="devID">
                            <!--<option selected disabled value=""></option>-->
                            <!--<option value="ABC127">ABC127</option>-->
                            <!--<option value="ABC128">ABC128</option>-->
                            <!--<option value="ABC129">ABC129</option>-->
                            <!--<option value="ABC1210">ABC1210</option>-->
                            <!--<option value="ABC1211">ABC1211</option>-->
                        </select>
                    </div>
                    <!--<div class="textOnInput">-->
                    <!--    <input type="file" id="actual-btn" hidden/>-->
                    <!--    <span id="file-chosen">Upload Photo</span>-->
                    <!--    <label for="actual-btn" class="upload-button">Choose File</label>-->
                    <!--</div>-->

            </form>                        
        </div>
        <div class="modal-footer">
            <div class="row">
                <input type="button" class="button-1-of-2"  id="modalClose" value="CLOSE" onclick="closeModal('fileUploadModal')">
                 <input type="button" class="button-1-of-2" value="CONFIRM" onclick="validateForm('fileUploadForm', 'monitoringUser')">
               
        </div>
    </div>
</div>