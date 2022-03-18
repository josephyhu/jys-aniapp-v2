<?php
session_start();
require_once 'inc/functions.php';
$query = [
    'client_id' => '7672',
    'redirect_uri' => 'https://jys-aniapp-v2.herokuapp.com', // http://example.com/callback
    'response_type' => 'code'    
];


$url = 'https://anilist.co/api/v2/oauth/authorize?' . urldecode(http_build_query($query));
$code = $_GET['code'];
$logged_out = $_GET['logged_out'];
$logged_in = $_GET['logged_in'];

require_once 'inc/header.php';
?>
<main>
    <?php
    if (!isset($code)) {
        echo "<div class='links'><a href='index.php'>Home</a>";
        echo "<a href='search.php'>Search</a></div>";
        echo "<div class='login'><a href='$url'>Log in with AniList</a></div>";
        if (isset($logged_in) && $logged_in == 0) {
            echo "<p class='warning'>You have to be logged in to use this feature.</p>";
        }
        if (isset($logged_out) && $logged_out == 1) {
            echo "<p class='success'>Successfully logged out.</p>";
            echo "<p class='notice'>Be sure to revoke the app to finish logging out.</p>";
        }
    } else {
        echo "<div class='links'><a href='index.php'>Home</a>";
        echo "<a href='animelist.php'>Anime List</a>";
        echo "<a href='mangalist.php'>Manga List</a>";
        echo "<a href='search.php'>Search</a></div>";
        echo "<div class='logout'><a href='logout.php'>Log out</a></div>";
        $accessToken = get_token($code);
        $_SESSION['userId'] = get_userId($accessToken);
        $_SESSION['username'] = get_username($_SESSION['userId']);
        if (!empty($_SESSION['userId'])) {
            try {
                $data = get_userStats($_SESSION['userId']);
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    ?>
        <h2><?php echo "Welcome " . $_SESSION['username'] . "!"; ?></h2>
        <?php if (!empty($data)) { ?>
            <img src='<?php echo $data['bannerImage']; ?>' alt='banner'>
            <div id='avatar'>
                <img src='<?php echo $data['avatar']['medium']; ?>' alt='avatar'>
            </div>
            <div id='bio'>
                <h3>Bio</h3>
                <?php
                $data['about'] = preg_replace(
                    "/\[\](.*)/i",
                    "",
                    $data['about'],
                );
                $data['about'] = substr($data['about'], 0, 400);
                echo wordwrap($data['about'], 75, '<br>');
                echo "<br><a href='" . $data['siteUrl'] . "' target='_blank'>Read full bio</a>";
                ?>
            </div>
            <h3>Anime Stats</h3>
            <table>
                <thead>
                    <tr>
                        <th>Count</th>
                        <th>Mean Score</th>
                        <th>Standard Deviation</th>
                        <th>Minutes Watched</th>
                        <th>Episodes Watched</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['statistics']['anime']['count']; ?></td>
                        <td><?php echo $data['statistics']['anime']['meanScore']; ?></td>
                        <td><?php echo $data['statistics']['anime']['standardDeviation']; ?></td>
                        <td><?php echo $data['statistics']['anime']['minutesWatched']; ?></td>
                        <td><?php echo $data['statistics']['anime']['episodesWatched']; ?></td>
                    </tr>
                </tbody>
            </table>
            <h3>Manga Stats</h3>
            <table>
                <thead>
                    <tr>
                        <th>Count</th>
                        <th>Mean Score</th>
                        <th>Standard Deviation</th>
                        <th>Chapters Read</th>
                        <th>Volumes Read</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['statistics']['manga']['count']; ?></td>
                        <td><?php echo $data['statistics']['manga']['meanScore']; ?></td>
                        <td><?php echo $data['statistics']['manga']['standardDeviation']; ?></td>
                        <td><?php echo $data['statistics']['manga']['chaptersRead']; ?></td>
                        <td><?php echo $data['statistics']['manga']['volumesRead']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
    <?php } ?>
</main>
<?php require_once 'inc/footer.php'; ?>
