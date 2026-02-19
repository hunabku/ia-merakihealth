# Build System — Meraki Theme

El sistema de compilación SCSS vive en la carpeta `build/`.
Los archivos fuente están en `assets/scss/` y compilan a `assets/css/main.css`.

## Requisitos

- Node.js 18+
- npm 9+

## Instalación (primera vez o al clonar el repo)

```bash
# Desde raíz del tema
npm --prefix build install

# O entrando a build/
cd build && npm install
```

## Desarrollo (watch mode)

Compila automáticamente al guardar cualquier archivo `.scss`:

```bash
# Desde raíz del tema
npm --prefix build run dev

# O entrando a build/
cd build && npm run dev
```

## Producción (build minificado)

```bash
# Desde raíz del tema
npm --prefix build run build

# O entrando a build/
cd build && npm run build
```

## Estructura SCSS

```
assets/scss/
├── main.scss          ← entry point (no editar directamente)
├── _variables.scss    ← CSS custom properties y variables SCSS
├── _reset.scss        ← box-sizing reset mínimo
├── _base.scss         ← body, headings, img, a
├── _layout.scss       ← .meraki-site, .meraki-content
├── _header.scss       ← .meraki-header
├── _nav.scss          ← .meraki-nav
├── _footer.scss       ← .meraki-footer
├── _elementor.scss    ← overrides de Elementor
├── _posts.scss        ← entry-meta, entry-content
└── _404.scss          ← página de error 404
```

## Notas

- `assets/css/main.css` se commitea al repo — el tema funciona en producción sin necesitar Node.
- `style.css` (raíz) carga **después** de `main.css` — úsalo para overrides rápidos sin tocar SCSS.
- `build/node_modules/` está en `.gitignore`.
