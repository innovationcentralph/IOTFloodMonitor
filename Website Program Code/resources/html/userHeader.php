           
                <div class="row">
                    <h2>WELCOME, <?php echo $_SESSION[$sessionName]['name'];?> </h2>
                    <ul class="menu left">
                        <li><a href="user_dashboard.php"  id="profile">PROFILE</a></li>
                       <li><a href="attendance.php"  id="attendance" >ATTENDANCE</a></li> 
                        <!-- <li><a href="students.php"  id="students">STUDENTS</a></li>
                        <li><a href="program.php"  id="program">PROGRAMS</a></li> --> 
                    </ul>

                    <ul class="menu right">
                        <li><a href="resources/data/logout.php"><ion-icon name="log-out" class="inline-icon"></ion-icon>
                            <span>LOGOUT</span>
                        </a></li>
                    </ul>
                </div>