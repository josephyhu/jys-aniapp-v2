<?php
require_once 'inc/functions.php';
$query = [
    'client_id' => '7672',
    'redirect_uri' => 'https://jys-aniapp-v2.herokuapp.com', // http://example.com/callback
    'response_type' => 'code'    
];


$url = 'https://anilist.co/api/v2/oauth/authorize?' . urldecode(http_build_query($query));
$code = $_GET['code'];
$logged_out = $_GET['logged_out'];

require_once 'inc/header.php';
?>
<main>
    <?php
    if (!isset($code)) {
        echo "<div id='login'><a href='$url'>Log in with AniList</a></div>";
        echo "<form method='post'>";
        echo "<label for='type'>Type<span class='required'>*</span></label><br>";
        echo "<input type='radio' id='anime' name='type' value='ANIME' required><label for='anime'>Anime</label> ";
        echo "<input type='radio' id='manga' name='type' value='MANGA'><label for='manga'>Manga</label><br>";
        echo "<label for'search'>Search<span class='required'>*</span></label> ";
        ?>
        <input type='text' id='search' name='search' value='<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''?>' required><br>
        <label for='page'>Page</label>
        <input type='number' id='page' name='page' value='<?php echo isset($_POST['page']) ? htmlspecialchars($_POST['page']) : 1 ?>'><br>
        <?php
        echo "<button type='submit'>Search</button><br>";
        echo "</form>";
        $type = htmlspecialchars($_POST['type']);
        $search = htmlspecialchars($_POST['search']);
        $page = htmlspecialchars($_POST['page']);
        if (isset($type) && isset($search)) {
            echo "<h2>Searched for " . $search . " in " . $type . "</h2>";
        }
        echo "<div>";
        try {
            if (!empty($type) && !empty($search) && !empty($page)) {
                $data = search_media($type, $page, $search);
            }
            if (!empty($data)) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Cover</th>";
                echo "<th>Title</th>";
                echo "<th>Format</th>";
                echo "<th>Start Date</th>";
                echo "<th>End Date</th>";
                echo "<th>Average Score</th>";
                echo "</tr>";
                echo "<thead>";
                echo "<tbody>";
                for ($i = 0; $i < count($data['media']); $i++) {
                    $html = "<tr><td><a href='" . $data['media'][$i]['siteUrl'] . "' target='_blank'><img src='" . $data['media'][$i]['coverImage']['large'] . "' alt='cover'></a></td>";
                    $html .= "<td>" . $data['media'][$i]['title']['romaji'] . " (" . $data['media'][$i]['title']['english'] . ")" . "</td>";
                    $html .= "<td>" . $data['media'][$i]['format'] . "</td>";
                    $html .= "<td>" . $data['media'][$i]['startDate']['year'] . "-" . $data['media'][$i]['startDate']['month'] . "-" . $data['media'][$i]['startDate']['day'] . "</td>";
                    $html .= "<td>" . $data['media'][$i]['endDate']['year'] . "-" . $data['media'][$i]['endDate']['month'] . "-" . $data['media'][$i]['endDate']['day'] . "</td>";
                    $html .= "<td>" . $data['media'][$i]['averageScore'] . "</td></tr>";
                    echo $html;
                }
                echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
                echo "</tbody>";
                echo "</table>";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        echo "</div>";
        if (isset($logged_out)) {
            echo "<p class='success'>Successfully logged out.</p>";
            echo "<p class='notice'>Be sure to revoke the app to finish logging out.</p>";
        }
    } else {
        echo "<div id='logout'><a href='logout.php'>Log out</a></div>";
        $accessToken = get_token($code);
        $userId = get_userId($accessToken);
        $username = get_username($userId);
    ?>
    <h2><?php echo "$username's Anime/Manga List";?></h2>
    <h3 class='anime-button btn'>Anime</h3>
    <div class='anime'>
        <?php $type = "ANIME"; ?>
        <h3 class='current-anime-button btn'>Currently watching anime</h3>
        <div class='current-anime'>
            <?php
            $status = "CURRENT";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='completed-anime-button btn'>Completed anime</h3>
        <div class='completed-anime'>
            <?php
            $status = "COMPLETED";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Completed Date</th>";
                    echo "<th>Score</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['completedAt']['year'] . "-" . $data[$i]['completedAt']['month'] . "-" . $data[$i]['completedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['score'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='planning-anime-button btn'>Planning to watch anime</h3>
        <div class='planning-anime'>
            <?php
            $status = "PLANNING";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td></tr>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='paused-anime-button btn'>Paused anime</h3>
        <div class='paused-anime'>
            <?php
            $status = "PAUSED";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='dropped-anime-button btn'>Dropped anime</h3>
        <div class='dropped-anime'>
            <?php
            $status = "DROPPED";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</td>";
                    echo "<th>Progress</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                $e->getMessage();
            }
            ?>
        </div>
        <h3 class='repeating-anime-button btn'>Repeating anime</h3>
        <div class='repeating-anime'>
            <?php
            $status = "REPEATING";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "<th>Repeats</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td>";
                        $html .= "<td>" . $data[$i]['repeat'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
    </div>
    <h3 class='manga-button btn'>Manga</h3>
    <div class='manga'>
        <?php $type = "MANGA"; ?>
        <h3 class='current-manga-button btn'>Currently reading manga</h3>
        <div class='current-manga'>
            <?php
            $status = "CURRENT";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='completed-manga-button btn'>Completed manga</h3>
        <div class='completed-manga'>
            <?php
            $status = "COMPLETED";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Completed Date</th>";
                    echo "<th>Score</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['completedAt']['year'] . "-" . $data[$i]['completedAt']['month'] . "-" . $data[$i]['completedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['score'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='planning-manga-button btn'>Planning to read manga</h3>
        <div class='planning-manga'>
            <?php
            $status = "PLANNING";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td></tr>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='paused-manga-button btn'>Paused manga</h3>
        <div class='paused-manga'>
            <?php
            $status = "PAUSED";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='dropped-manga-button btn'>Dropped manga</h3>
        <div class='dropped-manga'>
            <?php
            $status = "DROPPED";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <h3 class='repeating-manga-button btn'>Repeating manga</h3>
        <div class='repeating-manga'>
            <?php
            $status = "REPEATING";
            try {
                $data = get_userMediaList($userId, $type, $status);
                if (!empty($data)) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Cover</th>";
                    echo "<th>Title</th>";
                    echo "<th>Format</th>";
                    echo "<th>Started Date</th>";
                    echo "<th>Progress</th>";
                    echo "<th>Repeats</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for ($i = 0; $i < count($data); $i++) {
                        $html = "<tr><td><a href='" . $data[$i]['media']['siteUrl'] . "'g target='_blank'><img src='" . $data[$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                        $html .= "<td>" . $data[$i]['media']['title']['romaji'] . " (" . $data[$i]['media']['title']['english'] . ")" . "</td>";
                        $html .= "<td>" . $data[$i]['media']['format'] . "</td>";
                        $html .= "<td>" . $data[$i]['startedAt']['year'] . "-" . $data[$i]['startedAt']['month'] . "-" . $data[$i]['startedAt']['day'] . "</td>";
                        $html .= "<td>" . $data[$i]['progress'] . "</td>";
                        $html .= "<td>" . $data[$i]['repeat'] . "</td></tr>";
                        echo $html;
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
    </div>
    <?php } ?>
</main>
<script src='js/script.js'></script>
<?php require_once 'inc/footer.php'; ?>