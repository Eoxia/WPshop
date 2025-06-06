/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./blocks/src/utils/index.js":
/*!***********************************!*\
  !*** ./blocks/src/utils/index.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _render__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _render__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./render */ "./blocks/src/utils/render.js");


/***/ }),

/***/ "./blocks/src/utils/render.js":
/*!************************************!*\
  !*** ./blocks/src/utils/render.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

function render(element, containerClass) {
  document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector(containerClass);
    if (!container) {
      return;
    }
    const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createRoot)(container);
    root.render(element);
  });
}

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!*********************************************!*\
  !*** ./blocks/src/connection-block/view.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./blocks/src/utils/index.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);
/**
 * Use this file for JavaScript code that you want to run in the front-end 
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any 
 * JavaScript running in the front-end, then you should delete this file and remove 
 * the `viewScript` property from `block.json`. 
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */







const ConnectionBlock = ({
  accountUrl
}) => {
  const [isLogin, setIsLogin] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(true);
  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(false);
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)('');

  // Login form states
  const [loginCredentials, setLoginCredentials] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)({
    username: '',
    password: '',
    rememberMe: false
  });

  // Register form states
  const [registerInfo, setRegisterInfo] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)({
    username: '',
    email: '',
    password: '',
    confirmPassword: '',
    agreeTerms: false
  });
  const handleLoginChange = e => {
    const {
      name,
      value,
      type,
      checked
    } = e.target;
    setLoginCredentials({
      ...loginCredentials,
      [name]: type === 'checkbox' ? checked : value
    });
  };
  const handleRegisterChange = e => {
    const {
      name,
      value,
      type,
      checked
    } = e.target;
    setRegisterInfo({
      ...registerInfo,
      [name]: type === 'checkbox' ? checked : value
    });
  };

  // Function to redirect to my-account page
  const redirectToMyAccount = () => {
    window.location.href = accountUrl || '/my-account'; // Use the accountUrl prop if available
  };
  const handleLoginSubmit = e => {
    e.preventDefault();
    setIsLoading(true);
    setError('');
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
      path: 'wp-shop/v1/login',
      method: 'POST',
      data: {
        username: loginCredentials.username,
        password: loginCredentials.password
      }
    }).then(response => {
      // Redirect to my-account page on successful login
      redirectToMyAccount();
    }).catch(error => {
      setError(error.message || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Login failed. Please check your credentials.', 'wpshop'));
    }).finally(() => {
      setIsLoading(false);
    });
  };
  const handleRegisterSubmit = e => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    // Validation
    if (registerInfo.password !== registerInfo.confirmPassword) {
      setError((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Passwords do not match', 'wpshop'));
      setIsLoading(false);
      return;
    }
    if (!registerInfo.agreeTerms) {
      setError((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('You must agree to the terms and conditions', 'wpshop'));
      setIsLoading(false);
      return;
    }
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
      path: 'wp-shop/v1/register',
      method: 'POST',
      data: {
        username: registerInfo.username,
        email: registerInfo.email,
        password: registerInfo.password
      }
    }).then(response => {
      // Reset form data
      setRegisterInfo({
        username: '',
        email: '',
        password: '',
        confirmPassword: '',
        agreeTerms: false
      });
      // Redirect to my-account page on successful registration
      redirectToMyAccount();
    }).catch(error => {
      setError(error.message || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Registration failed.', 'wpshop'));
    }).finally(() => {
      setIsLoading(false);
    });
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
    className: "wp-block-wpshop-connection-block",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      className: "connection-container",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "connection-header",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "connection-tabs",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("button", {
            className: `connection-tab ${isLogin ? 'active' : ''}`,
            onClick: () => setIsLogin(true),
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Login', 'wpshop')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("button", {
            className: `connection-tab ${!isLogin ? 'active' : ''}`,
            onClick: () => setIsLogin(false),
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Register', 'wpshop')
          })]
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "connection-content",
        children: [error && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "connection-error",
          children: error
        }), isLogin ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("form", {
          className: "login-form",
          onSubmit: handleLoginSubmit,
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "username",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Username', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "text",
              id: "username",
              name: "username",
              value: loginCredentials.username,
              onChange: handleLoginChange,
              required: true
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "password",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Password', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "password",
              id: "password",
              name: "password",
              value: loginCredentials.password,
              onChange: handleLoginChange,
              required: true
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group checkbox-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "checkbox",
              id: "rememberMe",
              name: "rememberMe",
              checked: loginCredentials.rememberMe,
              onChange: handleLoginChange
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "rememberMe",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Remember me', 'wpshop')
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-actions",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("button", {
              type: "submit",
              className: "submit-button",
              disabled: isLoading,
              children: isLoading ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Logging in...', 'wpshop') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Login', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("a", {
              href: "#",
              className: "forgot-password",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Forgot your password?', 'wpshop')
            })]
          })]
        }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("form", {
          className: "register-form",
          onSubmit: handleRegisterSubmit,
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "reg-username",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Username', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "text",
              id: "reg-username",
              name: "username",
              value: registerInfo.username,
              onChange: handleRegisterChange,
              required: true
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "email",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "email",
              id: "email",
              name: "email",
              value: registerInfo.email,
              onChange: handleRegisterChange,
              required: true
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "reg-password",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Password', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "password",
              id: "reg-password",
              name: "password",
              value: registerInfo.password,
              onChange: handleRegisterChange,
              required: true
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "confirm-password",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Confirm Password', 'wpshop')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "password",
              id: "confirm-password",
              name: "confirmPassword",
              value: registerInfo.confirmPassword,
              onChange: handleRegisterChange,
              required: true
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
            className: "form-group checkbox-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
              type: "checkbox",
              id: "agreeTerms",
              name: "agreeTerms",
              checked: registerInfo.agreeTerms,
              onChange: handleRegisterChange,
              required: true
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
              htmlFor: "agreeTerms",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('I agree to the Terms and Conditions', 'wpshop')
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "form-actions",
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("button", {
              type: "submit",
              className: "submit-button",
              disabled: isLoading,
              children: isLoading ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Registering...', 'wpshop') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Register', 'wpshop')
            })
          })]
        })]
      })]
    })
  });
};
(0,_utils__WEBPACK_IMPORTED_MODULE_0__.render)(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(ConnectionBlock, {
  accountUrl: document.getElementsByClassName('wp-block-wpshop-connection-block')[0].getAttribute('data-account-link')
}), '.wp-block-wpshop-connection-block');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ConnectionBlock);
})();

/******/ })()
;
//# sourceMappingURL=view.js.map