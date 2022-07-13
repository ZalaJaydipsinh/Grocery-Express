<style>
        svg {
            width: 100px;
            display: block;
            margin: 40px auto 0;
        }

        .path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 0;
        }

        .path.circle {
            -webkit-animation: dash 0.9s ease-in-out;
            animation: dash 0.9s ease-in-out;
        }

        .path.line {
            stroke-dashoffset: 1000;
            -webkit-animation: dash 0.9s 0.35s ease-in-out forwards;
            animation: dash 0.9s 0.35s ease-in-out forwards;
        }

        .path.check {
            stroke-dashoffset: -100;
            -webkit-animation: dash-check 0.9s 0.35s ease-in-out forwards;
            animation: dash-check 0.9s 0.35s ease-in-out forwards;
        }

        p,
        a {
            text-align: center;
            margin: 20px 0 60px;
            font-size: 1.25em;
            display: block;
        }

        a{
            color: grey;
        }

        p.success {
            color: #73AF55;
        }

        p.error {
            color: #D06079;
        }

        @-webkit-keyframes dash {
            0% {
                stroke-dashoffset: 1000;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes dash {
            0% {
                stroke-dashoffset: 1000;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @-webkit-keyframes dash-check {
            0% {
                stroke-dashoffset: -100;
            }

            100% {
                stroke-dashoffset: 900;
            }
        }

        @keyframes dash-check {
            0% {
                stroke-dashoffset: -100;
            }

            100% {
                stroke-dashoffset: 900;
            }
        }
    </style>
<?php
function success_msg($msg, $href, $a_name)
{
    echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
<circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
<polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />
</svg>';
    echo '<p class="success">' . $msg . '</p>';
    echo '<a href="' . $href . '" style="text-decoration: none;">Click here to goto ' . $a_name . ' page.</a>';
}
function failure_msg($msg, $href, $a_name)
{
    global $conn;
    echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
<circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
<line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" />
<line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" />
     </svg>';
    echo '<p class="error">' . $msg . '</p>';
    echo '<p class="error">Error: '.mysqli_error($conn).' (if you facing this error many times then please contact to developer.)</p>';
    echo '<a href="' . $href . '" style="text-decoration: none;">Click here to go back to ' . $a_name . ' page</a>';
}
?>