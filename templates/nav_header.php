
<!-- TOP NAVIGATION -->

<script>
    window.onload = (event) => {
        let sidebar = document.getElementById("sidebar");
        let overlay = document.getElementById("sidebarOverlay");

        let width = sidebar.getBoundingClientRect().width;

        sidebar.style.left = "-" + width + "px";
        overlay.style.display = "none";
        overlay.style.opacity = "0";
    };

    function w3_sidebar_toggle(force) {
        let sidebar = document.getElementById("sidebar");
        let overlay = document.getElementById("sidebarOverlay");
        let width = sidebar.getBoundingClientRect().width;

        if (sidebar.style.left == "0px" || force == 'close"') {
            sidebar.style.transition = "0.5s";
            sidebar.style.left = "-" + width + "px";

            overlay.style.transition = "0.5s";
            overlay.style.opacity = "0";

            setTimeout(function () {
            overlay.style.display = "none";
            w3_sidebar_close_all();
            }, 500);
        } else {
            sidebar.style.transition = "0.5s";
            sidebar.style.left = "0px";

            overlay.style.display = "block";

            setTimeout(function () {
            overlay.style.transition = "0.5s";
            overlay.style.opacity = "1";
            }, 0);
        }
    }
</script>

<nav
    class="w3-bar w3-border-bottom w3-padding w3-white w3-top"
    style="position: sticky; z-index: 110; height: 58px"
>
    <div class="w3-row">
        <div class="w3-col s6">
            <button class="w3-button" onclick="w3_sidebar_toggle()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a href="/" class="w3-margin-left"
            ><img
                src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png"
                style="height: 35px"
            /></a>
            <button
            class="w3-border w3-border-gray w3-button w3-margin-left"
            onclick="open_modal('city')"
            >
                <i class="fa-solid fa-city"></i>
                Smart City
                <i class="fa-solid fa-caret-down"></i>
            </button>
        </div>
        <div class="w3-col s6 w3-right-align">
            <img
                src="<?=$_SESSION['user']['avatar'] ? $_SESSION['user']['avatar'] : '/images/no_avatar.png'?>"
                style="height: 35px"
                class="w3-circle"
            />
            <button class="w3-button" onclick="open_modal('apps')">
                <i class="fa-solid fa-grip-vertical"></i>
            </button>
    </div>
    </div>
</nav>
