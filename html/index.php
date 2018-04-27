<!DOCTYPE html>
<html>
<head>
    <title>PiZero Karaoke</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css"/>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="/jquery.base64.js"></script>
</head>
<body>
<?php
// Path
$dir = "/home/pi/songs/";
$files = glob($dir . '*.{mp3,mp4}', GLOB_BRACE);
$ar = null; ?>

<div data-role="page" id="home">
    <div data-role="header">
        <h3>PiZero Karaoke</h3>
    </div>
    <div role="main" class="ui-content">
        <p><a href="#songlist" data-role="button" data-transition="fade" data-icon="search" data-iconpos="right">Search
                for a song</a></p>
        <p>
        <ul data-role="listview" data-inset="true">
            <li><a href="#mainQueuePage" data-transition="fade">Current Queue<span class="ui-li-count">0</span></a></li>
        </ul>
        </p>
        <p data-role="button" data-theme="e" class="myMarquee">Now performing: Null </p>
    </div>
</div>

<div data-role="page" id="trackDialog">
    <div data-role="header">
        <h1>Play this song next?</h1>
    </div>
    <div data-role="content">
        <h3 id="trackDialogArtistTitle"></h3>
        <p><a href="#" onclick="window.queueSong();" data-role="button" data-theme="b" data-icon="plus"
              data-iconpos="right">Add to Queue</a></p>
        <p><a href="#" onclick="window.playSong();" data-role="button" data-theme="b">Play Now</a></p>
        <p><a href="#" onclick="window.closeDialog();" data-role="button" data-theme="a">Cancel</a></p>
    </div>
</div>

<div data-role="page" id="alertDialog">
    <div data-role="header">
        <h2 id="alertDialogMessage"></h2>
    </div>
    <div data-role="content">
        <p><a href="#" onclick="window.closeDialog();" data-role="button" data-theme="b">OK</a></p>
    </div>
</div>

<div data-role="page" id="mainQueuePage">
    <div data-role="header">
        <h1>Current Queue</h1>
        <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-rel="back">Home</a>
    </div>
    <div data-role="content">
        <p>Who's up next! Get ready!</p>
        <p>
        <ol id="mainQueueListView" data-role="listview" data-inset="true">
        </ol>
        </p>
    </div>
</div>

<div data-role="page" id="songlist">
    <div data-role="header">
        <h1>Songs List
            <small>( <?php echo count($files); ?> songs )</small>
        </h1>
        <a class="ui-btn-right" data-icon="home" data-iconpos="notext" data-rel="back">Home</a>
    </div>
    <div data-role="content">
        <input data-type="search" id="searchFieldA" placeholder="Search for a song"/>
        <ul id="suggestionsa" data-role="listview" data-inset="true" data-input="#searchFieldA" data-filter="true"><?php

            foreach ($files as $file) {

                $filename = pathinfo($file, PATHINFO_FILENAME);
                $songinfo = explode(" - ", $filename);

                $artist = $songinfo[0];
                $song = $songinfo[1];

                if ($ar !== $artist) {
                    echo '<li data-role="list-divider">' . $artist . '</li>';
                    $ar = $artist;
                }

                echo '<li data-filtertext="' . $artist . ':' . $song . '" ><a href="#trackDialog" data-artist-title="' . $filename . '" onclick="selectTrack(\'' . str_replace("'","\'",$file) . '\');" data-rel="dialog" data-transition="pop">' . $song . '</a></li>';
            }
            ?></ul>
    </div>
</div>

<script>
    (function ($) {

        window.playSong = function (path_to_file) {
            var path = path_to_file || sessionStorage.selectedTrack;

            $.getJSON('/s.php?p=' + $.base64.encode(path), function (data) {
                $('.ui-dialog').dialog('close');
            });
        };

        window.queueSong = function (path_to_file) {

            var path = path_to_file || sessionStorage.selectedTrack;

            $.getJSON('/s.php?aq=' + $.base64.encode(path), function (data) {
                if (data.result === "OK") {

                    $('.ui-dialog').dialog('close');
                    window.setTimeout(function () {
                        window.showAlertDialog("Song added to Queue");
                    }, 500);
                }
            });
        };

        window.selectTrack = function (file_path) {
            //remember path but enhance in the futre to know the user's id to submit a performance
            sessionStorage.selectedTrack = file_path;
        };

        window.showAlertDialog = function (message) {

            $('#alertDialogMessage').text(message);

            $.mobile.changePage($('#alertDialog'), {role: 'dialog', transition: 'pop'});

        };

        window.closeDialog = function () {
            $('.ui-dialog').dialog('close');
        };

        window.updateMainQueueListView = function () {

            $.ajax({
                type: "GET",
                url: "/s.php?pl",
                dataType: "xml",
                success: xmlParser
            });

        };

        function xmlParser(xml) {

            var html = [];

            $($(xml).find('leaf').reverse()).each(function () {

                var uri = $(this).attr('uri');
                var Filename = uri.split('/').pop().slice(0, -4);
                var songinfo = Filename.split('-');

                if (songinfo[1]) {
                    html.push('<li><a href="#"><h2>' + songinfo[1] + '</h2><p>' + songinfo[0] + '</p></a></li>');
                }

            });

            var lv = $('#mainQueueListView');
            lv.html(html.join(''));
            lv.listview("refresh");

        };

        $('#home').on("pageshow", function (e) {
        });

        $("#mainQueuePage").on("pagebeforeshow", function (e) {
            window.updateMainQueueListView();
        });

        // Reverse trick
        jQuery.fn.reverse = [].reverse;

    })(jQuery);
</script>
</body>
</html>
