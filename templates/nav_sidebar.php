<?php

$nav = [
    [
        'title' => 'City Portal',
        'protected' => false,
        'sections' => [
            [
                'title' => 'Control',
                'pages' => [
                    'icon' => 'control-panel',
                    'url' => '/control-panel',
                    'text' => 'Control Panel',
                ],
            ],
        ],
    ],
];

?>


<!-- SIDE NAVIGATION -->

<script>

    function w3_toggle_sub(id) {
        let target = document.getElementById(id);
        let link = target.previousElementSibling;
        let icon = link.getElementsByTagName("i")[0];

        if (target.style.display == "block") {
            icon.classList.remove("fa-caret-down");
            icon.classList.add("fa-caret-right");
            target.style.display = "none";
        } else {
            w3_sidebar_close_all();
            icon.classList.remove("fa-caret-right");
            icon.classList.add("fa-caret-down");
            target.style.display = "block";
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

    <nav
      class="w3-sidebar w3-bar-block w3-border-right"
      id="sidebar"
      style="
        width: 100%;
        max-width: 250px;
        left: -250px;
        z-index: 109;
        top: 0px;
        padding-top: 58px;
      "
    >
      <div class="w3-padding-16 w3-border-bottom">
        <a href="dashboard.html" class="w3-bar-item w3-button bm-selected">
          <i class="fa-solid fa-gauge"></i>
          Dashboard
        </a>
      </div>

      <div class="w3-padding-16 w3-border-bottom">
        <div class="w3-bar-item w3-text-gray bm-caps">City Portal</div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('control')"
        >
          Control
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="control" style="display: none">
          <a href="control-panel.html" class="w3-bar-item w3-button">
            <i class="bm-control-panel"></i>
            Control Panel
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('transportation')"
        >
          Transportation
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="transportation" style="display: none">
          <a href="navigation.html" class="w3-bar-item w3-button">
            <i class="bm-navigation"></i>
            Navigation
          </a>
          <a href="roadview.html" class="w3-bar-item w3-button">
            <i class="bm-roadview"></i>
            Roadview
          </a>
          <a href="tracks.html" class="w3-bar-item w3-button">
            <i class="bm-tracks"></i>
            Tracks
          </a>
          <a href="train.html" class="w3-bar-item w3-button">
            <i class="bm-train"></i>
            Train
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('students')"
        >
          Students
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="students" style="display: none">
          <a href="brick-overflow.html" class="w3-bar-item w3-button">
            <i class="bm-brick-overflow"></i>
            Brick Overflow
          </a>
          <a href="flow.html" class="w3-bar-item w3-button">
            <i class="bm-flow"></i>
            Flow
          </a>
          <a href="timesheets.html" class="w3-bar-item w3-button">
            <i class="bm-timesheets"></i>
            Timesheets
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('community')"
        >
          Community
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="community" style="display: none">
          <a href="radio.html" class="w3-bar-item w3-button">
            <i class="bm-radio-station"></i>
            Radio
          </a>
          <a href="events.html" class="w3-bar-item w3-button">
            <i class="bm-events"></i>
            Events
          </a>
          <a href="qr.html" class="w3-bar-item w3-button">
            <i class="bm-qr"></i>
            QR Codes
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('social')"
        >
          Social
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="social" style="display: none">
          <a href="brix.html" class="w3-bar-item w3-button">
            <i class="bm-brix"></i>
            Brix
          </a>
          <a href="timeline.html" class="w3-bar-item w3-button">
            <i class="bm-timeline"></i>
            Timeline
          </a>
        </div>
        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('finances')"
        >
          Finances
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="finances" style="display: none">
          <a href="crypto-grid.html" class="w3-bar-item w3-button">
            <i class="bm-crypto"></i>
            Crypto
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('tools')"
        >
          Tools
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="tools" style="display: none">
          <a href="uptime.html" class="w3-bar-item w3-button">
            <i class="bm-uptime"></i>
            Uptime
          </a>
        </div>
      </div>

      <div class="w3-padding-16 w3-border-bottom">
        <div class="w3-bar-item w3-text-gray bm-caps">Administration</div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('admin-content')"
        >
          Content
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="admin-content" style="display: none">
          <a href="admin-bricksum.html" class="w3-bar-item w3-button">
            <i class="bm-bricksum"></i>
            Bricksum
          </a>
          <a href="navadmin-coloursigation.html" class="w3-bar-item w3-button">
            <i class="bm-colours"></i>
            Colours
          </a>
          <a href="admin-parts.html" class="w3-bar-item w3-button">
            <i class="bm-parts"></i>
            Parts
          </a>
          <a href="admin-stores.html" class="w3-bar-item w3-button">
            <i class="bm-stores"></i>
            Stores
          </a>
          <a href="admin-media.html" class="w3-bar-item w3-button">
            <i class="bm-media"></i>
            Media
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('admin-finances')"
        >
          Finances
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="admin-finances" style="display: none">
          <a href="admin-bricksum.html" class="w3-bar-item w3-button">
            <i class="bm-crypto"></i>
            Crypto
          </a>
        </div>

        <a
          class="w3-bar-item w3-button w3-text-red"
          href="#"
          onclick="return w3_toggle_sub('admin-tools')"
        >
          Tools
          <i class="fa-solid fa-caret-right"></i>
        </a>
        <div id="admin-tools" style="display: none">
          <a href="admin-github.html" class="w3-bar-item w3-button">
            <i class="bm-github"></i>
            GitHub Scanner
          </a>
          <a href="admin-stats.html" class="w3-bar-item w3-button">
            <i class="bm-stats"></i>
            Stats
          </a>
          <a href="admin-uptime.html" class="w3-bar-item w3-button">
            <i class="bm-uptime"></i>
            Uptime
          </a>
        </div>
    </div>
</nav>

<div
    class="w3-overlay"
    style="z-index: 100; display: none; background: rgba(0, 0, 0, 0.4)"
    id="sidebarOverlay"
></div>