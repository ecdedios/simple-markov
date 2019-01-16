<?php
/*
    Markov Chain Generator 2.0
    Copyright (c) 2018, Ednalyn C. De Dios <http://markov.datasciencenerd.us>
    Fork on Github: < http://github.com/ecdedios/markov-generator >
    
    Remixed from Hay Kranen's project!
    < https://www.haykranen.nl/2008/09/21/markov/ >
*/

require 'markov_chain_generator.php';

function process_post() {
    // generate text with markov library
    $order  = $_POST['order'];
    $length = 1000;
    $input  = $_POST['input'];
    $ptext  = 'emily';

    if (!ctype_digit($order) || !ctype_digit($length)) {
        throw new Exception("The order or length are not correct");
    }

    $order = (int) $order;
    $length = (int) $length;

    if ($order < 0 || $order > 20) {
        throw new Exception("Invalid order");
    }

    if ($length < 1 || $length > 25000) {
        throw new Exception("Text length is too short or too long");
    }

    if ($input) {
        $text = $input;
    } else if ($ptext) {
        if (!in_array($ptext, ['augustine', 'alice', 'beyond', 'calvin', 'emily', 'kant'])) {
            throw new Exception("Invalid text");
        } else {
            $text = file_get_contents("./text/$ptext.txt");
        }
    }

    if (empty($text)) {
        throw new Exception("No text given");
    }

    $markov_table = generate_markov_table($text, $order);
    $markov = generate_markov_text($length, $markov_table, $order);
    return htmlentities($markov);
}

if (isset($_POST['submit'])) {
    try {
        $markov = process_post();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>A Simple Markov Chain Generator by Ednalyn C. De Dios, a.k.a. &quot;Dd&quot;</title>
    
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=LbydQvl6Wb">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=LbydQvl6Wb">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=LbydQvl6Wb">
    <link rel="manifest" href="/site.webmanifest?v=LbydQvl6Wb">
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=LbydQvl6Wb" color="#1130c4">
    <link rel="shortcut icon" href="/favicon.ico?v=LbydQvl6Wb">
    <meta name="apple-mobile-web-app-title" content="Simple Markov Chain Generator">
    <meta name="application-name" content="Simple Markov Chain Generator">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png?v=LbydQvl6Wb">
    <meta name="theme-color" content="#ffffff">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    

  </head>

  <body>

    <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">About</h4>
              <p class="text-muted">My name is Dd and I’m an aspiring data scientist. <a href="http://datasciencenerd.us" title="Data Science Nerd blog." target="_self">Datasciencenerd.us</a> was created in December of 2018 when I got accepted to <a href="https://codeup.com/ds-admissions/" title="CodeUp's Data Science Career Accelerator program." target="_blank">CodeUp’s Data Science Career Accelerator program</a>. I hope to document my experience with this <a href="http://datasciencenerd.us" title="Data Science Nerd blog." target="_self">blog</a>.</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white">Contact</h4>
              <ul class="list-unstyled">
                <li><a href="https://twitter.com/ecdedios" class="text-white">Follow on Twitter</a></li>
                <li><a href="https://www.facebook.com/ddfloww" class="text-white">Like on Facebook</a></li>
                <li><a href="mailto:hello@datasciencenerd.us" class="text-white">Email me!</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
          <a href="http://datasciencenerd.us" class="navbar-brand d-flex align-items-center">
            
            <strong>MARKOV CHAIN GENERATOR</strong>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron text">
        <div class="container">
          <h1 class="jumbotron-heading">Emily's Prose</h1>
          <p class="lead text-muted">Input in, output out. This generator has been fed with the lyrical genius of Emily Dickinson. How will the machine live up to the fair maiden?</p>
          <p>
            <a href="#generated_text" class="btn btn-primary my-2">Let's dig in!</a>
          </p>
        </div>
      </section>
      
      <!-- MAIN CONTENT -->
      
      <section class="container">
        <div class="col-8">
          <h2>What It Is</h2>
          <p>According to <em><a href="https://brilliant.org/wiki/markov-chains/" >Brilliant.org</a></em>, "A Markov chain is a mathematical system that experiences transitions from one state to another according to certain probabilistic rules. The defining characteristic of a Markov chain is that no matter how the process arrived at its present state, the possible future states are fixed."</p>
          <p>Basically, in a chain of thinghies, a thinghy is a function of the previous thinghy depending on its weight or the frequency with which it follows the original thinghy.</p>
          <p><em>Confused yet? Yupp, me too.</em> I'm not even sure if i got that right. Alls I know is that it's fun to mess around with it.</p>
          <p>How about we just do it?</p>
          <h2>Doin' It</h2>
          <p id="generated_text">I've taken the full text of <em><a href="http://www.gutenberg.org/cache/epub/12242/pg12242.txt" title="The Complete Poems of Emily Dickinson" target="_blank">The Complete Poems of Emily Dickinson</a></em> and fed it to the generator. Click the "GO" button below to see the result!</p>
          
          <p><em>Pro Tip: Experiment with &quot;Order&quot; and see how it affects the output text. A low order allows for more 'creative' arrangement of words while a higher order will make the most sense.</em></p>
                    
          <!-- START: FORM -->
          <form method="post" action="#generated_text" name="markov">
            <input type="text" name="order" value="5" />
            <input class="btn btn-primary" type="submit" name="submit" value="GO">
          </form>
          <!-- END: FORM -->

            <p>&nbsp;</p>
            
            <?php if ($error): ?>
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>
                  <?= $error; ?>
                </strong> Contact Dd for debugging.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>

          
            <?php if ($markov): ?>
              <div class="card text-white bg-dark">
                <h5 class="card-header">Generated Text</h5>
                <div class="card-body">
                  <p class="card-text"><?= $markov; ?></p>
                </div>
              </div>
              <p>&nbsp;</p>
              
              <form method="post" action="#generated_text" name="markov">
                <input type="text" name="order" value="5" />
                <input class="btn btn-primary" type="submit" name="submit" value="GO">    
              </form>            
            <?php endif; ?>
          <p>&nbsp;</p>
          <p>&nbsp;</p>

        </div>
        <div class="col-4">
          
        </div>
      </section>
      <section class="jumbotron">
        <div class="container">
          <h1>Credits due...</h1>
          <a href="http://trump.frost.works/" title="Insta-Trump!" target="_blank">Victor Frost</a> for the inspiration and <a href="http://projects.haykranen.nl/markov/demo/" title="PHP Markov chain generator" target="_blank">Hay Kranen</a> for the Markov engine that powers this generator.
        </div>
      </section>

      <!-- END MAIN -->
        
    </main>

    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Back to top</a>
        </p>
        <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
        <p>Copyright &copy; <?php echo date("Y"); ?> <a href="http://datasciencenerd.us" target="_blank" title="Data Science Nerd Blog.">Data Science Nerd Blog</a>.</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-slim.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.min.js"></script>
    
  </body>
</html>
