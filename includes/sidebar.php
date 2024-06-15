<nav class="w3-sidebar w3-bar-block w3-border-right" id="sidebar" style="width: 100%; max-width: 250px; left: 0px; z-index: 101">
    <div class="w3-padding-16 w3-border-bottom w3-custom-padding">
        <a href="dashboard.php" class="w3-bar-item w3-button">
            <i class="fa-solid fa-gauge"></i>
            Dashboard
        </a>
    </div>

    <div class="w3-padding-16">
        <a class="w3-bar-item w3-button bm-caps w3-text-red" href="#" onclick="return w3_toggle_sub('transportation')">
            Transportation
            <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="transportation" style="display: none" class="w3-border-bottom">
            <a href="gps.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                GPS
            </a>
            <a href="navigation.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Navigation
            </a>
            <a href="roadview.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Road View
            </a>
            <a href="tracks.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Tracks
            </a>
            <a href="trains.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Trains
            </a>
        </div>
        <a class="w3-bar-item w3-button bm-caps w3-text-red" href="#" onclick="return w3_toggle_sub('students')">
            Students
            <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="students" style="display: none" class="w3-border-bottom">
            <a href="brick-overflow.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Brick Overflow
            </a>
            <a href="flow.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Flow
            </a>
            <a href="timesheets.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Timesheets
            </a>
        </div>
        <a class="w3-bar-item w3-button bm-caps w3-text-red" href="#" onclick="return w3_toggle_sub('communications')">
            Communications
            <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="communications" style="display: none" class="w3-border-bottom">
            <a href="brix.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Brix
            </a>
            <a href="events.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Events
            </a>
            <a href="radio.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Radio
            </a>
        </div>
        <a class="w3-bar-item w3-button bm-caps w3-text-red" href="#" onclick="return w3_toggle_sub('community')">
            Community
            <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="community" style="display: none" class="w3-border-bottom">
            <a href="colours.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Colours
            </a>
            <a href="parts.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Parts
            </a>
            <a href="pick-a-brick.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Pick-a-Brick
            </a>
            <a href="stores.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Stores
            </a>
        </div>
        <a class="w3-bar-item w3-button bm-caps w3-text-red" href="#" onclick="return w3_toggle_sub('content')">
            Content
            <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="content" style="display: none" class="w3-border-bottom">
            <a href="bricksum.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Bricksum
            </a>
            <a href="placekit.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Placekit
            </a>
            <a href="qr.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                QR Codes
            </a>
            <a href="videokit.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Videokit
            </a>
        </div>
        <a class="w3-bar-item w3-button bm-caps w3-text-red" href="#" onclick="return w3_toggle_sub('manage-city')">
            Manage City
            <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="manage-city" style="display: none" class="w3-border-bottom">
            <a href="power-grid.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Power Grid
            </a>
            <a href="control-panel.php" class="w3-bar-item w3-button">
                <i class="fa-solid fa-globe"></i>
                Control Panel
            </a>
        </div>
    </div>
</nav>

<!-- Script for Side-navigation -->
<script>
    function w3_toggle_sub(id) {
        let target = document.getElementById(id);
        let link = target.previousElementSibling;
        let icon = link.getElementsByTagName("i")[0];

        if (target.style.display == "none") {
            w3_sidebar_close_all();
            icon.classList.remove("fa-caret-right");
            icon.classList.add("fa-caret-down");
            target.style.display = "block";
        } else {
            icon.classList.remove("fa-caret-down");
            icon.classList.add("fa-caret-right");
            target.style.display = "none";
        }

        return false;
    }

    function w3_sidebar_close_all() {
        let down = document.querySelectorAll("#sidebar .fa-caret-down");
        for (let i = 0; i < down.length; i++) {
            down[i].classList.add("fa-caret-right");
            down[i].classList.remove("fa-caret-down");
            down[i].parentElement.nextElementSibling.style.display = "none";
        }
    }
</script>