<?php
session_start();
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <title>Gambling Room</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="center-box">

  <?php
  echo '<h1 class="animated-title">Gambling Room</h1>';
  
  // Nastavimo privzeti korak (step). Če ni poslan, je step = 0.
  $step = isset($_POST['step']) ? (int)$_POST['step'] : 0;
  
  /*
    KORAKI:
      step=0 -> Izbira števila igralcev, rund in kock (izberi vodoravno)
      step=1 -> Vnos podatkov (ime, priimek) za igralce (v fieldsetih postavljenih vodoravno)
      step=2 -> Izvedba ene runde: met kock za vsakega igralca, animacija in prikaz rezultatov ter gumbi (gumbi so skriti, dokler se animacija ne zaključi)
      step=3 -> Končni leaderboard in zmagovalci ter časovnik, ki šteje do preusmeritve na domačo stran
  */
  
  switch ($step) {
  
    // -------------------------------
    // KORAK 0: Izbira števila igralcev, rund in kock (vodoravno)
    // -------------------------------
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
  
    // -------------------------------
    // KORAK 1: Vnos imen in priimkov (vodoravno urejeni fieldseti)
    // -------------------------------
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
  
    // -------------------------------
    // KORAK 2: Izvedba ene runde
    // -------------------------------
    case 2:
      if (isset($_POST['ime']) && isset($_POST['priimek'])) {
        $_SESSION['players'] = [];
        $numP = $_SESSION['num_players'];
        for ($i = 0; $i < $numP; $i++) {
          $_SESSION['players'][] = [
            'ime'     => $_POST['ime'][$i],
            'priimek' => $_POST['priimek'][$i],
            'sum'     => 0
          ];
        }
        $_SESSION['currentRound'] = 1;
      } else {
        if (isset($_POST['next_round'])) {
          $_SESSION['currentRound']++;
        }
      }
      if ($_SESSION['currentRound'] > $_SESSION['num_rounds']) {
        ?>
        <form method="post">
          <input type="hidden" name="step" value="3">
          <script>document.forms[0].submit();</script>
        </form>
        <?php
        break;
      }
      $round = $_SESSION['currentRound'];
      echo "<h2>Runda $round od " . $_SESSION['num_rounds'] . "</h2>";
  
      $numP    = $_SESSION['num_players'];
      $numDice = $_SESSION['num_dice'];
      for ($i = 0; $i < $numP; $i++) {
        $diceResults = [];
        for ($j = 0; $j < $numDice; $j++) {
          $diceResults[] = rand(1, 6);
        }
        $roundSum = array_sum($diceResults);
        $_SESSION['players'][$i]['sum'] += $roundSum;
  
        echo "<div class='user-box fade-in' style='display:inline-block; margin:10px;'>";
        echo "<p><strong>Igralec " . ($i+1) . "</strong><br>";
        echo $_SESSION['players'][$i]['ime'] . " " . $_SESSION['players'][$i]['priimek'] . "</p>";
  
        echo "<p>Meti kock:<br>";
        foreach ($diceResults as $res) {
          echo '<img class="dice" data-result="' . $res . '" src="Slike/dice-anim.gif" alt="Kocka">';
        }
        echo "</p>";
        echo "<p class='dice-sum' style='display:none;'>";
        echo "Točke v tej rundi: <strong>$roundSum</strong><br>";
        echo "Skupaj do zdaj: <strong>" . $_SESSION['players'][$i]['sum'] . "</strong>";
        echo "</p>";
        echo "</div>";
      }
  
      // Gumbi so oviti v div#round-buttons, sprva skriti (prikaže jih JS po 2 sekundah)
      echo "<div id='round-buttons' style='display:none; margin-top:20px;'>";
      if ($round < $_SESSION['num_rounds']) {
        echo "<form method='post' style='display:inline;'>";
        echo "<input type='hidden' name='step' value='2'>";
        echo "<button type='submit' name='next_round'>Naslednja runda</button>";
        echo "</form>";
      } else {
        echo "<form method='post' style='display:inline;'>";
        echo "<input type='hidden' name='step' value='3'>";
        echo "<input type='submit' value='Pokaži končni rezultat'>";
        echo "</form>";
      }
      echo "</div>";
      echo '<script src="script.js"></script>';
      break;
  
    // -------------------------------
    // KORAK 3: Končni scoreboard in preusmeritev z timerjem
    // -------------------------------
    case 3:
      echo "<h2>Končni rezultati</h2>";
      $scoreboard = $_SESSION['players'];
      usort($scoreboard, function($a, $b) {
        return $b['sum'] <=> $a['sum'];
      });
      echo "<table class='leaderboard-table' style='margin:0 auto;'>";
      echo "<tr><th>Mesto</th><th>Igralec</th><th>Skupni rezultat</th></tr>";
      $place = 1;
      $najvec = $scoreboard[0]['sum'];
      foreach ($scoreboard as $p) {
        echo "<tr>";
        echo "<td>" . $place . "</td>";
        echo "<td>" . $p['ime'] . " " . $p['priimek'] . "</td>";
        echo "<td>" . $p['sum'] . "</td>";
        echo "</tr>";
        $place++;
      }
      echo "</table>";
      
      $zmagovalci = array_filter($scoreboard, function($x) use ($najvec) {
        return $x['sum'] == $najvec;
      });
      
      echo "<div class='winner-box fade-in' style='margin-top:20px;'>";
      if (count($zmagovalci) == 1) {
        $w = reset($zmagovalci);
        echo "<h3>Zmagovalec: " . $w['ime'] . " " . $w['priimek'] . "</h3>";
      } else {
        echo "<h3>Več zmagovalcev:</h3>";
        foreach ($zmagovalci as $w) {
          echo $w['ime'] . " " . $w['priimek'] . "<br>";
        }
      }
      echo "</div>";
      
      // Dodajamo timer za preusmeritev
      ?>
      <p id="redirect-timer">Preusmeritev na domačo stran čez <span id="countdown">5</span> sekund...</p>
      <script>
        var seconds = 5;
        var countdownSpan = document.getElementById("countdown");
        var countdownInterval = setInterval(function() {
          seconds--;
          countdownSpan.textContent = seconds;
          if (seconds <= 0) {
            clearInterval(countdownInterval);
          }
        }, 1000);
        setTimeout(function(){
          window.location.href = 'index.php';
        }, 5000);
      </script>
      <?php
      break;
  
  } // end switch
  ?>
  
</div><!-- end center-box -->
</body>
</html>
