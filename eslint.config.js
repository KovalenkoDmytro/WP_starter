import globals from "globals";
import pluginJs from "@eslint/js";
import pluginReact from "eslint-plugin-react"; // Import eslint-plugin-react
import pluginReactConfig from "eslint-plugin-react/configs/recommended.js";
import { fixupConfigRules } from "@eslint/compat";

export default [
    {
        files: ["**/*.{js,mjs,cjs,jsx}"],
        ignores: ["/*.config.js"],
    },
    {
        languageOptions: {
            parserOptions: {
                ecmaVersion: 2021,
                sourceType: "module",
                ecmaFeatures: { jsx: true },
            },
            globals: globals.browser,
        },
    },
    {
        plugins: {
            js: pluginJs.configs.recommended,
            react: pluginReact, // Add eslint-plugin-react as a plugin
        },
    },
    {
        rules: {
            // Add recommended React rules
            ...fixupConfigRules(pluginReactConfig),
            // Disable react-in-jsx-scope for React 17+
            "react/react-in-jsx-scope": "off",
        },
    },
];