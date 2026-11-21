# MilletRush — Guruswad Ocean Private Limited

Corporate site for Guruswad Ocean Private Limited, a vertically integrated
millet company in Maharashtra, and its consumer brand **MilletRush**.

The repository holds two deliverables that are kept deliberately in step:

| | |
|---|---|
| [`Guruswad-Ocean-Website/wordpress-theme/guruswad-ocean`](Guruswad-Ocean-Website/wordpress-theme/guruswad-ocean) | The WordPress + WooCommerce theme. What goes live. |
| [`Guruswad-Ocean-Website/static-preview`](Guruswad-Ocean-Website/static-preview) | A static HTML mirror of the same site, for review without a WordPress install. |

Both load the **same** `assets/css/main.css` and `assets/js/main.js`. A change
to either is made in the preview and synced across, so the preview is not an
approximation of the theme — it is the theme's markup with the PHP resolved.

## Running the preview

```bash
cd Guruswad-Ocean-Website/static-preview
python -m http.server 3000 --bind 127.0.0.1
```

Then <http://localhost:3000>. The admin panel mock-up is at `/admin/`.

> The admin sign-in in the static preview is a **demonstration gate, not
> security**. The check runs in the browser and anyone can read the source.
> The live site uses WordPress accounts, and must use a different password.

## Installing the theme

Copy `Guruswad-Ocean-Website/wordpress-theme/guruswad-ocean` into
`wp-content/themes/`, activate it, and work through the setup checklist the
theme adds under **Guruswad Ocean** in wp-admin. WooCommerce is optional —
the product pages work without it, since products are quoted rather than sold
from a cart.

## Commissioning notes

The brief and the design blueprint are the client's own documents and are kept
with the project files rather than in this repository, which is public. Two
things in them are not yet settled in code:

- **Products are presented on quotation**, not on a cart. The brief's §7 also
  describes retail online shopping; whether that is dropped or deferred is a
  client decision, and the cart was removed on that basis.
- Several pieces of content are placeholders by choice rather than oversight —
  leadership names and photographs, the nutrition panel pending FSSAI lab
  analysis, HS codes pending the CHA, and the ICAR–IIMR collaboration wording.
  They are marked in place; none of them are invented.

Replace `yourdomain.com` in the SEO meta and sitemap once the domain is final.

---

Built by Imperial Tech Innovations.
