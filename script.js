window.onload = function() {
    setTimeout(function() {
      let allDice = document.querySelectorAll(".dice");
      allDice.forEach(function(die) {
        let result = die.getAttribute("data-result");
        die.src = "Slike/dice" + result + ".gif";
      });
      let sums = document.querySelectorAll(".dice-sum");
      sums.forEach(function(sum) {
        sum.style.display = "block";
      });
      let roundButtons = document.getElementById("round-buttons");
      if (roundButtons) {
        roundButtons.style.display = "block";
      }
    }, 2000);
  };
  