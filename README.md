# DK Starter Theme - Webpack + BrowserSync + SCSS + WordPress Integration

This is a starter front-end development environment that integrates SCSS, Webpack, and BrowserSync for a streamlined and modern WordPress theme development workflow. This version is tailored for the `dktheme` inside the `/app/themes/` directory of a WordPress setup.

## 📁 Project Structure

```
your-project/
├── app/
│   └── themes/
│       └── dktheme/
│           ├── public/                # Compiled JS and CSS output
│           ├── src/                   # Source files
│           │   ├── js/
│           │   │   └── main.js
│           │   └── style/
│           │       └── scss/
│           │           ├── base/
│           │           ├── components/
│           │           ├── layout/
│           │           ├── pages/
│           │           ├── themes/
│           │           ├── utils/
│           │           └── main.scss
│           ├── functions.php          # WordPress theme PHP entry
│           ├── index.php              # Main template file
│           └── style.css              # Theme metadata and global styles
├── docker/
│   ├── custom.ini
│   └── Dockerfile (optional)
├── docker-compose.yml
├── package.json
├── webpack.config.cjs
├── .gitignore
└── README.md
```

## 🚀 Installation Instructions

### 1. Prerequisites

Ensure you have the following installed on your system:

- Docker & Docker Compose
- Node.js (v18+ recommended)
- npm

### 2. Clone and Set Up Project

```bash
git clone <your-repo-url>
cd <project-folder>
```

### 3. Install Node Modules

```bash
npm install
```

### 4. Start Development Server

This will start Webpack in development mode with file watching and BrowserSync:

```bash
npm run dev
```

The site should now be accessible at [http://localhost:3000](http://localhost:3000), proxied through BrowserSync, with live CSS injection and JS reload.

### 5. Build for Production

```bash
npm run build
```

This compiles and minifies the SCSS/JS into `app/themes/dktheme/public/`.

### 6. Start Docker (WordPress Environment)

```bash
docker-compose up -d
```

This brings up:

- WordPress (PHP + Apache)
- MySQL
- phpMyAdmin

Accessible at:
- WordPress: [http://localhost:8080](http://localhost:8080)
- phpMyAdmin: [http://localhost:8081](http://localhost:8081)

## ⚙️ Webpack Configuration Highlights

- Entry points: `main.scss` and `main.js`
- Output: Compiled assets to `app/themes/dktheme/public/`
- Plugins:
  - `MiniCssExtractPlugin`: Extracts CSS
  - `CssMinimizerPlugin`: Minifies CSS
  - `BrowserSyncPlugin`: Live reloading via local domain proxy

## 📦 Dependencies

Includes essential front-end tooling:

- `sass` & `sass-loader`
- `css-loader`, `postcss-loader`, `autoprefixer`
- `file-loader`, `url-loader`
- `browser-sync`, `browser-sync-webpack-plugin`
- `webpack`, `webpack-cli`, `mini-css-extract-plugin`
- `css-minimizer-webpack-plugin`

## 🛠 Custom PHP Settings

- The `custom.ini` file allows overriding default PHP values like memory limit, upload size, etc., within the container.