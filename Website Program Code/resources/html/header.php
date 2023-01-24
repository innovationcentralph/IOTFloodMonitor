
        <div class="header-tab left">
            
            <ul class="sidebarnav" > 
             <li class="sidebar-item " >  
            <!--<li class="sidebar-item" id="deviceSelector" >  -->
            <li class="sidebar-item" id="deviceSelector" onmouseover="displayChild('devicelist', true)" onmouseout="displayChild('devicelist', false)">  
            <a class="sidebar-link active selectIndex"  href="#" aria-expanded="false" >
                    <!-- ABC123 <ion-icon name="caret-down-outline"></ion-icon> -->
                </a>
            
            
            </li>


             <!-- <ul class="nav-options" id="devicelist">
                <li>
                    <a class="sidebar-link"  href="#" aria-expanded="false">
                ABC123
                    </a>
                </li>
                <li>
                    <a class="sidebar-link" href="#" aria-expanded="false">
                ABC124
                    </a>
                </li>
                <li>
                    <a class="sidebar-link"  href="#" aria-expanded="false">
                ABC125
                    </a>
                </li>
            </ul>  -->
         </ul>
            
            
        </div>
        <div class="flex-nav right">
            <div class="mobile-nav-icon" onmouseover="displayChild('headertabs', true)" onmouseout="displayChild('headertabs', false)">
                <ion-icon name="menu" class="nav-icon"></ion-icon>
            </div>
            <ul class="sidebarnav headertabs" id="headertabs">
                <li class="sidebar-item">
                <li class="sidebar-item" > 
                    <a class="sidebar-link" id="dashboard" href="dashboard.php" aria-expanded="false">
                        CHARTS
                    </a>
                </li>
                
                <li class="sidebar-item" > 
                    <a class="sidebar-link" id="logout" href="resources/data/logout.php" aria-expanded="false" id="logout">
                        LOGOUT
                    </a>
                </li>
            </ul>
        </div>
        

    <script>
        
function displayChild(listid, display){
    if(display == true){
        $("#"+listid).css("display","block")
    }if(display == false){
        $("#"+listid).css("display","none")
    }
}

    </script>