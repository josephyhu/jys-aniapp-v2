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

require_once 'inc/header.php';
?>
<main>
    <?php
    if (!isset($code)) {
        echo "<a href='search.php'>Search</a>";
        echo "<div id='login'><a href='$url'>Log in with AniList</a></div>";
        if (isset($logged_out)) {
            echo "<p class='success'>Successfully logged out.</p>";
            echo "<p class='notice'>Be sure to revoke the app to finish logging out.</p>";
        }
    } else {
        echo "<div id='links'><a href='index.php'>Home</a>&nbsp;";
        echo "<a href='animelist.php'>Anime List</a>&nbsp;";
        echo "<a href='mangalist.php'>Manga List</a>&nbsp;";
        echo "<a href='search.php'>Search</a></div>";
        echo "<div id='logout'><a href='logout.php'>Log out</a></div>";
        $accessToken = get_token($code);
        $_SESSION['userId'] = get_userId($accessToken);
        $_SESSION['username'] = get_username($_SESSION['userId']);
    ?>
    <h2><?php echo $_SESSION['username'] . "'s Profile"; ?></h2>
    </div>
    <?php } ?>
</main>
<?php require_once 'inc/footer.php'; ?>