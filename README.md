# Gambling Room

## Pregled

**Gambling Room** je interaktivna spletna igra, ki posnema vzdušje igralnice s poudarkom na metanju kock. Igra združuje elemente animacije in interaktivnosti z uporabo sodobnih spletnih tehnologij, kot so PHP, JavaScript in CSS. Namen igre je ustvariti dinamično izkušnjo, kjer igralci opazujejo animirane kocke in spremljajo rezultate metov.

## Funkcionalnosti

- **Animacija kock:**  
  Ob nalaganju strani se z uporabo JavaScript kode (vidi [script.js]&#8203;:contentReference[oaicite:0]{index=0}) pričakuje 2-sekundni zamik, nato pa se na osnovi vrednosti atributa `data-result` posameznim kockam dodelijo animirane slike (npr. `Slike/dice1.gif`, `Slike/dice2.gif`, itd.).

- **Prikaz rezultatov:**  
  Elementi, označeni z razredom `.dice-sum`, se prikažejo po zaključku animacije, kar omogoča igralcem, da vidijo seštevek rezultatov.

- **Interaktivnost in navigacija:**  
  Ob dodajanju dodatnih možnosti v igri se prikažejo tudi kontrolni gumbi (npr. za izbiro rund), ki omogočajo nadaljnje interakcije med igro.

- **Vizualni učinki:**  
  Igra uporablja privlačno stilizacijo z ozadjem, animiranimi naslovi (npr. neon učinek) in elegantnim centriranjem elementov, kar izboljša celostno uporabniško izkušnjo (vidi [style.css]&#8203;:contentReference[oaicite:1]{index=1}).

## Struktura projekta

- **index.php**  
  Glavna vhodna datoteka, ki nalaga vse potrebne vire (HTML, CSS, JavaScript) in ustvarja osnovno strukturo spletne strani.

- **script.js**  
  Vsebina JavaScript datoteke je ključna za animacijo kock. Skripta čaka 2 sekundi po nalaganju strani, nato spremeni slike kock glede na njihove podatke in prikaže elemente z rezultati metov.  
  *(Podrobnosti: :contentReference[oaicite:2]{index=2})*

- **style.css**  
  Datoteka vsebuje stile za celotno aplikacijo, vključno s centriranjem strani, ozadjem, animacijami in drugimi vizualnimi učinki, ki prispevajo k izgledu igralnice.  
  *(Podrobnosti: :contentReference[oaicite:3]{index=3})*

- **Slike/**  
  V mapi Slike so shranjene vse potrebne grafike, med katerimi so ozadja ter animirane slike kock (npr. `dice1.gif`, `dice2.gif` itd.).

## Namestitev in zagon

1. **Nastavitev strežniškega okolja:**  
   Poskrbite, da imate nameščen spletni strežnik s podporo za PHP (npr. Apache ali Nginx).

2. **Kopiranje datotek:**  
   Kopirajte vse datoteke in mape (index.php, script.js, style.css, Slike) v korensko mapo vašega spletnega strežnika.

3. **Zagon igre:**  
   Zaženite spletni strežnik in odprite URL, kjer ste namestili projekt (npr. `http://localhost/GamblingRoom`) v vašem brskalniku.

## Delovanje igre

- **JavaScript interakcija:**  
  Ko se stran naloži, se izvede funkcija, ki čez 2 sekundi posodobi vse elemente z razredom `.dice` glede na vrednosti atributa `data-result`. Poleg tega se prikažejo rezultati in kontrolni gumbi za nadaljnje kroženje iger.

- **CSS stilizacija:**  
  Datoteka style.css zagotavlja privlačno postavitev strani z ozadjem, animacijami, in interaktivnimi elementi, kar skupaj ustvarja izkušnjo, podobno tisti v prave igralnici.

## Prilagoditve in nadgradnje

- **Dodajanje novih funkcionalnosti:**  
  Strukturo igre je mogoče razširiti z dodatnimi elementi, kot so leader board, možnost stave ali dodatne igre, ki bi še bolj obogatile igralniško izkušnjo.

- **Spremembe v dizajnu:**  
  Z enostavno prilagoditvijo CSS stilov lahko spremenite videz igre, da bo ustrezal vaši viziji in potrebam.

## Licenca

Dodajte ustrezno licenco glede na vaše zahteve in način distribucije projekta.
