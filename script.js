// script.js

// Modal z navodili
document.getElementById('btn-navodila').addEventListener('click', function() {
  Swal.fire({
    title: 'Navodila za igro',
    icon: 'info',
    html: `
      <ol style="text-align:left;">
        <li>Izberite število igralcev, rund in kock.</li>
        <li>Vnesite ime in priimek za vsakega igralca.</li>
        <li>Kliknite “Začni igro” in počakajte na animacijo metov.</li>
        <li>Po 2 sekundah se prikažejo rezultati trenutne runde in gumb za naslednjo rundo.</li>
        <li>Po zadnji rundi bo prikazan končni rezultat in zmagovalec.</li>
        <li>Stran se samodejno preusmeri nazaj na začetek čez 5 sekund.</li>
      </ol>
    `,
    confirmButtonText: 'V redu',
    heightAuto: false
  });
});

// Modal z vizitko
document.getElementById('btn-vizitka').addEventListener('click', function() {
  Swal.fire({
    title: 'Vizitka',
    icon: 'info',
    html: `
      <p style="text-align:center;">
        <strong>Gambling Room</strong><br>
        Avtor: Miha Sever<br>
        Github: <a href="https://github.com/miholin/">miholin</a><br>
      </p>
    `,
    confirmButtonText: 'Zapri',
    heightAuto: false
  });
});

// Obstoječa logika za animacijo kock
window.onload = function() {
  setTimeout(function() {
    document.querySelectorAll(".dice").forEach(function(die) {
      let res = die.getAttribute("data-result");
      die.src = "Slike/dice" + res + ".gif";
    });
    document.querySelectorAll(".dice-sum").forEach(el => el.style.display = "block");
    let btns = document.getElementById("round-buttons");
    if (btns) btns.style.display = "block";
  }, 2000);
};
