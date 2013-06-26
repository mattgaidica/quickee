
<?php
ini_set('display_errors', true);
date_default_timezone_set('America/Detroit');

include_once('quickee/markdown/markdown.php');
$config = json_decode(file_get_contents('config.json'), true);

function parse_post($post, $return_html=true) {
  $raw_post = file_get_contents($post);
  if(strpos($raw_post, '{') == 0) {
    $meta_string = substr($raw_post, 0, strpos($raw_post, '}') + 1);
    $post_string = trim(substr($raw_post, strpos($raw_post, '}') + 1));
  } else {
    return false;
  }
  if($return_html) {
    $post_string = Markdown($post_string);
  }
  $meta = json_decode($meta_string, true);
  $meta['date'] = filectime($post);
  return Array($meta, $post_string);
}

function get_posts() {
  $posts = glob('posts/*.md');
  if(!empty($posts)) {
    foreach($posts as $post) {
      $tmp[$post] = filemtime($post);
    }
    arsort($tmp);
    return array_keys($tmp);
  }
  return false;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php if(!empty($config['title'])): ?>
      <title><?php echo $config['title']; ?></title>
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/png" href="./quickee/icon.png">
    <link href="./quickee/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./quickee/style.css" rel="stylesheet">
    <link href="./quickee/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

  </head>

  <body>

    <div class="container-narrow">

      <?php if(!empty($config['title']) || !empty($config['subtitle'])): ?>
        <div class="jumbotron">
          <?php if(!empty($config['title'])): ?><h1><?php echo $config['title'] ?></h1><?php endif; ?>
          <?php if(!empty($config['subtitle'])): ?><p class="lead"><?php echo $config['subtitle'] ?></p><?php endif; ?>
        </div>
        <hr style="margin-left:100px;margin-right:100px;">
      <?php endif; ?>

      <div class="row-fluid marketing">
        <?php $posts = get_posts(); ?>
        <?php if($posts !== false): ?>
          <?php foreach($posts as $post): ?>
            <?php $data = parse_post($post); ?>
            <?php if($data !== false): ?>
              <h2><?php echo $data[0]['title'] ?></h2>
              <?php echo $data[1]; ?>
              <br/>
              <p class="muted"><em>posted <?php echo date('l, F j, Y', $data[0]['date']) ?></em></p>
              <hr>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="footer muted">
        <p>&copy; <?php echo $config['copyright']; ?> <?php echo date('Y'); ?></p>
      </div>

    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="./quickee/bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>
