<div class="leftMenu">
    <div class="session">
        <?php 
            if($user = Auth::user()){
                $name = $user->Username;
                echo "<h3>Witaj, $name</h3>";
            }
        ?>
        <a href="/logout"><h3>Wyloguj się</h1></a>  
    </div>
    <div class="leftMenuBox">
        <a href="/import"><h3>Import plików</h1></a>
        <a href="/view"><h3>Podgląd danych</h1></a> 
    </div>
</div>
