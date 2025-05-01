const { Plugins, PluginsDir } = require("./Plugins");
const fse = require("fs-extra");
const path = require("path");

class Publish {
    constructor() {
        this.options = {
            verbose: false
        };

        this.getArguments();
    }

    getArguments() {
        if (process.argv.length > 2) {
            let arg = process.argv[2];
            switch (arg) {
                case "-v":
                case "--verbose":
                    this.options.verbose = true;
                    break;
                default:
                    throw new Error(`Unknown option ${arg}`);
            }
        }
    }

    run() {
        // Remove previous files
        if (fse.pathExistsSync(PluginsDir)) {
            fse.removeSync(PluginsDir);

            if (this.options.verbose) {
                console.log(`Removed folder: ${PluginsDir}`);
            }
        }
        // Publish files
        Plugins.forEach((module) => {
            try {
                const src = module.from;
                const dest = path.join(PluginsDir, module.toPluginsDir);

                this.copyDirectory(src, dest);

                if (this.options.verbose) {
                    console.log(`Copied ${src} to ${dest}`);
                }
            } catch (err) {
                console.error(`Error: ${err}`);
            }
        });
    }

    copyDirectory(src, dest) {
        fse.copySync(src, dest, {
            filter: (src) => {
                const baseName = path.basename(src);
                if (baseName === "node_modules") {
                    if (this.options.verbose) {
                        console.log(`Skipped copying folder: ${src}`);
                    }
                    return false;
                }
                return true;
            }
        });
    }
}

new Publish().run();
