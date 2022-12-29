module.exports = {
  parser: '@babel/eslint-parser',
  root: true,
  extends: ['react-app', 'prettier'],
  env: {
    browser: true,
    node: true,
    es6: true,
    'jest/globals': true
  },
  globals: {
    route: true,
    Echo: true,
    Pusher: true
  },
  plugins: ['react', 'jest'],
  parserOptions: {
    ecmaVersion: 2020,
    requireConfigFile: false,
    ecmaFeatures: {
      legacyDecorators: true,
      jsx: true
    }
  },
  settings: {
    react: {
      version: '17'
    },
    jest: {
      version: 'latest'
    }
  },
  rules: {
    'no-shadow': 0,
    'no-eval': 0,
    camelcase: 0,
    'array-callback-return': 0,
    'no-extend-native': 0,
    'react-hooks/exhaustive-deps': 0,
    'import/no-cycle': 'error',
    'react/jsx-uses-react': 0,
    'react/react-in-jsx-scope': 0,
    'jest/no-commented-out-tests': 0
  }
};
