import js from '@eslint/js';
import react from 'eslint-plugin-react';
import reactHooks from 'eslint-plugin-react-hooks';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import importPlugin from 'eslint-plugin-import';
import prettier from 'eslint-config-prettier';
import globals from 'globals';

export default [
  js.configs.recommended, // Base JS rules
  prettier, // Ensure compatibility with Prettier
  {
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser, // Allow browser globals like `window`, `document`
        route: 'readonly' // Laravel Inertia route helper
      }
    },
    plugins: {
      react,
      'react-hooks': reactHooks,
      'jsx-a11y': jsxA11y,
      import: importPlugin
    },
    rules: {
      'react/jsx-uses-react': 'off', // Not needed for React 17+
      'react/react-in-jsx-scope': 'off', // Next.js and Inertia auto-import React
      'react-hooks/rules-of-hooks': 'error', // Enforce React Hooks rules
      'react-hooks/exhaustive-deps': 'warn', // Warn on missing dependencies in Hooks
      'jsx-a11y/anchor-is-valid': 'off', // Inertia uses `<Link>` instead of `<a>`
      camelcase: 0,
      'array-callback-return': 0,
      'no-extend-native': 0,
      'react-hooks/exhaustive-deps': 0,
      'import/no-cycle': 'error',
      'react/jsx-uses-react': 0,
      'react/react-in-jsx-scope': 0,
      'jest/no-commented-out-tests': 0,
      'import/order': [
        'error',
        {
          groups: [
            'builtin',
            'external',
            'internal',
            'parent',
            'sibling',
            'index'
          ],
          alphabetize: { order: 'asc', caseInsensitive: true }
        }
      ],
      'import/no-unresolved': 'off' // Disable unresolved imports for Laravel alias paths
    },
    settings: {
      react: {
        version: 'detect'
      }
    }
  }
];
