<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <title>Gambling Room</title>
  <link rel="stylesheet" href="style.css">
  <!-- SweetAlert2 za modal -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="center-box">

    <?php
    echo '<h1 class="animated-title">Gambling Room</h1>';
    
    // Nastavimo privzeti korak (step). Če ni poslan, je step = 0.
    $step = isset($_POST['step']) ? (int)$_POST['step'] : 0;
    
    switch ($step) {
      // KORAK 0: izbira števila igralcev, rund in kock
      case 0:
    ?>
        <h2>Izberi število igralcev, rund in kock</h2>
        <form method="post">
          <div class="selection-row">
            <div class="selection-item">
              <label>Število igralcev:</label>
              <select name="num_players">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="selection-item">
              <label>Število rund:</label>
              <select name="num_rounds">
                <?php for ($r = 1; $r <= 10; $r++): ?>
                  <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="selection-item">
              <label>Število kock:</label>
              <select name="num_dice">
                <?php for ($d = 1; $d <= 6; $d++): ?>
                  <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                <?php endfor; ?>
              </select>
            </div>
          </div>
          <br>
          <input type="hidden" name="step" value="1">
          <input type="submit" value="Naprej">
        </form>
    <?php
        break;
      
      // KORAK 1: vnos imen in priimkov
      case 1:
        $_SESSION['num_players'] = (int)$_POST['num_players'];
        $_SESSION['num_rounds']  = (int)$_POST['num_rounds'];
        $_SESSION['num_dice']    = (int)$_POST['num_dice'];
    ?>
        <h2>Vnesite podatke za <?php echo $_SESSION['num_players']; ?> igralcev</h2>
        <form method="post">
          <div class="fieldset-row">
            <?php
            for ($i = 1; $i <= $_SESSION['num_players']; $i++) {
              echo "<fieldset>";
              echo "<legend>Igralec $i</legend>";
              echo "<label>Ime:</label><br>";
              echo "<input type='text' name='ime[]' required><br><br>";
              echo "<label>Priimek:</label><br>";
              echo "<input type='text' name='priimek[]' required>";
              echo "</fieldset>";
            }
            ?>
          </div>
          <br>
          <input type="hidden" name="step" value="2">
          <input type="submit" value="Začni igro">
        </form>
    <?php
        break;
      
      // KORAK 2: izvajanje rund
      case 2:
        if (isset($_POST['ime']) && isset($_POST['priimek'])) {
          $_SESSION['players'] = [];
          for ($i = 0; $i < $_SESSION['num_players']; $i++) {
            $_SESSION['players'][] = [
              'ime'     => $_POST['ime'][$i],
              'priimek' => $_POST['priimek'][$i],
              'sum'     => 0
            ];
          }
          $_SESSION['currentRound'] = 1;
        } elseif (isset($_POST['next_round'])) {
          $_SESSION['currentRound']++;
        }
        if ($_SESSION['currentRound'] > $_SESSION['num_rounds']) {
          echo '<form method="post">'
             .    '<input type="hidden" name="step" value="3">'
             .    '<script>document.forms[0].submit();</script>'
             .  '</form>';
          break;
        }
        $round = $_SESSION['currentRound'];
        echo "<h2>Runda $round od " . $_SESSION['num_rounds'] . "</h2>";
        for ($i = 0; $i < $_SESSION['num_players']; $i++) {
          $diceResults = [];
          for ($j = 0; $j < $_SESSION['num_dice']; $j++) {
            $diceResults[] = rand(1, 6);
          }
          $roundSum = array_sum($diceResults);
          $_SESSION['players'][$i]['sum'] += $roundSum;
    
          echo "<div class='user-box fade-in'>";
          echo "<p><strong>Igralec " . ($i + 1) . "</strong><br>"
             . $_SESSION['players'][$i]['ime'] . " " . $_SESSION['players'][$i]['priimek']
             . "</p>";
          echo "<p>Meti kock:<br>";
          foreach ($diceResults as $res) {
            echo '<img class="dice" data-result="' . $res . '" src="Slike/dice-anim.gif" alt="Kocka">';
          }
          echo "</p>";
          echo "<p class='dice-sum' style='display:none;'>"
             . "Točke v tej rundi: <strong>$roundSum</strong><br>"
             . "Skupaj do zdaj: <strong>" . $_SESSION['players'][$i]['sum'] . "</strong>"
             . "</p>";
          echo "</div>";
        }
        echo "<div id='round-buttons' style='display:none;'>";
        if ($round < $_SESSION['num_rounds']) {
          echo '<form method="post" style="display:inline;">'
             .    '<input type="hidden" name="step" value="2">'
             .    '<button type="submit" name="next_round">Naslednja runda</button>'
             .  '</form>';
        } else {
          echo '<form method="post" style="display:inline;">'
             .    '<input type="hidden" name="step" value="3">'
             .    '<input type="submit" value="Pokaži končni rezultat">'
             .  '</form>';
        }
        echo "</div>";
        echo '<script src="script.js"></script>';
        break;
      
      // KORAK 3: končni rezultati
      case 3:
        echo "<h2>Končni rezultati</h2>";
        $board = $_SESSION['players'];
        usort($board, function($a, $b) {
          return $b['sum'] <=> $a['sum'];
        });
        echo '<table class="leaderboard-table">';
        echo '<tr><th>Mesto</th><th>Igralec</th><th>Skupni rezultat</th></tr>';
        $first = $board[0]['sum'];
        $place = 1;
        foreach ($board as $p) {
          echo "<tr><td>$place</td><td>{$p['ime']} {$p['priimek']}</td><td>{$p['sum']}</td></tr>";
          $place++;
        }
        echo '</table>';
        $winners = array_filter($board, function($x) use ($first) {
          return $x['sum'] == $first;
        });
        echo '<div class="winner-box fade-in">';
        if (count($winners) === 1) {
          $w = array_values($winners)[0];
          echo "<h3>Zmagovalec: {$w['ime']} {$w['priimek']}</h3>";
        } else {
          echo '<h3>Več zmagovalcev:</h3>';
          foreach ($winners as $w) {
            echo "{$w['ime']} {$w['priimek']}<br>";
          }
        }
        echo '</div>';
        ?>
        <p id="redirect-timer">
          Preusmeritev na domačo stran čez <span id="countdown">5</span> sekund...
        </p>
        <script>
          let sec = 5, sp = document.getElementById("countdown");
          setInterval(function() {
            if (sec > 0) sp.textContent = --sec;
          }, 1000);
          setTimeout(function(){
            location.href = 'index.php';
          }, 5000);
        </script>
        <?php
        break;
    }
    ?>

    <!-- Gumbi Navodila + Vizitka pod vsakim form/submita -->
    <div class="button-row">
      <button id="btn-navodila">Navodila</button>
      <button id="btn-vizitka">Vizitka</button>
    </div>

  </div><!-- end center-box -->

  <script src="script.js"></script>
</body>
</html>
