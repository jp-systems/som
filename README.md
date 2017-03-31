# Student Online Messenger
<p align="center">
  <a href="http://standardjs.com/"><img src="https://img.shields.io/badge/code%20style-standard-brightgreen.svg" alt="Code Style - Standard"></a>
  <a href="https://david-dm.org/jp-systems/som"><img src="https://david-dm.org/jp-systems/som/status.svg" alt="npm Dependencies Status"></a>
  <a href="https://david-dm.org/jp-systems/som?type=dev"><img src="https://david-dm.org/jp-systems/som/dev-status.svg" alt="npm DevDependencies Status"></a>
  <a href="https://travis-ci.org/jp-systems/som"><img src="https://travis-ci.org/jp-systems/som.svg?branch=master" alt="TravisCI Build Status"></a>
  <img src="https://img.shields.io/github/license/jp-systems/som.svg" alt="License - MIT License">
</p>

---

Student Online Messenger online web application prototype developed for COMP2311 Systems Analysis & Design module.

## How to Build
In order to bundle and test the web app, you must first install <a href="https://nodejs.org/en/">NodeJS</a> and npm (Comes bundled with NodeJS).

Once that is installed, navigate to this repositories folder (if cloned or downloaded via GitHub) and from the command line;

````
npm install
````

Once all dependencies are installed, you can deploy a local development server via;

````
npm run dev
````

From here, you can edit the code within the 'src' directory and the bundle will automatically re-build as you make changes.

## Style Notes
Vue is being used as the templating/data-binding framework for the application, however the smaller 'runtime-only' build is being used. This reduces the overhead of compiling templates at run-time, however means that for this project all Vue components/objects must be declared as *.vue files, and then explicitly declared in whatever component/object is using that component.

All JavaScript should conform to the linting style (`npm run lint`), and all JavaScript will first be transpiled by the `babel-loader` webpack loader, running with the latest feature set (`babel-preset-latest`).

All CSS should be defined as SCSS, as defined by the `lang` attribute within the .vue files. During development, this CSS will be inlined into the JavaScript bundle, however on building this compiled CSS will be extracted into it's own stylesheet.

All HTML templating should be done in pure HTML5.
