<?php
session_start();

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']=='yes'){
    include("meniu_privat.php");
}
else
    include("meniu_public.php");
