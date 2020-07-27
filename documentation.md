# Blog - Dokumentace
[zpět na blog](http://wa.toad.cz/~jezekpe6)

## 1. Popis úlohy - Blog s komentáři

1. Cíl: Cílem projektu je vytvořit jednoduchý blog umožňující administrátorovi a editorům přidávat příspěvky a přihlášeným uživatelům tyto příspěvky komentovat

2. Uživatelské role:
- Nepřihlášený uživatel
- Přihlášený uživatel (user, editor, admin)

3. Funkce:
- příspěvek obsahuje thumbnail, název příspěvku, datum přidání, kategorii a text
- komentář obsahuje jméno autora, datum a čas přidání a text
- hlavní stránka blogu obsahuje několik příspěvků, seznamy jsou stránkovány
- příspěvek má pod obsahem sekci komentářů

4. Funkce podle rolí:
- Admin a editor mohou přidávat, upravovat a odebírat příspěvky
- Přihlášený uživatel, admin a editor mohou komentovat příspěvky
- Všichni mohou číst příspěvky
- Nepřihlášení uživatelé se mohou registrovat a posléze přihlásit
- Admin může mazat účty a pověřovat uživatele rolemi

5. Akceptační podmínky
Projekt je akceptovatelný pokud splňuje všechny body zadání.

## 2. [Uživatelská příručka](http://wa.toad.cz/~jezekpe6/manual.html)

## 3. Popis implementace
### 1. Frontend:
Klientská část webu využívá čistého HTML/HTML5, CSS a JavaScriptu s použitím jQuery frameworku.

HTML zpravidla sestává z head sekce s nalinkovaným CSS a jQuery / AJAX, dále sekcí body, která obsahuje PHP import šablony horní navigace a footeru a dále hlavní `<main>` sekci obsahující hlavní content stránky.

CSS styly jsou linkované z externích souborů a jsou rozdělené do souborů podle sekcí webu, kterých se týkají.
Je hojně využit `display: table` s `vertical-align: middle` a několik animací jako "slide-in" animace při načtení hlavní stránky blogu, nebo animace hlavního submit tlačítka např. u loginu. CSS animace jsou realizovány pomocí transitions a změny atributů např. přidáním třídy.
``` JavaScript
// includes/navbar.php - top navbar animation trigger after scrolling 100px from window's top via jQuery
function updateNavbar() {
    var scroll = $(window).scrollTop();
    if (scroll > 100) {
        $('#navbar').addClass('navbar-scroll');
        $('#login-dropdown-content').addClass('dropdown-content-scroll');
    } else if (scroll < 100) {
        $('#navbar').removeClass('navbar-scroll');
        $('#login-dropdown-content').removeClass('dropdown-content-scroll');
    }
}
```
Elementy jsou selectovány téměř výhradně pomocí tříd.

Při registraci je použit JavaScript pro real-time validaci obsahu formulářových polí. Email je ověřen funkcí match s regexovým výrazem:
``` JavaScript
// register.php - email format regex
var emailFormat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
if (!email.value.match(emailFormat)) {
    errElems.push(email);
    showErrMsg('&#9888;  Email format is invalid!');
}
```
Ostatní parametry musí mít nějakou nejmenší délku atd.

Dále je pak javascript použit pro asynchronní AJAX dotazy při mazání příspěvků nebo uživatelů.
``` JavaScript
// user/editor/posts.php - delete post AJAX GET request with url: delpost.php?key=id
function delpost(id, title) {
    if (confirm("Are you sure you want to delete '" + title + "'")) {
        $.ajax({
            type: 'GET',
            url: 'delpost.php',
            data: {
                key: id
            },
            success: function() {
                window.location.href = 'posts.php';
            }
        });
    }
}
```

Web funguje i bez přítomnosti JavaScriptu a vyhodí nad horní nabídkou červenou lištu upozorňující na fakt, že web funguje nejlépe s JavaScriptem.

### 2. Backend:
Serverová část je veskrze čisté PHP. Aplikace využívá dvě PHP třídy: `class.user.php` a `class.db.php`. Třída user uvěřuje přihlášení a poté uchovává data přihlášeného uživatele a umožňuje snadné ověření, jestli je uživatel přihlášen. Třida db je jakási zajímavost a je to na míru ušitý wrapper databáze, který provádí předdefinované dotazy voláním funkcí a vrací již parsovaná data.

Údaje o uživateli jako jeho `memeberID` nebo `username` jsou uloženy v proměnné `$_SESSION` spolu s údajem `loggedin`, který je použit právě ve tříde `class.user.php` pro ověření přihlášeného uživatele.

Většina nastavení aplikace se nachází v `includes/config.php`, kde jsou i definované údaje pro připojení do databáze.
Dále jsou tu načítány třídy pomocí `spl_autoload_register` funkce a definované konstanty. `config.php` je pak includován do většiny ostatních souborů, kde jsou poté dostupné všechy definované konstanty a proměnné.

Ve složce `includes/` jsou také přítomny dvě šablony: `navbar.php` a `footer.php`, které jsou includovány na stránkách.
`navbar.php` využívá definované PHP konstanty `$WEB_ROOT` pro relativní odkazy.
``` PHP
// relative path to $WEB_ROOT allows including from different directiores
<a href="<?php echo $WEB_ROOT; ?>user/logout.php">log out</a>
```

Chyby jsou zachytávány a errorové zprávy jsou přidávány do pole a posléze zobrazeny formátovaně v blízkosti formuláře / místa, kde chyby vznikly.
``` PHP
<!-- print errors formatted within the form -->
if (isset($err_msgs)) {
    foreach ($err_msgs as $err_msg) {
        echo '<p class="embed-err">&#9888; ' . $err_msg . '</p>';
    }
}
```

Všechny výpisy proměnných jsou realizované pomocí `htmlspecialchars()` funkce a XSS by nemělo být možné.
Při neúspěšném odeslání formuláře zůstanou hodnoty vyplněné (krom hesel). To je možné načtením z pole `$_POST` z odeslaného formuláře:
``` PHP
// register.php - data load from $_POST after unsuccesful validation
<input id="email" type='text' name='email' value='<?php if (isset($err_msgs)) { echo htmlspecialchars($_POST['email']); } ?>' autofocus>
```

Je implementována možnost registrace / přihlášení. Údaje jsou po submitu nejprve zkontrolovány javascriptem, poté poslány metodou POST na server, kde jsou opět zkontrolovány a při absenci errorů je zavolána funkce v databázovém wrapperu nebo třídě user, které provedou přislušný dotaz. Heslo je v databázi uchováváno v osoleném zahashovaném stavu. Hash je generování pomocí built-in PHP funkce `password_hash($pass, PASSWORD_BCRYPT)` a ověřován pomocí `password_verify()`.
``` PHP
// register.php - on submit
if (!isset($err_msgs)) {
    // encrypt password using the PHP bcrypt hash algo which includes salt
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);

    // add user record into database
    if ($db->addUser($email, $username, $hashedpassword)) {

.
.
.

// classes/class.user.php - DB wrapper
/* adds a user to the DB, storing email, username and hashed password */
    public static function addUser($email, $username, $hashedpassword)
    {
        try {
            $user_st = self::$_con->prepare('INSERT INTO blog_members (email,username,password) VALUES (:email, :username, :password)');
            return $user_st->execute(array(
                ':email' => $email,
                ':username' => $username,
                ':password' => $hashedpassword
            ));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
```

Web disponuje vzhledovým tématem, které lze navolit ve footeru kterékoliv stránky webu. Theme využívá cookies, které se při prvním navštívení nastaví defaultně "no theme", a při změně uchová nastavení po dobu 60ti minut. Každá stránka poté hledá přítomnost `theme` cookie a podlě něj se rozhodne nalinkovat theme.css soubor.
``` PHP
// theme.php - theme setcookie with 60 minutes duration
if (isset($_POST['theme'])) {
    if ($_POST['theme'] == 1) {
        setcookie('theme', '1', time() + 3600);
    } else {
        setcookie('theme', '0', time() + 3600);
    }
    header('Location: index.php');
}
.
.
.
// include theme style in any page
if (isset($_COOKIE['theme'])) {
    if ($_COOKIE['theme'] == 1) {
        echo '<link rel="stylesheet" href="' . $WEB_ROOT .  'style/theme.css">';
    }
}
```

Hlavní stránka webu využívá jednoduché stránkování realizované pomocí metody `$_GET`.
``` PHP
// index.php pagination
if (isset($_GET['pageNumber'])) {
    $pageNumber = $_GET['pageNumber'];
} else {
    // default page is 1st
    $pageNumber = 1;
}
```

Příspěvky v sekci "everything" jsou poté zobrazeny stránkovaně pomocí proměnných `$offset` a `$nRecordsPerPage`, kde offset definuje index prvního příspěvku na stránce a je zobrazeno n příspěvků. Číslo stránky je uchováváno metodou `$_GET` a přeposíláno při změně stránky.

Je použito uploadování souborů (obrázků) pomocí `move_uploaded_file()`, které jsou využity jako náhledové obrázky příspěvků.
Případ kdy soubor není nahrán je ošetřen, a příspěvek poté není nahrán do databáze.
V databázi je poté u příspěvku sloupec s umístěním náhladového obrázku a při zobrazení se z tohoto umístění obrázek načte pomocí `<img src="umisteni">`.
Adresář definovaný pro ukládání náhledových obrázků je `images/`.
``` PHP
// user/editor/addpost.php - upload thumbnail
if (!isset($err_msg)) {
    $file_uploaded = move_uploaded_file($_FILES["postThumb"]["tmp_name"], $thumbPath);

    if ($file_uploaded) {
        if ($db->addPost($postTitle, $_SESSION['memberID'], $thumbUrl, $postCont)) {
            header('Location: ' . $WEB_ROOT);
            exit;
        } else {
            $err_msg[] = 'Database error.';
        }
    } else {
        $err_msg[] = 'Failed to upload file.';
    }
}
```

## 4. Popis úložiště dat
Databáze je v jazyce `MySQL` a obsahuje tabulky `blog_comments`, `blog_members` a `blog_posts`. Komentáře, uživatelé i příspěvky mají unikátní ID, pomocí kterých probíhá v databázi vyhledávání (dotazování). Ve složitějších dotazech typu: "najdi komentáře k tomuto příspěvku" je v dotazu použit `LEFT OUTER JOIN`.

![](http://wa.toad.cz/~jezekpe6/assets/doc/doc_1.png)
![](http://wa.toad.cz/~jezekpe6/assets/doc/doc_2.png)
![](http://wa.toad.cz/~jezekpe6/assets/doc/doc_3.png)
![](http://wa.toad.cz/~jezekpe6/assets/doc/doc_4.png)

Údaje k připojení jsou definovány v souboru `config.php`:
``` PHP
// includes/config.php DB consts definition
define('DBHOST', 'remotemysql.com');
define('DBUSER', '8jdyVkqPuG');
define('DBPASS', 'aqCtuEef9y');
define('DBNAME', '8jdyVkqPuG');
```
Samotné připojení je pak realizování pomocí `PDO` ve wrapperu `classes/class.db.php`.

Databáze je dotazována pouze wrapperem `classes/class.db.php`, jehož funkce volají ostatní skripty pokud chtějí data.

Dalším využitým úložištěm je samotná složka aplikace, kam se do adresáře `images/` ukládají náhledové obrázky k příspěvkům.

[zpět na blog](http://wa.toad.cz/~jezekpe6)