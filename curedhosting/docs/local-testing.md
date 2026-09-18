# Local syntax & coding-standards testing

## 1. PHP syntax (`php -l`)

With PHP installed locally (any 7.4+):

```bash
cd wp-content/themes/curedhosting
find . -name "*.php" -print0 | while IFS= read -r -d '' f; do php -l "$f"; done
```

Expected: `No syntax errors detected` for every file.

No PHP locally? Any CI runner with PHP can run the same one-liner. A pure-JS
parser fallback (used during this theme’s authoring) is `php-parser`:

```bash
npm i --no-save php-parser
node -e '
const {readFileSync,readdirSync,statSync}=require("fs"),{join}=require("path");
const P=require("php-parser");const eng=new P.Engine({parser:{version:"7.4"}});
const walk=d=>readdirSync(d).flatMap(f=>{const p=join(d,f);
  return statSync(p).isDirectory()?walk(p):(p.endsWith(".php")?[p]:[]);});
let bad=0;
for(const f of walk(".")){try{eng.parseCode(readFileSync(f,"utf8"))}
catch(e){bad++;console.log("SYNTAX ERROR",f,e.message)}}
console.log(bad?bad+" file(s) failed":"All PHP files parse OK");process.exit(bad?1:0);'
```

## 2. WordPress Coding Standards (PHPCS)

```bash
composer create-project wp-coding-standards/wpcs --no-plugins
vendor/bin/phpcs -i   # register the WordPress standard if needed
vendor/bin/phpcs --standard=WordPress \
  --extensions=php \
  --ignore=node_modules,vendor \
  wp-content/themes/curedhosting
```

Notes on this codebase:

- It targets WPCS but is not yet WPCS-clean by audit; expect informational
  findings (e.g. Yoda conditions, file-name rules). Run the report, fix or
  whitelist deliberately. `phpcs --report=summary` gives the short list.
- Intentional, documented exceptions: `$_GET`/`$_POST` reads are paired with
  `sanitize_*` and carry `phpcs:ignore` notes where the sniff can’t see it;
  nonces/capabilities guard every state-changing path.

## 3. Theme Check (optional, local only)

Install the **Theme Check** plugin on a disposable local site, run it against
CuredHosting, and treat its output as advisory: block themes intentionally
skip some classic-theme expectations (e.g. no `add_theme_support('custom-menu')`
because the navigation block replaces menus).

## 4. JSON validity

```bash
python3 -m json.tool wp-content/themes/curedhosting/theme.json > /dev/null && echo OK
```

## 5. JS syntax

```bash
node --check wp-content/themes/curedhosting/assets/js/theme.js && echo OK
```

## 6. Smoke test (after install)

1. Activate → dashboard notice → create pages → front page set.
2. Home renders 11 sections; no PHP notices with `WP_DEBUG=true`.
3. Submit contact form empty → error summary lists fields, values repopulate.
4. Submit valid onboarding form → success banner; private post appears under
   **CuredHosting → Submissions**; notification email arrives.
5. Edit a plan limit in settings → card *and* table update together.
6. Toggle promo off → section disappears from home and landing page.

What was verified in the authoring sandbox: `php-parser` syntax pass, JSON
validity, `node --check`. What was **not**: no WordPress runtime execution,
no PHPCS/WPCS audit, no browser testing. Run the checklists in §2–§6 locally.
