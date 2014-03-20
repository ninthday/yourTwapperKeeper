<?php
/*
  yourTwapperKeeper - Twitter Archiving Application - http://your.twapperkeeper.com
  Copyright (c) 2010 John O'Brien III - http://www.linkedin.com/in/jobrieniii

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// Set Important / Load important
session_start();
require_once('config.php');
require_once('function.php');
require_once('twitteroauth.php');

// OAuth login check
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    $login_status = "<a href='./oauthlogin.php' ><img src='./resources/lighter.png'/></a>";
    $logged_in = FALSE;
} else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth($tk_oauth_consumer_key, $tk_oauth_consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $login_info = $connection->get('account/verify_credentials');
    $login_status = "<a href='./clearsessions.php'>Hi " . $_SESSION['access_token']['screen_name'] . ", logout</a>";
    $logged_in = TRUE;
}
?>

<!DOCTYPE html>
<html lang="zh-tw">

    <head>
        <title>niceKeeper - Archive your own tweets</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>
        <!-- Fixed navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Flood and Fire Keeper</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><?php echo $login_status; ?></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <div class="container" style="margin-top: 60px;" role="main">
            <div class="row">
                <div class="col-xs-9">
                    <p><center><a href='index.php'><img src='resources/ownerLogo.png'/></a></center></p>
                </div>
                <div class="col-xs-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">Archiving processes<span class="label label-success pull-right">3</span></div>
                        <div class="panel-body">
                            <?php
                            $archiving_status = $tk->statusArchiving($archive_process_array);
                            echo "<p>" . $archiving_status[1] . "</p>";
                            if (in_array($_SESSION['access_token']['screen_name'], $admin_screen_name)) {
                                if ($archiving_status[0] == FALSE) {
                                    echo '<a href="startarchiving.php" class="btn btn-success btn-sm" title="Start Archving"><span class="glyphicon glyphicon-play"></span> Start</a>';
                                } else {
                                    echo '<a href="stoparchiving.php" class="btn btn-danger btn-sm" title="Stop Archiving"><span class="glyphicon glyphicon-stop"></span> Stop</a>';
                                }
                            }
                            ?>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item"><span class="glyphicon glyphicon-tasks"></span> PID:</li>
                            <li class="list-group-item"><span class="glyphicon glyphicon-tasks"></span> PID:</li>
                            <li class="list-group-item"><span class="glyphicon glyphicon-tasks"></span> PID:</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if ($logged_in) { ?>
                    <div class="well">
                        <form class="form-inline" action='create.php' method='post' role="form">
                            <div class="form-group">
                                <label class="sr-only" for="InputKeyword">Keyword or Hashtag</label>
                                <input type="keyword" class="form-control" id="InputKeyword" placeholder="Keyword or Hashtag">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="InputDescription">Description</label>
                                <input type="description" class="form-control" id="InputDescription" placeholder="Description">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="InputTags">Tags</label>
                                <input type="tags" class="form-control" id="InputTags" placeholder="Tags">
                            </div>
                            <input type='submit' class="btn btn-primary" value ='Create Archive'/>
                        </form>
                    </div>
                <?php } ?>
            </div>
            <hr>
            <?php if (isset($_SESSION['notice'])) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Sorry!</strong> <?php echo $_SESSION['notice']; ?>
                </div>
                <?php
                unset($_SESSION['notice']);
            }
            ?> 
            <div class="row">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Archive ID</th><th>Keyword / Hashtag</th><th>Description</th><th>Tags</th><th>Screen Name</th><th>Count</th><th>Create Time</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // list table of archives
                        $archives = $tk->listArchive();
                        foreach ($archives['results'] as $value) {
                            echo "<tr><td>" . $value['id'] . "</td><td>" . $value['keyword'] . "</td><td>" . $value['description'] . "</td><td>" . $value['tags'] . "</td><td>" . $value['screen_name'] . "</td><td>" . $value['count'] . "</td><td>" . date(DATE_RFC2822, $value['create_time']) . "</td>";
                            echo "<td>";
                            echo "<a href='archive.php?id=" . $value['id'] . "' target='_blank' alt='View'><img src='./resources/binoculars_24.png' alt='View Archive' title='View Archive'/></a>";
                            if ($_SESSION['access_token']['screen_name'] == $value['screen_name']) {
                                ?>
                            <script type="text/javascript">
                                $(function() {
                                    $("#deletedialog<?php echo $value['id']; ?>").dialog({
                                        autoOpen: false,
                                        height: 150,
                                        width: 800,
                                        modal: true
                                    });

                                    $('#deletelink<?php echo $value['id']; ?>').click(function() {
                                        $('#deletedialog<?php echo $value['id']; ?>').dialog('open');
                                        return false;
                                    });

                                    $("#updatedialog<?php echo $value['id']; ?>").dialog({
                                        autoOpen: false,
                                        height: 300,
                                        width: 300,
                                        modal: true
                                    });

                                    $('#updatelink<?php echo $value['id']; ?>').click(function() {
                                        $('#updatedialog<?php echo $value['id']; ?>').dialog('open');
                                        return false;
                                    });


                                });
                            </script>

                            <div id = 'deletedialog<?php echo $value['id']; ?>' title='Are you sure you want to delete <?php echo $value['keyword']; ?> archive?'>
                                <br><br><center><form method='post' action='delete.php'><input type='hidden' name='id' value='<?php echo $value['id']; ?>'/><input type='submit' value='Yes'/></form></center>
                            </div> 

                            <div id = 'updatedialog<?php echo $value['id']; ?>' title='Update <?php echo $value['keyword']; ?> archive'>
                                <br><br><center><form method='post' action='update.php'>Description<br><input name='description' value='<?php echo $value['description']; ?>'/><br><br>Tags<br><input name='tags' value='<?php echo $value['tags']; ?>'/><input type='hidden' name='id' value='<?php echo $value['id']; ?>'/><br><br><p><input type='submit' value='Update'/></p></form></center>
                            </div> 
                            <?php
                            echo "<a href='#' id='updatelink" . $value['id'] . "'><img src='./resources/pencil_24.png' alt='Edit Archive' title='Edit Archive'/></a>";
                            echo "  <a href='#' id='deletelink" . $value['id'] . "'><img src='./resources/close_2_24.png' alt='Delete Archive' title='Delete Archive'/></a>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>

                    </tbody>
                </table>
            </div>
            <hr>
            <footer>
                <p>niceKeeper version 0.1.1 (yourTwapperKeeper <?php echo $yourtwapperkeeper_version; ?>)</p>
            </footer>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="resources/js/jquery-ui-1.8.4.custom.min.js"></script>
    </body>
</html>
