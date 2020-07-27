# Blog - Manuál
[zpět na blog](http://wa.toad.cz/~jezekpe6)

## 1. Obecné
Na celém webu nalezneme u horního okraje stránky horní menu, které obsahuje logo webu, odkazy na tento manuál, dokumentaci a blog.
Na pravé straně tohoto horního menu pak nalezneme uživatelskou sekci, kde nepřihlášený uživatel uvidí "sign in" odkaz, který ho navede na stránku
s přihlášením. Přihlášení uživatelé pak v tomto pravém horním rohu uvidí svoje "username". Při najetí na toho "username" se objeví nabídka s možnostmi
podle úrovně pravomocí přihlášeného uživatele. Běžný "user" má pravomoc pouze komentovat, a v nabídce bude pouze možnost odhlásit se. Uživatel "editor"
může spravovat příspěvky, proto se v nabídce objeví odkaz na stránku "posts". Nakonec uživatel "admin" může spravovat uživatele, a v nabídce proto krom
všeho co vidí editor a user, uvidí i odkaz na stránku "users".

Pro demonstraci jsou k dispozici 3 předvytvořené účty:
1. user (username: user, password: user)
2. editor (username: editor, password: editor)
3. admin (username: admin, password: admin)

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_1.png)

Na celém webu také nalezneme patičku, kde můžeme měnit vzhled webu. Defaultní nastavení vzhledu je "no theme".

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_2.png)

## 2. Úvodní stránka - Blog
Úvodní stránka blogu je rozdělená na dve sekce: "latest" a "everything". 

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_3.png)

V sekci "latest" je poslední, nejnovější příspěvek a v sekci "everything" 
všechny příspěvky, stránkované po pěti od nejnovějšího k nejstaršímu, tedy nejstarší příspěvek nalezneme na poslední straně. Mezi stránkami příspěvků
lze navigovat pomocí odkazů "pages" pod příspěvky. Bíle zvýrazněné číslo udává aktuální stránku. Kliknutím na obrázek nebo odkaz v názvu příspěvku se dostaneme na jeho náhled.

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_4.png)

## 3. Sign in
Tato stránka slouží k přihlášení uživatelů, kteří jsou již registrovaní. Obsahuje dvě kolonky - "username" na jméno a "password" na heslo. Obě jsou povinné.
V případě chybové hlášky je jeden z údajů nebo oba údaje chybné. Uživatelé, kteří se chtějí zaregistrovat mohou kliknout na odkaz "Dont have an accout yet?", který je dostane na stránku "register".

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_5.png)

## 4. Register
Stránka register slouží k registraci nových uživatelů. Obsahuje jak odkaz "Have an account already?" vedoucí zpět na stránku "sign in", tak kolonky na údaje. Všechny údaje jsou povinné. Email musí být ve správném formátu a jméno nesmí obsahovat jiné znaky než písmena bez diakritiky. Hesla se musí shodovat.

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_6.png)

## 5. View post
Tato stránka je náhled příspěvku. Sem se dostaneme klikem na příspěvek na hlavní straně blogu, nebo klikem na jméno příspěvku na stránka "pages" pro editory
a adminy.
Stránka obsahuje obrázek, název a datum přidání příspěvku a stejně tak jeho obsah. Pod příspěvkem nalezneme komentáře k příspěvku. Komentovat může přihlášený uživatel bez ohledu na jeho práva.

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_7.png)

## 6. Posts
Stránka posts je dostupná pouze pro uživatele role "editor" nebo "admin". Dostaneme se na ní přes pravé horní menu. Stránka obsahuje příspěvky v kompaktní formě. Klikem na název příspěvku se dostaneme na jeho náhled. Záznam příspěvku dále obsahuje datum přidání a odkazy pro akce "edit", "delete JS" a "delete".
Klikem na "edit" u příspěvku ho můžeme upravovat. Klikem na "delete JS" dostaneme alert okno, kdy po odsouhlasení smažeme příspěvek. 

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_8.png)

Odkaz "delete" smaže příspěvek bez optání.
Pod příspěvky se nachází tlačítko "new post", kterým můžeme přidat nový příspěvek.

## 7. Add post
Tato stránka obsahuje pole pro název příspěvku, tlačítko pro výběr náhledového obrázku a pole pro obsah příspěvku. Název i obrázek jsou povinné, obsah povinný není. Stisktnutím tlačítka "post" příspěvek přidáme.

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_9.png)

## 8. Edit post
Stránka je téměř totožná se stránkou "add post", jen bude již předvyplněná údaji z příspěvku který editujeme. Náhledový obrázek je z bezpečnostních důvodů nutno znovu vybrat i když ho původní příspěvek obsahoval.

## 9. Users
Stránka je dostupná jen pro uživatele role "admin". Je přístupná z pravého horního rohu. Stránka obsahuje uživatelské údaje, konkrétně jméno, email a úroveň pravomocí. Pravomoci můžeme měnit pomocí dropdownu, při absenci JavaScriptu aplikujeme změnu tlačítkem "update" u uživatele, u kterého jsme změnili pravamoci.
Tlačítkem pod uživateli se dostaneme na stránku registrace nového uživatele.

![](http://wa.toad.cz/~jezekpe6/assets/doc/man_10.png)

[zpět na blog](http://wa.toad.cz/~jezekpe6)
