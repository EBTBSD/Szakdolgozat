<div id="side_menu" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSideMenu()">×</a>
    <?php if(!auth()->user()):{?>
    <a href="/login">Bejelentkezés</a>
    <a href="/register">Regisztráció</a>
    <?php } else :{ ?>
        <a href="/">Főoldal</a>
        <a href="/courses">Kurzusok</a>
        <a href="/account">Adatok</a>
        <a href="/grades">Osztályzatok</a>
    <?php } endif ?>
    <a href="/contact">Elérhetőség</a>
    <a href="/logout">Kijelentkezés</a>

</div>



<div id="main">

    <?php if(auth()->user()):{?>
        <button class="openbtn" onclick="openSideMenu()">☰</button>
    <?php } endif ?>
    <h3 class="h3_menu_head">@yield('site_name', 'Web:Crafter')</h3>
</div>
