# Nordic Vagabonds — WordPress-tema

Standalone WordPress-tema for Nordic Vagabonds AS.

## Krav
- WordPress 6.0+
- Advanced Custom Fields (ACF) — gratis versjon holder
- Contact Form 7 (valgfritt, for kontaktskjema)

## Installasjon

### 1. Last opp temaet
Last opp `nordicvagabonds-theme/` mappen (eller zip) via Utseende → Temaer → Last opp tema.
Aktiver **Nordic Vagabonds**.

### 2. Installer plugins
- **Advanced Custom Fields** (søk i Plugins → Legg til ny)
- **Contact Form 7** (valgfritt)

### 3. Sett startside
- Gå til Innstillinger → Lesing
- Velg «En statisk side»
- Opprett en tom side kalt «Forside» og velg den

### 4. Rediger innhold
- Åpne forsiden i redigering — alle felt vises under «Forsideinnhold»
- Legg til teammedlemmer under **Teammedlemmer** i venstremenyen
- Legg til/endre tjenester under **Tjenester**

### 5. Kontaktskjema (valgfritt)
- Lag et nytt skjema i Contact Form 7
- Kopier skjema-ID og lim inn i ACF-feltet «Kontakt — CF7 skjema-ID» på forsiden

## Statisk versjon
`index.html` i rotkatalogen er en komplett statisk versjon som kan deployes direkte (GitHub Pages, Netlify, Vercel, etc.) uten WordPress.

## Filstruktur
```
nordicvagabonds/
├── index.html                ← Statisk versjon (ingen WP nødvendig)
├── nordicvagabonds-theme/    ← WordPress-tema
│   ├── style.css
│   ├── functions.php
│   ├── front-page.php
│   ├── header.php
│   ├── footer.php
│   ├── index.php
│   ├── 404.php
│   ├── page.php
│   ├── assets/js/main.js
│   └── template-parts/
│       ├── hero.php
│       ├── about.php
│       ├── services.php
│       ├── team.php
│       ├── press.php
│       └── contact.php
```
