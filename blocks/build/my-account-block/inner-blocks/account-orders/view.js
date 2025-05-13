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
/*!*************************************************************************!*\
  !*** ./blocks/src/my-account-block/inner-blocks/account-orders/view.js ***!
  \*************************************************************************/
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









const AccountOrders = () => {
  const [orders, setOrders] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: '/wp-shop/v1/account/orders',
      method: 'GET'
    }).then(response => {
      setOrders(response);
    });
  }, []);
  const {
    setPage
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useDispatch)(_store_my_account_store__WEBPACK_IMPORTED_MODULE_4__.storeName);
  const page = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    try {
      const store = select(_store_my_account_store__WEBPACK_IMPORTED_MODULE_4__.storeName);
      return store ? store.getPage() : null;
    } catch (e) {
      console.error('Erreur lors de l\'accès au store:', e);
      return null;
    }
  }, []);
  const [openedOrder, setOpenedOrder] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)([]);
  const openOrder = index => {
    if (openedOrder.includes(index)) {
      setOpenedOrder(openedOrder.filter(item => item !== index));
    } else {
      setOpenedOrder([...openedOrder, index]);
    }
  };
  if (page != 'orders') {
    return null;
  }
  if (orders === null) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      className: "wp-block-wpshop-account-orders",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Loading...', 'wpshop')
      })
    });
  }
  if (orders.length === 0) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      className: "wp-block-wpshop-account-orders",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('No orders.', 'wpshop')
      })
    });
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
    className: "wp-block-wpshop-account-orders",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      class: "wps-list-order wps-list-box",
      children: orders.map((order, index) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "wps-order wps-box",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
          class: "wps-box-resume",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
            class: "wps-box-primary",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
              class: "wps-box-title",
              children: order.data.datec
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("ul", {
              class: "wps-box-attributes",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("li", {
                class: "wps-box-subtitle-item",
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("i", {
                  class: "wps-box-subtitle-icon fas fa-shopping-cart"
                }), " ", order.data.title]
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
              class: `${openedOrder.includes(index) ? 'active wps-box-display-more' : 'wps-box-display-more'}`,
              onClick: () => openOrder(index),
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("i", {
                class: "wps-box-display-more-icon fas fa-angle-right"
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("span", {
                class: "wps-box-display-more-text",
                children: [" ", (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('View details', 'wpshop')]
              })]
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
            class: "wps-box-secondary",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
              class: "wps-box-status",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
                class: "wps-box-status-dot"
              }), " ", order.data.status.class.text]
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
              class: "wps-box-price",
              children: [parseFloat(order.data.total_ttc).toFixed(2), "\u20AC"]
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
            class: "wps-box-action",
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
              target: "_blank",
              href: order.data.download,
              class: "wpeo-button button-primary button-square-50 button-rounded",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("i", {
                class: "button-icon fas fa-file-download"
              })
            })
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
          class: `${openedOrder.includes(index) ? 'wps-list-product' : 'wps-box-detail wps-list-product'}`,
          children: order.data.lines.map(product => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
            itemscope: true,
            itemtype: "https://schema.org/Product",
            class: "wps-product",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("figure", {
              class: "wps-product-thumbnail",
              dangerouslySetInnerHTML: {
                __html: product.thumbnail
              },
              itemprop: "image"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
              class: "wps-product-content",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
                itemprop: "name",
                class: "wps-product-title",
                children: product.libelle
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("ul", {
                class: "wps-product-attributes",
                children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("li", {
                  class: "wps-product-attributes-item",
                  children: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Unit price:', 'wpshop'), " ", product.subprice, " \u20AC"]
                })
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
                class: "wps-product-footer",
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
                  class: "wps-product-quantity",
                  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
                    class: "qty",
                    children: product.qty
                  })
                }), product.subprice && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
                  itemprop: "offers",
                  itemscope: true,
                  itemtype: "https://schema.org/Offer",
                  class: "wps-product-price",
                  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
                    itemprop: "price",
                    children: (product.subprice * product.qty).toFixed(2)
                  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
                    itemprop: "priceCurrency",
                    content: "EUR",
                    children: "\u20AC"
                  })]
                })]
              })]
            })]
          }))
        })]
      }, index))
    })
  });
};
(0,_utils__WEBPACK_IMPORTED_MODULE_0__.render)(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(AccountOrders, {}), '.wp-block-wpshop-account-orders');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AccountOrders);
})();

/******/ })()
;
//# sourceMappingURL=view.js.map