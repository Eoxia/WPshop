/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./blocks/src/store/my-account-store.js":
/*!**********************************************!*\
  !*** ./blocks/src/store/my-account-store.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   storeName: () => (/* binding */ storeName)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);


// Définir un état initial
const initialState = {
  page: 'orders'
};

// Actions
const actions = {
  setPage(value) {
    return {
      type: 'SET_PAGE',
      value
    };
  }
};

// Réducteur
const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_PAGE':
      return {
        ...state,
        page: action.value
      };
    default:
      return state;
  }
};

// Sélecteurs
const selectors = {
  getPage(state) {
    return state.page;
  }
};

// Définir le nom du store
const storeName = 'wpshop/my-account-store';
if (!(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(storeName)) {
  let store = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.createReduxStore)(storeName, {
    reducer,
    actions,
    selectors
  });

  // Enregistrer le store
  (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.register)(store);
}


/***/ }),

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
/*!**************************************************************************!*\
  !*** ./blocks/src/my-account-block/inner-blocks/account-details/view.js ***!
  \**************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../utils */ "./blocks/src/utils/index.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _store_my_account_store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../store/my-account-store */ "./blocks/src/store/my-account-store.js");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);
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








const AccountDetails = () => {
  const [contact, setContact] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(null);
  const [third_party, setThirdParty] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(null);
  const page = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    try {
      const store = select(_store_my_account_store__WEBPACK_IMPORTED_MODULE_4__.storeName);
      return store ? store.getPage() : null;
    } catch (e) {
      console.error('Erreur lors de l\'accès au store:', e);
      return null;
    }
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: '/wp-shop/v1/account'
    }).then(response => {
      setContact(response.contact.data);
      setThirdParty(response.third_party.data);
    });
  }, []);
  if (page !== 'account') {
    return null;
  }

  // Determine if we should show loading state
  const isLoading = contact === null && third_party === null;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
    className: 'wp-block-wpshop-account-details wpeo-form',
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      class: "form-element form-element-disable",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
        class: "form-label",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Login', 'wpshop')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
        class: "form-field-container",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
          type: "text",
          class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
          name: "email",
          value: contact?.login,
          readonly: true
        })
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      class: "gridlayout grid-2",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Firstname', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: contact?.firstname,
            readonly: true
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Lastname', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: contact?.lastname,
            readonly: true
          })
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      class: "gridlayout grid-2",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: contact?.email,
            readonly: true
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Phone', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: contact?.phone,
            readonly: true
          })
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      class: "form-element form-element-disable",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
        class: "form-label",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Address', 'wpshop')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
        class: "form-field-container",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
          type: "text",
          class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
          name: "email",
          value: third_party?.address,
          readonly: true
        })
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      class: "gridlayout grid-3",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Zip', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: third_party?.zip,
            readonly: true
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Town', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: third_party?.town,
            readonly: true
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "form-element form-element-disable",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
          class: "form-label",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Country', 'wpshop')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("label", {
          class: "form-field-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("input", {
            type: "text",
            class: `form-field ${isLoading ? 'animate-pulse' : ''}`,
            name: "email",
            value: third_party?.country,
            readonly: true
          })
        })]
      })]
    })]
  });
};
(0,_utils__WEBPACK_IMPORTED_MODULE_0__.render)(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(AccountDetails, {}), '.wp-block-wpshop-account-details');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AccountDetails);
})();

/******/ })()
;
//# sourceMappingURL=view.js.map